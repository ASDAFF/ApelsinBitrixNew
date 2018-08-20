<?define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();

session_start();

if($request->isPost()) {
    $_SESSION['GEOLOCATION_REGION_ID'] = $request->getPost("regionId");
}