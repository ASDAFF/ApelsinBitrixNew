<?define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();
$APPLICATION->AddHeadScript("/bitrix/js/main/dd.js");

use Bitrix\Main\Loader,
	Bitrix\Main\Application,
	Bitrix\Main\Text\Encoding;

if(!Loader::IncludeModule("iblock") || !Loader::IncludeModule("sale"))
	return;

$request = Application::getInstance()->getContext()->getRequest();

if($request->isPost() && check_bitrix_sessid()) {
	$action = $request->getPost("action");

	$arParams = $request->getPost("arParams");
	if(!empty($arParams))
		$arParams = unserialize(gzuncompress(stripslashes(base64_decode(strtr($arParams, '-_,', '+/=')))));

	//DELETE_GEOLOCATION_COOKIES//
	foreach($arParams["OPTIONS"] as $arOption) {
		if($APPLICATION->get_cookie($arOption))
			$APPLICATION->set_cookie($arOption, null, time() - 3600, "/", SITE_SERVER_NAME);
	}
	
	switch($action) {
		case "searchLocation":
			//GEOLOCATION_COUNTRY//
			$country = $request->getPost("country");
			if(SITE_CHARSET != "utf-8")
				$country = Encoding::convertEncoding($country, "utf-8", SITE_CHARSET);
			
			//GEOLOCATION_REGION//
			$region = $request->getPost("region");
			if(SITE_CHARSET != "utf-8")
				$region = Encoding::convertEncoding($region, "utf-8", SITE_CHARSET);
			
			//GEOLOCATION_CITY//
			$city = $request->getPost("city");
			if(SITE_CHARSET != "utf-8")
				$city = Encoding::convertEncoding($city, "utf-8", SITE_CHARSET);
			if(empty($city))
				return;
			
			//GEOLOCATION_LOCATION_ID//
			$locationId = false;
			$rsLocation = CSaleLocation::GetList(
				array(),
				array(
					"CITY_NAME" => $city,
					"LID" => LANGUAGE_ID
				),
				false,
				false,
				array()
			);
			if($arLocation = $rsLocation->GetNext())
				$locationId = $arLocation["ID"];
			
			//GEOLOCATION_CONTACTS_ID//
			//GEOLOCATION_CONTACTS//			
			$contactsList = false;
			$rsElements = CIBlockElement::GetList(
				array(
					"SORT" => "ASC"
				), 
				array(
					"ACTIVE" => "Y",
					"IBLOCK_ID" => intval($arParams["IBLOCK_ID"])
				), 
				false, 
				false, 
				array("ID", "IBLOCK_ID", "PREVIEW_TEXT")
			);				
			while($obElement = $rsElements->GetNextElement()) {
				$arElement = $obElement->GetFields();
				$arElement["PROPERTIES"] = $obElement->GetProperties();
				$contactsList[] = $arElement;
			}

			$contactsId = $contacts = false;
			foreach($contactsList as $arElement) {
				if(in_array($city, $arElement["PROPERTIES"]["LOCATION"]["VALUE"])) {
					$contactsId = $arElement["ID"];
					$contacts = $arElement["PREVIEW_TEXT"];
					break;
				}
			}
			if(empty($contactsId) && !empty($region)) {
				foreach($contactsList as $arElement) {
					if(in_array($region, $arElement["PROPERTIES"]["LOCATION"]["VALUE"])) {
						$contactsId = $arElement["ID"];
						$contacts = $arElement["PREVIEW_TEXT"];
						break;
					}
				}
			}
			if(empty($contactsId) && !empty($country)) {
				foreach($contactsList as $arElement) {
					if(in_array($country, $arElement["PROPERTIES"]["LOCATION"]["VALUE"])) {
						$contactsId = $arElement["ID"];
						$contacts = $arElement["PREVIEW_TEXT"];
						break;
					}
				}
			}
			
			//SEARCH_RESULT//
			$searchResult = array(
				"city" => $city,
				"contacts" => $arParams["GEOLOCATION_REGIONAL_CONTACTS"] == "Y" ? (!empty($contacts) ? $contacts : false) : false
			);
			if(SITE_CHARSET != "utf-8")
				$searchResult = Encoding::convertEncoding($searchResult, SITE_CHARSET, "utf-8");

			//SET_GEOLOCATION_COOKIES//
			$APPLICATION->set_cookie("GEOLOCATION_CITY", $searchResult["city"], time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
			if(!empty($locationId))
				$APPLICATION->set_cookie("GEOLOCATION_LOCATION_ID", $locationId, time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
			if(!empty($contactsId))
				$APPLICATION->set_cookie("GEOLOCATION_CONTACTS_ID", $contactsId, time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
			
			echo json_encode($searchResult);
			break;		
		case "setLocation":			
			//GEOLOCATION_LOCATION_ID//
			$locationId = $request->getPost("locationId");
			if(intval($locationId) <= 0)
				return;
			
			//GEOLOCATION_COUNTRY_REGION_CITY//
			$country = $region = $city = false;
			$rsLocation = CSaleLocation::GetList(
				array(),
				array(
					"ID" => intval($locationId),
					"LID" => LANGUAGE_ID
				),
				false,
				false,
				array()
			);
			if($arLocation = $rsLocation->GetNext()) {
				$country = $arLocation["COUNTRY_NAME"];
				$region = $arLocation["REGION_NAME"];
				$city = $arLocation["CITY_NAME"];
			}
			
			//GEOLOCATION_CONTACTS_ID//
			$contactsList = false;
			$rsElements = CIBlockElement::GetList(
				array(
					"SORT" => "ASC"
				), 
				array(
					"ACTIVE" => "Y",
					"IBLOCK_ID" => intval($arParams["IBLOCK_ID"])
				), 
				false, 
				false, 
				array("ID", "IBLOCK_ID", "PREVIEW_TEXT")
			);				
			while($obElement = $rsElements->GetNextElement()) {
				$arElement = $obElement->GetFields();
				$arElement["PROPERTIES"] = $obElement->GetProperties();					
				$contactsList[] = $arElement;
			}

			$contactsId = false;
			if(!empty($city)) {
				foreach($contactsList as $arElement) {
					if(in_array($city, $arElement["PROPERTIES"]["LOCATION"]["VALUE"])) {
						$contactsId = $arElement["ID"];
						break;
					}
				}
			}
			if(empty($contactsId) && !empty($region)) {
				foreach($contactsList as $arElement) {
					if(in_array($region, $arElement["PROPERTIES"]["LOCATION"]["VALUE"])) {
						$contactsId = $arElement["ID"];
						break;
					}
				}
			}
			if(empty($contactsId) && !empty($country)) {
				foreach($contactsList as $arElement) {
					if(in_array($country, $arElement["PROPERTIES"]["LOCATION"]["VALUE"])) {
						$contactsId = $arElement["ID"];
						break;
					}
				}
			}
			
			//SET_RESULT//
			$setResult = array(
				"city" => $city,
				"region" => $region
			);
			if(SITE_CHARSET != "utf-8")
				$setResult = Encoding::convertEncoding($setResult, SITE_CHARSET, "utf-8");

			//SET_GEOLOCATION_COOKIES//
			if(!empty($setResult["city"]))
				$APPLICATION->set_cookie("GEOLOCATION_CITY", $setResult["city"], time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
			if(empty($setResult["city"]) && !empty($setResult["region"]))
				$APPLICATION->set_cookie("GEOLOCATION_CITY", $setResult["region"], time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
			$APPLICATION->set_cookie("GEOLOCATION_LOCATION_ID", $locationId, time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
			if(!empty($contactsId))
				$APPLICATION->set_cookie("GEOLOCATION_CONTACTS_ID", $contactsId, time() + $arParams["COOKIE_TIME"], "/", SITE_SERVER_NAME);
			break;
	}
	die();
}?>