<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionImageHelper.php";

if ( $_FILES['file']['error'] > 0) {
    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
    $imageHelper = new PromotionImageHelper();
    $imageHelper->uploadImage($_FILES['file']['name'], $_FILES['file']['tmp_name'], $_REQUEST['typeId'], PromotionImageHelper::RESIZE_TYPE_MAX_HEIGHT);
}

