<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

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
    array("PRODUCT_ID","NAME","PRICE","QUANTITY","DELAY")
);
while ($arItems = $dbBasketItems->Fetch())
{
    if ($arItems["DELAY"] == "N") {
        $arBasketItems[$arItems["PRODUCT_ID"]]["NAME"] = $arItems["NAME"];
        $arBasketItems[$arItems["PRODUCT_ID"]]["PRICE"] = $arItems["PRICE"];
        $arBasketItems[$arItems["PRODUCT_ID"]]["QUANTITY"] = $arItems["QUANTITY"];
        $arBasketItems[$arItems["PRODUCT_ID"]]["TOTAL_PRICE"] = $arItems["QUANTITY"]*$arItems["PRICE"];
    }
}

$responce = [
    'values'=> $arBasketItems,
];
echo json_encode($responce);