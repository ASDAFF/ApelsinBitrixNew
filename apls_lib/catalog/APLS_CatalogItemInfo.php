<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . '/apls_lib/catalog/APLS_CatalogItemDetailsPropertiesBlock.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/apls_lib/catalog/APLS_CatalogItemDetailsInfo.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/apls_lib/catalog/APLS_CatalogItemDetailsAction.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/apls_lib/catalog/APLS_CatalogItemDetailsServiceCenters.php';
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionHelper.php";

class APLS_CatalogItemInfo
{

    const promotionCss = "stock";
    const promotionText = "Акция";

    public static function getLables($elementId, $properties, $textSpan = false)
    {
        $html = "";
        $promotions = PromotionHelper::getPromotionsIdByElementId(
            $elementId,
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

    public static function getItemMorInfo($properties) {
        $html = "";
        $APLS_CIDPB = new APLS_CatalogItemDetailsPropertiesBlock($properties);
        $APLS_Action = new APLS_CatalogItemDetailsAction($properties);
        $APLS_DetailsInfo = new APLS_CatalogItemDetailsInfo($properties);
        $html .= $APLS_CIDPB->getHtml();
        $html .= $APLS_Action->getHtml();
        $html .= $APLS_DetailsInfo->getHtml();
        $html .= '<div class="LAWYER_GOODS_NOTICE">';
        $html .= APLS_GetGlobalParam::getParams("LAWYER_GOODS_NOTICE ");
        $html .= '</div>';
        return $html;
    }

    public static function getRetailPrice($prices) {
        $html = "";
        foreach ($prices as $price) {
            if($price["PRICE_ID"] === "1" && isset($price["PRINT_VALUE"]) && $price["PRINT_VALUE"] !== "") {
                $html .= "<div class='APLS_RetailPrice'>";
                $html .= "Цена в магазине: <span class='price'>".$price["PRINT_VALUE"]."</span>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    public static function getServiceCenters($servisString) {
        $APLS_ServiceCenters = new APLS_CatalogItemDetailsServiceCenters($servisString);
        return $APLS_ServiceCenters->getHtml();
    }
}