<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
$region = new PromotionRegionModel($_REQUEST['regionId']);
$cities = $region->getCities();
foreach ($cities as $city) {
    $cityId = $city->getId();
    echo "<div class='RegionCity'>";
    echo "<div class='RegionCityBlock' cityId='$cityId'>";
    echo $city->getFieldValue('city');
    echo "<div class='RegionCityDellButton' cityId='$cityId' onclick='AdminPromotionsRegionCityDelete(\"$cityId\")'></div>";
    echo "</div>";
    echo "</div>";
}
?>