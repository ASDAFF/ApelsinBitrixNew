<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . $_REQUEST['componentFolder'] ."classes/AdminPromotions_PromotionsList.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
$revisionType = PromotionModel::REVISION_TYPE_ALL;
$sortingField = PromotionModel::SORT_FIELD_DEFAULT;
$sortingType = PromotionModel::SORT_DESC;
$searchString = "";
$location = "";
$section = "";
$publishedOn = array();
if(isset($_REQUEST['FILTER_SEARCH_STRING'])) {
    $searchString = $_REQUEST['FILTER_SEARCH_STRING'];
}
if(isset($_REQUEST['FILTER_REVISION_TYPE'])) {
    $revisionType = $_REQUEST['FILTER_REVISION_TYPE'];
}
if(isset($_REQUEST['FILTER_SECTION']) && $_REQUEST['FILTER_SECTION'] !== 'all') {
    $section = $_REQUEST['FILTER_SECTION'];
}
if(isset($_REQUEST['FILTER_REGION']) && $_REQUEST['FILTER_REGION'] !== 'all') {
    $location = $_REQUEST['FILTER_REGION'];
}
if(isset($_REQUEST['FILTER_SORT_FIELD'])) {
    $sortingField = $_REQUEST['FILTER_SORT_FIELD'];
}
if(isset($_REQUEST['FILTER_SORT_FIELD'])) {
    $sortingType = $_REQUEST['FILTER_SORT_TYPE'];
}
if(isset($_REQUEST['FILTER_PUBLISHED_ON']) && $_REQUEST['FILTER_REGION'] !== 'none') {
    $publishedOn = array($_REQUEST['FILTER_PUBLISHED_ON']);
}
$list = new AdminPromotions_PromotionsList(
    $revisionType,
    $sortingField,
    $sortingType,
    $searchString,
    $location,
    $section,
    $publishedOn
);
echo $list->showList();
?>