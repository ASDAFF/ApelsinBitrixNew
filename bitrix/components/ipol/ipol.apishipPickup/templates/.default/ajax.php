<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->RestartBuffer();?>
<?$GLOBALS["APPLICATION"]->IncludeComponent(
	"ipol:ipol.apishipPickup",
	"",
Array(
),
false
);?>
<?

// echo json_encode("ajax_call");
// echo CUtil::PHPToJSObject($arResult);
?>