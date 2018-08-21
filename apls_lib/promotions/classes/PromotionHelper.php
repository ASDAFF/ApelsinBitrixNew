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

    private static $actualPromotionsCatalogSections = array();
    private static $actualPromotionsCatalogProducts = array();
    private static $actualPromotionsCatalogExceptions = array();

    /**
     * Принудительно обновляет и возвращает данные по актуальным акцим
     * Результирующий массив имеет структуру:
     * array (
     *      promotions => <массив действующих акций>
     *      revisions => <массив действующих ревизий>
     *      sections => <массив актуальных разделов действующих акций>
     *      promotionsInSections => array (
     *          <ключ секции> => массив акций
     *          ...
     *      )
     *      revisionPromotion => array (
     *          <id ревизии> => <id акции>
     *          ...
     *      )
     * )
     *
     * @param $region - регион действия акции
     * @param string $activityType - тип активности
     * @return array - массив результирующих данных
     */
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
                    REV.`start_from`,
                    REV.`in_all_regions`
                    FROM (
                        SELECT
                        REV.`id`, 
                        REV.`promotion`,
                        REV.`global_activity`,
                        REV.`local_activity`,
                        REV.`vk_activity`,
                        REV.`show_from`,
                        REV.`start_from`,
                        REV.`in_all_regions`
                        FROM (
                            SELECT 
                            `id`, 
                            `promotion`,
                            `global_activity`,
                            `local_activity`,
                            `vk_activity`,
                            `show_from`,
                            `start_from`,
                            `in_all_regions`
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
                        REV.`in_all_regions` > 0
                    )";
        $records = static::getConnection(self::CONNECTION_NAME)->query($sql);
        $promotions = array();
        $revisions = array();
        $sections = array();
        $promotionsInSections = array();
        $revisionPromotion = array();
        while ($record = $records->fetch()) {
            $promotionsInSections[$record['section']][] = $record['promotion'];
            $revisionPromotion[$record['revision']] = $record['promotion'];
            $promotions[] = $record['promotion'];
            $revisions[] = $record['revision'];
            $sections[] = $record['section'];
        }
        $result = array();
        $orderByObj = new MySQLOrderByString();
        $orderByObj->add('sort',MySQLOrderByString::ASC);
        $allSections = PromotionSectionModel::getElementList(null,null,null,$orderByObj);
        foreach ($allSections as $section) {
            if($section instanceof PromotionSectionModel and in_array($section->getId(),$sections)) {
                $result['sections'][] = $section->getId();
            }
        }
        $result['promotions'] = array_unique($promotions);
        $result['revisions'] = array_unique($revisions);
        $result['promotionsInSections'] = $promotionsInSections;
        $result['revisionPromotion'] = $revisionPromotion;
        return $result;
    }

    /**
     * Возвращает данные по актуальным акцим
     * Результирующий массив имеет структуру:
     * array (
     *      promotions => <массив действующих акций>
     *      revisions => <массив действующих ревизий>
     *      sections => <массив актуальных разделов действующих акций>
     *      promotionsInSections => array (
     *          <ключ секции> => массив акций
     *          ...
     *      )
     *      revisionPromotion => array (
     *          <id ревизии> => <id акции>
     *          ...
     *      )
     * )
     *
     * @param $region - регион действия акции
     * @param string $activityType - тип активности
     * @return array - массив результирующих данных
     */
    public static function getActualPromotionsDataForRegion($region, $activityType = self::DEFAULT_ACTIVITY) {
        if(!isset(static::$actualPromotions[$region][$activityType])) {
            static::$actualPromotions[$region][$activityType] = static::getActualPromotionsDataForRegionNoCache($region,$activityType);
        }
        return static::$actualPromotions[$region][$activityType];
    }

    /**
     * вернет список XML_ID секций каталога для указанной ревизии
     * @param $revisionId - id ревизии
     * @return array - массив секций каталога
     */
    public static function getActualPromotionCatalogSectionByRevision($revisionId):array {
        if(!isset(static::$actualPromotionsCatalogSections[$revisionId])) {
            $result = PromotionCatalogSection::getElementList(
                MySQLWhereElementString::getBinaryOperationString (
                    "revision",
                    MySQLWhereElementString::OPERATOR_B_EQUAL,
                    $revisionId
                )
            );

            static::$actualPromotionsCatalogSections[$revisionId] = array();
            foreach ($result as $element) {
                if($element instanceof ModelAbstract) {
                    $section = $element->getFieldValue('section');
                    static::$actualPromotionsCatalogSections[$revisionId][] = $section;
                    $catalogChildren = APLS_CatalogSections::getAllChildrenListForSection($section);
                    foreach ($catalogChildren as $child) {
                        static::$actualPromotionsCatalogSections[$revisionId][] = $child;
                    }
                }
            }
        }
        return static::$actualPromotionsCatalogSections[$revisionId];
    }

    /**
     * вернет список XML_ID товаров для указанной ревизии
     * @param $revisionId - id ревизии
     * @return array - массив товаров
     */
    public static function getActualPromotionCatalogProductsByRevision($revisionId) {
        if(!isset(static::$actualPromotionsCatalogProducts[$revisionId])) {
            $result = PromotionCatalogProduct::getElementList(
                MySQLWhereElementString::getBinaryOperationString (
                    "revision",
                    MySQLWhereElementString::OPERATOR_B_EQUAL,
                    $revisionId
                )
            );
            static::$actualPromotionsCatalogProducts[$revisionId] = array();
            foreach ($result as $element) {
                if($element instanceof ModelAbstract) {
                    static::$actualPromotionsCatalogProducts[$revisionId][] = $element->getFieldValue('product');
                }
            }
        }
        return static::$actualPromotionsCatalogProducts[$revisionId];
    }

    /**
     * вернет список XML_ID товаров-исключений для указанной ревизии
     * @param $revisionId - id ревизии
     * @return array - массив товаров-исключений
     */
    public static function getActualPromotionCatalogExceptionsByRevision($revisionId) {
        if(!isset(static::$actualPromotionsCatalogExceptions[$revisionId])) {
            $result = PromotionCatalogException::getElementList(
                MySQLWhereElementString::getBinaryOperationString (
                    "revision",
                    MySQLWhereElementString::OPERATOR_B_EQUAL,
                    $revisionId
                )
            );
            static::$actualPromotionsCatalogExceptions[$revisionId] = array();
            foreach ($result as $element) {
                if($element instanceof ModelAbstract) {
                    static::$actualPromotionsCatalogExceptions[$revisionId][] = $element->getFieldValue('product');
                }
            }
        }
        return static::$actualPromotionsCatalogExceptions[$revisionId];
    }

    /**
     * Метод возвращает массив ID акций, к которым пркреплен искомый товар в определенной локации, если товар не
     * прикреплен ни к одной акции возвращает пустой массив $resultArray
     * @param $productId - id товара
     * @param $productXmlId - xml_id товара
     * @param $region - ID локации акции
     * @return array
     */
    public static function getPromotionsId($productId, $productXmlId, $region): array {
        //Создаем результирующий массив
        $promotionIdList = array();
        $revisionsIdList = array();
        //Получаем массив всех активных акций, ревизий
        $actualPromotions = PromotionHelper::getActualPromotionsDataForRegion($region);
        foreach ($actualPromotions['revisions'] as $revisionId) {
            $exceptions = static::getActualPromotionCatalogExceptionsByRevision($revisionId);
            if(empty($exceptions) || !in_array($productXmlId,$exceptions)) {
                $products = static::getActualPromotionCatalogProductsByRevision($revisionId);
                if(!empty($products) && in_array($productXmlId,$products)) {
                    $revisionsIdList[] = $revisionId;
                } else {
                    $sections = static::getActualPromotionCatalogSectionByRevision($revisionId);
                    if(!empty($sections)) {
                        $referenceCatalogId = CatalogElementModel::searchByElementId($productId);
                        if(!empty($referenceCatalogId)) {
                            $section = array_shift($referenceCatalogId)->getFieldValue("XML_ID");
                            if(in_array($section,$sections)) {
                                $revisionsIdList[] = $revisionId;
                            }
                        }
                    }
                }
            }
        }
        //При нахождении совпадений и записи в массив подходящих ревизий $resultRevisionId,
        // перебираем их и получаем ИД соответсвующей акции
        foreach ($revisionsIdList as $revId) {
            $promotionIdList[] = $actualPromotions['revisionPromotion'][$revId];
        }
        //Точка выхода с пустым массивом
        return $promotionIdList;
    }
}