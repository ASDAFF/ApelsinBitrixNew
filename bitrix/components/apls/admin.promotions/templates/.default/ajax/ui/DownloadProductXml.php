<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/parser/AplsXmlParser.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionRevisionModel.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionCatalogProduct.php");
?>
<?
$xmlParser = new AplsXmlParser();
$result = $xmlParser->xmlwebi($_FILES['file']['tmp_name']);
if (!empty($result['Body'][0]['#']['Item'])) {
    $arrayFromUploadFile = array();
    foreach ($result['Body'][0]['#']['Item'] as $element) {
        $arrayFromUploadFile[] = $element['#'];
    }
    $revision = new PromotionRevisionModel($_REQUEST['revision']);
    $revisionProducts = $revision->getCatalogProducts();
    $productAdds = array();
    foreach ($revisionProducts as $product) {
        $productAdds[] = $product->getFieldValue('product');
    }
    $revisionExceptions = $revision->getCatalogExceptions();
    $exceptionAdds = array();
    foreach ($revisionExceptions as $exception) {
        $exceptionAdds[] = $exception->getFieldValue('product');
    }
    $resultArray = array_diff($arrayFromUploadFile,$productAdds,$exceptionAdds);
    foreach ($resultArray as $productXml) {
        PromotionCatalogProduct::createElement(array('revision'=>$_REQUEST['revision'],'product'=>$productXml));
    }
}
?>
