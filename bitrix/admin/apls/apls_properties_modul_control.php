<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/iblock.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/prolog.php");
$APPLICATION->SetTitle("Сортировка свойств товаров");

$_SESSION["uploader"]["message"] = array();
$_SESSION["uploader"]["images_data"] = array();
$_SESSION["uploader"]["elements_id"] = array();
$_SESSION["uploader"]["elements_xml_id"] = array();
$_SESSION["uploader"]["counter"] = 0;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>

<? $APPLICATION->IncludeComponent(
    "apls:sort.properties",
    ".default",
    array(

    ),
    false
);?>

<?
CAdminMessage::ShowMessage(implode("<br>", $_SESSION["uploader"]["message"]));
unset($_SESSION["uploader"]["message"]);
unset($_SESSION["uploader"]["images_data"]);
unset($_SESSION["uploader"]["elements_id"]);
unset($_SESSION["uploader"]["elements_xml_id"]);
unset($_SESSION["uploader"]["counter"]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>
