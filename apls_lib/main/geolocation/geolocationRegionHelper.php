<?php
use Bitrix\Main\Application;
session_start();

class geolocationRegionHelper
{
    public static function getGeolocationRegionId() {
        if(isset($_SESSION['GEOLOCATION_REGION_ID'])) {
            return $_SESSION['GEOLOCATION_REGION_ID'];
        } else {
            $request = Application::getInstance()->getContext()->getRequest();
            return $request->getCookie("GEOLOCATION_REGION_ID");
        }
    }
}