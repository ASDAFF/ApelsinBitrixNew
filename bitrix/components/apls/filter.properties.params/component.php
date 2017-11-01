<?
CJSCore::Init(array("jquery2"));
CJSCore::Init(array('ajax'));
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogConfigurator.php";
$arResult = APLS_CatalogConfigurator::getHighloadPropertiesParams();
$this->IncludeComponentTemplate();
