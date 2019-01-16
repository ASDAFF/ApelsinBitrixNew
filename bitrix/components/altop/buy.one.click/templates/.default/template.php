<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

if($arResult["ELEMENT"]["ID"] > 0):?>
	<div class="info">
		<div class="image">
			<?if(is_array($arResult["ELEMENT"]["PREVIEW_PICTURE"])):?>
				<img src="<?=$arResult['ELEMENT']['PREVIEW_PICTURE']['SRC']?>" width="<?=$arResult['ELEMENT']['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arResult['ELEMENT']['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arResult['ELEMENT']['NAME']?>" title="<?=$arResult['ELEMENT']['NAME']?>" />
			<?else:?>
				<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arResult['ELEMENT']['NAME']?>" title="<?=$arResult['ELEMENT']['NAME']?>" />
			<?endif?>
		</div>
		<div class="name"><?=$arResult["ELEMENT"]["NAME"]?></div>
	</div>
<?endif;?>
<form action="<?=$this->__component->__path?>/script.php" id="boc_<?=$arParams['ELEMENT_AREA_ID']?>_form" onsubmit="ym(51503324, 'reachGoal', 'bistr_zakaz2'); return true;">
	<span class="alert"></span>
	<?foreach($arParams["PROPERTIES"] as $arCode):?>
		<div class="row">
			<div class="span1"><?=Loc::getMessage("FORMS_1CB_".$arCode).(in_array($arCode, $arParams["REQUIRED"]) ? "<span class='mf-req'>*</span>" : "");?></div>
			<div class="span2">
				<?if($arCode != "MESSAGE"):?>
					<input type="text" name="<?=$arCode?>" value="<?
                    if ($arCode == 'NAME') {
                        echo $arResult['USER']['NAME'];
                    } else if ($arCode == 'EMAIL') {
                        echo $arResult['USER']['EMAIL'];
                    } else if ($arCode == 'PHONE') {
                        echo $arResult['USER']['PHONE'];
                    }
//                    ($arCode == 'NAME' ? $arResult['USER']['NAME'] : ($arCode == 'EMAIL' ? $arResult['USER']['EMAIL'] : ''));
					?>" />
				<?else:?>
					<textarea name="<?=$arCode?>" rows="3"></textarea>
				<?endif;?>
			</div>
		</div>
	<?endforeach;
	if($arParams["USE_CAPTCHA"] == "Y"):?>
		<div class="row">
			<div class="span1"><?=Loc::getMessage("FORMS_1CB_CAPTCHA")?><span class="mf-req">*</span></div>
			<div class="span2">					
				<input type="text" name="CAPTCHA_WORD" maxlength="5" value="" />			
				<img src="" width="127" height="30" alt="CAPTCHA" style="display:none;" />
				<input type="hidden" name="CAPTCHA_SID" value="" />
			</div>
		</div>
	<?endif;?>
	<input type="hidden" name="PARAMS_STRING" value="<?=$arParams['PARAMS_STRING']?>" />
	<?if($arResult["ELEMENT"]["ID"] > 0):?>
		<input type="hidden" name="ID" value="<?=$arResult['ELEMENT']['ID']?>" />
		<input type="hidden" name="PROPS" value="" />
		<input type="hidden" name="SELECT_PROPS" value="" />
		<input type="hidden" name="QUANTITY" value="" />
	<?endif;?>	
	<input type="hidden" name="BUY_MODE" value="<?=$arParams['BUY_MODE']?>" />
	<?//AGREEMENT//
	if($arParams["SHOW_PERSONAL_DATA"] == "Y") {?>
		<div class="hint_agreement">
			<input type="hidden" name="PERSONAL_DATA" id="PERSONAL_DATA_<?=$arParams['ELEMENT_AREA_ID']?>" value="N">
			<div class="checkbox">
				<span class="input-checkbox" id="input-checkbox_<?=$arParams['ELEMENT_AREA_ID']?>"></span>
			</div>	
			<div class="label">
				<?=$arParams["TEXT_PERSONAL_DATA"]?>
			</div>
		</div>
	<?}?>
	<div class="submit">
		<button type="button" id="boc_<?=$arParams['ELEMENT_AREA_ID']?>_btn" class="btn_buy popdef"><?=Loc::getMessage("FORMS_1CB_SEND")?></button>
	</div>
