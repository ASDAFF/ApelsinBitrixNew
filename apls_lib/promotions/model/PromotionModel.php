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

    const REVISION_TYPE_ALL = "all";
    const REVISION_TYPE_COMING = "coming";
    const REVISION_TYPE_CURRENT = "current";
    const REVISION_TYPE_PAST = "past";
    const REVISION_TYPE_WITHOUT = "without";
    const REVISION_TYPE_DEFAULT = self::REVISION_TYPE_CURRENT;

    private static $revisionTypeArray = array(
        self::REVISION_TYPE_ALL,
        self::REVISION_TYPE_COMING,
        self::REVISION_TYPE_CURRENT,
        self::REVISION_TYPE_PAST,
        self::REVISION_TYPE_WITHOUT
    );

    const SORT_ASC = 'ask';
    const SORT_DESC = 'desc';

    const SORT_FIELD_REVISION_APPLY_FROM = "revision_apply_from";
    const SORT_FIELD_REVISION_SHOW_FROM = "revision_show_from";
    const SORT_FIELD_REVISION_START_FROM = "revision_start_from";
    const SORT_FIELD_REVISION_STOP_FROM = "revision_stop_from";
    const SORT_FIELD_REVISION_CREATED = "revision_created";
    const SORT_FIELD_REVISION_CHANGED = "revision_changed";
    const SORT_FIELD_PROMOTIONS_TITLE = "promotions_title";
    const SORT_FIELD_PROMOTIONS_CREATED = "promotions_created";
    const SORT_FIELD_DEFAULT = self::SORT_FIELD_REVISION_APPLY_FROM;

    private static $sortRevisionFieldsArray = array(
        self::SORT_FIELD_REVISION_APPLY_FROM,
        self::SORT_FIELD_REVISION_SHOW_FROM,
        self::SORT_FIELD_REVISION_START_FROM,
        self::SORT_FIELD_REVISION_STOP_FROM,
        self::SORT_FIELD_REVISION_CREATED,
        self::SORT_FIELD_REVISION_CHANGED
    );

    private static $sortPromotionsFieldsArray = array(
        self::SORT_FIELD_PROMOTIONS_TITLE,
        self::SORT_FIELD_PROMOTIONS_CREATED
    );

    private static $sortStringToFields = array (
        self::SORT_FIELD_REVISION_APPLY_FROM => 'apply_from',
        self::SORT_FIELD_REVISION_SHOW_FROM => 'show_from',
        self::SORT_FIELD_REVISION_START_FROM => 'start_from',
        self::SORT_FIELD_REVISION_STOP_FROM => 'stop_from',
        self::SORT_FIELD_REVISION_CREATED => 'created',
        self::SORT_FIELD_REVISION_CHANGED => 'changed',
        self::SORT_FIELD_PROMOTIONS_TITLE => 'title',
        self::SORT_FIELD_PROMOTIONS_CREATED => 'created',
    );

    const PUBLISHED_ON_GLOBAL = "global_activity";
    const PUBLISHED_ON_LOCAL = "local_activity";
    const PUBLISHED_ON_VK = "vk_activity";

    private static $publishedOnArray = array(
        self::PUBLISHED_ON_GLOBAL,
        self::PUBLISHED_ON_LOCAL,
        self::PUBLISHED_ON_VK
    );

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

    public static function promotionSearch (
        $revisionType = self::REVISION_TYPE_DEFAULT,
        $sortingField = self::SORT_FIELD_DEFAULT,
        $sortingType = self::SORT_ASC,
        $searchString = "",
        $location = "",
        $section = "",
        $publishedOn = array()
    ):array {
        if($revisionType === self::REVISION_TYPE_ALL) {
            // если у нас поиск по всем ревизиям то поиск будет идти по текущей ревизии
        }
        if($revisionType === self::REVISION_TYPE_WITHOUT) {
            // если у нас поиск по всем ревизиям то поиск будет идти не по всем полям
        }
        // получаем список всех акций
        $promotions = PromotionModel::getElementList();
        $promotionsSort = array();
        // проверяем корректность типа ревизии
        if(!in_array($revisionType,static::$revisionTypeArray)) {
            $revisionType = self::REVISION_TYPE_DEFAULT;
        }
        // корректируем список мест пуликации
        $publishedOn = array_intersect($publishedOn, static::$publishedOnArray);
        // перебираем акции
        foreach ($promotions as $promotionId => $promotion) {
            // если акция
            if($promotion instanceof PromotionModel) {
                $mainRevisionId = ""; // ID ревизии по которой будет осуществлятсья дальнейший поиск
                // находим ID ревизии по которой будет осуществлятсья дальнейший поиск согласно типу
                switch ($revisionType) {
                    case self::REVISION_TYPE_COMING:
                        $mainRevisionId = $promotion->getNextRevisionId();
                        break;
                    case self::REVISION_TYPE_CURRENT:
                        $mainRevisionId = $promotion->getCurrentRevisionId();
                        break;
                    case self::REVISION_TYPE_PAST:
                        $mainRevisionId = $promotion->getPreviousRevisionId();
                        break;
                    case self::REVISION_TYPE_ALL:
                        $mainRevisionId = $promotion->getCurrentRevisionId();
                        break;
                }
                // поулчаем ревизию для дальнейшего поиска
                $mainRevision = new PromotionRevisionModel($mainRevisionId);
                $toDelete = false;
                // првоеряем на соответствие типа для WITHOUT
                if(!$toDelete &&
                    $revisionType === self::REVISION_TYPE_WITHOUT &&
                    (
                        $promotion->getNextRevisionId() !== "" ||
                        $promotion->getCurrentRevisionId() !== "" ||
                        $promotion->getPreviousRevisionId() !== ""
                    )
                ) {
                    $toDelete = true;
                }
                // првоеряем на соответствие типа дял остальных типов
                if(
                    !$toDelete &&
                    (
                        $mainRevisionId === "" &&
                        $revisionType !== self::REVISION_TYPE_ALL &&
                        $revisionType !== self::REVISION_TYPE_WITHOUT
                    )
                ) {
                    $toDelete = true;
                }
                // првоеряем на соответствие локации
                if(!$toDelete && $location !== "" && !PromotionRevisionModel::isRevisionAvailableForRegion($mainRevisionId, $location)) {
                    $toDelete = true;
                }
                // првоеряем на соответствие секции
                if(!$toDelete && $section !== "" && !PromotionRevisionModel::isRevisionAvailableForSection($mainRevisionId, $section)) {
                    $toDelete = true;
                }
                // ищем на соответствие по публикациям
                if(!$toDelete && !empty($publishedOn)) {
                    foreach ($publishedOn as $place) {
                        if($mainRevision->getFieldValue("$place") < 1) {
                            $toDelete = true;
                        }
                    }
                }
                // ищем хотябы одно совпадение по строке
                if(!$toDelete && $searchString !== "") {
                    $searchString = mb_strtolower($searchString);
                    $searchText = mb_strtolower(static::getPromotionSearchText($promotion, $mainRevision));
                    $searchCount = 0;
                    foreach (array_diff(explode(" ", $searchString),array("")) as $searchWord) {
                        $searchWord = str_replace("_"," ", $searchWord);
                        $searchCount += substr_count($searchText,$searchWord);
                    }
                    if($searchCount < 1) {
                        $toDelete = true;
                    }
                }
                // исключаем акцию из списка если надо
                if(!$toDelete) {
                    if(
                        $revisionType === self::REVISION_TYPE_WITHOUT &&
                        in_array($sortingField, static::$sortRevisionFieldsArray)
                    ) {
                        $promotionsSort[$promotionId] = static::getPromotionSortString(
                            $promotion,
                            self::SORT_FIELD_PROMOTIONS_TITLE
                        );
                    } else if(in_array($sortingField, static::$sortRevisionFieldsArray) && $mainRevisionId !== "") {
                        $promotionsSort[$promotionId] = static::getRevisionSortString($mainRevision, $sortingField);
                    } else if(in_array($sortingField, static::$sortPromotionsFieldsArray)) {
                        $promotionsSort[$promotionId] = static::getPromotionSortString($promotion, $sortingField);
                    } else {
                        $promotionsSort[$promotionId] = "";
                    }
                }
            }
        }
        if($sortingType === self::SORT_DESC) {
            arsort($promotionsSort);
        } else {
            asort($promotionsSort);
        }
        $lastPromotionsId = array();
        $newPromotionsId = array();
        foreach ($promotionsSort as $promotionId => $sortValue) {
            if($sortValue === "") {
                $lastPromotionsId[] = $promotionId;
            } else {
                $newPromotionsId[] = $promotionId;
            }
        }
        $newPromotions = array();
        foreach (array_merge($newPromotionsId,$lastPromotionsId) as $promotionsId) {
            $newPromotions[$promotionsId] = $promotions[$promotionsId];
        }
        return $newPromotions;
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

    public function verificationOfEditingRights() {
        return true;
//        $rsUser = CUser::GetByID(CUser::GetID());
//        $arUser = $rsUser->Fetch();
//        if ($this->originalData['created_user'] == $arUser['LOGIN']) {
//            return true;
//        }
//        return false;
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


    private static function getPromotionSearchText(PromotionModel $promotion, PromotionRevisionModel $revision) {
        $searchText = "";
        $searchText .= $promotion->getId()." ";
        $searchText .= $promotion->getFieldValue('title')." ";
        $searchText .= $revision->getId()." ";
        $searchText .= $revision->getFieldValue('preview_text')." ";
        $searchText .= $revision->getFieldValue('main_text')." ";
        $searchText .= $revision->getFieldValue('vk_text')." ";
        return $searchText;
    }

    private static function getPromotionSortString(PromotionModel $promotion, string $field):string {
        $string = $promotion->getFieldValue(static::$sortStringToFields[$field]);
        if($string instanceof Bitrix\Main\Type\DateTime) {
            return $string->format("YmdHis");
        }
        return $string;
    }

    private static function getRevisionSortString(PromotionRevisionModel $revision, string $field):string {
        $string = $revision->getFieldValue(static::$sortStringToFields[$field]);
        if($string instanceof Bitrix\Main\Type\DateTime) {
            return $string->format("YmdHis");
        } elseif ($string === null) {
            $string = "";
        }
        return $string;
    }


    /* ПЕРЕОПРЕДЕЛЕННЫЕ МЕТОДЫ */

    protected static function beforeCreateElement(array &$fieldsValue, array &$attr): bool
    {
        $rsUser = CUser::GetByID(CUser::GetID());
        $arUser = $rsUser->Fetch();
        $fieldsValue['created'] = static::mysqlDateTime();
        $fieldsValue['created_user'] = $arUser['LOGIN'];
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

    protected function beforeSaveElement(): bool
    {
        return $this->verificationOfEditingRights();
    }

    protected static function beforeUpdateElement($id, array &$updateFieldsValue, array &$attr): bool
    {
        $thisElement = new PromotionModel($id);
        return $thisElement->verificationOfEditingRights();
    }

    protected static function beforeDeleteElement($id, array &$attr): bool
    {
        $thisElement = new PromotionModel($id);
        return $thisElement->verificationOfEditingRights();
    }

    public function beforeCreateCopy(array &$fieldsValue, array &$attr):bool {
        return static::beforeCreateElement($fieldsValue, $attr);
    }

    public function afterCreateCopy(string $id, array &$fieldsValue, array &$attr):bool {
        if(isset($attr[static::REVISIONS_KEY])) {
            foreach ($attr[static::REVISIONS_KEY] as $revisionId) {
                if(is_string($revisionId) && PromotionRevisionModel::isRevisionInPromotion($revisionId,$this->id)) {
                    $revision = new PromotionRevisionModel($revisionId);
                    $revision->createCopy(array("promotion"=>$id));
                }
            }

        }
        return true;
    }
}