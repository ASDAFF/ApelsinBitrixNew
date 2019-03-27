<?if(is_array($arResult["brand"]["PREVIEW_PICTURE"])){
    $brandLogo = '<img src="'.$arResult["brand"]['PREVIEW_PICTURE']['SRC'].'" width="'.$arResult["brand"]['PREVIEW_PICTURE']['WIDTH'].'" height="'.$arResult["brand"]['PREVIEW_PICTURE']['HEIGHT'].'" alt="'.$arResult["brand"]['NAME'].'" />';
} else {
    $brandLogo = "";
}?>
<div class="brand-detail">
    <?
    $APPLICATION->SetTitle($arResult["brand"]["NAME"]);

    if($arResult["brand"]["PROPERTIES"]["TITLE"]["VALUE"] != "") {
        $APPLICATION->SetPageProperty("title",$arResult["brand"]["PROPERTIES"]["TITLE"]["VALUE"]);
    }
    if($arResult["brand"]["PROPERTIES"]["KEYWORDS"]["VALUE"] != "") {
        $APPLICATION->SetPageProperty("keywords",$arResult["brand"]["PROPERTIES"]["KEYWORDS"]["VALUE"]);
    }
    if($arResult["brand"]["PROPERTIES"]["DESCRIPTION"]["VALUE"] != "") {
        $APPLICATION->SetPageProperty("description",$arResult["brand"]["PROPERTIES"]["DESCRIPTION"]["VALUE"]);
    }
    ?>
    <?if($arResult["brand"]["DETAIL_PICTURE"] != ""):?>
        <div class="banner">
            <img src="<?=$arResult["brand"]["DETAIL_PICTURE"]?>">
        </div>
    <?endif;?>
    <?if($arResult["brand"]["PREVIEW_TEXT"] != ""):?>
    <div class="preview-text">
        <?=$arResult["brand"]["PREVIEW_TEXT"]?>
    </div>
    <?endif;?>
    <?
    $propertyCode = "BREND";
    $value = "0";
    $property_enums = CIBlockPropertyEnum::GetList(Array("ID"=>"ASC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>16, "CODE"=>$propertyCode));
    while ($enum_fields = $property_enums->GetNext()) {
        if($enum_fields["XML_ID"] == $arResult["brand"]["XML_ID"]) {
            $value = $enum_fields["ID"];
        }
    }

    global $arVendorProdPrFilter;
    $arVendorProdPrFilter = array(
        "PROPERTY_".$propertyCode => $value
    );
    $APPLICATION->IncludeComponent("bitrix:catalog.section", "table",
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
            "FILTER_NAME" => "arVendorProdPrFilter",
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
            "PAGE_ELEMENT_COUNT" => "12",
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
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_TEMPLATE" => "arrows",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
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
            "ADD_SECTIONS_CHAIN" => "Y",
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
    );
    ?>
    <?if($arResult["brand"]["DETAIL_TEXT"] != ""):?>
        <div class="detail-text">
            <?=$arResult["brand"]["DETAIL_TEXT"]?>
        </div>
    <?endif;?>
</div>
<div class="ButtonPanel">
    <div class="StylishButton Orange" onclick="history.back();">на предыдущую страницу</div>
</div>