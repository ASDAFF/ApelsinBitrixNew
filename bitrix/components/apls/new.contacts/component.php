<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/model/GeolocationRegionsContacts.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/GeolocationRegionHelper.php";

$regionObj = new PromotionRegionModel(GeolocationRegionHelper::getGeolocationRegionId());
$arResult["regionName"] = $regionObj->getFieldValue("region");
$arResult["longitude"] = $regionObj->getFieldValue("longitude");
$arResult["latitude"] = $regionObj->getFieldValue("latitude");
$arResult["zoom"] = $regionObj->getFieldValue("zoom");

$shopsList = $regionObj->getContacts();
foreach ($shopsList as $shop) {
    $arResult["SHOPS"][$shop->getFieldValue("id")]["name"] = $shop->getFieldValue("name");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["address"] = $shop->getFieldValue("address");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["email"] = $shop->getFieldValue("email");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["phone_number_1"] = $shop->getFieldValue("phone_number_1");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["additional_number_1"] = $shop->getFieldValue("additional_number_1");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["phone_number_2"] = $shop->getFieldValue("phone_number_2");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["additional_number_2"] = $shop->getFieldValue("additional_number_2");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["MON"]["start"] = $shop->getFieldValue("mon_start");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["MON"]["stop"] = $shop->getFieldValue("mon_stop");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["TUE"]["start"] = $shop->getFieldValue("tue_start");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["TUE"]["stop"] = $shop->getFieldValue("tue_stop");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["WED"]["start"] = $shop->getFieldValue("wed_start");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["WED"]["stop"] = $shop->getFieldValue("wed_stop");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["THU"]["start"] = $shop->getFieldValue("thu_start");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["THU"]["stop"] = $shop->getFieldValue("thu_stop");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["FRI"]["start"] = $shop->getFieldValue("fri_start");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["FRI"]["stop"] = $shop->getFieldValue("fri_stop");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["SAT"]["start"] = $shop->getFieldValue("sat_start");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["SAT"]["stop"] = $shop->getFieldValue("sat_stop");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["SUN"]["start"] = $shop->getFieldValue("sun_start");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["time_table"]["SUN"]["stop"] = $shop->getFieldValue("sun_stop");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["credit_department"] = $shop->getFieldValue("credit_department");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["wholesale_department"] = $shop->getFieldValue("wholesale_department");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["receipt_of_goods"] = $shop->getFieldValue("receipt_of_goods");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["round_the_clock"] = $shop->getFieldValue("round_the_clock");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["longitude"] = $shop->getFieldValue("longitude");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["latitude"] = $shop->getFieldValue("latitude");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["zoom"] = $shop->getFieldValue("zoom");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["sort"] = $shop->getFieldValue("sort");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["b_img"] = $shop->getFieldValue("b_img");
    $arResult["SHOPS"][$shop->getFieldValue("id")]["s_img"] = $shop->getFieldValue("s_img");
}

$this->IncludeComponentTemplate();