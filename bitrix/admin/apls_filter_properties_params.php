<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/iblock.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/prolog.php");
$APPLICATION->SetTitle("Настройка отображения свойств товаров");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>
<?$APPLICATION->IncludeComponent("apls:filter.properties.params",".default");?>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>