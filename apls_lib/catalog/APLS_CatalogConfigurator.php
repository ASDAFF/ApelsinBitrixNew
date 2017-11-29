<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSectionsTree.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogProperties.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSectionsProperties.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";

class APLS_CatalogConfigurator
{

    protected static $instance = null;
    protected static $propertiesArray = array();
    protected static $propertiesIDtoXMLID = array();
    protected static $propertiesXMLIDtoCODE = array();
    protected static $HLPropParamsEntityDataClass = null;
    protected static $HLPropParamsSortEntityDataClass = null;
    protected static $HLPropParamsSortArray = null;
    protected static $catalogSectionsTreeOBJ = null;
    const HIGHLOAD_PROPERTIES_PARAMS = "FilterPropertiesParams";
    const HIGHLOAD_PROPERTIES_SORT = "PropertyGoods";
    const XML_ID_FIELD = "UF_XML_ID";
    const DETAIL_PROPERTY_FIELD = "UF_DETAIL_PROPERTY";
    const COMPARE_PROPERTY_FIELD = "UF_COMPARE_PROPERTY";
    const SMART_FILTER_FIELD = "UF_SMART_FILTER";
    const ACTIVITY_FIELD = "UF_ACTIVITY";
    const APPROVED_FIELD = "UF_APPROVED";
    const DEFAULT_DETAIL_PROPERTY_VALUE = 1;
    const DEFAULT_COMPARE_PROPERTY_VALUE = 1;
    const DEFAULT_SMART_FILTER_VALUE = 0;
    const DEFAULT_SORT_INDEX = 3000;

    /**
     * APLS_CatalogConfigurator constructor.
     */
    protected function __construct()
    {
        static::$HLPropParamsEntityDataClass = APLS_GetHighloadEntityDataClass::getByHLName(static::HIGHLOAD_PROPERTIES_PARAMS);
        static::updateCatalogActiveProperties();
    }

