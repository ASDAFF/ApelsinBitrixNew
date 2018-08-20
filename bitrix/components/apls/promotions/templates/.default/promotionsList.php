<?
$sectionUrl = $arResult["sefFolder"];
$moreUrl = $arResult["sefFolder"]."id/";
if($arResult["urlRegion"] !== "") {
    $sectionUrl .= $arResult["urlRegion"]."/";
}
?>
<script type="text/javascript">
    //JS_MESSAGE//
    BX.message({
        CITY_CHANGE_COMPONENT_TEMPLATE: "<?=$this->GetFolder();?>",
        CITY_CHANGE_WINDOW_TITLE: "Смените регион действия акций",
        CITY_CHANGE_URL: "<?=$arResult["sefFolder"]?>",
    });
</script>
<div class="PromotionsCities">
    <a id="CITY_CHANGE_BUTTON" class="btn">Посмотреть в других регионах</a>
</div>
<div class="PromotionsCitiesSectionsWrapper">
    <div class="PromotionsCitiesSection">
        <div class="Element" sectionkey="all"><a href="<?=$sectionUrl?>"><span class="sectionName">Все</span></a></div>
    </div>
    <? foreach ($arResult["sections"] as $section): ?>
        <? if ($section instanceof PromotionSectionModel): ?>
            <div class="PromotionsCitiesSection">
                <?
                if($section->getId() === $arResult["section"]){
                    $sectionClass = "thisSection";
                } else {
                    $sectionClass = "";
                }
                ?>
                <div class="Element <?=$sectionClass?>" sectionkey="<?= $section->getFieldValue('alias') ?>">
                    <a href="<?=$sectionUrl?><?=$section->getFieldValue('alias')?>">
                        <span class="sectionName"><?= $section->getFieldValue('section') ?></span>
                        <span class="counter"><?= count($arResult['promotionsInSections'][$section->getId()]) ?></span>
                    </a>
                </div>
            </div>
        <? endif; ?>
    <? endforeach; ?>
    <div class="clear"></div>
</div>
<div class="PromotionsWrapper">
    <? foreach ($arResult["promotions"] as $promotion): ?>
        <? if ($promotion instanceof PromotionModel): ?>
            <?
            $revision = $promotion->getCurrentRevision();
            $title = $revision->getFieldValue('title');
            $previewText = $revision->getFieldValue('preview_text');
            $mainText = $revision->getFieldValue('main_text');
            $images = $revision->getImages();
            $startFrom = $revision->getFieldValue('start_from');
            $stopFrom = $revision->getFieldValue('stop_from');
            if($startFrom !== null && $stopFrom !== null) {
                $dateTime = "c <span class'DateString'>".$startFrom->format("d.m.Y")."</span> по <span class'DateString'>".$stopFrom->format("d.m.Y")."</span>";
            } else if($startFrom !== null) {
                $dateTime = "c <span class'DateString'>".$startFrom->format("d.m.Y")."</span>";
            } else if($stopFrom !== null) {
                $dateTime = "по <span class'DateString'>".$stopFrom->format("d.m.Y")."</span>";
            } else {
                $dateTime = "";
            }
            if (isset($images[$arResult["pImgType"]])) {
                $pImg = $images[$arResult["pImgType"]];
            } else {
                $pImg = null;
            }
            ?>
            <div class="PromotionElement">
                <div class="PromotionImage">
                    <? if ($pImg instanceof PromotionImageModel): ?>
                        <img src="<?=PromotionImageHelper::getBigImagePath($pImg->getId())?>">
                    <? else: ?>
                        <img src="<?=$templateFolder?>/defaultPromotionImage.jpg">
                    <? endif; ?>
                </div>
                <div class="PromotionContent">
                    <? if($title !== ""): ?>
                        <div class="PromotionTitle"><?= $title ?></div>
                    <? endif; ?>
                    <div class="PromotionText"><?= $previewText ?></div>
                </div>
                <div class="PromotionFooter">
                    <?if( $mainText !== null && $mainText !== "" ):?>
                    <a class="ButtonMore" href="<?=$moreUrl.$promotion->getId()?>/">Подробности</a>
                    <?endif;?>
                    <div class="DateTime"><?=$dateTime?></div>
                </div>
            </div>
        <? endif; ?>
    <? endforeach; ?>
    <div class="clear"></div>
</div>