<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLTrait.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogException.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogProduct.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogSection.php";


class PromotionHelper
{
    use MySQLTrait;

    const CONNECTION_NAME = "APLS_PROMOTION";

    const GLOBAL_ACTIVITY = "global_activity";
    const LOCAL_ACTIVITY = "local_activity";
    const VK_ACTIVITY = "vk_activity";
    const DEFAULT_ACTIVITY = self::GLOBAL_ACTIVITY;

    private static $activityTypes = array(
        self::GLOBAL_ACTIVITY,
        self::LOCAL_ACTIVITY,
        self::VK_ACTIVITY
    );

    private static $actualPromotions = array();

    public static function getActualPromotionsDataForRegionNoCache($region, $activityType = self::DEFAULT_ACTIVITY)
    {
        if (!in_array($activityType, static::$activityTypes)) {
            $activityType = self::DEFAULT_ACTIVITY;
        }
        $sql = "SELECT
                    REV.`promotion`, 
                    RIS.`revision`, 
                    RIS.`section`,
                    REV.`global_activity`,
                    REV.`local_activity`,
                    REV.`vk_activity`,
                    REV.`show_from`,
                    REV.`start_from`
                    FROM (
                        SELECT
                        REV.`id`, 
                        REV.`promotion`,
                        REV.`global_activity`,
                        REV.`local_activity`,
                        REV.`vk_activity`,
                        REV.`show_from`,
                        REV.`start_from`
                        FROM (
                            SELECT 
                            `id`, 
                            `promotion`,
                            `global_activity`,
                            `local_activity`,
                            `vk_activity`,
                            `show_from`,
                            `start_from`
                            FROM `apls_promotions_revision` 
                            WHERE 
                            `disable`<'1' AND
                            `apply_from` < now()
                            order by `apply_from` DESC
                        ) as REV
                        group by REV.`promotion`
                    ) as REV
                    RIGHT JOIN `apls_promotions_in_sections` as RIS
                    on REV.`id` = RIS.`revision`
                    WHERE 
                    `$activityType` > '0' AND
                    (
                      (`show_from` IS NOT NULL AND `show_from`<=now()) OR
                      (`show_from` IS NULL AND `start_from` IS NOT NULL AND `start_from`<=now()) OR
                      (`show_from` IS NULL AND `start_from` IS NULL)
                    ) AND
                    REV.`promotion` IS NOT NULL AND 
                    (
                        RIS.`revision` IN (SELECT `revision` FROM `apls_promotions_in_region` WHERE `region`='$region') OR
                        RIS.`revision` NOT IN (SELECT `revision` FROM `apls_promotions_in_region` WHERE `region`<>'$region')
                    )";
        $records = static::getConnection(self::CONNECTION_NAME)->query($sql);
        $promotions = array();
        $revisions = array();
        $sections = array();
        $promotionsInSections = array();
        while ($record = $records->fetch()) {
            $promotionsInSections[$record['section']][] = $record['promotion'];
            $promotions[] = $record['promotion'];
            $revisions[] = $record['revision'];
            $sections[] = $record['section'];
        }
        $result = array();
        $result['promotions'] = array_unique($promotions);
        $result['revisions'] = array_unique($revisions);
        $result['sections'] = array_unique($sections);
        $result['promotionsInSections'] = $promotionsInSections;
        return $result;
    }

    public static function getActualPromotionsDataForRegion($region, $activityType = self::DEFAULT_ACTIVITY) {
        if(!isset(static::$actualPromotions[$region][$activityType])) {
            static::$actualPromotions[$region][$activityType] = static::getActualPromotionsDataForRegionNoCache($region,$activityType);
        }
        return static::$actualPromotions[$region][$activityType];
    }

    /**
     * Метод возвращает массив ID акций, к которым пркреплен искомый товар в определенной локации, если товар не
     * прикреплен ни к одной акции возвращает пустой массив $resultArray
     * @param $productId - ID товара
     * @param $region - ID региона
     * @return array
     */
    public static function getPromotionsIdByElementId($productId, $region):array
    {
        // должны получить xmlId товара по его Id
        $productXmlId = PromotionHelper::getProductXmlIdById ($productId);
        // должны запустить метод getPromotionsIdByElementXmlId
        return PromotionHelper::getPromotionsIdByElementXmlId($productXmlId, $region);
    }

