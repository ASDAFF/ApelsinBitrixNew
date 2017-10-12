<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$arUrlTempl = Array(
	"delete" => $APPLICATION->GetCurPage()."?action=delete&id=#ID#",
	"delay" => $APPLICATION->GetCurPage()."?action=delay&id=#ID#",
	"add" => $APPLICATION->GetCurPage()."?action=add&id=#ID#",
	"BasketClear" => $APPLICATION->GetCurPage()."?BasketClear=Y",
	"DelayClear" => $APPLICATION->GetCurPage()."?DelayClear=Y",		
);

if(strlen($arResult["ERROR_MESSAGE"]) <= 0) {?>	
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
		<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delay.php");?>
		<input type="hidden" name="BasketOrder" value="BasketOrder" />
	</form>
<?} else {
	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
}

//CART_ACCESSORIES//
if(!empty($arResult["ITEMS"]["ACCESSORIES"])):
	global $arAcsCartFilter;
	$arAcsCartFilter = array(
		"ID" => array_unique($arResult["ITEMS"]["ACCESSORIES"]),
		"!ID" => $arResult["ITEMS"]["PARENT_PRODUCT_IDS"]
	);?>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "filtered",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
			"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
			"ELEMENT_SORT_FIELD2" => "",
			"ELEMENT_SORT_ORDER2" => "",
			"PROPERTY_CODE" => "",
			"SET_META_KEYWORDS" => "N",		
			"SET_META_DESCRIPTION" => "N",		
			"SET_BROWSER_TITLE" => "N",
			"SET_LAST_MODIFIED" => "N",
			"INCLUDE_SUBSECTIONS" => "Y",
			"SHOW_ALL_WO_SECTION" => "Y",
			"BASKET_URL" => "/personal/cart/",
			"ACTION_VARIABLE" => "action",
			"PRODUCT_ID_VARIABLE" => "id",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"FILTER_NAME" => "arAcsCartFilter",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "Y",
			"SET_TITLE" => "N",
			"MESSAGE_404" => "",
			"SET_STATUS_404" => "N",
			"SHOW_404" => "N",
			"FILE_404" => "",
			"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
			"PAGE_ELEMENT_COUNT" => "8",
			"LINE_ELEMENT_COUNT" => "",
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"USE_PRODUCT_QUANTITY" => "Y",
			"ADD_PROPERTIES_TO_BASKET" => "",
			"PARTIAL_PRODUCT_PROPERTIES" => "",
			"PRODUCT_PROPERTIES" => "",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_TITLE" => GetMessage("SALE_ACCESSORIES_ITEMS"),
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_BASE_LINK" => "",
			"PAGER_PARAMS_NAME" => "",
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
			"SECTION_ID" => "",
			"SECTION_CODE" => "",
			"SECTION_URL" => "",
			"DETAIL_URL" => "",
			"USE_MAIN_ELEMENT_SECTION" => "Y",
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"ADD_SECTIONS_CHAIN" => "N",		
			"COMPARE_PATH" => "",
			"BACKGROUND_IMAGE" => "",
			"DISABLE_INIT_JS_IN_COMPONENT" => "",
			"DISPLAY_IMG_WIDTH"	 =>	$arParams["DISPLAY_IMG_WIDTH"],
			"DISPLAY_IMG_HEIGHT" =>	$arParams["DISPLAY_IMG_HEIGHT"],
			"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"]
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?endif;

//BIGDATA_ITEMS//
if(!empty($arResult["ITEMS"]["AnDelCanBuy"])):	
	$arRecomData = array();
	$recomCacheID = array("IBLOCK_ID" => $arParams["IBLOCK_ID"]);
	$obCache = new CPHPCache();
	if($obCache->InitCache(86400, serialize($recomCacheID), "/catalog/recommended")) {
		$arRecomData = $obCache->GetVars();		
	} elseif($obCache->StartDataCache()) {
		if(Loader::includeModule("catalog")) {
			$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams["IBLOCK_ID"]);
			$arRecomData["OFFER_IBLOCK_ID"] = (!empty($arSKU) ? $arSKU["IBLOCK_ID"] : 0);
		}
		$obCache->EndDataCache($arRecomData);
	}
	if(!empty($arRecomData)):
		if(ModuleManager::isModuleInstalled("sale") && (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N')):?>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", ".default",
				array(
					"DISPLAY_IMG_WIDTH" => $arParams["DISPLAY_IMG_WIDTH"],
					"DISPLAY_IMG_HEIGHT" => $arParams["DISPLAY_IMG_HEIGHT"],
					"SHARPEN" => $arParams["SHARPEN"],
					"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
					"SHOW_POPUP" => "N",
					"LINE_ELEMENT_COUNT" => "4",
					"TEMPLATE_THEME" => "",
					"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
					"BASKET_URL" => "/personal/cart/",
					"ACTION_VARIABLE" => "action",
					"PRODUCT_ID_VARIABLE" => "id",
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",
					"ADD_PROPERTIES_TO_BASKET" => "Y",
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"PARTIAL_PRODUCT_PROPERTIES" => "",
					"SHOW_OLD_PRICE" => "",
					"SHOW_DISCOUNT_PERCENT" => "",
					"PRICE_CODE" => $arParams["PRICE_CODE"],
					"SHOW_PRICE_COUNT" => "1",
					"PRODUCT_SUBSCRIPTION" => "",
					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"USE_PRODUCT_QUANTITY" => "Y",
					"SHOW_NAME" => "Y",
					"SHOW_IMAGE" => "Y",
					"MESS_BTN_BUY" => "",
					"MESS_BTN_DETAIL" => "",
					"MESS_BTN_SUBSCRIBE" => "",
					"MESS_NOT_AVAILABLE" => "",
					"PAGE_ELEMENT_COUNT" => "4",
					"SHOW_FROM_SECTION" => "N",
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"DEPTH" => "2",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_GROUPS" => "Y",
					"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
					"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => "",
					"LABEL_PROP_".$arParams["IBLOCK_ID"] => "",
					"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
					"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"SECTION_ID" => "",
					"SECTION_CODE" => "",
					"SECTION_ELEMENT_ID" => "",
					"SECTION_ELEMENT_CODE" => "",
					"ID" => "",
					"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => "",
					"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
					"CART_PROPERTIES_".$arParams["IBLOCK_ID"] => "",
					"RCM_TYPE" => $arParams["BIG_DATA_RCM_TYPE"],
					"OFFER_TREE_PROPS_".$arRecomData["OFFER_IBLOCK_ID"] => $arParams["OFFERS_PROPERTY_CODE"],
					"ADDITIONAL_PICT_PROP_".$arRecomData["OFFER_IBLOCK_ID"] => ""
				),
				false,
				array("HIDE_ICONS" => "Y")
			);?>
		<?endif;
	endif;
endif;?>