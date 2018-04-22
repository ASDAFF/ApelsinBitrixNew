<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
$title = $_REQUEST['title'];
if($title === "") {
    $title = null;
}
$result = PromotionRevisionModel::updateElement($_REQUEST['revisionId'], array('title'=>$title));

