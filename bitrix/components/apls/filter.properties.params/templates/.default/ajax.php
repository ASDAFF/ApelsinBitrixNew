<?php
if (empty($_SERVER["HTTP_REFERER"])) die();

define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogConfigurator.php";

$filterArray = array();
// добавляет значение в фильтр
function addFilterValue(&$filterArray, $field, $value)
{
    if ($value !== "all") {
        $filterArray[$field] = $value;
    }
}

addFilterValue($filterArray, APLS_CatalogConfigurator::SMART_FILTER_FIELD, $_REQUEST["sf"]);
addFilterValue($filterArray, APLS_CatalogConfigurator::DETAIL_PROPERTY_FIELD, $_REQUEST["dp"]);
addFilterValue($filterArray, APLS_CatalogConfigurator::COMPARE_PROPERTY_FIELD, $_REQUEST["cp"]);
addFilterValue($filterArray, APLS_CatalogConfigurator::APPROVED_FIELD, $_REQUEST["approved"]);

$arResult = APLS_CatalogConfigurator::getHighloadPropertiesParams(false, $_REQUEST["sortBy"] === "name", $filterArray, $_REQUEST["searchString"]);
$templateFolder = $_REQUEST["templateFolder"];

require_once($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/view.php");
echo $VIEW_HTML;
