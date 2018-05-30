<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/parser/AplsXmlParser.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionRevisionModel.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionCatalogProduct.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionCatalogException.php");
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
    $revisionExceptions = $revision->getCatalogExceptions();
    $exceptionAdds = array();
    foreach ($revisionExceptions as $exception) {
        $exceptionAdds[] = $exception->getFieldValue('product');
    }
    $revisionProducts = $revision->getCatalogProducts();
    $productAdds = array();
    foreach ($revisionProducts as $product) {
        $productAdds[$product->getId()] = $product->getFieldValue('product');
    }

    $delArray = array_intersect($productAdds, $arrayFromUploadFile);
    foreach ($delArray as $key=>$delElement) {
        $delResult [] = PromotionCatalogProduct::deleteElement($key);
    }
    $resultArray = array_diff($arrayFromUploadFile,$exceptionAdds);
    foreach ($resultArray as $exceptionXml) {
        PromotionCatalogException::createElement(array('revision'=>$_REQUEST['revision'],'product'=>$exceptionXml));
    }
    if (!empty($delResult)) {
        return 'Произошла ошибка удаления';
    }
}
?>
