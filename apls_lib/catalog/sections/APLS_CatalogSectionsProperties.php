<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogConfigurator.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";

class APLS_CatalogSectionsProperties
{
    private static $instance = null;

    private static $HLSectionsPropertiesMapEntityDataClass = null; // self::getEntityDataClassSPM()
    private static $smartFilterSettingsEntityDataClass = null;
    private static $sectionsPropertiesMapData = array(); // self::getSectionsPropertiesMap()
    private static $unknownPropertiesData = array(); // self::getUnknownProperties()
    private static $sectionNodeIntersectProperties = array();
    private static $sectionNodeProperties = array();
    private static $smartFilterSettings = array();
    private static $smartFilterSettingsValueId = array();

    const SECTIONS_PROPERTIES_MAP = "KartaSvyazeyKatalogaIM";
    const PROPERTY_FIELD = "UF_GUIDSVOYSTVA";
    const SECTION_FIELD = "UF_GUIDKATALOGA";
    const PROPERTY_XML_ID_FIELD = "UF_PROPERTY_XML_ID";
    const ACTIVITY_FIELD = "UF_ACTIVITY";
    const DETAIL_PROPERTY_FIELD = "UF_DETAIL_PROPERTY";
    const COMPARE_PROPERTY_FIELD = "UF_COMPARE_PROPERTY";
    const SMART_FILTER_FIELD = "UF_SMART_FILTER";

    const SMART_FILTER_SETTINGS = "SmartFilterSetting";
    const SHOW_FIELD = "UF_SHOW";
    const PROPERTIES_XML_ID_FIELD = "UF_PROPERTIES_XML_ID";
    const SECTION_XML_ID_FIELD = "UF_SECTION_XML_ID";

    public function __construct()
    {
        self::getSectionsPropertiesMapData();
        self::getSmartFilterSettingsData();
        self::generateAllNodeElementProperties();
    }

    /**
     * Вернет спсиок своств разбитых по секциям согласно данных 1с
     * @return array
     */
    public static function getSectionsPropertiesMap()
    {
        self::getInstance();
        return self::$sectionsPropertiesMapData;
    }

    /**
     * Вернет список свойств не привязанных к секциям
     * @return array
     */
    public static function getUnknownProperties()
    {
        self::getInstance();
        return self::$unknownPropertiesData;
    }

    /**
     * Вернет своства общие для всех секций потомков
     * @param $xmlid - XML_ID секции
     * @return array
     */
    public static function getSectionNodeIntersectProperties($xmlid)
    {
        self::getInstance();
        return self::$sectionNodeIntersectProperties[$xmlid];
    }

    /**
     * Вернет своства сумарные для всех секций потомков
     * @param $xmlid - XML_ID секции
     * @return array
     */
    public static function getSectionNodeProperties($xmlid)
    {
        self::getInstance();
        return self::$sectionNodeProperties[$xmlid];
    }

    /**
     * Вернет своства которых нет хотябы в одном из родителей
     * @param $xmlid - XML_ID секции
     * @return array
     */
    public static function getSectionNodeDiffProperties($xmlid)
    {
        self::getInstance();
        return array_diff(self::$sectionNodeProperties[$xmlid], self::$sectionNodeIntersectProperties[$xmlid]);
    }

    public static function getSmartFilterSettings()
    {
        self::getInstance();
        return self::$smartFilterSettings;
    }

    public static function getSmartFilterShow($sectionXmlId)
    {
        self::getInstance();
        if (isset(self::$smartFilterSettings[$sectionXmlId]["show"])) {
            return self::$smartFilterSettings[$sectionXmlId]["show"];
        } else {
            return array();
        }
    }

    public static function getSmartFilterHide($sectionXmlId)
    {
        self::getInstance();
        if (isset(self::$smartFilterSettings[$sectionXmlId]["hide"])) {
            return self::$smartFilterSettings[$sectionXmlId]["hide"];
        } else {
            return array();
        }
    }

    public static function getSmartFilterValueId($sectionXmlId, $propertiesXmlId) {
        self::getInstance();
        if(isset(self::$smartFilterSettingsValueId[$sectionXmlId][$propertiesXmlId])) {
            return self::$smartFilterSettingsValueId[$sectionXmlId][$propertiesXmlId];
        }
        return null;
    }

    public static function getSmartFilterValueIdArray($sectionXmlId) {
        self::getInstance();
        if(isset(self::$smartFilterSettingsValueId[$sectionXmlId])) {
            return self::$smartFilterSettingsValueId[$sectionXmlId];
        }
        return array();
    }

    /**
     * поулчение $HLSectionsPropertiesMapEntityDataClass осуществлять только через эту функцию, даже внутри класса.
     * @param bool $reload - принудительное обновление
     * @return \Bitrix\Main\Entity\DataManager | null
     */
    public static function getSectionsPropertiesMapEntityDataClass($reload = false)
    {
        if (self::$HLSectionsPropertiesMapEntityDataClass === null || $reload) {
            self::$HLSectionsPropertiesMapEntityDataClass = APLS_GetHighloadEntityDataClass::getByHLName(self::SECTIONS_PROPERTIES_MAP);
        }
        return self::$HLSectionsPropertiesMapEntityDataClass;
    }

