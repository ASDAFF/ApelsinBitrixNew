<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/model/GeolocationRegionsContacts.php";

$region = new PromotionRegionModel($_POST["regionId"]);
if (isset($_POST["longitudeValue"])) {
    $result1 = $region->setFieldValue("longitude",$_POST["longitudeValue"]);
} else {
    $result2 = $region->setFieldValue("longitude","0");
    $_POST["longitudeValue"] = "0";
}

if (isset($_POST["latitudeValue"])) {
    $result3 = $region->setFieldValue("latitude",$_POST["latitudeValue"]);
} else {
    $region->setFieldValue("latitude","0");
    $_POST["latitudeValue"] = '0';
}

if (isset($_POST["regionZoom"])) {
    $region->setFieldValue("zoom",$_POST["regionZoom"]);
} else {
    $region->setFieldValue("zoom","0");
    $_POST["regionZoom"] = '0';
}
$region->saveElement();
$html = '<div class="regionLongitude" result="'.$result1.'" coordsValue="'.$_POST["longitudeValue"].'"><span>Дологота: </span>'.$_POST["longitudeValue"].'</div>';
$html .= '<div class="regionLatitude" result="'.$result2.'" coordsValue="'.$_POST["latitudeValue"].'"><span>Широта: </span>'.$_POST["latitudeValue"].'</div>';
$html .= '<div class="regionZoom" result="'.$result3.'" coordsValue="'.$_POST["regionZoom"].'"><span>Зум: </span>'.$_POST["regionZoom"].'</div>';
$html .= '<div class="regionChange">Изменить</div>';
echo $html;