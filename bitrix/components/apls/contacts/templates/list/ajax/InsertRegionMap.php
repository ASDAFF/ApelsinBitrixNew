<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
?>
    <a name="contacts_map"></a>
<?
if (isset($_REQUEST['shopID'])) {
$hlbl = $_REQUEST['shopHLName'];
$filter = array('ID' => $_REQUEST['shopID']);
$mapColumnID = 'UF_YA_CODE';
} elseif (isset($_REQUEST['regionId'])) {
$hlbl = $_REQUEST['regionHLName'];
$filter = array('ID' => $_REQUEST['regionId']);
$mapColumnID = 'UF_REGION_YA_CODE';
}
if ($hlbl) {
    $code = '';
    try {
        $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName($hlbl);
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
//    var_dump($code);
    $APPLICATION->IncludeComponent("apls:map.shop",
        ".default",
        array(
            "INIT_MAP_TYPE" => "MAP",
            "MAP_DATA" => $code,
            "MAP_WIDTH" => "AUTO",
            "MAP_HEIGHT" => "600px",
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