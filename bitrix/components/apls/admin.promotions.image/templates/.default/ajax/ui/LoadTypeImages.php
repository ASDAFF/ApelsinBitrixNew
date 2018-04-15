<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionImageHelper.php";

$images = PromotionImageModel::imageSearchByType($_REQUEST['typeId'],"",false);
?>
<div class="UploadImageWrapper">
    <input type="file" name="PromotionImageFile" id="PromotionImageFile" typeId="<?=$_REQUEST['typeId']?>" contentId="<?=$_REQUEST['contentId']?>">
</div>
<div class="ShowImageWrapper">
    <?foreach ($images as $image):?>
        <?if($image instanceof PromotionImageModel):?>
            <div class="ImageBlock">
                <img src="<?=PromotionImageHelper::getSmallImagePath($image->getId())?>">
                <div class="DeleteButton" imageId="<?=$image->getId()?>" typeId="<?=$_REQUEST['typeId']?>" contentId="<?=$_REQUEST['contentId']?>"></div>
            </div>
        <?endif;?>
    <?endforeach;?>
</div>

