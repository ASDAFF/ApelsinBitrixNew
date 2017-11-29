<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSectionsProperties.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogProperties.php";

class APLS_CatalogSectionsSettings
{
    private static $systemCatalogEntityDataClass = null;  // self::getSystemCatalogEntityDataClass()
    private static $HLPropParamsEntityDataClass = null;  // self::getSystemCatalogEntityDataClass()

    const SYSTEM_CATALOG_PROPERTIES = "SystemCatalogProperties";
    const HIGHLOAD_PROPERTIES_PARAMS = "FilterPropertiesParams";
    const XML_ID_FIELD = "UF_XML_ID";
    const PROPERTY_XML_ID_FIELD = "UF_XML_ID";
    const DETAIL_PROPERTY_FIELD = "UF_DETAIL_PROPERTY";
    const COMPARE_PROPERTY_FIELD = "UF_COMPARE_PROPERTY";
    const SMART_FILTER_FIELD = "UF_SMART_FILTER";
    const ACTIVITY_FIELD = "UF_ACTIVITY";
    const APPROVED_FIELD = "UF_APPROVED";



    private function __construct()
    {
        static::$HLPropParamsEntityDataClass = APLS_GetHighloadEntityDataClass::getByHLName(static::HIGHLOAD_PROPERTIES_PARAMS);
    }

    public static function getSmartFilterRezultArray(&$properies)
    {
        $arrXmlId = static::getSmartFilterSettings(static::getCatalogExternalId());
        $smartFilter = APLS_CatalogProperties::convertPropertyArrayXMLIDtoID($arrXmlId);
        foreach ($properies as $key => $propery) {
            if (!in_array($propery["CODE"], $smartFilter)) {
                unset($properies[$key]);
            }
        }
    }

    public static function getComparePropertyCodeList()
    {
        $intersectProperties = APLS_CatalogSectionsProperties::getSectionNodeIntersectProperties(static::getCatalogExternalId());
        $systemProperties = APLS_CatalogProperties::getSystemPropertiesCOMPARE();
        $arrXmlId = array_merge($intersectProperties,$systemProperties);
        return APLS_CatalogProperties::convertPropertyArrayXMLIDtoID($arrXmlId);
    }

    public static function getDetailPropertyCodeList()
    {
        $intersectProperties = APLS_CatalogSectionsProperties::getSectionNodeIntersectProperties(static::getCatalogExternalId());
        $systemProperties = APLS_CatalogProperties::getSystemPropertiesDETAIL();
        $arrXmlId = array_merge($intersectProperties,$systemProperties);
        return APLS_CatalogProperties::convertPropertyArrayXMLIDtoID($arrXmlId);
    }

    public static function getSmartFilterSettingsDefault($catalogXmlId)
    {
        return array_unique(
            array_merge(
                APLS_CatalogSectionsProperties::getSectionNodeIntersectProperties($catalogXmlId),
                APLS_CatalogProperties::getSystemPropertiesSMARTFILTER()
            )
        );
    }

    public static function getSmartFilterSettings($catalogXmlId)
    {
        return array_diff(
            array_unique(
                array_merge(
                    APLS_CatalogSectionsProperties::getSectionNodeIntersectProperties($catalogXmlId),
                    APLS_CatalogProperties::getSystemPropertiesSMARTFILTER(),
                    APLS_CatalogSectionsProperties::getSmartFilterShow($catalogXmlId)
                )
            ),
            APLS_CatalogSectionsProperties::getSmartFilterHide($catalogXmlId)
        );
    }

    /**
     * Возвращает внешний код открытого каталога товаров
     * @return null | string - внешний код текущего каталога
     */
    public static function getCatalogExternalId()
    {
        $externalID = null;
        $iden = explode("/", $_SERVER['REQUEST_URI']);
        if (isset($iden[1]) && $iden[1] == "catalog" && isset($iden[2]) && $iden[2] !== "") {
            $code = $iden[2];
            $db_list = CIBlockSection::GetList(
                array("SORT" => "ASC"),
                array('CODE' => $code),
                true,
                array("EXTERNAL_ID", "NAME"),
                false
            );
            while ($ar_result = $db_list->GetNext()) {
                $externalID = $ar_result['EXTERNAL_ID'];
            }
        }
        return $externalID;
    }


    public static function getSystemDetailPropertiesXMLID()
    {
        return self::getSystemPropertiesXMLIDFromBoolField(self::DETAIL_PROPERTY_FIELD);
    }

    public static function getSystemComparePropertiesXMLID()
    {
        return self::getSystemPropertiesXMLIDFromBoolField(self::COMPARE_PROPERTY_FIELD);
    }

    public static function getSystemSmartFilterXMLID()
    {
        return self::getSystemPropertiesXMLIDFromBoolField(self::SMART_FILTER_FIELD);
    }

    public static function getSystemCatalogEntityDataClass()
    {
        if (self::$systemCatalogEntityDataClass === null) {
            self::$systemCatalogEntityDataClass = APLS_GetHighloadEntityDataClass::getByHLName(self::SYSTEM_CATALOG_PROPERTIES);
        }
        return self::$systemCatalogEntityDataClass;
    }

    private static function getSystemPropertiesXMLIDFromBoolField($field)
    {
        try {
            $rsData = self::getSystemCatalogEntityDataClass()::getList(array(
                "select" => array(static::PROPERTY_XML_ID_FIELD, $field),
                "filter" => array(static::ACTIVITY_FIELD => true, $field => true)
            ));
            $properties = array();
            while ($arData = $rsData->Fetch()) {
                $properties[] = $arData[static::PROPERTY_XML_ID_FIELD];
            }
            return $properties;
        } catch (Exception $e) {
            return array();
        }
    }
}