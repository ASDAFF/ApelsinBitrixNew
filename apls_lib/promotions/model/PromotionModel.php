<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";

class PromotionModel extends PromotionModelAbstract
{

    protected static $tableName = "apls_promotions";
    protected static $privateFields = array('created', 'created_user');
    protected static $requiredFields = array('title');
    protected static $optionalFields = array('sort');

    const REVISION_PROMOTION_FIELDS = 'promotion';
    const REVISION_APPLY_FROM_FIELDS = 'apply_from';
    const REVISION_DISABLE_FIELDS = 'disable';
    const REVISIONS_KEY = 'revisions';

    /**
     *  Возвращает список id активных ревизий для этой акции
     * @return array - массив идентификаторов
     */
    public function getActiveRevisionsId(): array
    {
        return $this->getRevisionsId(false);
    }

    /**
     *  Возвращает список активных ревизий для этой акции
     * @return array - массив ревизий PromotionRevisionModel
     */
    public function getActiveRevisions(): array
    {
        return $this->getRevisions(false);
    }

    /**
     *  Возвращает список id не активных ревизий для этой акции
     * @return array - массив идентификаторов
     */
    public function getDisableRevisionsId(): array
    {
        return $this->getRevisionsId(true);
    }

    /**
     *  Возвращает список не активных ревизий для этой акции
     * @return array - массив ревизий PromotionRevisionModel
     */
    public function getDisableRevisions(): array
    {
        return $this->getRevisions(true);
    }

    /**
     * Возвращает id дейстующей ревизии или пустую строку если такой ревизии нет.
     * @return string - id дейстующей ревизии или пустая строка
     */
    public function getCurrentRevisionId(): string
    {
        return $this->getLeftShiftRevisionId();
    }

    /**
     * Возвращает текущую ревизию
     * @return PromotionRevisionModel
     */
    public function getCurrentRevision(): PromotionRevisionModel
    {
        return new PromotionRevisionModel($this->getCurrentRevisionId());
    }

    /**
     * Возвращает id следующей ревизии или пустую строку если такой ревизии нет.
     * @return string - id следующей ревизии или пустая строка
     */
    public function getNextRevisionId(): string
    {
        // сортируем по дате применения по возрастанию
        $orderByString = new MySQLOrderByString();
        $orderByString->add(self::REVISION_APPLY_FROM_FIELDS, MySQLOrderByString::ASC);
        // Получить один элемент
        $sql = MySQLTrait::selectSQL(
            PromotionRevisionModel::getTableName(),
            array(static::$pk),
            $this->revisionApplyingWhereStringObj(false),
            1,
            null,
            $orderByString
        );
        $record = static::getConnection()->query($sql)->fetch();
        if (isset($record[static::$pk])) {
            return $record[static::$pk];
        } else {
            return "";
        }
    }

    /**
     * Возвращает следующую ревизию
     * @return PromotionRevisionModel
     */
    public function getNextRevision(): PromotionRevisionModel
    {
        return new PromotionRevisionModel($this->getNextRevisionId());
    }

    /**
     * Возвращает id предыдущей ревизии или пустую строку если такой ревизии нет.
     * @return string - id предыдущей ревизии или пустая строка
     */
    public function getPreviousRevisionId(): string
    {
        // выбиарем первый перед текущим
        return $this->getLeftShiftRevisionId(1);
    }

    /**
     * Возвращает предыдущую ревизию
     * @return PromotionRevisionModel
     */
    public function getPreviousRevision(): PromotionRevisionModel
    {
        return new PromotionRevisionModel($this->getPreviousRevisionId());
    }

