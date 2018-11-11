<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

CUtil::InitJSCore(array('jquery'));
CUtil::InitJSCore( array('ajax' , 'popup' ));

if($arParams['ACCEPT_BUTTON_TEXT'] == "") {
    $arParams['ACCEPT_BUTTON_TEXT'] = "Применить";
}

$arResult = array();
$arResult['ALIAS'] = $arParams['ALIAS'];
$arResult['TITLE_TEXT'] = $arParams['TITLE_TEXT'];
$arResult['BUTTON_ID'] = $arParams['BUTTON_ID'];
$arResult['BUTTON_TEXT'] = $arParams['BUTTON_TEXT'];
$arResult['FILE_PATH'] = $arParams['FILE_PATH'];
$arResult['OVERLAY'] = $arParams['OVERLAY'];
$arResult['AUTO_HIDE'] = $arParams['AUTO_HIDE'];
$arResult['OVERFLOW_HIDDEN'] = $arParams['OVERFLOW_HIDDEN'];
$arResult['OLD_CORE'] = $arParams['OLD_CORE'];
$arResult['CLOSE_BUTTON'] = $arParams['CLOSE_BUTTON'];
$arResult['ACCEPT_BUTTON'] = $arParams['ACCEPT_BUTTON'];
$arResult['ACCEPT_BUTTON_TEXT'] = $arParams['ACCEPT_BUTTON_TEXT'];
$arResult['ACCEPT_BUTTON_JS'] = $arParams['ACCEPT_BUTTON_JS'];
$arResult['CLOSE_AFTER_ACCEPT'] = $arParams['CLOSE_AFTER_ACCEPT'];
$arResult['BUTTON_CREATE'] = $arParams['BUTTON_CREATE'];

$this->IncludeComponentTemplate();