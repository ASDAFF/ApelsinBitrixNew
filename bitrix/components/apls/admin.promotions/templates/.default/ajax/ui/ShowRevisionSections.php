<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortLists.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionSectionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionInSectionModel.php";
$promotionInSection = PromotionInSectionModel::searchByRevision($_REQUEST['revisionId']);
$selectedSectionsId = array();
foreach ($promotionInSection as $element) {
    if($element instanceof PromotionInSectionModel) {
        $selectedSectionsId[] = $element->getFieldValue('section');
    }
}

$orderByObj = new MySQLOrderByString();
$orderByObj->add('sort',MySQLOrderByString::ASC);
$allSections = PromotionSectionModel::getElementList(null, null, null, $orderByObj);
$selectedSections = new APLS_SortListElements();
$unselectedSections = new APLS_SortListElements();
foreach ($allSections as $section) {
    if($section instanceof PromotionSectionModel) {
        $content = "<span class='section'>".$section->getFieldValue('section')."</span>";
        $listElement = new APLS_SortListElement($content);
        $listElement->addAttribute('sectionId', $section->getId());
        if(in_array($section->getId(),$selectedSectionsId)) {
            $selectedSections->addSortListElement($listElement);
        } else {
            $unselectedSections->addSortListElement($listElement);
        }
    }
}

$selectedList = new APLS_SortList();
$selectedList->setSortListTitle("Выбранные разделы");
$selectedList->setSortListElements($selectedSections);
$unselectedList = new APLS_SortList();
$unselectedList->setSortListTitle("Не выбранные разделы");
$unselectedList->setSortListElements($unselectedSections);
?>
<div class='PromotionSectionsSelectedList'><?=$selectedList->getSortListHtml()?></div>
<div class='PromotionSectionsUnselectedList'><?=$unselectedList->getSortListHtml()?></div>