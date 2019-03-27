<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

//$APPLICATION->SetTitle("Страница не найдена - Ошибка 404");
?>

    <div class="errorWrapper">
        <div class="errorWrapperContent">
            <div class="errorWrapperImg"><img src="/images/404.png"></div>
            <div class="errorWrapperHeader">Упс, такой страницы нет,</div>
            <div class="errorWrapperDisclaimer">но у нас есть:</div>
            <div class="errorWrapperBtnContainer">
                <ul class="errorWrapperBtnList">
                    <li><a class="errorWrapperBtn" href="/catalog/">В наличии более 70 000 товаров</a></li>
                    <li><a class="errorWrapperBtn" href="/promotions">Лучшие акции и уникальные предложения</a></li>
                </ul>
            </div>
            <div class="errorWrapperText">и еще много всего</div>
            <div class="errorWrapperColumnLeft">
                <div class="errorWrapperRow1">
                    <div class="errorWrapperBtnRow"><a href="/information/how_to_buy/">Как купить</a></div>
                    <div class="errorWrapperBtnRow"><a href="/payments/">Оплата</a></div>
                </div>
                <div class="errorWrapperBtnLong"><a href="/information/loyalty-programs">Программа лояльности</a></div>
            </div>
            <div class="errorWrapperColumnRight">
                <div class="errorWrapperRow1">
                    <div class="errorWrapperBtnRow"><a href="/delivery">Доставка</a></div>
                    <div class="errorWrapperBtnRow"><a href="/information/service">Услуги</a></div>
                </div>
                <div class="errorWrapperBtnLong"><a href="/information/service_centers">Сервисные центры</a></div>
            </div>
        </div>
    </div>
    <div class="errorWrapperPromotions">
        <div class="errorWrapperPromotionsHeader">Акционные товары</div>
        <?
        $propertyCode = "AKTSIYA";
        $yesCodValue = "0";
        $property_enums = CIBlockPropertyEnum::GetList(Array("ID"=>"ASC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>16, "CODE"=>$propertyCode));
        while ($enum_fields = $property_enums->GetNext()) {
            if($enum_fields["VALUE"] == "Да") {
                $yesCodValue = $enum_fields["ID"];
            }
        }

        global $arDiscPrFilter;
        $arDiscPrFilter = array(
            "PROPERTY_".$propertyCode => $yesCodValue
        );?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "filtered",
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
                "FILTER_NAME" => "arDiscPrFilter",
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
                "PAGE_ELEMENT_COUNT" => "4",
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
                "ADD_PROPERTIES_TO_BASKET" => "N",
                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                "PRODUCT_PROPERTIES" => array(
                ),
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
                    0 => "COLOR,PROP2,PROP3",
                ),
                "OFFERS_FIELD_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "OFFERS_PROPERTY_CODE" => array(
                    0 => "COLOR",
                    1 => "PROP2",
                    2 => "PROP3",
                    3 => "",
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
                "ADD_SECTIONS_CHAIN" => "N",
                "COMPARE_PATH" => "",
                "BACKGROUND_IMAGE" => "",
                "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                "DISPLAY_IMG_WIDTH" => "178",
                "DISPLAY_IMG_HEIGHT" => "178",
                "PROPERTY_CODE_MOD" => array(
                    0 => "GUARANTEE",
                    1 => "",
                ),
                "COMPONENT_TEMPLATE" => "filtered",
                "SECTION_USER_FIELDS" => array(
                    0 => "",
                    1 => "",
                ),
                "CUSTOM_FILTER" => "",
                "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                "SEF_MODE" => "N",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "BROWSER_TITLE" => "-",
                "META_KEYWORDS" => "-",
                "META_DESCRIPTION" => "-",
                "COMPATIBLE_MODE" => "Y"
            ),
            false
        );?>
    </div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>