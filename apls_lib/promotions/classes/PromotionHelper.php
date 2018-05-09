<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLTrait.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogException.php";


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

    public static function getActualPromotionsDataForRegion($region, $activityType = self::DEFAULT_ACTIVITY)
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


    public static function getPromotionsIdByElementId($productXmlId, $region = 'rzn'):array
    {
        $promotionsIdList = array();
        return $promotionsIdList;
    }

    /**Метод возвращает массив ID акций, к которым пркреплен искомый товар в определенной локации, если товар не
     * прикреплен ни к одной акции возвращает пустой массив $resultArray
     * @param $productXmlId - xml_id товара
     * @param string $region -алиас локации
     * @return array
     */
    public static function getPromotionsIdByElementXmlIdOld($productXmlId, $region = 'rzn'):array
    {
        $resultArray = array();
        //*Проверка прикреплен ли товар, к какой-либо акции*//
        //Готовим запрос к базе, на предмет присутсвия искомого товара в списке прикрепленных к какой-либо ревизии
        $productObj = new MySQLWhereString();
        $productObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "product",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $productXmlId)
        );
        //Таких товаров может и не быть...
        if (!empty($result = PromotionCatalogProduct::getElementList($productObj))) {
            foreach ($result as $product) {
                if ($product instanceof PromotionCatalogProduct) {
                    //...а если есть записываем в массив xml этих ревизий
                    $revisionId[] = $product->getFieldValue('revision');
                }
            }
            //Так как ID ревизий может быть несколько перебираем их
            foreach ($revisionId as $id) {
                //Проверяем есть ли наша ревизия в списке текущих ревизий и если есть возвращаем ID акции
                $promotionId = PromotionHelper::checkCurrentRevision($id, $region);
                if ($promotionId) {
                    //результирующий массив
                    $resultArray[] = $promotionId;
                }
            }
        }
        //*Проверка прикреплен ли товар, к исключениям*//
        $exceptionObj = new MySQLWhereString();
        $exceptionObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "product",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $productXmlId)
        );
        if (!empty($result = PromotionCatalogException::getElementList($exceptionObj))) {
            //Получаем массив ревизий, к которым прикреплен данный товар в качестве исключения, это потребуется для
            //проверкок по выборке каталогов товаров
            foreach ($result as $exception) {
                if ($exception instanceof PromotionCatalogException) {
                    //записываем в массив xml этих ревизий, он пригодится при исключении ревизий каталога
                    $revisionExceptionId[] = $exception->getFieldValue('revision');
                }
            }
        }
        //*Проверка прикреплен ли каталог, в котором содержится товар, к какой-либо акции*//
        $whereObj = new MySQLWhereString();
        $productElementID = $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "XML_ID",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $productXmlId)
        );
        //Конвертируем xml_id товара в id
        $result = CatalogElementModel::getElementList($whereObj);
        foreach ($result as $catalog) {
            if ($catalog instanceof CatalogElementModel) {
                $elementId = $catalog->getId();
            }
        }
        //удаляем текущий запрос where
        $whereObj->removeBlock($productElementID);
        //Из смежной таблицы возвращаем объект каталога, в котором находится искомый товар
        $catalogObj = CatalogElementModel::searchByElementId($elementId);
        if (!empty($catalogObj)) {
            foreach ($catalogObj as $catalog) {
                //Получаем из объекта xml_id каталога в кторой находится исходный товар
                $resultCatalogId = $catalog->getFieldValue("XML_ID");
            }
        } else {
            return $resultArray;
        }

        //Готовим запрос к таблице, которая содержит связь ревизия акции - секция каталога товаров
        $catalogElementID = $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "section",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $resultCatalogId)
        );
        //Может придти пустое значение, сразу проверяем и если что-то пришло
        if (!empty($result = PromotionCatalogSection::getElementList($whereObj))) {
            foreach ($result as $catalog) {
                if ($catalog instanceof PromotionCatalogSection) {
                    //...получаем массив ID ревизий, к которым прикреплен каталог товаров
                    $revisionCatalogId[] = $catalog->getFieldValue('revision');
                }
            }
            $whereObj->removeElement($catalogElementID);
            //проверяем есть ли схождения в ревизиям между исключениями и выбраными каталогами
            if(isset($revisionExceptionId) && !empty($revisionExceptionId)) {
                //и удаляем лишние ИД ревизий
                    $revisionCatalogId = array_diff($revisionCatalogId, $revisionExceptionId);

            }
            //Так как ID ревизий может быть несколько перебираем их
            foreach ($revisionCatalogId as $id) {
                //Проверяем есть ли наша ревизия в списке текущих ревизий и если есть возвращаем ID акции
                $promotionId = PromotionHelper::checkCurrentRevision($id, $region);
                if ($promotionId) {
                    //результирующий массив
                    $resultArray[] = $promotionId;
                } else {
                    return $resultArray;
                }
            }
            if ($resultArray) {
                //точка выхода с положительным результатом
                return $resultArray;
            }
        } else {
            return $resultArray;
        }
    }

    /** Возвращает ID акции при условии, что ревизия актуальна, либо FALSE
     * @param $revisionId - ID ревизии из базы акций
     * @param $region - алиас региона
     * @return bool|mixed
     */
    protected static function checkCurrentRevision($revisionId, $region)
    {
        $cityId = PromotionHelper::getCityIdByAlias($region);
        $actualPromotions = PromotionHelper::getActualPromotionsDataForRegion($cityId);
        if (in_array($revisionId, $actualPromotions['revisions'])) {
            $whereObj = new MySQLWhereString();
            $whereObj->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    "ID",
                    MySQLWhereElementString::OPERATOR_B_EQUAL,
                    $revisionId)
            );
            $result = PromotionRevisionModel::getElementList($whereObj);
            foreach ($result as $revision) {
                if ($revision instanceof PromotionRevisionModel) {
                    $resultPromotionId = $revision->getFieldValue('promotion');
                }
            }
            return $resultPromotionId;
        } else {
            return false;
        }
    }

    /** Возвращает ИД локации по алиасу
     * @param $alias - Алиас локации применения акций
     * @return string
     */
    protected static function getCityIdByAlias($alias): string
    {
        $whereObjCity = new MySQLWhereString();
        $whereObjCity->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "alias",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $alias)
        );
        $result = PromotionRegionModel::getElementList($whereObjCity);
        foreach ($result as $city) {
            if ($city instanceof PromotionRegionModel) {
                $cityId = $city->getId();
            }
        }
        return $cityId;
    }

    /** Возвращает ID товара по XML_ID
     * @param $xml XML_ID товара
     * @return int|string
     */
    public static function getProductIdByXml ($xml):int
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
}