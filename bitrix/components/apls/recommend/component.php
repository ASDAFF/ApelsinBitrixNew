<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
$entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName($arParams['HLBLOCK_NAME']);
$rsData = $entity_data_class::getList(array("select" => array("UF_ITEM")));
$arResult["RECOMMEND_ITEMS"]['XML_ID'] = array();
while ($arData = $rsData->Fetch()) {
    $arResult["RECOMMEND_ITEMS"]['XML_ID'][] = $arData["UF_ITEM"];
};
$arResult['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
$this->IncludeComponentTemplate();