    /**
     * Возвращает список обхектов PromotionModel для указанного региона и секции
     * Список формируется на основе данных текущих активных ревизий акций
     * @param string $region
     * @param string $section
     * @return array
     */
    public static function getPromotionsList(string $region, string $section = ""): array
    {
        // получаем список всех акций
        $promotions = PromotionModel::getElementList();
        // перебираем акции
        foreach ($promotions as $key => $promotion) {
            if ($promotion instanceof PromotionModel) {
                $currentRevision = $promotion->getCurrentRevisionId();
                if (
                    $currentRevision == '' ||
                    !PromotionRevisionModel::isRevisionAvailableForRegion($currentRevision, $region)
                ) { // если текущей ревизии нет или она недоступна для региона,
                    // удаляем эту акцию из спсика
                    unset($promotions[$key]);
                } elseif (
                    $section != "" &&
                    !PromotionRevisionModel::isRevisionAvailableForSection($currentRevision, $section)
                ) { // если секция указана для поиска и текущая ревизия недостпна дял этйо секции ,
                    // удаляем эту акцию из спсика
                    unset($promotions[$key]);
                }
            }
        }
        // возвращаем оставшиеся акции
        return $promotions;
    }

    /* ПРИВАТНЫЕ МЕТОДЫ */

    /**
     * Возвращает список id ревизий для этой акции, отбирая их по активности.
     * @param bool $disable - активные или неактивные ревизии
     *      Если true, то вернется список заблокированных ревизий.
     *      Если false, то вернется список активных ревизий.
     * @return array список id ревизий
     */
    private function getRevisionsId(bool $disable = false): array
    {
        $idList = array();
        // отсортирвоать по дате применения по убыванию
        $orderByString = new MySQLOrderByString();
        $orderByString->add(self::REVISION_APPLY_FROM_FIELDS, MySQLOrderByString::DESC);
        // Получить один элемент
        $sql = MySQLTrait::selectSQL(
            PromotionRevisionModel::getTableName(),
            array(static::$pk),
            $this->revisionWhereStringObj($disable),
            1,
            null,
            $orderByString
        );
        $recordset = static::getConnection()->query($sql);
        while ($record = $recordset->fetch()) {
            $idList[] = $record[static::$pk];
        }
        return $idList;
    }

    /**
     * Список ревизий отобранных по активности
     * @param bool $disable - активные или неактивные ревизии
     *      Если true, то вернется список заблокированных ревизий.
     *      Если false, то вернется список активных ревизий.
     * @return array список ревизий
     */
    private function getRevisions(bool $disable = false): array
    {
        $revisionsList = array();
        $idList = $this->getRevisionsId($disable);
        foreach ($idList as $id) {
            $revisionsList[$id] = new PromotionRevisionModel($id);
        }
        return $revisionsList;
    }

    /**
     * Возвращает id ревизии со здвигом в лево на значение $leftShift от текущей ревизии.
     * @param int $leftShift - если значение задано, то вместо текущего элемента берется
     *      пердыдущий элемент элемент со здвигом на указанное значение.
     *      Например, при знаечнии 2 будет взят пред-предыдущий элемент.
     * @return string - id дейстующей ревизии или пустая строка
     */
    public function getLeftShiftRevisionId(int $leftShift = null): string
    {
        // отсортирвоать по дате применения по убыванию
        $orderByString = new MySQLOrderByString();
        $orderByString->add(self::REVISION_APPLY_FROM_FIELDS, MySQLOrderByString::DESC);
        // Получить один элемент
        $sql = MySQLTrait::selectSQL(
            PromotionRevisionModel::getTableName(),
            array(static::$pk),
            $this->revisionApplyingWhereStringObj(true),
            1,
            $leftShift,
            $orderByString
        );
        $record = static::getConnection()->query($sql)->fetch();
        if (isset($record[static::$pk])) {
            return $record[static::$pk];
        } else {
            return "";
        }
    }

