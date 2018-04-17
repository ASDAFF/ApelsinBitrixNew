<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortLists.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionInRegionModel.php";
$promotionInRegion = PromotionInRegionModel::searchByRevision($_REQUEST['revisionId']);
$selectedRegionsId = array();
foreach ($promotionInRegion as $element) {
    if($element instanceof PromotionInRegionModel) {
        $selectedRegionsId[] = $element->getFieldValue('region');
    }
}

$orderByObj = new MySQLOrderByString();
$orderByObj->add('sort',MySQLOrderByString::ASC);
$allRegions = PromotionRegionModel::getElementList(null, null, null, $orderByObj);
$selectedRegions = new APLS_SortListElements();
$unselectedRegions = new APLS_SortListElements();
foreach ($allRegions as $region) {
    if($region instanceof PromotionRegionModel) {
        $content = "<span class='region'>".$region->getFieldValue('region')."</span>";
        $listElement = new APLS_SortListElement($content);
        $listElement->addAttribute('locationId', $region->getId());
        if(in_array($region->getId(),$selectedRegionsId)) {
            $selectedRegions->addSortListElement($listElement);
        } else {
            $unselectedRegions->addSortListElement($listElement);
        }
    }
}

$selectedList = new APLS_SortList();
$selectedList->setSortListTitle("Выбранные разделы");
$selectedList->setSortListElements($selectedRegions);
$unselectedList = new APLS_SortList();
$unselectedList->setSortListTitle("Не выбранные разделы");
$unselectedList->setSortListElements($unselectedRegions);
?>
<div class='PromotionLocationsSelectedList'><?=$selectedList->getSortListHtml()?></div>
<div class='PromotionLocationsUnselectedList'><?=$unselectedList->getSortListHtml()?></div>