    /**
     * Получение SmartFilterSettingsEntityDataClass
     * @return \Bitrix\Main\Entity\DataManager
     */
    public static function getSmartFilterSettingsEntityDataClass()
    {
        if (self::$smartFilterSettingsEntityDataClass === null) {
            self::$smartFilterSettingsEntityDataClass = APLS_GetHighloadEntityDataClass::getByHLName(self::SMART_FILTER_SETTINGS);
        }
        return self::$smartFilterSettingsEntityDataClass;
    }

    /**
     * Обновляем данные по настрйокам умных фильтров
     */
    public static function updateSmartFilterSettingsData() {
        self::getSmartFilterSettingsData();
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
     * Формируем все необходимые даныне по свойствами и карте свойств:
     *      self::$unknownPropertiesData[] - список XML_ID свойств не привязанных к каталогам и не являющихся системными
     *      self::$SectionsPropertiesMapData[<XML_ID секции>][] - список XML_ID свойств с привязкой к секциям
     */
    private static function getSectionsPropertiesMapData()
    {
        self::$sectionsPropertiesMapData = array();
        self::$unknownPropertiesData = array();
        $notRootPropertiesData = array();
        $rsData = self::getSectionsPropertiesMapEntityDataClass()::getList(array(
            "select" => array(static::PROPERTY_FIELD, static::SECTION_FIELD),
            "filter" => array()
        ));
        while ($arData = $rsData->Fetch()) {
            if (in_array($arData[static::PROPERTY_FIELD], APLS_CatalogProperties::getNotSystemProperties())) {
                if ($arData[static::SECTION_FIELD] !== "" && $arData[static::PROPERTY_FIELD] !== "") {
                    self::$sectionsPropertiesMapData[$arData[static::SECTION_FIELD]][] = $arData[static::PROPERTY_FIELD];
                    $notRootPropertiesData[$arData[static::PROPERTY_FIELD]] = $arData[static::PROPERTY_FIELD];
                }
            }
        }
        self::$unknownPropertiesData = array_diff(APLS_CatalogProperties::getNotSystemProperties(), $notRootPropertiesData);
    }

    /**
     * Получаем список кастомных настроек по секциям для умного фильтра
     */
    private static function getSmartFilterSettingsData()
    {
        self::$smartFilterSettings = array();
        $rsData = self::getSmartFilterSettingsEntityDataClass()::getList(array(
            "select" => array("ID","UF_SHOW", "UF_PROPERTIES_XML_ID", "UF_SECTION_XML_ID"),
            "filter" => array()
        ));
        while ($arData = $rsData->Fetch()) {
            if (in_array($arData["UF_SHOW"], array("1", "Y", "y", true, "yes"))) {
                $shkey = "show";
            } else {
                $shkey = "hide";
            }
            self::$smartFilterSettings[$arData["UF_SECTION_XML_ID"]][$shkey][] = $arData["UF_PROPERTIES_XML_ID"];
            self::$smartFilterSettingsValueId[$arData["UF_SECTION_XML_ID"]][$arData["UF_PROPERTIES_XML_ID"]] = $arData["ID"];
        }
    }

    /**
     * Генерируем пересечения и общий список свойств по узлам
     */
    private static function generateAllNodeElementProperties()
    {
        foreach (APLS_CatalogSections::getSectionsXMLIDtoID() as $xmlid => $id) {
            self::generateNodeElementIntersectProperties($xmlid);
            self::generateNodeElementProperties($xmlid);
        }
    }

    /**
     * Генерирует список пересечения свойств по XMLID каталога
     * @param $xmlid
     */
    private static function generateNodeElementIntersectProperties($xmlid)
    {
        if (APLS_CatalogSections::getSectionsChild($xmlid) === null) {
            if (isset(self::$sectionsPropertiesMapData[$xmlid])) {
                self::$sectionNodeIntersectProperties[$xmlid] = self::$sectionsPropertiesMapData[$xmlid];
            } else {
                self::$sectionNodeIntersectProperties[$xmlid] = array();
            }
        } else {
            $arIntersect = APLS_CatalogProperties::getNotSystemProperties();
            foreach (APLS_CatalogSections::getSectionsChild($xmlid) as $child) {
                if (!isset(self::$sectionNodeIntersectProperties[$child])) {
                    self::generateNodeElementIntersectProperties($child);
                }
                $arIntersect = array_intersect($arIntersect, self::$sectionNodeIntersectProperties[$child]);
            }
            self::$sectionNodeIntersectProperties[$xmlid] = $arIntersect;
        }
    }

    /**
     * Генерирует список всех свойств по XMLID каталога
     * @param $xmlid
     */
    private static function generateNodeElementProperties($xmlid)
    {
        if (APLS_CatalogSections::getSectionsChild($xmlid) === null) {
            if (isset(self::$sectionsPropertiesMapData[$xmlid])) {
                self::$sectionNodeProperties[$xmlid] = self::$sectionsPropertiesMapData[$xmlid];
            } else {
                self::$sectionNodeProperties[$xmlid] = array();
            }
        } else {
            self::$sectionNodeProperties[$xmlid] = array();
            foreach (APLS_CatalogSections::getSectionsChild($xmlid) as $child) {
                if (!isset(self::$sectionNodeProperties[$child])) {
                    self::generateNodeElementProperties($child);
                }
                self::$sectionNodeProperties[$xmlid] = array_unique(array_merge(self::$sectionNodeProperties[$xmlid], self::$sectionNodeProperties[$child]));
            }
        }
    }
}