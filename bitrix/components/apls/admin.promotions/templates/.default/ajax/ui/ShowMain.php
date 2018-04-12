<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";

$orderByObj = new MySQLOrderByString();
$orderByObj->add('sort', MySQLOrderByString::ASC);
$sections = PromotionSectionModel::getElementList(null,null,null, $orderByObj);
$regions = PromotionRegionModel::getElementList(null,null,null,$orderByObj);

?>

<!--<div class='PromotionMainWrapperTitle'>Акции</div>-->
<div class='PromotionMainWrapper'>
    <div class='PromotionListWrapper'>
        <div class='PromotionList'></div>
    </div>
    <div class='PromotionShowWrapper'>
        <div class='PromotionShow'></div>
    </div>
</div>
