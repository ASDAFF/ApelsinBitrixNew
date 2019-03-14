<?php

class APLS_StoreAmount
{
    protected static $cityArray = [
        "CNAKBEIAA9D7Q-CMAXB5HXA8E5N-CXJAGZA4H"=>"Рязань",
        "C9AVBNIWAVDNX-C4AGBRHWACE57-CGJ5G5BDE"=>"Луховицы",
        "C2A7B3I5ADIB8-C3ARB7DWDOC1H-EAFDFEGBG"=>"Коломна",
        "CGAKBYI8A6INU-CHAJBUDQDCHIZ-CRIED9AYF"=>"Бронницы",
        "C3AKB1IXA6IVO-CMAIBMDCDODE4-EEJTBLJRI"=>"Воскресенск",
        "CPALBSI2AII1W-C7AUBSD0DVD9U-BXJOA0F7G"=>"Дмитров",
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
        $count = '';
        while ($ar = $dbResult->Fetch()){
            $count += $ar["PRODUCT_AMOUNT"];
        }
        return $count;
    }
}