<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_Tabs/APLS_Tabs.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortLists.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSectionsSettings.php";

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

function APLS_SmartFilterSettingGenerateInfoTabDetail(array $diffProperties,array $intersectProperties, array $systemProperties, array $modifiedShow, array $modifiedHide) {
    $html = "<div class='InfoTabWrapper'>";
    $html .= APLS_SmartFilterSettingGenerateInfoTabPropertiesList($diffProperties['show'], Loc::getMessage("ALL_PROPERTIES_COLUMN_NAME"));
    $html .= APLS_SmartFilterSettingGenerateInfoTabPropertiesList($intersectProperties['show'], Loc::getMessage("PARENT_PROPERTIES_COLUMN_NAME"));
    $html .= APLS_SmartFilterSettingGenerateInfoTabPropertiesList($systemProperties['show'], Loc::getMessage("SYSTEM_PROPERTIES_COLUMN_NAME"));
    $html .= APLS_SmartFilterSettingGenerateInfoTabPropertiesList($modifiedShow, Loc::getMessage("MODIFIED_SHOW"));
    $html .= APLS_SmartFilterSettingGenerateInfoTabPropertiesList($modifiedHide, Loc::getMessage("MODIFIED_HIDE"));
    $html .= "</div>";
    return $html;
}
function APLS_SmartFilterSettingGenerateInfoTab(array $show, array $hide) {
    $html = "<div class='InfoTabWrapper'>";
    $html .= APLS_SmartFilterSettingGenerateInfoTabPropertiesList($show, Loc::getMessage("SHOW"));
    $html .= APLS_SmartFilterSettingGenerateInfoTabPropertiesList($hide, Loc::getMessage("HIDE"));
    $html .= "</div>";
    return $html;
}
function APLS_SmartFilterSettingGenerateInfoTabPropertiesList(array $array, $title) {
    $html = "";
    $show = false;
    foreach ($array as $xmlId) {
        $show = true;
        $property = APLS_CatalogProperties::getPropertyFromXmlId($xmlId);
        $html .= "<div class='InfoTabProperty'>";
        if($property["NAME"] !== null) {
            $html .= "<div class='name'>".$property["NAME"]."</div>";
        }
        $html .= "<div class='xmlID'>$xmlId</div>";
        $html .= "</div>";
    }
    if($show) {
        $html = "<h2>$title</h2><div class='InfoTabWrapperBlock'>".$html."</div>";
    }
    return $html;
}

function APLS_SmartFilterSettingGenerateSortList(array $array, string $title, array $modifiedProperties, $className) {
    $sortList = new APLS_SortList();
    $sortList->setSortListTitle($title);
    $sortListElements = new APLS_SortListElements();
    foreach ($array as $xmlid) {
        $property = APLS_CatalogProperties::getPropertyFromXmlId($xmlid);
        if($property["NAME"] !== null) {
            $sortListElement = new APLS_SortListElement($property["NAME"]);
        } else {
            $sortListElement = new APLS_SortListElement("XML_ID: ".$property["XML_ID"]);
            $sortListElement->addClassName("undefined-property");
        }
        $sortListElement->addClassName($className);
        if(in_array($xmlid, $modifiedProperties)) {
            $sortListElement->addClassName("modified-property");
        }
        $sortListElement->addAttribute("prop_xmlid", $xmlid);
        $sortListElements->addSortListElement($sortListElement);
    }
    $sortList->setSortListElements($sortListElements);
    $sortList->addClassName($className);
    return $sortList;
}

function APLS_SmartFilterSettingGenerateSortLists(array $array, array $modifiedProperties = array()) {
    $sortLists = new APLS_SortLists();
    if(isset($array['show'])) {
        $sortLists->addSortList(APLS_SmartFilterSettingGenerateSortList($array['show'], Loc::getMessage("SHOW_PROPERTIES"), $modifiedProperties, "show"));
    }
    if(isset($array['hide'])) {
        $sortLists->addSortList(APLS_SmartFilterSettingGenerateSortList($array['hide'], Loc::getMessage("HIDE_PROPERTIES"), $modifiedProperties, "hide"));
    }
    $html = "<div class='apls-smart-filter-setting-sort-lists-wrapper'>";
    foreach ($sortLists as $sortList) {
        $html .= $sortList->getSortListHtml();
    }
    $html .= "</div>";
    return $html;
}

/** ПРВОЕРЯЕМ НАЛИЧИЕ XML_ID И ПРИРЫВАЕМ СКРИПТ ПО НЕОБХОДИМОСТИ **/
if(!isset($_REQUEST["xmlid"]) || $_REQUEST["xmlid"]==="undefined") {
    ?><div class="SmartFilterSettingNoData">Извините, раздела с таким внешним кодом не найдено</div><?
    die();
}

