<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";

class APLS_CatalogProperties
{
    protected static $instance = null;
    protected static $propertiesArray = array();
    protected static $propertiesIDtoXMLID = array();
    protected static $propertiesXMLIDtoID = array();
    protected static $propertiesXMLIDtoCODE = array();
    protected static $propertiesCODEtoXMLID = array();
    protected static $systemPropertiesData = array();
    protected static $systemPropertiesXMLID = array();
    protected static $systemProperties = array();
    protected static $notSystemProperties = array();

    const SYSTEM_CATALOG_PROPERTIES = "SystemCatalogProperties";

    protected function __construct()
    {
        static::updateCatalogActiveProperties();
        static::updateSystemPropertiesData();
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
     * Получение системных свойств
     */
    public static function updateSystemPropertiesData()
    {
        static::$systemPropertiesData = array();
        static::$systemPropertiesXMLID = array();
        static::$systemProperties = array(
            "DETAIL_PROPERTY"=>array(),
            "COMPARE_PROPERTY"=>array(),
            "SMART_FILTER"=>array(),
            "ACTIVITY"=>array()
        );
        $entityDataClass = APLS_GetHighloadEntityDataClass::getByHLName(static::SYSTEM_CATALOG_PROPERTIES);
        $rsData = $entityDataClass::getList(array(
            "select" => array("UF_PROPERTY_XML_ID", "UF_ACTIVITY", "UF_DETAIL_PROPERTY", "UF_COMPARE_PROPERTY", "UF_SMART_FILTER", "UF_SORT"),
            "order" => array("UF_SORT" => "ASC"),
            "filter" => array()
        ));
        while ($arData = $rsData->Fetch()) {
            $xmlId = $arData["UF_PROPERTY_XML_ID"];
            if (in_array($arData["UF_ACTIVITY"],array("1","Y","y","yes",true))) {
                if (in_array($arData["UF_DETAIL_PROPERTY"],array("1","Y","y","yes",true))) {
                    static::$systemProperties["DETAIL_PROPERTY"][] = $xmlId;
                }
                if (in_array($arData["UF_COMPARE_PROPERTY"],array("1","Y","y","yes",true))) {
                    static::$systemProperties["COMPARE_PROPERTY"][] = $xmlId;
                }
                if (in_array($arData["UF_SMART_FILTER"],array("1","Y","y","yes",true))) {
                    static::$systemProperties["SMART_FILTER"][] = $xmlId;
                }
                static::$systemProperties["ACTIVITY"][] = $xmlId;
            }
            static::$systemPropertiesXMLID[] = $arData["UF_PROPERTY_XML_ID"];
            static::$systemPropertiesData[$xmlId]["XML_ID"] = $xmlId;
            static::$systemPropertiesData[$xmlId]["ACTIVITY"] = $arData["UF_ACTIVITY"];
            static::$systemPropertiesData[$xmlId]["DETAIL_PROPERTY"] = $arData["UF_DETAIL_PROPERTY"];
            static::$systemPropertiesData[$xmlId]["COMPARE_PROPERTY"] = $arData["UF_COMPARE_PROPERTY"];
            static::$systemPropertiesData[$xmlId]["SMART_FILTER"] = $arData["UF_SMART_FILTER"];
            static::$systemPropertiesData[$xmlId]["SORT"] = $arData["UF_SORT"];
        }
        static::$notSystemProperties = array_diff(
            static::$propertiesIDtoXMLID,
            static::$systemPropertiesXMLID
        );
    }

    public static function getSystemPropertiesXMLID()
    {
        static::getInstance();
        return static::$systemPropertiesXMLID;
    }

    public static function getSystemPropertiesData()
    {
        static::getInstance();
        return static::$systemPropertiesData;
    }

    public static function getSystemPropertiesACTIVITY()
    {
        static::getInstance();
        return static::$systemProperties["ACTIVITY"];
    }

    public static function getSystemPropertiesDETAIL()
    {
        static::getInstance();
        return static::$systemProperties["DETAIL_PROPERTY"];
    }

    public static function getSystemPropertiesCOMPARE()
    {
        static::getInstance();
        return static::$systemProperties["COMPARE_PROPERTY"];
    }

    public static function getSystemPropertiesSMARTFILTER()
    {
        static::getInstance();
        return static::$systemProperties["SMART_FILTER"];
    }

    public static function getNotSystemProperties()
    {
        static::getInstance();
        return static::$notSystemProperties;
    }

    /**
     * насильно обновляем данные о свойствах компоненты
     */
    public static function updateCatalogActiveProperties()
    {
        static::$propertiesArray = array();
        $properties = CIBlockProperty::GetList(
            array(),
            array("ACTIVE" => "Y", "IBLOCK_ID" => APLS_CatalogHelper::getShopIblockId())
        );
        while ($prop_fields = $properties->GetNext()) {
            static::$propertiesArray[$prop_fields["XML_ID"]] = $prop_fields;
            static::$propertiesIDtoXMLID[$prop_fields["ID"]] = $prop_fields["XML_ID"];
            static::$propertiesXMLIDtoID[$prop_fields["XML_ID"]] = $prop_fields["ID"];
            static::$propertiesXMLIDtoCODE[$prop_fields["XML_ID"]] = $prop_fields["CODE"];
            static::$propertiesCODEtoXMLID[$prop_fields["CODE"]] = $prop_fields["XML_ID"];
        }
    }

    public static function getProperties()
    {
        static::getInstance();
        return static::$propertiesArray;
    }

    public static function getPropertyFromId($id)
    {
        static::getInstance();
        return static::$propertiesArray[static::convertPropertyXMLIDtoID($id)];
    }

    public static function getPropertyFromCode($code)
    {
        static::getInstance();
        return static::$propertiesArray[static::$propertiesCODEtoXMLID[$code]];
    }

    public static function getPropertyFromXmlId($xmlid)
    {
        static::getInstance();
        return static::$propertiesArray[$xmlid];
    }

    public static function getPropertiesIDtoXMLID()
    {
        static::getInstance();
        return static::$propertiesIDtoXMLID;
    }

    public static function getPropertiesXMLIDtoID()
    {
        static::getInstance();
        return static::$propertiesXMLIDtoID;
    }

    public static function getPropertiesXMLIDtoCODE()
    {
        static::getInstance();
        return static::$propertiesXMLIDtoCODE;
    }

    public static function getPropertiesCODEtoXMLID()
    {
        static::getInstance();
        return static::$propertiesCODEtoXMLID;
    }

    public static function convertPropertyXMLIDtoID($xmlid)
    {
        static::getInstance();
        return static::$propertiesXMLIDtoID[$xmlid];
    }

    public static function convertPropertyXMLIDtoCODE($xmlid)
    {
        static::getInstance();
        return static::$propertiesXMLIDtoCODE[$xmlid];
    }

    public static function convertPropertyArrayXMLIDtoID(array $arrXmlId) {
        $arrCode = array();
        foreach ($arrXmlId as $xmlid) {
            $arrCode[] = static::convertPropertyXMLIDtoCODE($xmlid);
        }
        return $arrCode;
    }

    public static function convertPropertyIDtoXMLID($id)
    {
        static::getInstance();
        return static::$propertiesIDtoXMLID[$id];
    }

    public static function convertPropertyIDtoCODE($id)
    {
        static::getInstance();
        return static::$propertiesXMLIDtoCODE[static::convertPropertyIDtoXMLID($id)];
    }

    public static function convertPropertyCODEtoXMLID($code)
    {
        static::getInstance();
        return static::$propertiesCODEtoXMLID[$code];
    }

    public static function convertPropertyCODEtoID($code)
    {
        static::getInstance();
        return static::convertPropertyXMLIDtoID(static::$propertiesCODEtoXMLID[$code]);
    }
}