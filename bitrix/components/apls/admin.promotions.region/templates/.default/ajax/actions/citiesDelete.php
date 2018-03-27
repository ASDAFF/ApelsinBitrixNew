<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

if(isset($_REQUEST['regionId']) && isset($_REQUEST['cityId'])) {
    $region = new PromotionRegionModel($_REQUEST['regionId']);
    $region->deleteCity($_REQUEST['cityId']);
}
?>