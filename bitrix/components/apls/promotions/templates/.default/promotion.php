<?php
if(isset($arResult["revision"])) {
    $revision = $arResult["revision"];
    if($revision instanceof PromotionRevisionModel) {
        ?>
        <div class="PromotionWrapper">
            <div class="PromotionMainText">
                <?=$revision->getFieldValue('main_text')?>
            </div>
            <div class="ButtonPanel">

            </div>
        </div>
        <?
    }
}