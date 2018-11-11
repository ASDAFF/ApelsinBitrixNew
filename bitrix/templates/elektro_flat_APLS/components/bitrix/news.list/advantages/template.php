<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>

<div class="advantages">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<div id="advantages_<?=$arItem["CODE"]?>" class="advantages-item">
			<div class="advantages-item-icon">
                <img src="<?=SITE_TEMPLATE_PATH?>/icon/<?=$arItem["CODE"]?>.svg">
			</div>
			<div class="advantages-item-text">
				<b><?=$arItem['NAME']?></b>
			</div>
            <div class="advantages-item-previw">
                <?=$arItem['PREVIEW_TEXT']?>
            </div>
            <?
            global $APPLICATION;
            $randId = rand(1, 9999);
            $APPLICATION->IncludeComponent(
                "apls:popap.by.button",
                "",
                Array(
                    "ALIAS" => "popapByButton_".$arItem["CODE"],
                    "BUTTON_ID" => "advantages_".$arItem["CODE"],
                    "BUTTON_TEXT" => "",
                    "TITLE_TEXT" => $arItem['NAME'],
                    "FILE_PATH" => "/include/advantages/".$arItem["CODE"].".php?g=$randId",
                    "OVERLAY"=>"Y",
                    "AUTO_HIDE"=>"Y",
                    "OVERFLOW_HIDDEN"=>"Y",
                    "CLOSE_BUTTON" => "Y",
                ),
                false
            );
            ?>
		</div>
	<?endforeach;?>
</div>