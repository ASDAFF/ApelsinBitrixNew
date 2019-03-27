<?
$propertyCode = "NOVINKA";
$yesCodValue = "0";
$property_enums = CIBlockPropertyEnum::GetList(Array("ID"=>"ASC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>16, "CODE"=>$propertyCode));
while ($enum_fields = $property_enums->GetNext()) {
    if($enum_fields["VALUE"] == "Да") {
        $yesCodValue = $enum_fields["ID"];
    }
}

global $arNewPrFilter;
$arNewPrFilter = array(
    "PROPERTY_".$propertyCode => $yesCodValue
);?>
<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "filtered",
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "16",
		"ELEMENT_SORT_FIELD" => "RAND",
		"ELEMENT_SORT_ORDER" => "ASC",
		"ELEMENT_SORT_FIELD2" => "",
		"ELEMENT_SORT_ORDER2" => "",
		"PROPERTY_CODE" => array(
			0 => "NOVINKA",
			1 => "KHITPRODAZH",
			2 => "AKTSIYA",
			3 => "",
		),
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
		"FILTER_NAME" => "arNewPrFilter",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"MESSAGE_404" => "",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"FILE_404" => "",
		"DISPLAY_COMPARE" => "Y",
		"PAGE_ELEMENT_COUNT" => "8",
		"LINE_ELEMENT_COUNT" => "",
        "PRICE_CODE" => array(
            0 => "Розничная цена",
            1 => "М. оптовая",
            2 => "Ср. оптовая",
            3 => "Оптовая",
            4 => "Кр. оптовая",
            5 => "ИМ",
        ),
        "USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"USE_PRODUCT_QUANTITY" => "Y",
		"ADD_PROPERTIES_TO_BASKET" => "",
		"PARTIAL_PRODUCT_PROPERTIES" => "",
		"PRODUCT_PROPERTIES" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_BASE_LINK" => "",
		"PAGER_PARAMS_NAME" => "",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3"
		),
		"OFFERS_FIELD_CODE" => array(),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3"
		),
		"OFFERS_SORT_FIELD" => "SORT",
		"OFFERS_SORT_ORDER" => "ASC",
		"OFFERS_SORT_FIELD2" => "ID",
		"OFFERS_SORT_ORDER2" => "ASC",
		"OFFERS_LIMIT" => "",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"ADD_SECTIONS_CHAIN" => "N",		
		"COMPARE_PATH" => "",
		"BACKGROUND_IMAGE" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "",
		"DISPLAY_IMG_WIDTH"	 =>	"178",
		"DISPLAY_IMG_HEIGHT" =>	"178",
		"PROPERTY_CODE_MOD" => array(
			0 => "GUARANTEE"
		)
	),
	false
);?>