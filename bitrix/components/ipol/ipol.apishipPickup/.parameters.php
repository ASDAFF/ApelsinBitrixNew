<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// if(!\Bitrix\Main\Loader::includeModule("sale"))
	// return;

// if(!cmodule::includeModule('ipol.apiship'))
	// return false;

// $arCities = array();
// $arList = CDeliveryapiship::getListFile();
// $arCities=array_keys($arList);

$arComponentParameters = array(
	"PARAMETERS" => array(
		"NOMAPS" => array(
			"PARENT"   => "BASE",
			"NAME"     => GetMessage("IPOLapiship_COMPOPT_NOMAPS"),
			// "NAME"     => "123",
			"TYPE"     => "CHECKBOX",
		),
		"SHOW_CITY_INPUT" => array(
			"PARENT"   => "BASE",
			"NAME"     => GetMessage('IPOLapiship_SHOW_CITY_INPUT'),
			"TYPE"     => "CHECKBOX",
		),
		"SHOW_COURIER" => array(
			"PARENT"   => "BASE",
			"NAME"     => GetMessage('IPOLapiship_SHOW_COURIER'),
			"TYPE"     => "CHECKBOX",
		),
		"CITY_ID" => array(
			"PARENT"   => "BASE",
			"NAME"     => GetMessage('IPOLapiship_CITY_ID'),
			"TYPE"     => "TEXT",
		),
	),
);
?>