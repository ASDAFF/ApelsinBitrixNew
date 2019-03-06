<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Производители");
?>
<?
$APPLICATION->IncludeComponent(
    "apls:brands",
    ".default",
    array(
        "IBlockID"=>"14"
    )
);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>