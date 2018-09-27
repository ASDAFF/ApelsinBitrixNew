<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>

<?
include_once '../apls_lib/apls_lib.php';
includeSistemClasses("../../");

$APPLICATION->IncludeComponent(
	"apls:contacts", 
	"list",
	array(
		"COMPONENT_TEMPLATE" => "list",
		"INIT_MAP_TYPE" => "SATELLITE",
		"MAP_DATA" => "a:3:{s:10:\"yandex_lat\";d:55.73829999999371;s:10:\"yandex_lon\";d:37.59459999999997;s:12:\"yandex_scale\";i:10;}",
		"MAP_WIDTH" => "600",
		"MAP_HEIGHT" => "500",
		"CONTROLS" => array(
			0 => "ZOOM",
			1 => "MINIMAP",
			2 => "TYPECONTROL",
			3 => "SCALELINE",
		),
		"OPTIONS" => array(
			0 => "ENABLE_SCROLL_ZOOM",
			1 => "ENABLE_DBLCLICK_ZOOM",
			2 => "ENABLE_DRAGGING",
		),
		"MAP_ID" => "esa_13",
		"HIGHLOAD_SHOPS_ID" => "4",
		"HIGHLOAD_REGION_ID" => "6",
		"REGION_ID" => "POINTS_SHOPS",
		"CONTACTS_TYPE" => "1"
	),
	false
);

?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>