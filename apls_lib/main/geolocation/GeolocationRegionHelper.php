<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

use Bitrix\Main\Application;
session_start();

class GeolocationRegionHelper
{
    public static function getGeolocationRegionId() {
        if(isset($_SESSION['GEOLOCATION_REGION_ID'])) {
            return $_SESSION['GEOLOCATION_REGION_ID'];
        } else {
            $request = Application::getInstance()->getContext()->getRequest();
            return $request->getCookie("GEOLOCATION_REGION_ID");
        }
    }

    public static function getGeolocationRegionAlias() {
        $region = new PromotionRegionModel(self::getGeolocationRegionId());
        return $region->getFieldValue("alias");
    }
}