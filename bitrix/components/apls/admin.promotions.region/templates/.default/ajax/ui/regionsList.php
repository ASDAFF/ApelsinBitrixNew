<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . $_REQUEST['componentFolder'] ."AdminPromotionsRegion.php";
echo AdminPromotionsRegion::getRegionsListHtml();