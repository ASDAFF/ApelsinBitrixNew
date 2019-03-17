<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/GeolocationRegionHelper.php";

$pageAlias = GeolocationRegionHelper::getGeolocationRegionAlias();
$APPLICATION->IncludeFile("/include/region_pages/delivery/$pageAlias.php", Array(), Array("MODE" => "html"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>