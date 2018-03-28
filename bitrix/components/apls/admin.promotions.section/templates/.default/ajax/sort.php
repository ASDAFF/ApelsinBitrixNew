<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionSectionModel.php";
if(isset($_REQUEST['sections']) && is_array($_REQUEST['sections']) && !empty($_REQUEST['sections'])) {
    $sort = 1;
    foreach ($_REQUEST['sections'] as $sectionId) {
        PromotionSectionModel::updateElement($sectionId,array('sort'=>$sort++));
    }
}