<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";

$revision = new PromotionRevisionModel($_REQUEST['revisionId']);
if($revision->getFieldValue("promotion") !== "" && $revision->getFieldValue("promotion") !== null) {
    echo $revision->createCopy(array("disable"=>"1"));
}