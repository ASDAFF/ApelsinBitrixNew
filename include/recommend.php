
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
//    "28f46b6d-fbe9-11e5-80d7-00155d41010d",
//    "a04877c2-4d16-11e7-80e0-00155d410242",
//    "c7a757d6-30df-11e8-8153-00155d3703b2",
//    "a6e3614d-0de4-11e8-80fe-00155d37033c",
//    "5a00af87-d037-11e3-b9df-005056be1f7b",
//    "52efa3db-82fa-11e8-8100-00155d862e1f",
//    "e7f19134-ff1a-11e6-80dc-00155d410385",
//    "3b702df7-d903-11e4-9dc8-005056be1f7b",
    "c8b207d3-2700-11dd-942f-000e0c431b58",
    "95d3bf7d-a00d-11e8-8102-00155d862e1f",
    "5c86aecf-37eb-11e4-8256-005056be1f7b",
    "60503d0e-de1f-11e2-a4f3-005056be3574",
    "9b71a892-c60f-11e3-999c-005056be1f7b",
    "b0b2066d-5d01-11e0-894e-000e0c431b58",
    "fc957afc-9d43-11e3-98ac-005056be1f7b",
    "c8254550-b8d9-11e3-bec8-005056be1f7b",
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