    /**
     * Метод возвращает массив ID акций, к которым пркреплен искомый товар в определенной локации, если товар не
     * прикреплен ни к одной акции возвращает пустой массив $resultArray
     * @param $productXmlId - xml_id товара
     * @param $region - ID локации акции
     * @return array
     */
    public static function getPromotionsIdByElementXmlId($productXmlId, $region): array
    {
        //Получаем массив всех активных акций, ревизий
        $actualPromotions = PromotionHelper::getActualPromotionsDataForRegion($region);
        $whereObj = new MySQLWhereString();
        //Создаем результирующий массив
        $resultPromotionId = array();
        //Перебираем подмассив ревизий
        foreach ($actualPromotions['revisions'] as $revisionId) {
            //Формируем запрос к таблице соответвий ревизий добавленных к ним товаров
            $whereObjProduct = $whereObj->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    "revision",
                    MySQLWhereElementString::OPERATOR_B_EQUAL,
                    $revisionId)
            );
            $resultProduct = PromotionCatalogProduct::getElementList($whereObj);
            foreach ($resultProduct as $product) {
                //Перебираем все полученные XML_ID товаров и сразу сравниваем с XML_ID искомого товара
                if (
                    $product instanceof PromotionCatalogProduct &&
                    $productXmlId == $product->getFieldValue('product')
                ) {
                    //Записываем совпадения в результирующий массив
                    $resultRevisionId[] = $revisionId;
                }
            }
            //Удаляем элемент запроса к базе
            $whereObj->removeElement($whereObjProduct);
            //Формируем новый запрос к таблице соответвий ревизий и добавленным к ним каталогам
            $whereObjCatalog = $whereObj->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    "revision",
                    MySQLWhereElementString::OPERATOR_B_EQUAL,
                    $revisionId)
            );
            $resultCatalog = PromotionCatalogSection::getElementList($whereObj);
            if (!empty($resultCatalog)) {
                foreach ($resultCatalog as $catalog) {
                    //Перебираем все полученные XML_ID каталогов и проверяем состоит ли искомый товар в каталоге и добавлен ли товар в искдючения к данной ревизии
                    if (
                        $catalog instanceof PromotionCatalogSection &&
                        PromotionHelper::checkProductXmlIdIsSubjectToCatalog($productXmlId, $catalog->getFieldValue('section')) &&
                        PromotionHelper::checkProductXmlIdIsSubjectToException($productXmlId, $revisionId)
                    ) {
                        //Записываем совпадения в результирующий массив
                        $resultRevisionId[] = $revisionId;
                    }
                }
            }
            //Удаляем элемент запроса к базе
            $whereObj->removeElement($whereObjCatalog);
        }
        //При нахождении совпадений и записи в массив подходящих ревизий $resultRevisionId, перебираем их и получаем ИД соответсвующей акции
        if (!empty($resultRevisionId)) {
            foreach ($resultRevisionId as $revId) {
                $whereObjRevision = $whereObj->addElement(
                    MySQLWhereElementString::getBinaryOperationString(
                        "ID",
                        MySQLWhereElementString::OPERATOR_B_EQUAL,
                        $revId)
                );
                $resultRevision = PromotionRevisionModel::getElementList($whereObj);
                foreach ($resultRevision as $revision) {
                    if ($revision instanceof PromotionRevisionModel) {
                        //Записываем получившиеся значения в итоговый массив
                        $resultPromotionId[] = $revision->getFieldValue('promotion');
                    }
                }
                $whereObj->removeElement($whereObjRevision);
            }
            //Точка выхода с какими-либо значениями
            return $resultPromotionId;
        }
        //Точка выхода с пустым массивом
        return $resultPromotionId;
    }

    /**
     * Проверяет присутствует ли товар в каталоге
     * @param $productXmlId - xml_id товара
     * @param $catalogXmlId - xml_id каталога
     * @return bool - Возвращает TRUE если товар присутствует в каталоге
     */
    protected static function checkProductXmlIdIsSubjectToCatalog($productXmlId, $catalogXmlId)
    {
        $productId = PromotionHelper::getProductIdByXml($productXmlId);
        $referenceCatalogId = CatalogElementModel::searchByElementId($productId);
        if (!empty($referenceCatalogId)) {
            foreach ($referenceCatalogId as $catalog) {
                $resultCatalogId = $catalog->getFieldValue("XML_ID");
            }
            if ($resultCatalogId == $catalogXmlId) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * Проверяет присутствует ли товар в исключениях в конкретной ревизии
     * @param $productXmlId - xml_id товара
     * @param $revisionId - ID ревизии
     * @return bool - Возвращает TRUE если исключенный товар присутствует в ревизии
     */
    protected static function checkProductXmlIdIsSubjectToException($productXmlId, $revisionId)
    {
        $whereObj = new MySQLWhereString();
        $select = $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "revision",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $revisionId)
        );
        $result = PromotionCatalogException::getElementList($whereObj);
        foreach ($result as $exception) {
            if ($exception instanceof PromotionCatalogException) {
                $resultProductId = $exception->getFieldValue('product');
            }
        }
        $whereObj->removeElement($select);
        if ($productXmlId == $resultProductId) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /** Возвращает ID товара по XML_ID
     * @param $xml XML_ID товара
     * @return int|string
     */
    public static function getProductIdByXml($xml): int
    {
        $whereObj = new MySQLWhereString();
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "XML_ID",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $xml)
        );
        $result = CatalogElementModel::getElementList($whereObj);
        foreach ($result as $product) {
            if ($product instanceof CatalogElementModel) {
                $productId = $product->getId();
            }
        }
        return $productId;
    }

    /**
     * Возвращает XML_ID по ID
     * @param $productId - ID товара
     * @return string
     */
    public static function getProductXmlIdById ($productId):string {
        $whereObj = new MySQLWhereString();
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "ID",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $productId)
        );
        $result = CatalogElementModel::getElementList($whereObj);
        foreach ($result as $product) {
            if ($product instanceof CatalogElementModel) {
                $productXmlid = $product->getFieldValue('XML_ID');
            }
        }
        return $productXmlid;
    }
}