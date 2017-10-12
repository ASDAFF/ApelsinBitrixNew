<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);


$arComponentParameters = array(
    "PARAMETERS" => array(
        "HIGHLOAD_ID" => Array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("HIGHLOAD_ID"),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "3",
            "REFRESH" => "Y",
        ),
        "CACHE_TIME"  => array(
            "DEFAULT" => 36000000
        )
    )
);

;?>