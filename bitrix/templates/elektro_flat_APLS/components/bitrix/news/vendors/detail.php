<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

use Bitrix\Main\Loader,
	Bitrix\Iblock,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\ModuleManager;
	
if(!Loader::includeModule("iblock"))
	return;

Loc::loadMessages(__FILE__);

global $arSetting;

//CURRENT_VENDOR//
$arFilter = array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ACTIVE" => "Y"
);
if(0 < intval($arResult["VARIABLES"]["ELEMENT_ID"])) {
	$arFilter["ID"] = $arResult["VARIABLES"]["ELEMENT_ID"];
} elseif("" != $arResult["VARIABLES"]["ELEMENT_CODE"]) {
	$arFilter["CODE"] = $arResult["VARIABLES"]["ELEMENT_CODE"];
}

$arSelect = array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT");

$cache_id = md5(serialize($arFilter));
$cache_dir = "/catalog/vendor";
$obCache = new CPHPCache();
if($obCache->InitCache($arParams["CACHE_TIME"], $cache_id, $cache_dir)) {
	$arCurVendor = $obCache->GetVars();	
} elseif($obCache->StartDataCache()) {
	$rsElement = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	global $CACHE_MANAGER;
	$CACHE_MANAGER->StartTagCache($cache_dir);
	$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
	if($arElement = $rsElement->GetNext()) {	
		$arCurVendor["ID"] = $arElement["ID"];
		$arCurVendor["NAME"] = $arElement["NAME"];
		if($arElement["PREVIEW_PICTURE"] > 0)
			$arCurVendor["PREVIEW_PICTURE"] = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);
		$arCurVendor["PREVIEW_TEXT"] = $arElement["PREVIEW_TEXT"];
		$ipropValues = new Iblock\InheritedProperty\ElementValues($arElement["IBLOCK_ID"], $arElement["ID"]);
		$arCurVendor["IPROPERTY_VALUES"] = $ipropValues->getValues();
		$CACHE_MANAGER->EndTagCache();
		$obCache->EndDataCache($arCurVendor);
	} else {
		$CACHE_MANAGER->abortTagCache();
		Iblock\Component\Tools::process404(
			trim($arParams["MESSAGE_404"]) ? : GetMessage("T_NEWS_DETAIL_NF")
			,true
			,$arParams["SET_STATUS_404"] === "Y"
			,$arParams["SHOW_404"] === "Y"
			,$arParams["FILE_404"]
		);
	}
}

