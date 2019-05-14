
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
    "XML_ID" => array(
//        "4e40e896-ed9f-11e5-80d6-00155d41010d",
//        "5f2d7e97-9fe2-11e7-80f6-00155d370324",
//        "69c3cf20-6fa9-11de-9531-000e0c431b58",
//        "4dc4c1e2-1f5a-11e9-8103-00155d862e1f",
//        "df44f8e8-d037-11e3-b9df-005056be1f7b",
//        "c6f416d3-d201-11e4-8a74-005056be1f7b",
//        "90d2a928-7571-11e1-ab00-005056be3bd8",
//        "04bc2406-d90f-11e4-9dc8-005056be1f7b",
    "4a0dad80-6cd4-11e0-894e-000e0c431b58",
    "3772b40a-0521-11e5-8100-00155d3a0124",
    "5b486021-a5a2-11e2-b66c-005056be3574",
    "22ee0092-c9fb-11e3-a4fc-005056be1f7b",
    "06411596-05e0-11e4-b2e2-005056be1f7b",
    "c80e4b3e-c9fb-11e3-a4fc-005056be1f7b",
    "9ff99ec4-cffc-11e8-8103-00155d862e1f",
    "0e27091a-cbc5-11e4-a1f9-005056be1f7b",
    )
);

$sortField = array(
    "RAND",
    "SORT",
    "NAME",
    "PRICE",
    "ID",
);
$sortOrder = array(
    "ASC",
    "DESC",
);



?>
<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "filtered",
    array(
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "16",
        "ELEMENT_SORT_FIELD" => $sortField[rand(0,4)],
        "ELEMENT_SORT_ORDER" => $sortOrder[rand(0,1)],
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


<?
/*
$APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", "",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"RCM_TYPE" => "any",
		"ID" => "",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "16",
		"SHOW_FROM_SECTION" => "N",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_NAME" => "N",
		"SHOW_IMAGE" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"PAGE_ELEMENT_COUNT" => "8",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_IMG_WIDTH" => "178",
		"DISPLAY_IMG_HEIGHT" => "178",
		"SHARPEN" => "30",
		"DISPLAY_COMPARE" => "Y",
		"SHOW_POPUP" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SHOW_OLD_PRICE" => "N",
        "PRICE_CODE" => array(
            0 => "Розничная цена",
            1 => "М. оптовая",
            2 => "Ср. оптовая",
            3 => "Оптовая",
            4 => "Кр. оптовая",
			5 => "ИМ",
        ),
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_PRODUCTS_16" => "Y",
		"PROPERTY_CODE_16" => array(),
		"PROPERTY_CODE_MOD" => array(
			0 => "GUARANTEE"
		),
		"CART_PROPERTIES_16" => array(),
		"ADDITIONAL_PICT_PROP_16" => "",
		"LABEL_PROP_16" => "",
		"PROPERTY_CODE_17" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3"
		),
		"CART_PROPERTIES_17" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3"
		),
		"ADDITIONAL_PICT_PROP_17" => "",
		"OFFER_TREE_PROPS_17" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3"
		)
	),
	false
);
*/
?>