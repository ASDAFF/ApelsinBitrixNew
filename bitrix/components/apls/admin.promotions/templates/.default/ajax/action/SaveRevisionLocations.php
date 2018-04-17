<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionInRegionModel.php";
$promotionInRegion = PromotionInRegionModel::searchByRevision($_REQUEST['revisionId']);
if(isset($_REQUEST['locations']) && is_array($_REQUEST['locations']) && !empty($_REQUEST['locations'])) {
    $promotionRegionsList = array();
    $selectedRegionsId = array();
    foreach ($promotionInRegion as $element) {
        if($element instanceof PromotionInRegionModel) {
            $promotionRegionsList[$element->getFieldValue('region')] = $element->getId();
            $selectedRegionsId[] = $element->getFieldValue('region');
        }
    }
    foreach ($selectedRegionsId as $regionId) {
        if(!in_array($regionId, $_REQUEST['locations'])) {
            PromotionInRegionModel::deleteElement($promotionRegionsList[$regionId]);
        }
    }
    foreach ($_REQUEST['locations'] as $regionId) {
        if(!in_array($regionId, $selectedRegionsId)) {
            PromotionInRegionModel::createElement(array('revision'=>$_REQUEST['revisionId'], 'region'=>$regionId));
        }
    }
} else {
    foreach ($promotionInRegion as $element) {
        PromotionInRegionModel::deleteElement($element->getId());
    }
}