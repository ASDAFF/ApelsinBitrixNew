<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
?>
<?
if (isset($_REQUEST['shopID'])) {
$hlbl = $_REQUEST['shopHLName'];
$filter = array();
$elementId = $_REQUEST['shopID'];
$select = array('UF_COORDS','UF_ZOOM','UF_SHORT_ADDRESS','ID');
} elseif (isset($_REQUEST['regionId'])) {
$hlbl = $_REQUEST['regionHLName'];
$filter = array();
$elementId = $_REQUEST['regionId'];
$select = array('UF_COORDS','UF_ZOOM','UF_REGION','ID');
}
    if ($hlbl) {
        $code = array();
        try {
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName($hlbl);
        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "<br>";
        }
        $rsData = $entity_data_class::getList(array(
            "select" => $select,
            "filter" => $filter,
        ));
        if ($hlbl = $_REQUEST['shopID']) {
            while ($arData = $rsData->Fetch()) {
                $code[$arData['ID']]['adress'] = $arData['UF_SHORT_ADDRESS'];
                $code[$arData['ID']]['coords'] = $arData['UF_COORDS'];
                $code[$arData['ID']]['zoom'] = $arData['UF_ZOOM'];
            }
        } else {
            while ($arData = $rsData->Fetch()) {
                $code[$arData['ID']]['adress'] = $arData['UF_REGION'];
                $code[$arData['ID']]['coords'] = $arData['UF_COORDS'];
                $code[$arData['ID']]['zoom'] = $arData['UF_ZOOM'];
            }
        }
        ?>
        <script>
            myMap.setCenter([<?=$code[$elementId]['coords']?>],<?=$code[$elementId]['zoom']?>,{
                checkZoomRange: true
            });
        </script>
<?
} else {
    echo "Упс, что-то пошло не так, попробуйте снова.";
}