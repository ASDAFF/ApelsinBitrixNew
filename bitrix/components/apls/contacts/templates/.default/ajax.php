<?
if (empty($_SERVER["HTTP_REFERER"])) die();

define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";

CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Highloadblock,
    Bitrix\Main\Entity,
    Bitrix\Highloadblock\HighloadBlockTable;

Loc::loadMessages(__FILE__);
$code = "";
?>
<a name="APLS_map"></a>
<?
if (isset($_REQUEST['shopID'])) {
    $hlbl = $_REQUEST['HLS'];
    $filter = array('ID' => $_REQUEST['shopID']);
    $mapColumnID = 'UF_YA_CODE';

} elseif (isset($_REQUEST['regionID'])) {
    $hlbl = $_REQUEST['HLR'];
    $filter = array('ID' => $_REQUEST['regionID']);
    $mapColumnID = 'UF_REGION_YA_CODE';
}

if(isset($hlbl)) {
    try {
        $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLID($hlbl);
    } catch (Exception $e) {
        echo 'Выброшено исключение: ',  $e->getMessage(), "<br>";
    }
    $rsData = $entity_data_class::getList(array(
        "select" => array($mapColumnID),
        "filter" => $filter
    ));
    while ($arData = $rsData->Fetch()) {
        $code = $arData[$mapColumnID];
    }
    $APPLICATION->IncludeComponent(
        "apls:map.shop",
        ".default",
        array(
            "INIT_MAP_TYPE" => "MAP",
            "MAP_DATA" => $code,
            "MAP_WIDTH" => "AUTO",
            "MAP_HEIGHT" => "100%",
            "CONTROLS" => array(
                0 => "ZOOM",
            ),
            "OPTIONS" => array(
                //0 => "ENABLE_SCROLL_ZOOM",
                1 => "ENABLE_DBLCLICK_ZOOM",
                2 => "ENABLE_DRAGGING",
            ),
            "MAP_ID" => "map_ryazan" . rand(1, 999999),
            "COMPONENT_TEMPLATE" => ".default"
        ),
        false
    );
} else {
    echo "Упс, что-то пошло не так, попробуйте снова.";
}