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

    protected function __construct()
    {
        static::updateСatalogActiveProperties();
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
     * насильно обновляем данные о свойствах компоненты
     */
    public static function updateСatalogActiveProperties()
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
        return static::$propertiesArray[static::getPropertyXmlIDFromId($id)];
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

    public static function getPropertiesIDtoXMLID() {
        static::getInstance();
        return static::$propertiesIDtoXMLID;
    }

    public static function getPropertiesXMLIDtoID() {
        static::getInstance();
        return static::$propertiesXMLIDtoID;
    }

    public static function getPropertiesXMLIDtoCODE() {
        static::getInstance();
        return static::$propertiesXMLIDtoCODE;
    }

    public static function getPropertiesCODEtoXMLID() {
        static::getInstance();
        return static::$propertiesCODEtoXMLID;
    }

    public static function convertPropertyXMLIDtoID($xmlid) {
        static::getInstance();
        return static::$propertiesXMLIDtoID[$xmlid];
    }

    public static function convertPropertyXMLIDtoCODE($xmlid) {
        static::getInstance();
        return static::$propertiesXMLIDtoCODE[$xmlid];
    }

    public static function convertPropertyIDtoXMLID($id) {
        static::getInstance();
        return static::$propertiesIDtoXMLID[$id];
    }

    public static function convertPropertyIDtoCODE($id) {
        static::getInstance();
        return static::$propertiesXMLIDtoCODE[static::convertPropertyIDtoXMLID($id)];
    }

    public static function convertPropertyCODEtoXMLID($code) {
        static::getInstance();
        return static::$propertiesCODEtoXMLID[$code];
    }

    public static function convertPropertyCODEtoID($code) {
        static::getInstance();
        return convertPropertyXMLIDtoID(static::$propertiesCODEtoXMLID[$code]);
    }
}