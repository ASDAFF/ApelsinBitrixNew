<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionImageTypeModel.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionImageInRevisionModel.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionImageModel.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/classes/PromotionImageHelper.php";

$imagesInRevision = PromotionImageInRevisionModel::searchByRevision($_REQUEST['revisionId']);
$images = array();
foreach ($imagesInRevision as $imageInRevision) {
    if($imageInRevision instanceof PromotionImageInRevisionModel){
        $image = new PromotionImageModel($imageInRevision->getFieldValue('img'));
        $images[$image->getFieldValue('type')] = $image->getId();
    }
}
$imageTypes = PromotionImageTypeModel::getElementList();?>
<div class="ImagesList">
    <?foreach ($imageTypes as $imageType):?>
        <div class="PromotionImageWrapper">
            <?if($imageType instanceof PromotionImageTypeModel):?>
                <?$typeId = $imageType->getId();?>
                <div class="ImageTypeTitle">
                    <?=$imageType->getFieldValue('type')?>
                    <div class="DeleteButton" typeId="<?=$typeId?>"></div>
                </div>
                <div class="PromotionImage type-<?=$typeId?>" typeId="<?=$typeId?>">
                    <?if(isset($images[$typeId])):?>
                        <img class="Image" src="<?=PromotionImageHelper::getSmallImagePath($images[$typeId])?>" imageId="<?=$images[$typeId]?>">
                    <?else:?>
                        <div class="Image noImage"></div>
                    <?endif;?>
                </div>
            <?endif;?>
            <div class="EditPointer"></div>
        </div>
    <?endforeach;?>
</div>
<div class="PromotionImageEditWrapper"></div>
