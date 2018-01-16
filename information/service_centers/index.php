<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сервисные центры");
?>

<?
$APPLICATION->IncludeComponent(
    "apls:servicecenters",
    ".default",
    Array(
        "HIGHLOAD_ID" => "3",
    ),
    false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>