<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogException.php";
$result = PromotionCatalogException::createElement(array('revision'=>$_REQUEST["revisionId"], 'product'=>$_REQUEST["xml_id"]));
if($result) {
    echo "yes";
}