    /**
     * @return - реализация Singleton
     */
    protected static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * функция синхронизируется с HL-блоком настроки свойств и со свойствами каталога,
     * после чего производит соответствующие изменения в HL-блоке
     * @return true | false
     */
    public static function updateHighloadPropertiesParams()
    {
        static::getInstance();
        try {
            $propertiesParamsXMLIDtoID = array(); // внешние коды свойств из HL-блока
            $propertiesParamsXMLID = array(); // внешние коды свойств из HL-блока
            $propertiesParamsXMLIDnoActiv = array(); // внешние коды неактивных свойств из HL-блока
            // поулчаем данные из HL-блока
            $rsData = static::$HLPropParamsEntityDataClass::getList(array(
                "select" => array('ID', static::XML_ID_FIELD, static::ACTIVITY_FIELD),
                "filter" => array()
            ));
            while ($arData = $rsData->Fetch()) {
                // массив соответствий внешних кодов и id свойств из HL-блока
                $propertiesParamsXMLIDtoID[$arData[static::XML_ID_FIELD]] = $arData['ID'];
                // сохраняем в массив $propertiesParamsXMLID внешние ключи всех элементов из HL-блока
                $propertiesParamsXMLID[] = $arData[static::XML_ID_FIELD];
                // сохраняем в массив $propertiesParamsXMLIDnoActiv внешние ключи всех неактивных элементов из HL-блока
                if (!$arData[static::ACTIVITY_FIELD]) {
                    $propertiesParamsXMLIDnoActiv[] = $arData[static::XML_ID_FIELD];
                }
            }
            // поулчаем внешние ключи всех активных свойств основного инфоблока каталога
            $propertiesXMLID = array_keys(static::$propertiesArray);
            // получаем список всех свойств которые нужно добавить в HL-блок
            $newProperties = array_diff($propertiesXMLID, $propertiesParamsXMLID);
            // получаем список всех свойств которые нужно деактивировать в HL-блоке
            $toDeactivate = array_diff($propertiesParamsXMLID, $propertiesXMLID, $propertiesParamsXMLIDnoActiv);
            // получаем список всех свойств которые нужно активировать в HL-блоке
            $toActivate = array_diff($propertiesParamsXMLIDnoActiv, $toDeactivate);

            // деактивация свойств в HL-блоке
            foreach ($toDeactivate as $item) {
                static::$HLPropParamsEntityDataClass::update($propertiesParamsXMLIDtoID[$item], array(static::ACTIVITY_FIELD => 0));
            }
            // активация свойств в HL-блоке
            foreach ($toActivate as $item) {
                static::$HLPropParamsEntityDataClass::update($propertiesParamsXMLIDtoID[$item], array(static::ACTIVITY_FIELD => 1));
            }
            // добавление свойств в HL-блоке
            foreach ($newProperties as $item) {
                static::$HLPropParamsEntityDataClass::add(array(
                    static::XML_ID_FIELD => $item,
                    static::ACTIVITY_FIELD => 1,
                    static::APPROVED_FIELD => 0,
                    static::DETAIL_PROPERTY_FIELD => static::DEFAULT_DETAIL_PROPERTY_VALUE,
                    static::COMPARE_PROPERTY_FIELD => static::DEFAULT_COMPARE_PROPERTY_VALUE,
                    static::SMART_FILTER_FIELD => static::DEFAULT_SMART_FILTER_VALUE,
                ));
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * насильно обновляем данные о свойствах компоненты
     */
    public static function updateCatalogActiveProperties()
    {
        APLS_CatalogProperties::updateCatalogActiveProperties();
        static::$propertiesArray = APLS_CatalogProperties::getProperties();
        static::$propertiesIDtoXMLID = APLS_CatalogProperties::getPropertiesIDtoXMLID();
        static::$propertiesXMLIDtoCODE = APLS_CatalogProperties::getPropertiesXMLIDtoCODE();
    }

    /**
     * обновляет индекс сортировки у всех свойств
     */
    public static function updateAllCatalogPropertySortIndex()
    {
        // получаем XML_ID свойсвта по его индексу
        $catalogId = APLS_GetGlobalParam::getParams("HIGHLOAD_CATALOG_ID");
        $properties = CIBlockProperty::GetList(
            array("ID"),
            array("IBLOCK_ID" => $catalogId)
        );
        while ($prop_fields = $properties->GetNext()) {
            static::updateCatalogPropertySortIndex($prop_fields["ID"]);
        }
    }

    /**
     * обновляет индекс сортировки у свойства по его ID
     * @param $propertyId - id свойства для обновления индекса сортировки
     * @return bool - true | false
     */
    public static function updateCatalogPropertySortIndex($propertyId)
    {
        static::getInstance();
        $xml_id = null;
        $sortIndex = static::DEFAULT_SORT_INDEX;
        // получаем XML_ID свойсвта по его индексу
        $catalogId = APLS_GetGlobalParam::getParams("HIGHLOAD_CATALOG_ID");
        $properties = CIBlockProperty::GetList(
            array("XML_ID"),
            array("ID" => $propertyId, "IBLOCK_ID" => $catalogId)
        );
        while ($prop_fields = $properties->GetNext()) {
            $xml_id = $prop_fields["XML_ID"];
        }
        // првоеряем удалось ли получить XML_ID свойсвта
        if ($xml_id !== null) {
            // проверяем создан ли список отсортированных свойств и если нет, то создаем
            if (static::$HLPropParamsSortArray === null) {
                static::$HLPropParamsSortEntityDataClass = APLS_GetHighloadEntityDataClass::getByHLName(static::HIGHLOAD_PROPERTIES_SORT);
                $rsData = static::$HLPropParamsSortEntityDataClass::getList(array(
                    "select" => array('UF_XML_ID', 'UF_SORT'),
                    "filter" => array()
                ));
                while ($arData = $rsData->Fetch()) {
                    static::$HLPropParamsSortArray[$arData["UF_XML_ID"]] = $arData["UF_SORT"];
                }
            }
            // если текущее свойство есть среди отсортированных то присваиваем известный индекс сортировки
            if (isset(static::$HLPropParamsSortArray[$xml_id])) {
                $sortIndex = static::$HLPropParamsSortArray[$xml_id];
            }
            $ibp = new CIBlockProperty;
            echo "<pre>";
            var_dump($propertyId);
            var_dump($xml_id);
            var_dump($sortIndex);
            return $ibp->Update($propertyId, array("SORT" => $sortIndex,));
        }
        return false;
    }
}
