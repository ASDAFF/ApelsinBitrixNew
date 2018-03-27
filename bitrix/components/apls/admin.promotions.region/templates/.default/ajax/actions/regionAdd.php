<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

$add = false;
if(isset($_REQUEST['regionName']) && $_REQUEST['regionName'] != "") {
    $add = PromotionRegionModel::createElement(array('region'=>$_REQUEST['regionName']));
}
echo $add;