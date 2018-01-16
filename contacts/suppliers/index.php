<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отдел закупок");
?>

<?
include_once '../../apls_lib/apls_lib.php';
includeSistemClasses("../../");

// $APPLICATION->IncludeComponent(
//    "apls:contacts.manager",
//    ".default",
//    array(
//        "HIGHLOAD_DEPARTAMENT_ID" => "10",
//        "HIGHLOAD_STAFF_ID" => "11",
//        "COMPONENT_TEMPLATE" => ".default",
//        "DEPARTAMENT_TYPE" => "1"
//    ),
//    false
//);
 ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>