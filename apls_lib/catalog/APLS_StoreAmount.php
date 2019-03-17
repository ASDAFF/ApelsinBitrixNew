<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/GeolocationRegionHelper.php";

class APLS_StoreAmount
{
    protected static $cityArray = [
        "ryazan"=>"Рязань",
        "lukhovitsy"=>"Луховицы",
        "kolomna"=>"Коломна",
        "voskresensk"=>"Воскресенск",
        "bronnitsy"=>"Бронницы",
        "dmitrov"=>"Дмитров",
        "7"=>"Клин",
    ];

    /** Функия возвращает количество товара на складах в определенном городе
     * @param $productId - Идентификатор товара в базе битрикс
     * @param $city - код города по геолокации
     * @return int - возвращает количство товара на складах в городе
     */
    public static function getStoresAmountByCity ($productId,$city):int {
        $dbResult = CCatalogStore::GetList(
            array(),
            array('PRODUCT_ID'=>$productId,'UF_STORE_CITY'=>self::$cityArray[$city]),
            false,
            false,
            array("PRODUCT_AMOUNT")
        );
        $count = 0;
        while ($ar = $dbResult->Fetch()){
            $count += $ar["PRODUCT_AMOUNT"];
        }
        return $count;
    }
    public static function getStoresAmountByGeolocation ($productId):int {
        $city = GeolocationRegionHelper::getGeolocationRegionAlias();
        return static::getStoresAmountByCity ($productId,$city);
    }


}