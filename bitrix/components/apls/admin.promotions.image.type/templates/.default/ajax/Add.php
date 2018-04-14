<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionImageHelper.php";
$imageHelper = new PromotionImageHelper();
if (isset($_REQUEST['name']) && $_REQUEST['name'] !== "" && isset($_REQUEST['alias']) && $_REQUEST['alias'] !== "") {
    $result = $imageHelper->createImageType($_REQUEST['alias'],$_REQUEST['name']);
} else {
    $result = false;
}
if(!$result) {
    echo "error";
}
