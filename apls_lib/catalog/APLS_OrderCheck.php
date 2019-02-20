<?php

class APLS_OrderCheck
{
    static $stores = null;

    static function orderPayPermission ($orderId) {
        $obBasket = \Bitrix\Sale\Basket::getList(array('filter' => array('ORDER_ID' => $orderId)));
        $arBasketItems = array();
        while($bItem = $obBasket->Fetch()){
            $arBasketItems[] = $bItem;
        }
        return static::orderItemsPayPermission ($arBasketItems);
    }

    static function getStores() {
        if(static::$stores == null) {
            static::$stores = array();
            $select_fields = Array();
            $filter = Array("ACTIVE" => "Y");
            $resStore = CCatalogStore::GetList(array(),$filter,false,false,$select_fields);
            while($store = $resStore->Fetch()) {
                static::$stores[] = $store;
            }
        }
        return static::$stores;
    }

    static function orderItemsPayPermission ($arBasketItems) {
        $storesId = array();
        foreach (static::getStores() as $store) {
            $storesId[] = $store["ID"];
        }
        $result = true;
        foreach ($arBasketItems as $item) {
            $rsStore = CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' => $item["PRODUCT_ID"], 'STORE_ID'=>$storesId), false, false, array('AMOUNT'));
            $sum = array();
            $allSum = 0;
            while ($arStore = $rsStore->Fetch()) {
                $sum[] = $arStore['AMOUNT'];
                $allSum += $arStore['AMOUNT'];
            }
            if($item["CAN_BUY"] != "Y" || $item["QUANTITY"] > $allSum) {
                $result = false;
            }
        }
        return $result;
    }
}