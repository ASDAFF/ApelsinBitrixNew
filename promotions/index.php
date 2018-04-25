<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Акции");
?>
<?
$APPLICATION->IncludeComponent(
    "apls:promotions",
    ".default",
    array(
        "SEF_FOLDER"=>"/promotions/"
    )
);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>