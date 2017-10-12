<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
$VIEW_HTML="";
?>
<div class="APLS-ServiceCenters">
<form action="#" id="<?=$arResult['FORM_ID']?>-form" rezultDiv="<?=$arResult['FORM_ID']?>-html" scriptFile="<?=$templateFolder?>/ajax.php" method="post">
    <i class="fa fa-search"></i>
    <input type="hidden" name="templateFolder" value="<?=$templateFolder?>" />
    <input type="hidden" name="highloadId" value="<?=$arParams['HIGHLOAD_ID']?>" />
    <input type="hidden" name="exceptionRoditel" value="<?=$arResult['EXCEPTION_RODITEL']?>" />
    <input type="text" name="q" id="<?=$arResult['FORM_ID']?>-search" class="" maxlength="50" autocomplete="off" placeholder="<?=Loc::getMessage("FORMS_SEARCH_TEXT")?>" value="">
</form>
<script type="text/javascript">
    BX.bind(BX("<?=$arResult['FORM_ID']?>-search"), "keyup", BX.delegate(BX.BocFormSubmit, BX));
    BX.bind(BX("<?=$arResult['FORM_ID']?>-form"), "keydown", BX.delegate(BX.NoEnter, BX));
</script>
<?require_once($_SERVER["DOCUMENT_ROOT"].$templateFolder."/view.php");?>
<div id="<?=$arResult['FORM_ID']?>-html">
<?=$VIEW_HTML?>
</div>
</div>