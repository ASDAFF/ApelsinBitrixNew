<?php
if (empty($_SERVER["HTTP_REFERER"])) die();

define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogConfigurator.php";

switch ($_REQUEST["field"]) {
    case "SMART_FILTER":
        APLS_CatalogConfigurator::setHLPropParamsSmartFilterValue($_REQUEST["HLID"], $_REQUEST["val"]);
        break;
    case "DETAIL_PROPERTY":
        APLS_CatalogConfigurator::setHLPropParamsDetailPropertyValue($_REQUEST["HLID"], $_REQUEST["val"]);
        break;
    case "COMPARE_PROPERTY":
        APLS_CatalogConfigurator::setHLPropParamsComparePropertyValue($_REQUEST["HLID"], $_REQUEST["val"]);
        break;
    case "APPROVED":
        APLS_CatalogConfigurator::setHLPropParamsApprovedValue($_REQUEST["HLID"], $_REQUEST["val"]);
        break;
}
