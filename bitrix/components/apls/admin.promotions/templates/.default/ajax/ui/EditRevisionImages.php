<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionImageHelper.php";

$images = PromotionImageModel::imageSearchByType($_REQUEST['typeId'],"",false);
?>
<!--<div class="UploadImageWrapper">-->
<!--    <input type="file" name="PromotionImageFile" id="PromotionImageFile" typeId="--><?//=$_REQUEST['typeId']?><!--">-->
<!--</div>-->
<div class="ShowImageWrapper" typeId="<?=$_REQUEST['typeId']?>">
    <?if(!empty($images)):?>
        <?foreach ($images as $image):?>
            <?if($image instanceof PromotionImageModel):?>
                <?if($_REQUEST['imageId'] === $image->getId()):?>
                    <?$class="ThisImage"?>
                <?else:?>
                    <?$class=""?>
                <?endif;?>
                <div class="ImageBlock <?=$class?>" imageId="<?=$image->getId()?>" typeId="<?=$_REQUEST['typeId']?>">
                    <img src="<?=PromotionImageHelper::getSmallImagePath($image->getId())?>">
                </div>
            <?endif;?>
        <?endforeach;?>
    <?else:?>
        <div class="noImage">нет ни одного изображения</div>
    <?endif;?>
</div>