<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageInRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageModel.php";
if($_REQUEST['typeId'] !== "" && $_REQUEST['imageId'] !== "" && $_REQUEST['revisionId'] !== "") {
    $imagesInRevision = PromotionImageInRevisionModel::searchByRevision($_REQUEST['revisionId']);
    foreach ($imagesInRevision as $imageInRevision) {
        if($imageInRevision instanceof PromotionImageInRevisionModel) {
            $image = new PromotionImageModel($imageInRevision->getFieldValue("img"));
            if($image->getFieldValue("type") === $_REQUEST['typeId']) {
                PromotionImageInRevisionModel::deleteElement($imageInRevision->getId());
            }
        }
    }
}

