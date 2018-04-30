<?php
if(isset($arResult["revision"])) {
    $revision = $arResult["revision"];
    if($revision instanceof PromotionRevisionModel) {
        $revisionImages = $revision->getImages();
        $bannerUrl = "";
        $bigUrl = "";
        if (isset($revisionImages[$arResult["bannerImageType"]])){
            $banner = $revisionImages[$arResult["bannerImageType"]];
            if($banner instanceof PromotionImageModel) {
                $bannerUrl = PromotionImageHelper::getBigImagePath($banner->getId());
            }
        }
        if (isset($revisionImages[$arResult["bigImageType"]])){
            $bigImage = $revisionImages[$arResult["bigImageType"]];
            if($bigImage instanceof PromotionImageModel) {
                $bigUrl = PromotionImageHelper::getBigImagePath($bigImage->getId());
            }
        }
        ?>
        <div class="PromotionWrapper">
            <? if($bannerUrl !== ""):?>
                <div class="PromotionBannerBlock">
                    <img src="<?=$bannerUrl?>" class="PromotionBanner">
                </div>
            <? endif;?>
            <div class="PromotionMainTextWrapper">
                <? if($bigUrl !== ""):?>
                    <img src="<?=$bigUrl?>" class="PromotionBigImage" align="right">
                <? endif;?>
                <div class="PromotionMainText">
                    <?=$revision->getFieldValue('main_text')?>
                </div>
            </div>
            <div class="PromotionCatalogItems">
                <?include_once $_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/productsList.php";?>
            </div>
            <div class="ButtonPanel">
                <div class="StylishButton Orange" onclick="history.back();">на предыдущую страницу</div>
            </div>
        </div>
        <?
    }
}
?>
