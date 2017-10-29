<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";

class APLS_CatalogConfigurator
{

    protected static $instance = null;
    protected static $propertiesArray = array();

    const HIGHLOAD_PROPERTIES_PARAMS = "FilterPropertiesParams";
    const DETAIL_PROPERTY_FIELD = "UF_DETAIL_PROPERTY";
    const COMPARE_PROPERTY_FIELD = "UF_COMPARE_PROPERTY";
    const SMART_FILTER_FIELD = "UF_SMART_FILTER";

    /**
     * APLS_CatalogConfigurator constructor.
     */
    protected function __construct() {
        $catalogId = APLS_GetGlobalParam::getParams("HIGHLOAD_CATALOG_ID");
        $properties = CIBlockProperty::GetList(
            array(),
            array("ACTIVE"=>"Y", "IBLOCK_ID"=>$catalogId)
        );
        while ($prop_fields = $properties->GetNext())
        {
            static::$propertiesArray[$prop_fields["XML_ID"]] = $prop_fields;
        }
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
     * возвращает массмов COMPARE_PROPERTY_CODE для bitrix:catalog
     */
    public static function getComparePropertyCode () {
        return static::getPropertiesCodeFromHLPropertiesParams(static::COMPARE_PROPERTY_FIELD);
    }

    /**
     * возвращает массмов DETAIL_PROPERTY_CODE для bitrix:catalog
     */
    public static function getDetailPropertyCode () {
        return static::getPropertiesCodeFromHLPropertiesParams(static::DETAIL_PROPERTY_FIELD);
    }

    /**
     * возвращает массив всех активных свойств каталога
     * @return array - массив всех активнх свойств основного каталога
     */
    public static function getAllActiveCatalogProperties () {
        static::getInstance();
        return static::$propertiesArray;
    }

    /**
     * @param string $field - ключ поля по которому будут обобраны своства из HL Properties Params
     * @return array - массив ключей свойств
     */
    private static function getPropertiesCodeFromHLPropertiesParams($field) {
        static::getInstance();
        try {
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName(static::HIGHLOAD_PROPERTIES_PARAMS);
            $rsData = $entity_data_class::getList(array(
                "select" => array('UF_XML_ID',$field),
                "filter" => array($field => true)
            ));
            $properties = array();
            while ($arData = $rsData->Fetch()) {
                if(isset(static::$propertiesArray[$arData['UF_XML_ID']]["CODE"])) {
                    $properties[] = static::$propertiesArray[$arData['UF_XML_ID']]["CODE"];
                }
            }
            return $properties;
        } catch (Exception $e) {
            return array();
        }
    }
}