if($arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS"):
	//SECTIONS//?>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "vendors",
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE_CATALOG"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID_CATALOG"],
			"SECTION_ID" => "",
			"SECTION_CODE" => "",
			"COUNT_ELEMENTS" => "N",
			"TOP_DEPTH" => "2",
			"SECTION_FIELDS" => array(),
			"SECTION_USER_FIELDS" => array(),
			"VIEW_MODE" => "",
			"SHOW_PARENT_NAME" => "",
			"SECTION_URL" => "",
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"ADD_SECTIONS_CHAIN" => "N",
			"DISPLAY_IMG_WIDTH"	 =>	"50",
			"DISPLAY_IMG_HEIGHT" =>	"50",
			"VENDOR_ID" => $arCurVendor["ID"],
			"VENDOR_NAME" => $arCurVendor["NAME"],
			"SEF_MODE" => $arParams["SEF_MODE"]
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?else:
	if($arSetting["VENDORS_VIEW"]["VALUE"] != "SECTIONS_PRODUCTS"):
		//COUNT//
		$arFilter = array(
			"IBLOCK_ID" => $arParams["IBLOCK_ID_CATALOG"],		
			"ACTIVE" => "Y",
			"PROPERTY_MANUFACTURER" => $arCurVendor["ID"]
		);
		$cache_id = md5(serialize($arFilter));
		$cache_dir = "/catalog/vendor/amount";
		$obCache = new CPHPCache();
		if($obCache->InitCache($arParams["CACHE_TIME"], $cache_id, $cache_dir)) {
			$count = $obCache->GetVars();
		} elseif($obCache->StartDataCache()) {		
			global $CACHE_MANAGER;
			$CACHE_MANAGER->StartTagCache($cache_dir);
			$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID_CATALOG"]);			
			$count = CIBlockElement::GetList(array(), $arFilter, array(), false);
			$CACHE_MANAGER->EndTagCache();
			$obCache->EndDataCache($count);
		}?>

		<div class="count_items">
			<label><?=Loc::getMessage("COUNT_ITEMS")?></label>
			<span><?=$count?></span>
		</div>

		<?//SORT//
		$arAvailableSort = array(
			"default" => array($arParams["ELEMENT_SORT_FIELD"], $arParams["ELEMENT_SORT_ORDER"]),
			"price" => array("PROPERTY_MINIMUM_PRICE", "asc"),
			"rating" => array("PROPERTY_rating", "desc"),
		);

		$sort = $APPLICATION->get_cookie("sort") ? $APPLICATION->get_cookie("sort") : $arParams["ELEMENT_SORT_FIELD"];

		if($_REQUEST["sort"]) {
			$sort = $arParams["ELEMENT_SORT_FIELD"];
			$APPLICATION->set_cookie("sort", $sort, false, "/", SITE_SERVER_NAME); 
		} 
		if($_REQUEST["sort"] == "price") {
			$sort = "PROPERTY_MINIMUM_PRICE";
			$APPLICATION->set_cookie("sort", $sort, false, "/", SITE_SERVER_NAME);
		}
		if($_REQUEST["sort"] == "rating") {
			$sort = "PROPERTY_rating";
			$APPLICATION->set_cookie("sort", $sort, false, "/", SITE_SERVER_NAME);
		}

		$sort_order = $APPLICATION->get_cookie("order") ? $APPLICATION->get_cookie("order") : $arParams["ELEMENT_SORT_ORDER"];

		if($_REQUEST["order"]) {
			$sort_order = "asc";	
			$APPLICATION->set_cookie("order", $sort_order, false, "/", SITE_SERVER_NAME);
		}
		if($_REQUEST["order"] == "desc") {
			$sort_order = "desc";
			$APPLICATION->set_cookie("order", $sort_order, false, "/", SITE_SERVER_NAME);
		}?>

		<div class="catalog-item-sorting">
			<label><span class="full"><?=Loc::getMessage("SECT_SORT_LABEL_FULL")?></span><span class="short"><?=Loc::getMessage("SECT_SORT_LABEL_SHORT")?></span>:</label>
			<?foreach($arAvailableSort as $key => $val):
				$className = $sort == $val[0] ? "selected" : "";
				if($className) 
					$className .= $sort_order == "asc" ? " asc" : " desc";
				$newSort = $sort == $val[0] ? $sort_order == "desc" ? "asc" : "desc" : $arAvailableSort[$key][1];?>

				<a href="<?=$APPLICATION->GetCurPageParam("sort=".$key."&amp;order=".$newSort, array("sort", "order"))?>" class="<?=$className?>" rel="nofollow"><?=Loc::getMessage("SECT_SORT_".$key)?></a>
			<?endforeach;?>
		</div>

		<?//LIMIT//
		$arAvailableLimit = array("12", "48", "900");

		$limit = $APPLICATION->get_cookie("limit") ? $APPLICATION->get_cookie("limit") : "12";

		if($_REQUEST["limit"]) {
			$limit = "12";	
			$APPLICATION->set_cookie("limit", $limit, false, "/", SITE_SERVER_NAME); 
		}
		if($_REQUEST["limit"] == "48") {
			$limit = "48";
			$APPLICATION->set_cookie("limit", $limit, false, "/", SITE_SERVER_NAME); 
		}
		if($_REQUEST["limit"] == "900") {
			$limit = "900";
			$APPLICATION->set_cookie("limit", $limit, false, "/", SITE_SERVER_NAME); 
		}?>

		<div class="catalog-item-limit">
			<label><span class="full"><?=Loc::getMessage("SECT_COUNT_LABEL_FULL")?></span><span class="short"><?=Loc::getMessage("SECT_COUNT_LABEL_SHORT")?></span>:</label>
			<?foreach($arAvailableLimit as $val):?>
				<a href="<?=$APPLICATION->GetCurPageParam("limit=".$val, array("limit"))?>" <?if($limit==$val) echo " class='selected'";?> rel="nofollow"><?if($val=="900"): echo Loc::getMessage("SECT_COUNT_ALL"); else: echo $val; endif;?></a>
			<?endforeach;?>
		</div>

		<?//VIEW//
		$arAvailableView = array("table", "list", "price");

		$view = $APPLICATION->get_cookie("view") ? $APPLICATION->get_cookie("view") : (isset($arCurSection["VIEW"]) && !empty($arCurSection["VIEW"]) ? $arCurSection["VIEW"] : "table");

		if($_REQUEST["view"]) {
			$view = "table";	
			$APPLICATION->set_cookie("view", $view, false, "/", SITE_SERVER_NAME); 
		}
		if($_REQUEST["view"] == "list") {
			$view = "list";
			$APPLICATION->set_cookie("view", $view, false, "/", SITE_SERVER_NAME); 
		}
		if($_REQUEST["view"] == "price") {
			$view = "price";
			$APPLICATION->set_cookie("view", $view, false, "/", SITE_SERVER_NAME);
		}?>

		<div class="catalog-item-view">
			<?foreach($arAvailableView as $val):?>
				<a href="<?=$APPLICATION->GetCurPageParam("view=".$val, array("view"))?>" class="<?=$val?><?if($view==$val) echo ' selected';?>" title="<?=Loc::getMessage('SECT_VIEW_'.$val)?>" rel="nofollow">
					<?if($val == "table"):?>
						<i class="fa fa-th-large"></i>
					<?elseif($val == "list"):?>
						<i class="fa fa-list"></i>
					<?elseif($val == "price"):?>
						<i class="fa fa-align-justify"></i>
					<?endif?>
				</a>
			<?endforeach;?>
		</div>
		<div class="clr"></div>
	<?endif;
	
	//ELEMENTS//
	global $arVendorFilter;
	$arVendorFilter = array(	
		"PROPERTY_MANUFACTURER" => $arCurVendor["ID"]
	);?>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.section", $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? "sections" : $view,
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE_CATALOG"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID_CATALOG"],
			"ELEMENT_SORT_FIELD" => $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? $arParams["ELEMENT_SORT_FIELD"] : $sort,
			"ELEMENT_SORT_ORDER" => $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? $arParams["ELEMENT_SORT_ORDER"] : $sort_order,
			"ELEMENT_SORT_FIELD2" => $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? $arParams["ELEMENT_SORT_FIELD2"] : "",
			"ELEMENT_SORT_ORDER2" => $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? $arParams["ELEMENT_SORT_ORDER2"] : "",
			"PROPERTY_CODE" => $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? "" : $arParams["PROPERTY_CODE"],
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
			"FILTER_NAME" => "arVendorFilter",
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SET_TITLE" => "N",
			"MESSAGE_404" => "",
			"SET_STATUS_404" => "N",
			"SHOW_404" => "N",
			"FILE_404" => "",
			"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
			"PAGE_ELEMENT_COUNT" => $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? "900" : $limit,
			"LINE_ELEMENT_COUNT" => "",
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => "Y",
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"USE_PRODUCT_QUANTITY" => "Y",
			"ADD_PROPERTIES_TO_BASKET" => "",
			"PARTIAL_PRODUCT_PROPERTIES" => "",
			"PRODUCT_PROPERTIES" => "",
			"DISPLAY_TOP_PAGER" => $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? "N" : $arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? "N" : $arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arSetting["VENDORS_VIEW"]["VALUE"] == "SECTIONS_PRODUCTS" ? "" : $arCurVendor["NAME"],
			"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
			"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
			"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
			"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
			"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
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

