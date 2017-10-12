<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>

<script type="text/javascript">
	$(function() {
		$(".anythingSlider").anythingSlider({			
			"expand": true,			
			"easing": "easeInOutExpo",			
			"buildStartStop": false,
			"forwardText": "<i class='fa fa-chevron-right'></i>",
			"backText": "<i class='fa fa-chevron-left'></i>",
			"hashTags": false,
			"autoPlay": true,
			"autoPlayLocked": true
		});
		$(window).resize(function () {
			currentWidth = $(".content-wrapper").children(".center").width();
			if(currentWidth < "768") {
				$(".anythingContainer").css({
					"height": currentWidth * 0.30 + "px"
				});
			} else {
				$(".anythingContainer").removeAttr("style");
			}
		});
		$(window).resize();
	});
</script>

<div class="anythingContainer">
	<ul class="anythingSlider">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<li>
				<?if(!empty($arItem["DISPLAY_PROPERTIES"]["URL"])):?>
					<a href="<?=$arItem["DISPLAY_PROPERTIES"]["URL"]["VALUE"]?>" style="background:url(<?=$arItem["PICTURE_PREVIEW"]["SRC"]?>) center center no-repeat; background-size:cover;"></a>
				<?else:?>
					<a href="javascript:void(0)" style="background:url(<?=$arItem["PICTURE_PREVIEW"]["SRC"]?>) center center no-repeat; background-size:cover;"></a>
				<?endif;?>
			</li>
		<?endforeach;?>
	</ul>
</div>