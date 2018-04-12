<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";

$fields = array(
    "PreviewPromotionText"=>"preview_text",
    "MainPromotionText"=>"main_text",
    "VkPromotionText"=>"vk_text",
);
if(isset($fields[$_REQUEST['inputId']])) {
    $result = PromotionRevisionModel::updateElement($_REQUEST['revisionId'], array($fields[$_REQUEST['inputId']]=>$_REQUEST['value']));
    if($result) {
        echo "yes";
    }
}

