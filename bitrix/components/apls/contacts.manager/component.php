<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock\HighloadBlockTable as HLBT,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Highloadblock,
    Bitrix\Main\Entity;

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";

Loc::loadMessages(__FILE__);

$arResult['NAME'] = $arParams['DEPARTAMENT_TYPE'];
try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLID(APLS_GetGlobalParam::getParams("HIGHLOAD_DEPARTAMENT_ID"));
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
}
$rsData = $entity_data_class::getList(array(
    'select' => array('ID','UF_NAME'),
    'filter' => array('ID' => $arResult['NAME']),
));
$departaments = array();
while ($arData = $rsData->Fetch()) {
    $arResult["departament"][$arData['ID']] = $arData;
    $departaments[] = $arData["ID"];
}

try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLID(APLS_GetGlobalParam::getParams("HIGHLOAD_STAFF_ID"));
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
}
$rsData = $entity_data_class::getList(array(
    'select' => array('*'),
    'filter' => array('UF_DEPARTAMENT' => $departaments),
));
while ($arData = $rsData->Fetch()) {
    $arResult["staffes"][$arData['ID']] = $arData;
}



$this->IncludeComponentTemplate();
?>