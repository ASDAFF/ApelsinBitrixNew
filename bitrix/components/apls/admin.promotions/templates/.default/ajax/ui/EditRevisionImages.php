<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionImageHelper.php";

$images = PromotionImageModel::imageSearchByType($_REQUEST['typeId'],"",false);
if(isset($_REQUEST['searchString']) && $_REQUEST['searchString'] !== "" && !empty($images)) {
    $searchString = mb_strtolower ($_REQUEST['searchString']);
    foreach ($images as $key => $image) {
        if($image instanceof PromotionImageModel) {
            $searchText = $image->getFieldValue('name');
        } else {
            $searchText = "";
        }
        $searchText = mb_strtolower(str_replace("_", " ", $searchText));
        $searchCount = 0;
        $searchStringArray = array_diff(explode(" ", $searchString), array(""));
        foreach ($searchStringArray as $searchWord) {
            $searchWord = str_replace("_"," ", $searchWord);
            if(substr_count($searchText,$searchWord) > 0) {
                $searchCount++;
            }
        }
        if($searchCount !== count($searchStringArray)) {
            unset($images[$key]);
        }
    }
}
?>
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
                    <img src="<?=PromotionImageHelper::getSmallImagePath($image->getId())?>" title="<?=$image->getFieldValue('name')?>">
                </div>
            <?endif;?>
        <?endforeach;?>
    <?else:?>
        <div class="noImage">нет ни одного изображения</div>
    <?endif;?>
</div>