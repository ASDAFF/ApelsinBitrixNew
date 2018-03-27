<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
if(isset($_REQUEST['regions']) && is_array($_REQUEST['regions']) && !empty($_REQUEST['regions'])) {
    $sort = 1;
    foreach ($_REQUEST['regions'] as $regionId) {
        PromotionRegionModel::updateElement($regionId,array('sort'=>$sort++));
    }

}