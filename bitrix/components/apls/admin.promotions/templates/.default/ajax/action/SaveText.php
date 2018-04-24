<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
if(isset($_REQUEST['field'])) {
    $result = PromotionRevisionModel::updateElement($_REQUEST['revisionId'], array($_REQUEST['field']=>$_REQUEST['value']));
    if($result) {
        echo "yes";
    }
}

