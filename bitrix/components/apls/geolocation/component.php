<?php

use Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Main\Text\Encoding,
    Bitrix\Iblock,
    Bitrix\Main\Service\GeoIp;

if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
if($arParams["IBLOCK_ID"] <= 0)
    return;

$request = Application::getInstance()->getContext()->getRequest();

if(isset($_SESSION['GEOLOCATION_REGION_ALIAS'])) {
    $arParams["GEOLOCATION_REGION_ALIAS"] = $_SESSION['GEOLOCATION_REGION_ALIAS'];
    $APPLICATION->set_cookie("GEOLOCATION_REGION_ALIAS", $_SESSION['GEOLOCATION_REGION_ALIAS'], time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
} else {
    $arParams["GEOLOCATION_REGION_ALIAS"] = $request->getCookie("GEOLOCATION_REGION_ALIAS");
}
if(isset($_SESSION['GEOLOCATION_REGION_NAME'])) {
    $arParams["GEOLOCATION_REGION_NAME"] = $_SESSION['GEOLOCATION_REGION_NAME'];
    $APPLICATION->set_cookie("GEOLOCATION_REGION_NAME", $_SESSION['GEOLOCATION_REGION_NAME'], time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
} else {
    $arParams["GEOLOCATION_REGION_NAME"] = $request->getCookie("GEOLOCATION_REGION_ALIAS");
}
$this->IncludeComponentTemplate();