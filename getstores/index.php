<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
use \Bitrix\Catalog;
if(isset($_REQUEST["item"]) && $_REQUEST["item"] != "" && $_REQUEST["item"] != null) {
    $element = array();
    if(CModule::IncludeModule("catalog") && CModule::IncludeModule("iblock")) {
        $arSelect = Array("ID", "NAME");
        $arFilter = Array("XML_ID" => $_REQUEST["item"]);
        $res = CIBlockElement::GetList( Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
        while($ob = $res->GetNextElement())
        {
            $arFields = $ob->GetFields();
            $element["ID"] = $arFields["ID"];
            $element["XML_ID"] = $_REQUEST["item"];
            $element["NAME"] = $arFields["NAME"];
        }
        if($element["ID"] != "" && $element["ID"]!=NULL) {
            $CATALOG_IBLOCK = APLS_CatalogHelper::getShopIblockId();
            // получаем список складов
            $stores = array();
            $resStore = CCatalogStore::GetList( array(), Array("ACTIVE" => "Y"), false, false, Array() );
            while($store = $resStore->Fetch()) {
                $stores[] = $store;
                $element['STORES'][$store['ID']]['NAME'] = $store['TITLE'];
                $element['STORES'][$store['ID']]['XML_ID'] = $store['XML_ID'];
                $element['STORES'][$store['ID']]['ACTIVE'] = $store['ACTIVE'];
            }
            $storesId = array();
            foreach ($stores as $store) {
                $storesId[] = $store["ID"];
            }

            $rsStore = CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' => $element['ID'], 'STORE_ID'=>$storesId), false, false, array());
            $sum = array();
            $amount = 0;
            while ($arStore = $rsStore->Fetch()) {
                $element['STORES'][$arStore['STORE_ID']]['AMOUNT'] = $arStore['AMOUNT'];
            }
            echo json_encode($element);
        }
    }
}