/** ПОЛУЧАЕМ ЗНАЧЕНИЕ XML_ID НАСТРАИВАЕМОГО КАТАЛОГА **/
$xmlid = $_REQUEST["xmlid"];

/** ВЫПОЛЯНЕМ СБРОС ДАННЫХ В HL-БЛОКЕ **/
if(isset($_REQUEST["edit"]) && $_REQUEST["edit"]==="default") {
    $valueIdArray = APLS_CatalogSectionsProperties::getSmartFilterValueIdArray($xmlid);
    $entityDataClass = APLS_CatalogSectionsProperties::getSmartFilterSettingsEntityDataClass();
    foreach ($valueIdArray as $delId) {
        $entityDataClass::Delete($delId);
    }
    APLS_CatalogSectionsProperties::updateSmartFilterSettingsData();
}

/** ВЫПОЛЯНЕМ СОХРАНЕНИЕ ДАННЫХ В HL-БЛОК **/
if(isset($_REQUEST["edit"]) && $_REQUEST["edit"]==="save") {
    // ПОЛУЧАЕМ ОТОБРАЖАЕМЫЕ СВОСТВА ПО УМОЛЧАНИЮ
    $smartFilterSettingsDefault = APLS_CatalogSectionsSettings::getSmartFilterSettingsDefault($xmlid);
    // ПОЛУЧАЕМ ОТОБРАЖАЕМЫЕ СВОСТВА ПО НАСТРОЙКАМ
    $smartFilterSettings = APLS_CatalogSectionsSettings::getSmartFilterSettings($xmlid);
    // СПИСОК СВОЙСТВ ПОДЕЛЕННЫЙ ПО СКРЫВАЕМЫМ И ОТОБРАЖАЕМЫМ
    $arrayProperties['show'] = array();
    $arrayProperties['hide'] = array();
    foreach (APLS_CatalogSectionsProperties::getSectionNodeIntersectProperties($xmlid) as $prop) {
        if(in_array($prop,$smartFilterSettings)) {
            $arrayProperties['show'][] = $prop;
        } else {
            $arrayProperties['hide'][] = $prop;
        }
    }
    foreach (APLS_CatalogSectionsProperties::getSectionNodeDiffProperties($xmlid) as $prop) {
        if(in_array($prop,$smartFilterSettings)) {
            $arrayProperties['show'][] = $prop;
        } else {
            $arrayProperties['hide'][] = $prop;
        }
    }
    foreach (APLS_CatalogProperties::getSystemPropertiesACTIVITY() as $prop) {
        if(in_array($prop,$smartFilterSettings)) {
            $arrayProperties['show'][] = $prop;
        } else {
            $arrayProperties['hide'][] = $prop;
        }
    }
    // ПОЛУЧАЕМ СПИСОК ОТЛИЧИЙ ОТ НАСТРОЕК ПО УМОЛЧАНИЮ
    $modifiedProperties = array_merge(APLS_CatalogSectionsProperties::getSmartFilterHide($xmlid), APLS_CatalogSectionsProperties::getSmartFilterShow($xmlid));
    // ЗАПОЛНЯЕМ МАССИВ ИЗМЕНЕНИЙ
    $updatePropertiesSettings = array(
        "show" => array(),
        "hide" => array(),
        "del" => array()
    );
    if(isset($_REQUEST["show"])) {
        foreach ($_REQUEST["show"] as $prop) {
            if(!in_array($prop, $arrayProperties["show"])) {
                if(!in_array($prop, $modifiedProperties)) {
                    $updatePropertiesSettings["show"][] = $prop;
                } else {
                    $valueId = APLS_CatalogSectionsProperties::getSmartFilterValueId($xmlid,$prop);
                    if($valueId !== null) {
                        $updatePropertiesSettings["del"][] = $valueId;
                    }
                }
            }
        }
    }
    if(isset($_REQUEST["hide"])) {
        foreach ($_REQUEST["hide"] as $prop) {
            if(!in_array($prop, $arrayProperties["hide"])) {
                if(!in_array($prop, $modifiedProperties)) {
                    $updatePropertiesSettings["hide"][] = $prop;
                } else {
                    $valueId = APLS_CatalogSectionsProperties::getSmartFilterValueId($xmlid,$prop);
                    if($valueId !== null) {
                        $updatePropertiesSettings["del"][] = $valueId;
                    }
                }
            }
        }
    }
    $entityDataClass = APLS_CatalogSectionsProperties::getSmartFilterSettingsEntityDataClass();
    foreach ($updatePropertiesSettings["del"] as $delId) {
        $entityDataClass::Delete($delId);
    }
    foreach ($updatePropertiesSettings["show"] as $prop) {
        $data = array(
            "UF_SHOW"=>1,
            "UF_PROPERTIES_XML_ID"=>$prop,
            "UF_SECTION_XML_ID"=>$xmlid,
        );
        $entityDataClass::add($data);
    }
    foreach ($updatePropertiesSettings["hide"] as $prop) {
        $data = array(
            "UF_SHOW"=>0,
            "UF_PROPERTIES_XML_ID"=>$prop,
            "UF_SECTION_XML_ID"=>$xmlid,
        );
        $entityDataClass::add($data);
    }
    APLS_CatalogSectionsProperties::updateSmartFilterSettingsData();
}


