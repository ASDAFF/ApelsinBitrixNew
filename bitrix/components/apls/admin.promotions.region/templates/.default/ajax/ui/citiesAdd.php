<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>
<div class="RegionCitiesAddPanelSearch">
<?
$APPLICATION->IncludeComponent(
    "bitrix:sale.location.selector.search",
    ".default",
    array(
        "COMPONENT_TEMPLATE" => ".default",
        "CODE" => "",
        "INPUT_NAME" => "LOCATION",
        "PROVIDE_LINK_BY" => "id",
        "JSCONTROL_GLOBAL_ID" => "",
        "JS_CALLBACK" => "",
        "FILTER_BY_SITE" => "Y",
        "SHOW_DEFAULT_LOCATIONS" => "N",
        "CACHE_TYPE" => "A",
        "FILTER_SITE_ID" => "current",
        "INITIALIZE_BY_GLOBAL_EVENT" => "",
        "SUPPRESS_ERRORS" => "N",
        "JS_CONTROL_GLOBAL_ID" => ""
    ),
    false
);
?>
</div>
<div class="RegionCitiesAddPanelButton" onclick="AdminPromotionsRegionCityAdd()"><span>Добавить</span></div>