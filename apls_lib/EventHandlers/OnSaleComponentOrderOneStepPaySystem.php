<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/GeolocationRegionHelper.php";

use Bitrix\Main;
Main\EventManager::getInstance()->addEventHandler("sale", "OnSaleComponentOrderOneStepPaySystem", ["APLS_UpgradePaySystem", "init"]);

class APLS_UpgradePaySystem {
    public static function init(&$arFields) {
        $totalPrice = 0;
        foreach ($arFields["BASKET_ITEMS"] as $key=>$product) {
            if ($product["QUANTITY"] != 1.0) {
                $totalPrice = $totalPrice + intval($product["PRICE"])*$product["QUANTITY"];
            } else {
                $totalPrice = $totalPrice + intval($product["PRICE"]);
            }
        }
        if (GeolocationRegionHelper::getGeolocationRegionAlias() == 'ryazan') {
            if ($_SESSION["APLS_ORDER"]["DELIVERY"]["IT_CITY"] !== true || $totalPrice > 15000) {
                if ($arFields["DELIVERY"]["20"]["CHECKED"] == "Y") {
                    foreach ($arFields["PAY_SYSTEM"] as $key=>$item) {
                        if ($item["PAY_SYSTEM_ID"] == '8') {
                            unset($arFields["PAY_SYSTEM"][$key]);
                        }
                        unset($item["CHECKED"]);
                    }
                    sort($arFields["PAY_SYSTEM"]);
                    $arFields["PAY_SYSTEM"]["0"]["CHECKED"] = "Y";
                }
            }
        }
        else {
            if ($arFields["DELIVERY"]["20"]["CHECKED"] == "Y") {
                foreach ($arFields["PAY_SYSTEM"] as $key=>$item) {
                    if ($item["PAY_SYSTEM_ID"] == '8') {
                        unset($arFields["PAY_SYSTEM"][$key]);
                    }
                    unset($item["CHECKED"]);
                }
                sort($arFields["PAY_SYSTEM"]);
                $arFields["PAY_SYSTEM"]["0"]["CHECKED"] = "Y";
            }
        }
    }
}