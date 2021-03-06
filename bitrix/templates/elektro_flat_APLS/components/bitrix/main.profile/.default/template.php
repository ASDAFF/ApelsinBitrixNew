<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?=ShowError($arResult["strProfileError"]);?>
<?
if ($arResult['DATA_SAVED'] == 'Y') {
    if ($arResult["arUser"]["UF_MESSAGE_ERROR"] == 'error') {
        echo ShowError(GetMessage('ERROR'));
    } elseif ($arResult["arUser"]["UF_MESSAGE_ERROR"] == 'error1') {
        echo ShowError(GetMessage('ERROR1'));
    } elseif ($arResult["arUser"]["UF_MESSAGE_ERROR"] == 'error2') {
        echo ShowError(GetMessage('ERROR2'));
    } else {
        echo ShowNote(GetMessage('PROFILE_DATA_SAVED'));
    }
}
?>
<div class="workarea personal">
	<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
		<?=$arResult["BX_SESSION_CHECK"]?>
		<input type="hidden" name="lang" value="<?=LANG?>" />
		<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
		<input type="hidden" name="LOGIN" value=<?=$arResult["arUser"]["LOGIN"]?> />
		<input type="hidden" name="EMAIL" value=<?=$arResult["arUser"]["EMAIL"]?> />

        <?
        $db_sales = CSaleOrderUserProps::GetList(
            array("DATE_UPDATE" => "DESC"),
            array("USER_ID" => $USER->GetID())
        );
        while ($ar_sales = $db_sales->Fetch())
        {
            if(isset($ar_sales)){
                $ar_profId [$ar_sales['NAME']] = $ar_sales['ID'];
            } else {
                $ar_profId = NULL;
            }
        }
        ?>
        <div class="personal_tabs">
            <div id="personal_card" class="personal_tab_title checked"><?=GetMessage("LEGEND_CARD")?></div>
            <div id="personal_fio" class="personal_tab_title"><?=GetMessage("LEGEND_PROFILE")?></div>
            <?
                if ($ar_profId !== NULL) {
                   ?><div id="personal_delivery" class="personal_tab_title"><?=GetMessage('LEGEND_DELIVERY')?></div><?
                }
            ?>
            <div id="personal_pass" class="personal_tab_title"><?=GetMessage("MAIN_PSWD")?></div>
        </div>
        <div class="personal-info personal_card">

            <div class="personal-info_in">
                <?
                if($arResult["arUser"]["UF_CARD_NUMBER"] == null){
                    ?><div class="personal-info_left_column" style="display: none"><?
                } else {
                    ?><div class="personal-info_left_column"><?
                }
                ?>
                    <div class="personal-info_codebar_block">
                        <div class="personal-info_codebar_block_header"><?=GetMessage($arResult["arUser"]["UF_1C_TYPE_PRICE"]);?></div>
                        <div class="personal-info_codebar_next_step"></div>
                        <div class="personal-info_codebar">
                            <?
                            include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
                            $entityClass = APLS_GetHighloadEntityDataClass::getByHLName('Kontragenty');
                            $res = $entityClass::getList(array(
                                'select' => array('*'),
                                'filter' => array('UF_NOMERKARTYKLIENTA' => $arResult["arUser"]["UF_CARD_NUMBER"].'%')
                            ));
                            $row = $res->fetch();
                            ?>
                            <img src="<?=$templateFolder?>/barcode.php?f=png&s=ean-13&d=<?=$row['UF_NOMERKARTYKLIENTA']?>&w=280&h=140&th=15&ts=12&ph=5">
                            <span class="ripple rippleEffect"></span>
                        </div>
                        <div class="personal-info_codebar_bonus"></div>
                    </div>
                </div>
                <div class="personal-info_right_column">
                    <div class="input_card_number">
                        <input class="card_number" type="text" name="UF_CARD_NUMBER" maxlength="255" value="<?=$arResult["arUser"]["UF_CARD_NUMBER"]?>" autocomplete="off"  placeholder="64777"/>
                    </div>
                    <div class="UF_CARD_NUMBER">
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/card_new.png" alt="Номер карты" title="<?echo GetMessage("UF_CARD_NUMBER_TITLE");?>">
                    </div>
                </div>
            </div>
        </div>
		<div class="personal-info personal_fio">
			<div class="personal-info_in">
<!--                <div class="personal-info_left_column">-->
                    <?=GetMessage('NAME')?><br>
                    <input type="text" name="NAME" maxlength="50" class="input_text_style" value="<?=$arResult["arUser"]["NAME"]?>" />
                    <br><br>

                    <?=GetMessage('LAST_NAME')?><br>
                    <input type="text" name="LAST_NAME" maxlength="50" class="input_text_style" value="<?=$arResult["arUser"]["LAST_NAME"]?>" />
                    <br><br>

                    <div class="personsl_photo_cell">
                        <?if(empty($arResult["arUser"]["PERSONAL_PHOTO"])):?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/userpic.jpg" width="69" height="69"/>
                            <input type="file" name="PERSONAL_PHOTO" size="20" class="typefile" />
                        <?else:?>
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td valign="middle" style="padding:0px 10px 0px 0px;">
                                        <img src="<?=$arResult["arUser"]["PERSONAL_IMG"]["SRC"]?>" width="<?=$arResult["arUser"]["PERSONAL_IMG"]["WIDTH"]?>" height="<?=$arResult["arUser"]["PERSONAL_IMG"]["HEIGHT"]?>" />
                                    </td>
                                    <td valign="middle">
                                        <input type="file" name="PERSONAL_PHOTO" size="20" class="typefile" />
                                    </td>
                                </tr>
                            </table>
                        <?endif;?>
                    </div>
<!--                </div>-->
			</div>
		</div>
            <?
            if ($ar_profId !== NULL) {
                ?><div class="personal-info personal_delivery"><?
                $profId = array_shift($ar_profId);
                $APPLICATION->IncludeComponent(
                    "bitrix:sale.personal.profile.detail",
                    "short",
                    array(
                        "ID" => $profId,
                    )
                );
                ?></div><?
            }
            ?>
		<div class="personal-info personal_pass">
			<div class="personal-info_in">
				<?=GetMessage('NEW_PASSWORD_REQ')?><br>
				<input type="password" name="NEW_PASSWORD" maxlength="50" class="input_text_style" value="" autocomplete="new-password" />
				<br><br>

				<?=GetMessage('NEW_PASSWORD_CONFIRM')?><br>
				<input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" class="input_text_style" value="" autocomplete="new-password" />
			</div>
		</div>
            <button type="submit" name="save" class="btn_buy popdef bt3" value="<?=GetMessage('MAIN_SAVE')?>"><?=GetMessage("MAIN_SAVE")?></button>
	</form>
</div>
<div class="clr"></div>
<br />
<?if($arResult["SOCSERV_ENABLED"]) {
	$APPLICATION->IncludeComponent("bitrix:socserv.auth.split", ".default", 
		array(
			"SHOW_PROFILES" => "Y",
			"ALLOW_DELETE" => "Y"
		),
		false
	);
}?>