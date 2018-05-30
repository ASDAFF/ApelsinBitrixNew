<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/parser/AplsXmlParser.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionRevisionModel.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionCatalogSection.php");
?>

<?
$xmlParser = new AplsXmlParser();
$result = $xmlParser->xmlwebi($_FILES['file']['tmp_name']);
if (!empty($result['ref'][0]['#']['refid'])) {
    $arrayFromUploadFile = array();
    foreach ($result['ref'][0]['#']['refid'] as $element) {
        $arrayFromUploadFile[] = $element['#'];
    }
    $revision = new PromotionRevisionModel($_REQUEST['revision']);
    $revisionSections = $revision->getCatalogSections();
    $sectionAdds = array();
    foreach ($revisionSections as $section) {
        $sectionAdds[] = $section->getFieldValue('section');
    }
    $resultArray = array_diff($arrayFromUploadFile,$sectionAdds);
    foreach ($resultArray as $sectionXml) {
        PromotionCatalogSection::createElement(array('revision'=>$_REQUEST['revision'],'section'=>$sectionXml));
    }
}
?>