/** ВЫПОЛЯНЕМ ОТОБРАЖЕНИЕ НАСТРОЕК ДЛЯ ВЫБРАННОГО КАТАЛОГА **/
if(isset($_REQUEST["view"]) && $_REQUEST["view"]==="yes") {
    $showProperties = array();
    // ПОЛУЧАЕМ ОТОБРАЖАЕМЫЕ СВОСТВА ПО УМОЛЧАНИЮ
    $smartFilterSettingsDefault = APLS_CatalogSectionsSettings::getSmartFilterSettingsDefault($xmlid);
    // ПОЛУЧАЕМ ОТОБРАЖАЕМЫЕ СВОСТВА ПО НАСТРОЙКАМ
    $smartFilterSettings = APLS_CatalogSectionsSettings::getSmartFilterSettings($xmlid);
    // ПОЛУЧАЕМ ОБЩИЕ СВОСТВА
    $intersectProperties['show'] = array();
    $intersectProperties['hide'] = array();
    foreach (APLS_CatalogSectionsProperties::getSectionNodeIntersectProperties($xmlid) as $prop) {
        if(in_array($prop,$smartFilterSettings)) {
            $intersectProperties['show'][] = $prop;
            $showProperties[] = $prop;
        } else {
            $intersectProperties['hide'][] = $prop;
        }
    }
    // ПОЛУЧАЕМ ДОСТУПНЫЕ СВОЙСТВА
    $diffProperties['show'] = array();
    $diffProperties['hide'] = array();
    foreach (APLS_CatalogSectionsProperties::getSectionNodeDiffProperties($xmlid) as $prop) {
        if(in_array($prop,$smartFilterSettings)) {
            $diffProperties['show'][] = $prop;
            $showProperties[] = $prop;
        } else {
            $diffProperties['hide'][] = $prop;
        }
    }
    // ПОЛУЧАЕМ СИСТЕМНЫЕ СВОСТВА
    $systemProperties['show'] = array();
    $systemProperties['hide'] = array();
    foreach (APLS_CatalogProperties::getSystemPropertiesACTIVITY() as $prop) {
        if(in_array($prop,$smartFilterSettings)) {
            $systemProperties['show'][] = $prop;
            $showProperties[] = $prop;
        } else {
            $systemProperties['hide'][] = $prop;
        }
    }
    // ПОЛУЧАЕМ ИЗМЕНЕННЫЕ СВОСТВА
    $modifiedHide = APLS_CatalogSectionsProperties::getSmartFilterHide($xmlid);
    $modifiedShow = APLS_CatalogSectionsProperties::getSmartFilterShow($xmlid);
    $modifiedProperties = array_merge($modifiedHide, $modifiedShow);
    // ГЕНЕРИРУЕМ ВКЛАДКИ
    $tabs = new APLS_Tabs();
    $tabs->addTab(Loc::getMessage("INFO_TAB"), APLS_SmartFilterSettingGenerateInfoTab($showProperties, $modifiedHide));
    $tabs->addTab(Loc::getMessage("INFO_TAB_DETAIL"), APLS_SmartFilterSettingGenerateInfoTabDetail($diffProperties,$intersectProperties,$systemProperties, $modifiedShow, $modifiedHide));
    if(!empty($diffProperties['show']) || !empty($diffProperties['hide'])) {
        $tabs->addTab(Loc::getMessage("ALL_PROPERTIES_COLUMN_NAME"), APLS_SmartFilterSettingGenerateSortLists($diffProperties,$modifiedProperties));
    }
    if(!empty($intersectProperties['show']) || !empty($intersectProperties['hide'])) {
        $tabs->addTab(Loc::getMessage("PARENT_PROPERTIES_COLUMN_NAME"), APLS_SmartFilterSettingGenerateSortLists($intersectProperties,$modifiedProperties));
    }
    if(!empty($systemProperties['show']) || !empty($systemProperties['hide'])) {
        $tabs->addTab(Loc::getMessage("SYSTEM_PROPERTIES_COLUMN_NAME"), APLS_SmartFilterSettingGenerateSortLists($systemProperties,$modifiedProperties));
    }
    echo $tabs->getTabsHtml();
}