//DESCRIPTION//
if(!empty($arCurVendor["PREVIEW_TEXT"])):
	if(!$_REQUEST["PAGEN_1"] || empty($_REQUEST["PAGEN_1"]) || $_REQUEST["PAGEN_1"] <= 1):?>
		<div class="catalog_description">
			<?=$arCurVendor["PREVIEW_TEXT"];?>
		</div>
	<?endif;
endif;

//BIGDATA_ITEMS//
$arRecomData = array();
$recomCacheID = array("IBLOCK_ID" => $arParams["IBLOCK_ID_CATALOG"]);
$obCache = new CPHPCache();
if($obCache->InitCache($arParams["CACHE_TIME"], serialize($recomCacheID), "/catalog/recommended")) {
	$arRecomData = $obCache->GetVars();	
} elseif($obCache->StartDataCache()) {
	if(Loader::includeModule("catalog")) {
		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams["IBLOCK_ID_CATALOG"]);
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
				"SHOW_POPUP" => "Y",
				"LINE_ELEMENT_COUNT" => "4",
				"TEMPLATE_THEME" => "",
				"DETAIL_URL" => "",
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
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE_CATALOG"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID_CATALOG"],
				"DEPTH" => "2",
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SHOW_PRODUCTS_".$arParams["IBLOCK_ID_CATALOG"] => "Y",
				"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID_CATALOG"] => "",
				"LABEL_PROP_".$arParams["IBLOCK_ID_CATALOG"] => "",
				"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				"SECTION_ID" => "",
				"SECTION_CODE" => "",
				"SECTION_ELEMENT_ID" => "",
				"SECTION_ELEMENT_CODE" => "",
				"ID" => "",
				"PROPERTY_CODE_".$arParams["IBLOCK_ID_CATALOG"] => "",
				"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
				"CART_PROPERTIES_".$arParams["IBLOCK_ID_CATALOG"] => "",
				"RCM_TYPE" => $arParams["BIG_DATA_RCM_TYPE"],
				"OFFER_TREE_PROPS_".$arRecomData["OFFER_IBLOCK_ID"] => $arParams["OFFERS_PROPERTY_CODE"],
				"ADDITIONAL_PICT_PROP_".$arRecomData["OFFER_IBLOCK_ID"] => ""
			),
			false,
			array("HIDE_ICONS" => "Y")
		);?>
	<?endif;