</form>

<script type="text/javascript">
	//TITLE//
	BX.adjust(BX("popup-window-titlebar-boc_<?=$arParams['ELEMENT_AREA_ID']?>"), {html: "<?=Loc::getMessage('FORMS_1CB_TITLE')?>"});

	//MASK//
	var input = $("#boc_<?=$arParams['ELEMENT_AREA_ID']?>_form").find("[name='PHONE']");
	if(!!input)
		input.inputmask("<?=$arParams['PHONE_MASK']?>");
	
	<?if($arResult["ELEMENT"]["ID"] > 0):?>
		//PROPS//
		var parentPropsInput = BX("props_<?=$arParams['ELEMENT_AREA_ID']?>"),
			bocPropsInput = BX.findChild(BX("boc_<?=$arParams['ELEMENT_AREA_ID']?>_form"), {attribute: {name: "PROPS"}}, true, false);
		if(!!parentPropsInput && !!bocPropsInput)
			bocPropsInput.value = parentPropsInput.value;

		//SELECT_PROPS//
		var parentSelPropsInput = BX("select_props_<?=$arParams['ELEMENT_AREA_ID']?>"),
			bocSelPropsInput = BX.findChild(BX("boc_<?=$arParams['ELEMENT_AREA_ID']?>_form"), {attribute: {name: "SELECT_PROPS"}}, true, false);
		if(!!parentSelPropsInput && !!bocSelPropsInput)
			bocSelPropsInput.value = parentSelPropsInput.value;

		//QUANTITY//
		var parentQntInput = BX("quantity_<?=$arParams['ELEMENT_AREA_ID']?>"),
			bocQntInput = BX.findChild(BX("boc_<?=$arParams['ELEMENT_AREA_ID']?>_form"), {attribute: {name: "QUANTITY"}}, true, false);
		if(!!parentQntInput && !!bocQntInput)
			bocQntInput.value = parentQntInput.value;
	<?endif;?>
	
	//FORM_SUBMIT//
	BX.bind(BX("boc_<?=$arParams['ELEMENT_AREA_ID']?>_btn"), "click", BX.delegate(BX.BocFormSubmit, BX));
	
	//CHEKED//
	BX.bind(BX("input-checkbox_<?=$arParams['ELEMENT_AREA_ID']?>"),"click",function(){
		if(!BX.hasClass(BX("input-checkbox_<?=$arParams['ELEMENT_AREA_ID']?>"),"cheked")){
			BX.addClass(BX("input-checkbox_<?=$arParams['ELEMENT_AREA_ID']?>"),"cheked");
			BX.adjust(BX("input-checkbox_<?=$arParams['ELEMENT_AREA_ID']?>"),{
				children:[
					BX.create("i",{
						props:{
							className:"fa fa-check"
						}
					})
				]
			});
			BX.adjust(BX("PERSONAL_DATA_<?=$arParams['ELEMENT_AREA_ID']?>"),{
				props:{
					"value":"Y"
				}
			});
		} else {
			BX.removeClass(BX("input-checkbox_<?=$arParams['ELEMENT_AREA_ID']?>"),"cheked");
			BX.remove(BX.findChild(BX("input-checkbox_<?=$arParams['ELEMENT_AREA_ID']?>"),{
				className:"fa fa-check"
			}));
			BX.adjust(BX("PERSONAL_DATA_<?=$arParams['ELEMENT_AREA_ID']?>"),{
				props:{
					"value":"N"
				}
			});
		}
	});
</script>