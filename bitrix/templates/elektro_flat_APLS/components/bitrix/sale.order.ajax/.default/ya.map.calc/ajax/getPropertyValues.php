<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogProperties.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";

if (!CModule::IncludeModule("sale")) return;

$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL"
    ),
    false,
    false,
    array("ID","PRODUCT_ID")
);
while ($arItems = $dbBasketItems->Fetch())
{
    $arBasketItems[$arItems["ID"]] = $arItems["PRODUCT_ID"];
}

$values = [];

foreach ($_POST as $item) {
    foreach ($arBasketItems as $key=>$element) {
        $property_id = APLS_CatalogProperties::convertPropertyXMLIDtoID($item);
        $db_props = CIBlockElement::GetProperty(APLS_CatalogHelper::getShopIblockId(),$element,array("sort" => "asc"),Array("ID"=>$property_id));
        while ($ar_props = $db_props->Fetch()) {
            if (isset($ar_props["VALUE"])) {
                if ($ar_props["PROPERTY_TYPE"] == 'N' || $ar_props["PROPERTY_TYPE"] == 'S') {
                    $values[$item][$element] = $ar_props["VALUE"];
                } elseif ($ar_props["PROPERTY_TYPE"] == 'L') {
                    $values[$item][$element] = $ar_props["VALUE_ENUM"];
                }
            } else {
                $values[$item][$element] = NULL;
            }
        }
    }
}

$responce = [
    'values'=> $values,
];
echo json_encode($responce);
