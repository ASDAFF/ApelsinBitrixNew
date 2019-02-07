<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

if ($_REQUEST['shopid'] !== '' && $_REQUEST['regionid']) {
    $regionObj = new PromotionRegionModel($_REQUEST['regionid']);
    $regionObj->deleteContacts($_REQUEST['shopid']);
    $result = array(
        "success" => array(
            "shopId" => $_REQUEST['shopid'],
        )
    );
}


echo Bitrix\Main\Web\Json::encode($result);?>