<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_StoreAmount.php";
include_once $_SERVER["DOCUMENT_ROOT"] . '/apls_lib/catalog/APLS_CatalogItemDetailsPropertiesBlock.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/apls_lib/catalog/APLS_CatalogItemDetailsInfo.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/apls_lib/catalog/APLS_CatalogItemDetailsAction.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/apls_lib/catalog/APLS_CatalogItemDetailsServiceCenters.php';
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionHelper.php";

class APLS_CatalogItemInfo
{

    const promotionCss = "stock";
    const promotionText = "Акция";

    const OLD_PRICE_CODE = "ROZNICHNAYATSENA";
    const OLD_PRICE_BOOL_CODE = "PRIZNAKAKTSII";

    const YES_BOOL_VALUE = "Да";
    const RUB_STRING = "руб.";

    static $amountStatus = array(
        "IN_STOCK" => array(
            "class" => "avl",
            "text" => "В наличии",
            "icon" => "fa-check-circle",
        ),
        "NOT_FOR_SALE" => array(
            "class" => "not_avl",
            "text" => "Под заказ",
            "icon" => "fa-clock-o",
        ),
        "UNDER_THE_ORDER" => array(
            "class" => "not_avl",
            "text" => "Под заказ",
            "icon" => "fa-plus-circle",
        ),
    );

    public static function getLables($elementId, $elementXmlId, $properties, $textSpan = false)
    {
        $html = "";
        $promotions = PromotionHelper::getPromotionsId(
            $elementId,
            $elementXmlId,
            PromotionRegionModel::getUserRegion()->getId()
        );
        if(!empty($promotions)) {
            if($textSpan) {
                $html .= "<span class='" . self::promotionCss . "'><span class='text'>" . self::promotionText . "</span></span>";
            } else {
                $html .= "<span class='" . self::promotionCss . "'>" . self::promotionText . "</span>";
            }
        }
        try {
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("ApelsinCatalogElementLables");
            $rsData = $entity_data_class::getList(array(
                "select" => array('ID', 'UF_CODE', 'UF_MESSAGE', 'UF_CSS', 'UF_TRUE_VALUE'),
                "order" => array("ID" => "ASC")
            ));
            while ($arData = $rsData->Fetch()) {
                if (
                    isset($properties[$arData['UF_CODE']]["VALUE"]) &&
                    $properties[$arData['UF_CODE']]["VALUE"] == $arData['UF_TRUE_VALUE']
                ) {
                    if($textSpan) {
                        $html .= "<span class='" . $arData["UF_CSS"] . "'><span class='text'>" . $arData["UF_MESSAGE"] . "</span></span>";
                    } else {
                        $html .= "<span class='" . $arData["UF_CSS"] . "'>" . $arData["UF_MESSAGE"] . "</span>";
                    }
                }
            }
        } catch (Exception $e) {
            $html = 'Выброшено исключение: ' . $e->getMessage() . "<br>";
        }
        return $html;
    }

    public static function getItemMorInfo($elementId, $elementXmlId,$properties) {
        $html = "";
        $APLS_CIDPB = new APLS_CatalogItemDetailsPropertiesBlock($properties);
        $promotions = PromotionHelper::getPromotionsId(
            $elementId,
            $elementXmlId,
            PromotionRegionModel::getUserRegion()->getId()
        );
        $APLS_Action = new APLS_CatalogItemDetailsAction($promotions);
        $APLS_DetailsInfo = new APLS_CatalogItemDetailsInfo($properties);
        $html .= $APLS_CIDPB->getHtml();
        $html .= $APLS_DetailsInfo->getHtml();
        $html .= $APLS_Action->getHtml();
        return $html;
    }

    public static function getLawyerGoodsNotice() {
        $html = '<div class="LAWYER_GOODS_NOTICE">';
        $html .= APLS_GetGlobalParam::getParams("LAWYER_GOODS_NOTICE ");
        $html .= '</div>';
        return $html;
    }

    public static function getRetailPrice($prices) {
        $html = "";
        global $USER;
        if ($USER->IsAuthorized()) {
            foreach ($prices as $price) {
                if($price["PRICE_ID"] === "1" && isset($price["PRINT_VALUE"]) && $price["PRINT_VALUE"] !== "") {
                    $html .= "<div class='APLS_RetailPrice'>";
                    $html .= "Цена в магазине: <span class='price'>".$price["PRINT_VALUE"]."</span>";
                    $html .= "</div>";
                }
            }
        }
        return $html;
    }

