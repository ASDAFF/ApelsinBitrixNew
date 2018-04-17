<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionInSectionModel.php";
$promotionInSection = PromotionInSectionModel::searchByRevision($_REQUEST['revisionId']);
if(isset($_REQUEST['sections']) && is_array($_REQUEST['sections']) && !empty($_REQUEST['sections'])) {
    $promotionSectionsList = array();
    $selectedSectionsId = array();
    foreach ($promotionInSection as $element) {
        if($element instanceof PromotionInSectionModel) {
            $promotionSectionsList[$element->getFieldValue('section')] = $element->getId();
            $selectedSectionsId[] = $element->getFieldValue('section');
        }
    }
    foreach ($selectedSectionsId as $sectionId) {
        if(!in_array($sectionId, $_REQUEST['sections'])) {
            PromotionInSectionModel::deleteElement($promotionSectionsList[$sectionId]);
        }
    }
    foreach ($_REQUEST['sections'] as $sectionId) {
        if(!in_array($sectionId, $selectedSectionsId)) {
            PromotionInSectionModel::createElement(array('revision'=>$_REQUEST['revisionId'], 'section'=>$sectionId));
        }
    }
} else {
    foreach ($promotionInSection as $element) {
        PromotionInSectionModel::deleteElement($element->getId());
    }
}