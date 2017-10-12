<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $arSetting;

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
	return;

foreach($arResult["BASKET"] as $key => $arBasketItems) {
	$ar = CIBlockElement::GetList(
		array(), 
		array("ID" => $arBasketItems["PRODUCT_ID"]), 
		false, 
		false, 
		array("ID", "IBLOCK_ID", "DETAIL_PICTURE")
	)->Fetch();		
	if($ar["DETAIL_PICTURE"] > 0) {
		$arResult["BASKET"][$key]["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	} else {
		$mxResult = CCatalogSku::GetProductInfo($ar["ID"]);
		if(is_array($mxResult)) {
			$ar = CIBlockElement::GetList(
				array(), 
				array("ID" => $mxResult["ID"]), 
				false, 
				false, 
				array("ID", "IBLOCK_ID", "DETAIL_PICTURE")
			)->Fetch();
			if($ar["DETAIL_PICTURE"] > 0) {
				$arResult["BASKET"][$key]["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			}
		}
	}
	if(in_array("OFFERS_LINK_SHOW", $arSetting["GENERAL_SETTINGS"]["VALUE"]) && is_array(CCatalogSku::GetProductInfo($arBasketItems["PRODUCT_ID"]))) {
		$arResult["BASKET"][$key]["DETAIL_PAGE_URL"] .= "?offer=".$arBasketItems["PRODUCT_ID"];
	}
}?>