endif;

//PAGE_TITLE//
$APPLICATION->SetTitle(!empty($arCurVendor["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) ? $arCurVendor["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arCurVendor["NAME"]);
if(!$_REQUEST["PAGEN_1"] || empty($_REQUEST["PAGEN_1"]) || $_REQUEST["PAGEN_1"] <= 1):
	$APPLICATION->SetPageProperty("title", !empty($arCurVendor["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]) ? $arCurVendor["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"] : $arCurVendor["NAME"]);
	$APPLICATION->SetPageProperty("keywords", !empty($arCurVendor["IPROPERTY_VALUES"]["ELEMENT_META_KEYWORDS"]) ? $arCurVendor["IPROPERTY_VALUES"]["ELEMENT_META_KEYWORDS"] : "");
	$APPLICATION->SetPageProperty("description", !empty($arCurVendor["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]) ? $arCurVendor["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"] : "");
else:
	$APPLICATION->SetPageProperty("title", (!empty($arCurVendor["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]) ? $arCurVendor["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"] : $arCurVendor["NAME"])." | ".Loc::getMessage("SECT_TITLE")." ".$_REQUEST["PAGEN_1"]);
	$APPLICATION->SetPageProperty("keywords", "");
	$APPLICATION->SetPageProperty("description", "");
endif;

//META_PROPERTY//
$APPLICATION->AddHeadString("<meta property='og:title' content='".(!empty($arCurVendor["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) ? $arCurVendor["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arCurVendor["NAME"])."' />", true);
if(!empty($arCurVendor["PREVIEW_TEXT"])):
	$APPLICATION->AddHeadString("<meta property='og:description' content='".strip_tags($arCurVendor["PREVIEW_TEXT"])."' />", true);
endif;
$APPLICATION->AddHeadString("<meta property='og:url' content='http://".SITE_SERVER_NAME.$APPLICATION->GetCurPage()."' />", true);
if(is_array($arCurVendor["PREVIEW_PICTURE"])):
	$APPLICATION->AddHeadString("<meta property='og:image' content='http://".SITE_SERVER_NAME.$arCurVendor["PREVIEW_PICTURE"]["SRC"]."' />", true);
	$APPLICATION->AddHeadString("<meta property='og:image:width' content='".$arCurVendor["PREVIEW_PICTURE"]["WIDTH"]."' />", true);
	$APPLICATION->AddHeadString("<meta property='og:image:height' content='".$arCurVendor["PREVIEW_PICTURE"]["HEIGHT"]."' />", true);
	$APPLICATION->AddHeadString("<link rel='image_src' href='http://".SITE_SERVER_NAME.$arCurVendor["PREVIEW_PICTURE"]["SRC"]."' />", true);
endif;

//CANONICAL//
if(!empty($_REQUEST["sort"]) || !empty($_REQUEST["order"]) || !empty($_REQUEST["limit"]) || !empty($_REQUEST["view"]) || !empty($_REQUEST["action"]) || !empty($_REQUEST["PAGEN_1"])):
	$APPLICATION->AddHeadString("<link rel='canonical' href='".$APPLICATION->GetCurPage()."'>");	
endif;

//BREADCRUMBS//
if($arParams["ADD_ELEMENT_CHAIN"] != "N"):
	$APPLICATION->AddChainItem(!empty($arCurVendor["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) ? $arCurVendor["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arCurVendor["NAME"]);
endif;?>