<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//ORDER_FIELDS//
$arOrderFields = array(
	"NAME" => GetMessage("1CB_PARAMETER_REQUIRED_NAME"),
    "PHONE" => GetMessage("1CB_PARAMETER_REQUIRED_PHONE"),
    "EMAIL" => GetMessage("1CB_PARAMETER_REQUIRED_EMAIL"),
	"MESSAGE" => GetMessage("1CB_PARAMETER_REQUIRED_MESSAGE"),
);

//BUY_MODES//
$arBuyModes = array(
	"ONE" => GetMessage("1CB_PARAMETER_BUY_MODE_ONE"),
	"ALL" => GetMessage("1CB_PARAMETER_BUY_MODE_ALL"),
);

$arComponentParameters = array(	
	"PARAMETERS" => array(		
		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_ELEMENT_ID"),
			"TYPE" => "STRING",
			"HIDDEN" => "Y"
		),
		"ELEMENT_AREA_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_ELEMENT_AREA_ID"),
			"TYPE" => "STRING",
			"HIDDEN" => "Y"
		),		
		"REQUIRED" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_REQUIRED"),
			"TYPE" => "LIST",
			"VALUES" => $arOrderFields,
			"DEFAULT" => array("NAME", "PHONE"),
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "N",
			"MULTIPLE" => "Y",
		),
		"BUY_MODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_BUY_MODE"),
			"TYPE" => "LIST",
			"VALUES" => $arBuyModes,
			"ADDITIONAL_VALUES" => "N",
			"DEFAULT" => "ONE",
			"REFRESH" => "N",
			"MULTIPLE" => "N",
			"HIDDEN" => "Y"
		),		
		"CACHE_TIME"  => array(
			"DEFAULT" => 36000000
		)
	)
);?>