<?php

use Bitrix\Main\Application;
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
if($arParams["IBLOCK_ID"] <= 0)
    return;

$request = Application::getInstance()->getContext()->getRequest();

if(isset($_SESSION['GEOLOCATION_REGION_ID'])) {
    $arParams["GEOLOCATION_REGION_ID"] = $_SESSION['GEOLOCATION_REGION_ID'];
    $APPLICATION->set_cookie("GEOLOCATION_REGION_ID", $_SESSION['GEOLOCATION_REGION_ID'], time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
} else {
    $arParams["GEOLOCATION_REGION_ID"] = $request->getCookie("GEOLOCATION_REGION_ID");
}
if(!empty($arParams["GEOLOCATION_REGION_ID"])) {
    $region = new PromotionRegionModel($arParams["GEOLOCATION_REGION_ID"]);
    $arParams["GEOLOCATION_REGION_ALIAS"] = $region->getFieldValue("alias");
    $arParams["GEOLOCATION_REGION_NAME"] = $region->getFieldValue("region");
    $arResult["CONTACTS"] = $region->getFieldValue("head_html");
}

$this->IncludeComponentTemplate();