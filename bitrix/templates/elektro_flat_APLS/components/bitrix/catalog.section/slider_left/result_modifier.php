<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//ELEMENTS//
foreach($arResult["ITEMS"] as $key => $arElement) {
	//PREVIEW_PICTURE//	
	if(is_array($arElement["PREVIEW_PICTURE"])) {
		if($arElement["PREVIEW_PICTURE"]["WIDTH"] > $arParams["DISPLAY_IMG_WIDTH"] || $arElement["PREVIEW_PICTURE"]["HEIGHT"] > $arParams["DISPLAY_IMG_HEIGHT"]) {
			$arFileTmp = CFile::ResizeImageGet(
				$arElement["PREVIEW_PICTURE"],
				array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"]
			);
		}
	} elseif(is_array($arElement["DETAIL_PICTURE"])) {
		if($arElement["DETAIL_PICTURE"]["WIDTH"] > $arParams["DISPLAY_IMG_WIDTH"] || $arElement["DETAIL_PICTURE"]["HEIGHT"] > $arParams["DISPLAY_IMG_HEIGHT"]) {
			$arFileTmp = CFile::ResizeImageGet(
				$arElement["DETAIL_PICTURE"],
				array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"]
			);
		} else {
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE"] = $arElement["DETAIL_PICTURE"];
		}
	}

	//MANUFACTURER//
	$vendorId = intval($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]);
	if($vendorId > 0)
		$vendorIds[] = $vendorId;

	//MIN_PRICE//
	if(count($arElement["ITEM_QUANTITY_RANGES"]) > 1) {
		$minPrice = false;
		foreach($arElement["ITEM_PRICES"] as $itemPrice) {
			if($itemPrice["RATIO_PRICE"] == 0)
				continue;
			if($minPrice === false || $minPrice > $itemPrice["RATIO_PRICE"]) {								
				$minPrice = $itemPrice["RATIO_PRICE"];					
				$arResult["ITEMS"][$key]["MIN_PRICE"] = array(		
					"RATIO_BASE_PRICE" => $itemPrice["RATIO_BASE_PRICE"],
					"PRINT_RATIO_BASE_PRICE" => $itemPrice["PRINT_RATIO_BASE_PRICE"],
					"RATIO_PRICE" => $minPrice,											
					"PERCENT" => $itemPrice["PERCENT"],
					"CURRENCY" => $itemPrice["CURRENCY"]
				);
			}
		}
		if($minPrice === false) {
			$arResult["ITEMS"][$key]["MIN_PRICE"] = array(
				"RATIO_PRICE" => "0",
				"CURRENCY" => $arElement["ITEM_PRICES"][$arElement["ITEM_PRICE_SELECTED"]]["CURRENCY"]
			);
		}
	} else {
		$arResult["ITEMS"][$key]["MIN_PRICE"] = $arElement["ITEM_PRICES"][$arElement["ITEM_PRICE_SELECTED"]];
	}
	
	//OFFERS//
	if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {
		//TOTAL_OFFERS//		
		$minPrice = false;
		$totalPrices = false;
		foreach($arElement["OFFERS"] as $key_off => $arOffer) {
			foreach($arOffer["ITEM_PRICES"] as $itemPrice) {
				if($itemPrice["RATIO_PRICE"] == 0)
					continue;						
				if($minPrice === false || $minPrice > $itemPrice["RATIO_PRICE"]) {							
					$minPrice = $itemPrice["RATIO_PRICE"];
					$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["MIN_PRICE"] = array(								
						"RATIO_BASE_PRICE" => $itemPrice["RATIO_BASE_PRICE"],
						"PRINT_RATIO_BASE_PRICE" => $itemPrice["PRINT_RATIO_BASE_PRICE"],
						"RATIO_PRICE" => $minPrice,
						"PERCENT" => $itemPrice["PERCENT"],
						"CURRENCY" => $itemPrice["CURRENCY"]
					);
				}			
				$totalPrices[] = $itemPrice["RATIO_PRICE"];
			}
		}
		if($minPrice === false) {
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["MIN_PRICE"] = array(
				"RATIO_PRICE" => "0",
				"CURRENCY" => $arElement["OFFERS"][0]["ITEM_PRICES"][$arElement["OFFERS"][0]["ITEM_PRICE_SELECTED"]]["CURRENCY"]
			);
		}
		if(count(array_unique($totalPrices)) > 1) {
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["FROM"] = "Y";
		} else {
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["FROM"] = "N";
		}
		//END_TOTAL_OFFERS//
	}
	//END_OFFERS//
}
//END_ELEMENTS//

//MANUFACTURER//
if(count($vendorIds) > 0) {	
	$arVendor = array();
	$rsElements = CIBlockElement::GetList(
		array(),
		array(
			"ID" => array_unique($vendorIds)
		),
		false,
		false,
		array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE")
	);
	while($arElement = $rsElements->GetNext()) {
		$arVendor[$arElement["ID"]]["NAME"] = $arElement["NAME"];
		if($arElement["PREVIEW_PICTURE"] > 0) {
			$arFile = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);		
			if($arFile["WIDTH"] > 69 || $arFile["HEIGHT"] > 24) {
				$arFileTmp = CFile::ResizeImageGet(
					$arFile,
					array("width" => 69, "height" => 24),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);
				$arVendor[$arElement["ID"]]["PREVIEW_PICTURE"] = array(
					"SRC" => $arFileTmp["src"],
					"WIDTH" => $arFileTmp["width"],
					"HEIGHT" => $arFileTmp["height"],
				);
			} else {
				 $arVendor[$arElement["ID"]]["PREVIEW_PICTURE"] = $arFile;
			}
		}
	}
	
	//ELEMENTS//
	foreach($arResult["ITEMS"] as $key => $arElement) {
		//MANUFACTURER//
		$vendorId = intval($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]);
		if($vendorId > 0 && isset($arVendor[$vendorId])) {
			$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["NAME"] = $arVendor[$vendorId]["NAME"];
			$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["PREVIEW_PICTURE"] = $arVendor[$vendorId]["PREVIEW_PICTURE"];
		}
	}
}?>