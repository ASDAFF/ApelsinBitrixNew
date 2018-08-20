<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
if(isset($_REQUEST['regionId']) && isset($_REQUEST['regionName']) && $_REQUEST['regionName'] != "" && isset($_REQUEST['regionAlias']) && $_REQUEST['regionAlias'] != "") {
    $region = new PromotionRegionModel($_REQUEST['regionId']);
    $region->setFieldValue('region', $_REQUEST['regionName']);
    $region->setFieldValue('alias', $_REQUEST['regionAlias']);
    $region->setFieldValue('head_html', $_REQUEST['headHtml']);
    $region->saveElement();
}