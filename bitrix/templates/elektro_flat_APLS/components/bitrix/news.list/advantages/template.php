<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>

<div class="advantages">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<div class="advantages-item">		
			<div class="advantages-item-icon">
                <img src="<?=SITE_TEMPLATE_PATH?>/icon/<?=$arItem["CODE"]?>.svg">
			</div>
			<div class="advantages-item-text">
				<b><?=$arItem['NAME']?></b>
			</div>
            <div class="advantages-item-previw">
                <?=$arItem['PREVIEW_TEXT']?>
            </div>
		</div>
	<?endforeach;?>
</div>