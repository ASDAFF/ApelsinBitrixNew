<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
Loader::includeModule('catalog');
$arPrice = CCatalogIBlockParameters::getPriceTypesList();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_ID" => array(
            "NAME" => GetMessage("RECOMMEND_IBLOCK_ID"),
            "TYPE" => "STRING",
        ),
        "HLBLOCK_NAME" => array(
            "NAME" => GetMessage("RECOMMEND_HLBLOCK_NAME"),
            "TYPE" => "STRING",
        ),
        "PRICE_CODE" => array(
            "NAME" => GetMessage("RECOMMEND_PRICE_CODE"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arPrice,
        ),

    ),
);