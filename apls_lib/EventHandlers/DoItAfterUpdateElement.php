<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";

use Bitrix\Catalog\MeasureRatioTable;

AddEventHandler("catalog", "OnProductUpdate", ["APLS_ElementUpdater", "updateValues"]);

class APLS_ElementUpdater
{
    const arCODE = array(
        "DOSTAVKADLINA" => "LENGTH",
        "DOSTAVKASHIRINA" => "WIDTH",
        "DOSTAVKAVYSOTA" => "HEIGHT"
    );

    const arKoeff = array(
        "CODE" => "KOEFFITSIENT"
    );

    public static function updateValues($arFields) {
        $arDimensions = self::getCatalogValue($arFields);
        $arResult = self::getDimensionsValue(APLS_GetGlobalParam::getParams("HIGHLOAD_CATALOG_ID"), $arFields);
        $update = array();
        foreach (self::arCODE as $key => $dimension) {
            if(isset($arResult[$dimension]) && $arDimensions[$dimension] !== $arResult[$dimension] && $arResult[$dimension] !== NULL && $arResult[$dimension] !== "") {
                $update[self::arCODE["$key"]] = $arResult[$dimension];
            }
        }
        if(!empty($update)) {
            $catalogProduct = new CCatalogProduct ();
            $catalogProduct->Update($arFields, $update);
        }
        $ratio = self::getCoefficientValue(APLS_GetGlobalParam::getParams("HIGHLOAD_CATALOG_ID"), $arFields);
        $db_ratio = CCatalogMeasureRatio::getList(array(), array("PRODUCT_ID" => $arFields), false, false, array());
        $ar_ratio = $db_ratio->Fetch();
        $updateRatio = array();
        if (isset($ar_ratio["RATIO"]) && $ar_ratio["RATIO"] !== $ratio["RATIO"] && $ratio["RATIO"] !== NULL && $ratio["RATIO"] !== "") {
            $updateRatio["RATIO"] = $ratio["RATIO"];
            MeasureRatioTable::update($ar_ratio["ID"], $updateRatio);
        }
    }

    protected static function getCatalogValue($elementID) {
        $getCatalogValue = new CCatalogProduct;
        $arCatalogValue = $getCatalogValue->GetList(array("ID" => $elementID), array(), self::arCODE);
        $res_arr = $arCatalogValue->Fetch();
        $dimensionsArray = array();
        foreach (self::arCODE as $key => $dimension) {
            $dimensionsArray[self::arCODE[$key]] = $res_arr[$dimension];
        }
        return $dimensionsArray;
    }

    protected static function getDimensionsValue($iblock_ID, $elementID) {
        $dimensionsArray = array();
        foreach (self::arCODE as $key => $code) {
            $dimensions = CIBlockElement::GetProperty($iblock_ID, $elementID, array("sort" => "asc"), array("CODE" => $key));
            $res_arr = $dimensions->Fetch();
            if ($res_arr["VALUE"] !== "") {
                $dimensionsArray[self::arCODE[$key]] = $res_arr["VALUE"];
            }
        }
        return $dimensionsArray;
    }

    protected static function getCoefficientValue($iblock_ID, $elementID) {
        $coefficientArray = CIBlockElement::GetProperty($iblock_ID, $elementID, array("sort" => "asc"), self::arKoeff);
        $coefficientValue = array();
        while ($res_arr = $coefficientArray->Fetch()) {
            if ($res_arr["VALUE"] !== "") {
                $coefficientValue["PRODUCT_ID"] = $elementID;
                $coefficientValue["RATIO"] = $res_arr["VALUE"];
            }
        }
        return $coefficientValue;
    }
}
