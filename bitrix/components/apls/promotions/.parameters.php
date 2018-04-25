<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);


$arComponentParameters = array(
    "PARAMETERS" => array(
        "SEF_FOLDER" => array(
            "NAME" => Loc::getMessage("PARAMETER_SEF_FOLDER"),
            "TYPE" => "STRING"
        ),
        "CACHE_TIME"  => array(
            "DEFAULT" => 36000000
        )
    )
);?>