    /**
     * Подготавлвиает обхект поиска по ревизиям акции основываясь на активности
     * @param bool $disable - заблокированные или активные
     * @return MySQLWhereString
     */
    private function revisionWhereStringObj(bool $disable = false): MySQLWhereString
    {
        if ($disable) {
            $operator = MySQLWhereElementString::OPERATOR_B_NOT_EQUAL;
        } else {
            $operator = MySQLWhereElementString::OPERATOR_B_EQUAL;
        }
        $whereObj = new MySQLWhereString(MySQLWhereString::AND_BLOCK);
        // Ревизии для текущей акции
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                self::REVISION_PROMOTION_FIELDS,
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $this->id
            )
        );
        // Только активные или неактивные ревизии
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                self::REVISION_DISABLE_FIELDS,
                $operator,
                "0"
            )
        );
        return $whereObj;
    }

    /**
     * Подготавлвиает обхект поиска по ревизиям акции основываясь на активности и применимости
     * @param bool $applied - примененные или нет
     * @param bool $disable - заблокированные или активные
     * @return MySQLWhereString
     */
    private function revisionApplyingWhereStringObj(bool $applied = true, bool $disable = false): MySQLWhereString
    {
        if ($applied) {
            $operator = MySQLWhereElementString::OPERATOR_B_LESS_OR_EQUAL;
        } else {
            $operator = MySQLWhereElementString::OPERATOR_B_MORE;
        }
        $whereObj = $this->revisionWhereStringObj($disable);
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                self::REVISION_APPLY_FROM_FIELDS,
                $operator,
                static::mysqlDateTime()
            )
        );
        return $whereObj;
    }

    /* ПЕРЕОПРЕДЕЛЕННЫЕ МЕТОДЫ */

    protected static function beforeCreateElement(array &$fieldsValue, array &$attr): bool
    {
        $fieldsValue['created'] = static::mysqlDateTime();
        $fieldsValue['created_user'] = CUser::GetID();
        return true;
    }

    protected static function afterCreateElement($id, $attr): bool
    {
        if (!empty($attr)) {
            $attr[self::REVISION_PROMOTION_FIELDS] = $id;
            $params = array();
            // если среди полей в $attr есть поле с массивом регионов,
            // то при создании ревизии она будет добавлена в эти регионы
            if (isset($attr[PromotionRevisionModel::REGIONS_KEY])) {
                $params[PromotionRevisionModel::REGIONS_KEY] = $attr[PromotionRevisionModel::REGIONS_KEY];
                unset($attr[PromotionRevisionModel::REGIONS_KEY]);
            }
            // если среди полей в $attr есть поле с массивом секций,
            // то при создании ревизии она будет добавлена в эти секции
            if (isset($attr[PromotionRevisionModel::SECTIONS_KEY])) {
                $params[PromotionRevisionModel::SECTIONS_KEY] = $attr[PromotionRevisionModel::SECTIONS_KEY];
                unset($attr[PromotionRevisionModel::SECTIONS_KEY]);
            }
            // если среди полей в $attr есть поле с массивом продуктов каталога,
            // то при создании ревизии они будут добавлены в ревизию
            if (isset($attr[PromotionRevisionModel::CATALOG_PRODUCTS_KEY])) {
                $params[PromotionRevisionModel::CATALOG_PRODUCTS_KEY] = $attr[PromotionRevisionModel::CATALOG_PRODUCTS_KEY];
                unset($attr[PromotionRevisionModel::CATALOG_PRODUCTS_KEY]);
            }
            // если среди полей в $attr есть поле с массивом секций каталога,
            // то при создании ревизии они будут добавлены в ревизию
            if (isset($attr[PromotionRevisionModel::CATALOG_SECTIONS_KEY])) {
                $params[PromotionRevisionModel::CATALOG_SECTIONS_KEY] = $attr[PromotionRevisionModel::CATALOG_SECTIONS_KEY];
                unset($attr[PromotionRevisionModel::CATALOG_SECTIONS_KEY]);
            }
            // если среди полей в $attr есть поле с массивом продуктов исключений каталога,
            // то при создании ревизии они будут добавлены в ревизию
            if (isset($attr[PromotionRevisionModel::CATALOG_EXCEPTIONS_KEY])) {
                $params[PromotionRevisionModel::CATALOG_EXCEPTIONS_KEY] = $attr[PromotionRevisionModel::CATALOG_EXCEPTIONS_KEY];
                unset($attr[PromotionRevisionModel::CATALOG_EXCEPTIONS_KEY]);
            }
            return PromotionRevisionModel::createElement($attr, $params);
        }
        return true;
    }

}