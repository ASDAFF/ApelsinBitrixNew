<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/options.php');
Loc::loadMessages(__FILE__);

if (!$USER->IsAdmin() || !Loader::includeModule('sale'))
{
	return;
}

require __DIR__.'/config.php';

$arStatuses = array();
$dbStatus = CSaleStatus::GetList(Array("SORT" => "ASC"), Array("LID" => LANGUAGE_ID), false, false, Array("ID", "NAME", "SORT"));
while ($arStatus = $dbStatus->GetNext())
{
	$arStatuses[$arStatus["ID"]] = "[".$arStatus["ID"]."] ".$arStatus["NAME"];
}

$status = COption::GetOptionString("rbs.payment", "result_order_status", "N");
$iso = COption::GetOptionString("rbs.payment", "iso", serialize(array()));
$iso = unserialize($iso);

if ($REQUEST_METHOD == 'POST' && strlen($Update.$Apply)>0 && check_bitrix_sessid())
{

	$status = $_POST['RESULT_ORDER_STATUS'];
	COption::SetOptionString("rbs.payment", "result_order_status", $status);
	
	$iso = $_POST['iso'];
	if (!is_array($iso))
		$iso = array();
	COption::SetOptionString("rbs.payment", "iso", serialize($iso));
}

$iso = array_filter($iso);
$arDefaultIso = unserialize(DEFAULT_ISO);
if (is_array($arDefaultIso))
	$iso = array_merge($arDefaultIso, $iso);

// VIEW

$tabControl = new CAdminTabControl('tabControl', array(
	array('DIV' => 'edit1', 'TAB' => Loc::getMessage('MAIN_TAB_SET'), 'ICON' => 'ib_settings', 'TITLE' => Loc::getMessage('MAIN_TAB_TITLE_SET'))
));

$tabControl->Begin();

?>
<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?echo LANGUAGE_ID?>">
	<?=bitrix_sessid_post()?>

	<?$tabControl->BeginNextTab()?>

	<tr>
		<td width="40%"><?$msg=Loc::getMessage('RESULT_ORDER_STATUS'); if (ENCODING) echo $msg; else echo iconv("utf-8","windows-1251",$msg)?>:</td>
		<td width="60%">
			<select name="RESULT_ORDER_STATUS">
				<?

				foreach ($arStatuses as $key => $name)
				{
					?><option value="<?=$key?>"<?=$key == $status ? ' selected' : ''?>><?=htmlspecialcharsex($name)?></option><?
				}

				?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="40%"></td>
		<td width="60%">
			<input type="button" id="check-https" value="<?$msg=Loc::getMessage('CHECK_HTTPS'); if (ENCODING) echo $msg; else echo iconv("utf-8","windows-1251",$msg)?>">
			<p id="result-check-https"></p>
		</td>
	</tr>
	<tr>
		<td width="40%"><?$msg=Loc::getMessage('CURRENCY_CHOISE'); if (ENCODING) echo $msg; else echo iconv("utf-8","windows-1251",$msg)?></td>
		<td width="60%">
			<table>
				<thead>
					<th><?$msg=Loc::getMessage('CC_HEAD_CURRENCY'); if (ENCODING) echo $msg; else echo iconv("utf-8","windows-1251",$msg)?></th>
					<th><?$msg=Loc::getMessage('CC_HEAD_CODE'); if (ENCODING) echo $msg; else echo iconv("utf-8","windows-1251",$msg)?></th>
					<th><?$msg=Loc::getMessage('CC_HEAD_ISO'); if (ENCODING) echo $msg; else echo iconv("utf-8","windows-1251",$msg)?></th>
				</thead>
				<tbody>
			<? $dbRes = CCurrency::GetList(($by = 'id'), ($order = 'asc'));
				while ($arItem=$dbRes->GetNext()):
			?>
				<tr>
					<td><?=$arItem["FULL_NAME"]?></td>
					<td><?=$arItem["CURRENCY"]?></td>
					<td><input name="iso[<?=$arItem["~CURRENCY"]?>]" type="text" value="<?echo $iso[$arItem["~CURRENCY"]] ? $iso[$arItem["~CURRENCY"]] : $arItem["NUMCODE"]?>"></td>
				</tr>
			<? endwhile;?>
				</tbody>
			</table>
		</td>
	</tr>
<script type="text/javascript">
BX.ready(function(){
	var oButtonCheck = document.getElementById('check-https');
	if (oButtonCheck)
	{
		oButtonCheck.onclick = function(){
			BX.ajax.loadJSON('/rbs.payment/ajax.php',
			'<?echo CUtil::JSEscape(bitrix_sessid_get())?>&check_https=Y',
			function(result){
				var oResultCH = document.getElementById('result-check-https');
				if (oResultCH)
				{					
					if ( result.SUCCESS === 'Y' )
					{
						oResultCH.innerHTML = '<span style="color: #00f;"><?$msg=Loc::getMessage('CHECK_HTTPS_SUCCESS'); if (ENCODING) echo $msg; else echo iconv("utf-8","windows-1251",$msg)?></span>';
					}
					else
					{
						oResultCH.innerHTML = '<span style="color: #f00;"><?$msg=Loc::getMessage('CHECK_HTTPS_FAIL'); if (ENCODING) echo $msg; else echo iconv("utf-8","windows-1251",$msg)?></span>';
					}
				}
					
			});
			return false;
		}
	}
});
</script>
	<?$tabControl->Buttons()?>

	<input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	<?if(strlen($_REQUEST["back_url_settings"])>0):?>
		<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<?endif?>

	<?$tabControl->End()?>
</form>
