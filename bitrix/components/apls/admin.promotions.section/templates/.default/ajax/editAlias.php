<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionSectionModel.php";
if(isset($_REQUEST['sectionId']) && $_REQUEST['sectionId'] != "" && isset($_REQUEST['newAlias']) && $_REQUEST['newAlias'] != "") {
    PromotionSectionModel::updateElement($_REQUEST['sectionId'],array('alias'=>$_REQUEST['newAlias']));
}