    public static function getRegisterPrice($id,$prices) {
        $html = "";
        global $USER;
        if (!$USER->IsAuthorized()) {
            $defaultPrice = 0;
            foreach ($prices as $price) {
                if($price["PRICE_ID"] === "1" && isset($price["PRINT_VALUE"]) && $price["PRINT_VALUE"] !== "") {
                    $defaultPrice = $price['VALUE_VAT'];
                }
            }
            $db_res = CPrice::GetList(
                array(),
                array(
                    "PRODUCT_ID" => $id,
                    "CATALOG_GROUP_ID" => DEFAULT_REGISTER_USER_PRICE_ID
                )
            );
            if ($ar_res = $db_res->Fetch())
            {
                if($defaultPrice != $ar_res["PRICE"]) {
                    $html .= "<div class='APLS_RegisterPrice'>";
                    //$html .= "Цена после регистрации<br><span class='price'>".CurrencyFormat($ar_res["PRICE"], $ar_res["CURRENCY"])."</span>";
                    $html .= "Скидка на этот товар при регистрации";
                    $html .= "</div>";
                }
            }
        }
        return $html;
    }

    public static function getItemElementOldPrice($price, $properties) {
        $html = "";
        $newPriceValue = substr($price,0, strpos($price," р"));
        $oldPriceValue = number_format (trim($properties[static::OLD_PRICE_CODE]["VALUE"]) , 2 , "." , " " );
        $fractionPoint = strpos($oldPriceValue,".");
        $fraction = substr($oldPriceValue, $fractionPoint+1);
        if($fraction === "00") {
            $oldPriceValue = substr($oldPriceValue,0, $fractionPoint);
        }
        if(
            $properties[static::OLD_PRICE_BOOL_CODE]["VALUE"] === static::YES_BOOL_VALUE &&
            $oldPriceValue != "" &&
            $newPriceValue < $oldPriceValue
        ) {
            $html .= "<span class='catalog-detail-item-price-old catalog-item-price-old'>";
            $html .= $oldPriceValue." ".static::RUB_STRING;
            $html .= "</span>";
        }
        return $html;
    }

    public static function getServiceCenters($servisString) {
        $APLS_ServiceCenters = new APLS_CatalogItemDetailsServiceCenters($servisString);
        return $APLS_ServiceCenters->getHtml();
    }

    public static function getPOSCreditBtn($price) {
        $json = "var productName = $('#pagetitle').text();";
        $json .= "var options = '{inn: \"623006579773\", kpp: \"\", ttName: \"390006, г.Рязань, ул. Есенина, д.13\",manualOrderInput: false, order: [{model: \"'+productName+'\", price: ".$price."},{model: \"'+productName+'\", price: ".$price."}]}';";
        $html = '';
        $html .= '<div id="pos-credit-container" class="pos-credit-container">';
        $html .= '<div class="pochta-bank-img-container">';
        $html .= '<i class="fa fa-percent"></i>';
        $html .= '</div>';
        $html .= '<div class="pos-credit-title">Оформить в рассрочку</div>';
        $html .= '';
        $html .= '</div>';
        $html .= '<script src="https://my.pochtabank.ru/sdk/v1/pos-credit.js"></script>';
        $html .= '<script>';
        $html .= '$(document).ready(function() {';
        $html .= '$("#pos-credit-container").click(function() {';
        $html .= $json;
        $html .= "window.PBSDK.posCredit.mount('#pos-credit-container', options);";
        $html .= "window.PBSDK.posCredit.on('done', function(id){console.log('Id заявки: ' + id)});";
//        $html .= "window.PBSDK.posCredit.unmount('#pos-credit-container');";
        $html .= '});';
        $html .= '});';
        $html .= '</script>';
        return $html;
    }

    public static function getAmountInfo($elementId) {
        $amount = APLS_StoreAmount::getStoresAmountByGeolocation ($elementId);
        if($amount > 0) {
            $key = "IN_STOCK";
        } else {
            $key = "NOT_FOR_SALE";
        }
        return '<div class="avl"><i class="fa '.static::$amountStatus[$key]["icon"].'"></i><span> '.static::$amountStatus[$key]["text"].'</span></div>';
    }
}