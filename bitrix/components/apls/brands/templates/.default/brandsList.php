<?php
if(count($arResult["brands"]) < 1)
    return;?>

<div class="vendors-section-list">
    <div class="vendors-section-items">
        <?foreach($arResult["brands"] as $arItem):?>
            <div class="vendors-section-item">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
					<span class="item">
						<span class="image">
							<?if(is_array($arItem["PREVIEW_PICTURE"])):?>
                                <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" width="<?=$arItem['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arItem['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
                            <?else:?>
                                <img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="50" height="50" alt="<?=$arItem['NAME']?>" />
                            <?endif;?>
						</span>
						<span class="item-title"><?=$arItem["NAME"]?></span>
					</span>
                </a>
            </div>
        <?endforeach;?>
        <div class="clr"></div>
    </div>
</div>