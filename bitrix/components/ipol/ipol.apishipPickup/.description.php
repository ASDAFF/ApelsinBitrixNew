<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IPOLapiship_COMP_NAME"),
	"DESCRIPTION" => GetMessage("IPOLapiship_COMP_DESCR"),
	"ICON" => "/images/apiship_pickup.png",
	"CACHE_PATH" => "Y",
	"SORT" => 40,
	"PATH" => array(
		"ID" => "e-store",
		"CHILD" => array(
			"ID" => "ipol",
			"NAME" => GetMessage("IPOLapiship_GROUP"),
			"SORT" => 30,
			"CHILD" => array(
				"ID" => "ipol_apishipPickup",
			),
		),
	),
);
?>