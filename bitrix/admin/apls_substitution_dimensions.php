<?php
if (isset($_REQUEST['work_start']))
{
    define("NO_AGENT_STATISTIC", true);
    define("NO_KEEP_STATISTIC", true);
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/prolog.php");
$APPLICATION->SetTitle("Подстановка значений свойств торгового каталога");

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
//include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
//include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_UpdateDimentions/APLS_DimentionHTMLGenerator.php";
//$APPLICATION->SetAdditionalCSS("/apls_lib/ui/APLS_UpdateDimentions/APLS_UpdateDimentionValue.css");

//echo APLS_DimentionHTMLGenerator::getHTML();
?>

<?$APPLICATION->IncludeComponent(
    "apls:update.dimensions.table",
    ".default",
    array(

    ),
    false
);
?>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
CJSCore::Init(array('jquery'));
