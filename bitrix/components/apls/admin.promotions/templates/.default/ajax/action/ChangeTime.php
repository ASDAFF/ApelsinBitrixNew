<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";

if($_REQUEST['value'] !== "") {
    $dateTime = new DateTime($_REQUEST['value']);
    $dateTimeString = $dateTime->format("Y-m-d H:i:s");
} elseif ($_REQUEST['field'] === 'apply_from') {
    $dateTime = new DateTime();
    $dateTimeString = $dateTime->format("Y-m-d H:i:s");
}
PromotionRevisionModel::updateElement($_REQUEST['revisionId'], array($_REQUEST['field']=>$dateTimeString));


