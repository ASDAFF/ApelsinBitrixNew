<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionInRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionInSectionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionSectionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogProduct.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogSection.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogException.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageInRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageModel.php";

class PromotionRevisionModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_revision";
    protected static $privateFields = array(
        'created',
        'changed',
        'created_user',
    );
    protected static $requiredFields = array(
        'promotion',
        'apply_from',
        'disable',
        'in_all_regions',
    );
    protected static $optionalFields = array(
        'global_activity',
        'local_activity',
        'vk_activity',
        'apply_from',
        'show_from',
        'start_from',
        'stop_from',
        'title',
        'preview_text',
        'main_text',
        'vk_text',
        'preview_image_id',
        'main_image_id',
        'vk_image_id',
    );

    const REGIONS_KEY = "REGIONS";
    const SECTIONS_KEY = "SECTIONS";
    const CATALOG_PRODUCTS_KEY = "CATALOG_PRODUCTS";
    const CATALOG_SECTIONS_KEY = "CATALOG_SECTIONS";
    const CATALOG_EXCEPTIONS_KEY = "CATALOG_EXCEPTIONS";

    /**
     * Вернет массив идентификаторов регионов в которых [строго = $strictly] определена данная ревизия
     * @param $strictly - если true, то будет возвращет только список явно установленных регионов,
     * если false, то в случае если строго установленных регионов нет, то вернет спсиок всех регионов.
     * @return array - массив идентификаторов регионов
     */
    public function getRegionsId(bool $strictly = false): array
    {
        $arrRegionsId = array();
        $promotionInRegion = PromotionInRegionModel::searchByRevision($this->id);
        foreach ($promotionInRegion as $element) {
            $arrRegionsId[$element['id']] = $element['region'];
        }
        if (!$strictly && empty($arrRegionsId)) {
            $regions = PromotionRegionModel::getElementList();
            foreach ($regions as $element) {
                $arrRegionsId[] = $element['id'];
            }
        }
        return $arrRegionsId;
    }

    /**
     * Вернет массив регионов (PromotionRegionModel) в которых [строго = $strictly] определена данная ревизия
     * @param $strictly - если true, то будет возвращет только список явно установленных регионов,
     * если false, то в случае если строго установленных регионов нет, то вернет спсиок всех регионов.
     * @return array - массив PromotionRegionModel
     */
    public function getRegions(bool $strictly = false): array
    {
        $arrRegionsId = $this->getRegionsId($strictly);
        $arrRegions = array();
        foreach ($arrRegionsId as $key => $region) {
            $arrRegions[$key] = new PromotionRegionModel($region);
        }
        return $arrRegions;
    }

    /**
     * Вернет массив идентификаторов секций в которых определена данная ревизия
     * @return array - массив идентификаторов секций
     */
    public function getSectionsId(): array
    {
        $arrSectionsId = array();
        $promotionInSection = PromotionInSectionModel::searchByRevision($this->id);
        foreach ($promotionInSection as $element) {
            if($element instanceof PromotionInSectionModel) {
                $arrSectionsId[$element->getId()] = $element->getFieldValue("section");
            }
        }
        return $arrSectionsId;
    }

    /**
     * Вернет массив секций (PromotionSectionModel) в которых определена данная ревизия
     * @return array - массив PromotionSectionModel
     */
    public function getSections(): array
    {
        $arrSectionsId = $this->getSectionsId();
        $arrSections = array();
        foreach ($arrSectionsId as $key => $section) {
            $arrSections[$key] = new PromotionSectionModel($section);
        }
        return $arrSections;
    }

    /**
     * Добавляет текущую ревизию в указанный регион
     * @param $regionId - идентификтаор региона
     * @return bool|int|string - идентификатор созданной записи или false в случае ошибки
     */
    public function addToRegion($regionId)
    {
        return PromotionInRegionModel::createElement(array('revision' => $this->id, 'region' => $regionId));
    }

    /**
     * Добавляет текущую ревизию в указанную секцию
     * @param $sectionId - идентификтаор секции
     * @return bool|int|string - идентификатор созданной записи или false в случае ошибки
     */
    public function addToSection($sectionId)
    {
        return PromotionInSectionModel::createElement(array('revision' => $this->id, 'region' => $sectionId));
    }

    /**
     * Удаляет ревизию из указанного региона. Если реивзия была доступна во всех регионах,
     * то добавдяет ее во все регионы кроме указанного
     * @param $regionId - идентификатор региона
     * @return bool
     */
    public function removeFromRegion($regionId)
    {
        $regionObj = new PromotionRegionModel($regionId);
        if (isset($regionObj['region'])) {
            $id = PromotionInRegionModel::getRevisionInRegionId($this->id, $regionId);
            if ($id !== "") {
                return PromotionInRegionModel::deleteElement($id);
            } elseif (static::isRevisionAvailableForRegion($this->id, $regionId)) {
                $regions = PromotionRegionModel::getElementList();
                foreach ($regions as $region) {
                    if ($region instanceof PromotionRegionModel && $region != $region->getId()) {
                        $this->addToRegion($region->getId());
                    }
                }
            }
        }
        return false;
    }

    /**
     * Удаляет ревизию из указанного региона.
     * @param $sectionId - идентификатор региона
     * @return bool
     */
    public function removeFromSection($sectionId)
    {
        $sectionObj = new PromotionSectionModel($sectionId);
        if (isset($sectionObj['section'])) {
            $id = PromotionInSectionModel::getRevisionInSectionId($this->id, $sectionId);
            if ($id !== "") {
                return PromotionInSectionModel::deleteElement($id);
            }
        }
        return false;
    }

    /**
     * Удаляет все записи по регионам делая ревизию доступной длz всех регионов
     */
    public function forAllRegions()
    {
        $array = PromotionInRegionModel::searchByRevision($this->id);
        foreach ($array as $region) {
            if ($region instanceof PromotionInRegionModel) {
                PromotionInRegionModel::deleteElement($region->getId());
            }
        }
    }

    /**
     * Вернет массив PromotionCatalogProduct для данной ревизии
     * @return array - массив PromotionCatalogProduct
     */
    public function getCatalogProducts(): array
    {
        return PromotionCatalogProduct::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $this->id
            )
        );
    }

    /**
     * Вернет массив PromotionCatalogSection для данной ревизии
     * @return array - массив PromotionCatalogSection
     */
    public function getCatalogSections(): array
    {
        return PromotionCatalogSection::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $this->id
            )
        );
    }

    /**
     * Вернет массив PromotionCatalogException для данной ревизии
     * @return array - массив PromotionCatalogException
     */
    public function getCatalogExceptions(): array
    {
        return PromotionCatalogException::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $this->id
            )
        );
    }

    /**
     * Добавляет для данной ревизии продукт из каталога
     * @param string $externalId - внешний код продукта
     * @return bool|int|string
     */
    public function addCatalogProduct(string $externalId)
    {
        return PromotionCatalogProduct::createElement(array('revision' => $this->id, 'product' => $externalId));
    }

    /**
     * Добавляет для данной ревизии секцию из каталога
     * @param string $externalId - внешний код секции
     * @return bool|int|string
     */
    public function addCatalogSection(string $externalId)
    {
        return PromotionCatalogSection::createElement(array('revision' => $this->id, 'section' => $externalId));
    }

    /**
     * Добавляет для данной ревизии исключение для продукта из каталога
     * @param string $externalId - внешний код продукта
     * @return bool|int|string
     */
    public function addCatalogException(string $externalId)
    {
        return PromotionCatalogException::createElement(array('revision' => $this->id, 'product' => $externalId));
    }

    /**
     * Удаляет добавленный продукт каталога из этой ревизии по внешнему коду продукта
     * @param string $externalId
     */
    public function removeCatalogProduct(string $externalId)
    {
        $where = new MySQLWhereString();
        $where->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $this->id
            )
        );
        $where->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                'product',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $externalId
            )
        );
        $elements = PromotionCatalogProduct::getElementList($where);
        foreach ($elements as $element) {
            if ($element instanceof ModelAbstract) {
                PromotionCatalogProduct::deleteElement($element->getId());
            }
        }
    }

    /**
     * Удаляет добавленную секцию каталога из этой ревизии по внешнему коду секции
     * @param string $externalId
     */
    public function removeCatalogSection(string $externalId)
    {
        $where = new MySQLWhereString();
        $where->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $this->id
            )
        );
        $where->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                'section',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $externalId
            )
        );
        $elements = PromotionCatalogSection::getElementList($where);
        foreach ($elements as $element) {
            if ($element instanceof ModelAbstract) {
                PromotionCatalogSection::deleteElement($element->getId());
            }
        }
    }

    /**
     * Удаляет добавленное исключение продукта каталога из этой ревизии по внешнему коду продукта
     * @param string $externalId
     */
    public function removeCatalogException(string $externalId)
    {
        $where = new MySQLWhereString();
        $where->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $this->id
            )
        );
        $where->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                'product',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $externalId
            )
        );
        $elements = PromotionCatalogException::getElementList($where);
        foreach ($elements as $element) {
            if ($element instanceof ModelAbstract) {
                PromotionCatalogException::deleteElement($element->getId());
            }
        }
    }

    /**
     * Возвращает многомерный массив объектов PromotionImageModel определенных для данной ревизии,
     * где в качестве ключа используется ID типа изображения
     * @return array - array[<typeID>] = obj PromotionImageModel
     */
    public function getImages() {
        $images = array();
        $imagesInRevision = PromotionImageInRevisionModel::searchByRevision($this->getId());
        foreach ($imagesInRevision as $imageInRevision) {
            if($imageInRevision instanceof PromotionImageInRevisionModel) {
                $imageId = $imageInRevision->getFieldValue('img');
                $image = new PromotionImageModel($imageId);
                if($image->getFieldValue('type') !== "") {
                    $images[$image->getFieldValue('type')] = $image;
                }
            }
        }
        return $images;
    }

    public function verificationOfEditingRights() {
//        $rsUser = CUser::GetByID(CUser::GetID());
//        $arUser = $rsUser->Fetch();
//        if ($this->originalData['created_user'] == $arUser['LOGIN']) {
//            return true;
//        }
//        return false;
        return true;
    }

    public function hasProblemsWithRegions() {
        $inAllRegions = $this->getFieldValue("in_all_regions");
        if($inAllRegions < 1 && empty(PromotionInRegionModel::searchByRevision($this->id))) {
            return true;
        }
        return false;
    }


    /**
     * Проверяет относится данная ревизия указанной акции
     * @param $revisionId - идентификатор ревизии
     * @param $promotionId - идентификатор акции
     * @return bool
     */
    public static function isRevisionInPromotion(string $revisionId, string $promotionId): bool
    {
        $revision = new PromotionRevisionModel($revisionId);
        $promId = $revision->getFieldValue('promotion');
        return $promId !== null && $promId == $promotionId;
    }

    /**
     * Проверяет доступна ли ревизия для региона
     * Она доступна если строго указана в регионе или если не указана ни для одного региона,
     *      в этом случае она доступна дял всех регионов
     * @param string $revisionId
     * @param string $regionId
     * @return bool
     */
    public static function isRevisionAvailableForRegion(string $revisionId, string $regionId): bool
    {
        $revision = new PromotionRevisionModel($revisionId);
        $inAllRegions = $revision->getFieldValue("in_all_regions");
        return
            PromotionInRegionModel::isRevisionInRegion($revisionId, $regionId) ||
            $inAllRegions > 0;
    }

    /**
     * Проверят доступность ревизии для указанной секции
     * @param string $revisionId - идентификатор ревизии
     * @param string $sectionId - идентификатор секции
     * @return bool
     */
    public static function isRevisionAvailableForSection(string $revisionId, string $sectionId): bool
    {
        return PromotionInSectionModel::isRevisionInSection($revisionId, $sectionId);
    }

    /* ПЕРЕОПРЕДЕЛЕННЫЕ МЕТОДЫ */

    protected static function beforeCreateElement(array &$fieldsValue, array &$attr): bool
    {
        $rsUser = CUser::GetByID(CUser::GetID());
        $arUser = $rsUser->Fetch();
        $fieldsValue['created'] = static::mysqlDateTime();
        $fieldsValue['changed'] = static::mysqlDateTime();
        $fieldsValue['created_user'] = $arUser['LOGIN'];
        if (!isset($fieldsValue['disable'])) {
            $fieldsValue['disable'] = "0";
        }
        if (!isset($fieldsValue['in_all_regions'])) {
            $fieldsValue['in_all_regions'] = "0";
        }
        return true;
    }

    protected static function afterCreateElement($id, $attr): bool
    {
        // Если были заданы регионы,
        // то при создании, ревизия будет добавлена в эти регионы
        if (isset($attr[self::REGIONS_KEY]) && is_array($attr[self::REGIONS_KEY])) {
            foreach ($attr[self::REGIONS_KEY] as $region) {
                PromotionInRegionModel::createElement(array('revision' => $id, 'region' => $region));
            }
        }
        // Если были заданы секции,
        // то при создании, ревизия будет добавлена в эти секции
        if (isset($attr[self::SECTIONS_KEY]) && is_array($attr[self::SECTIONS_KEY])) {
            foreach ($attr[self::SECTIONS_KEY] as $section) {
                PromotionInSectionModel::createElement(array('revision' => $id, 'section' => $section));
            }
        }
        // Если были заданы продукты каталога,
        // то при создании, для ревизии будут добавлены эти продукты каталога
        if (isset($attr[self::CATALOG_PRODUCTS_KEY]) && is_array($attr[self::CATALOG_PRODUCTS_KEY])) {
            foreach ($attr[self::CATALOG_PRODUCTS_KEY] as $product) {
                PromotionCatalogProduct::createElement(array('revision' => $id, 'product' => $product));
            }
        }
        // Если были заданы секции каталога,
        // то при создании, для ревизии будут добавлены эти секции каталога
        if (isset($attr[self::CATALOG_SECTIONS_KEY]) && is_array($attr[self::CATALOG_SECTIONS_KEY])) {
            foreach ($attr[self::CATALOG_SECTIONS_KEY] as $section) {
                PromotionCatalogSection::createElement(array('revision' => $id, 'section' => $section));
            }
        }
        // Если были заданы продукты исключения каталога,
        // то при создании, для ревизии будут добавлены эти продукты исключения каталога
        if (isset($attr[self::CATALOG_EXCEPTIONS_KEY]) && is_array($attr[self::CATALOG_EXCEPTIONS_KEY])) {
            foreach ($attr[self::CATALOG_EXCEPTIONS_KEY] as $product) {
                PromotionCatalogException::createElement(array('revision' => $id, 'product' => $product));
            }
        }
        return true;
    }

    protected function beforeSaveElement(): bool
    {
        if ($this->verificationOfEditingRights()) {
            $this->data['changed'] = static::mysqlDateTime();
            return true;
        }
        return false;
    }

    protected static function beforeUpdateElement($id, array &$updateFieldsValue, array &$attr): bool
    {
        $thisElement = new PromotionRevisionModel($id);
        if ($thisElement->verificationOfEditingRights()) {
            $updateFieldsValue['changed'] = static::mysqlDateTime();
            return true;
        }
        return false;
    }

    protected static function beforeDeleteElement($id, array &$attr): bool
    {
        $thisElement = new PromotionRevisionModel($id);
        return $thisElement->verificationOfEditingRights();
    }

    public function beforeCreateCopy(array &$fieldsValue, array &$attr):bool {
        $applyFromOld = $this->originalData["apply_from"];
        $applyFromNew = $this->originalData["apply_from"];
        if($applyFromOld instanceof \Bitrix\Main\Type\DateTime) {
            $applyFromOld = $applyFromOld->format("Y-m-d H:i:s");
        }
        if($applyFromNew instanceof \Bitrix\Main\Type\DateTime) {
            $applyFromNew = $applyFromNew->format("Y-m-d H:i:s");
        }
        if($applyFromOld == $applyFromNew) {
            $fieldsValue["apply_from"] = static::mysqlDateTime();
        }
        return static::beforeCreateElement($fieldsValue, $attr);
    }

    public function afterCreateCopy(string $id, array &$fieldsValue, array &$attr):bool {
        $promotionInRegion = PromotionInRegionModel::searchByRevision($this->id);
        foreach ($promotionInRegion as $item) {
            if($item instanceof ModelAbstract) {
                $item->createCopy(array("revision"=>$id));
            }
        }
        unset($promotionInRegion);
        $promotionInSection = PromotionInSectionModel::searchByRevision($this->id);
        foreach ($promotionInSection as $item) {
            if($item instanceof ModelAbstract) {
                $item->createCopy(array("revision"=>$id));
            }
        }
        unset($promotionInSection);
        $promotionImageInRevision = PromotionImageInRevisionModel::searchByRevision($this->id);
        foreach ($promotionImageInRevision as $item) {
            if($item instanceof ModelAbstract) {
                $item->createCopy(array("revision"=>$id));
            }
        }
        unset($promotionImageInRevision);
        $promotionCatalogSection = PromotionCatalogSection::searchByRevision($this->id);
        foreach ($promotionCatalogSection as $item) {
            if($item instanceof ModelAbstract) {
                $item->createCopy(array("revision"=>$id));
            }
        }
        unset($promotionCatalogSection);
        $promotionCatalogProduct = PromotionCatalogProduct::searchByRevision($this->id);
        foreach ($promotionCatalogProduct as $item) {
            if($item instanceof ModelAbstract) {
                $item->createCopy(array("revision"=>$id));
            }
        }
        unset($promotionCatalogProduct);
        $promotionCatalogException = PromotionCatalogException::searchByRevision($this->id);
        foreach ($promotionCatalogException as $item) {
            if($item instanceof ModelAbstract) {
                $item->createCopy(array("revision"=>$id));
            }
        }
        unset($promotionCatalogException);
        return true;
    }
}