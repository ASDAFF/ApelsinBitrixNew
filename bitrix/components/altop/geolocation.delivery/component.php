<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader,
	Bitrix\Main\Application,
	Bitrix\Main\Text\Encoding,
	Bitrix\Sale,
	Bitrix\Main\Type\Collection;

if(!Loader::includeModule("catalog") || !Loader::includeModule("sale"))
	return;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["ELEMENT_ID"] = intval($arParams["ELEMENT_ID"]);
if($arParams["ELEMENT_ID"] <= 0)
	return;

$arParams["ELEMENT_COUNT"] = floatval($arParams["ELEMENT_COUNT"]);

if(empty($arParams["CART_PRODUCTS"]))
	$arParams["CART_PRODUCTS"] = "N";
elseif($arParams["CART_PRODUCTS"] != "Y")
	$arParams["CART_PRODUCTS"] = "N";

if(empty($arParams["AJAX_CALL"]))
	$arParams["AJAX_CALL"] = "N";
elseif($arParams["AJAX_CALL"] != "Y")
	$arParams["AJAX_CALL"] = "N";

$request = Application::getInstance()->getContext()->getRequest();
$arParams["GEOLOCATION_CITY"] = $request->getCookie("GEOLOCATION_CITY");
if(SITE_CHARSET != "utf-8")
	$arParams["GEOLOCATION_CITY"] = Encoding::convertEncoding($arParams["GEOLOCATION_CITY"], "utf-8", SITE_CHARSET);
$arParams["GEOLOCATION_LOCATION_ID"] = $request->getCookie("GEOLOCATION_LOCATION_ID");

if($this->StartResultCache(false, $arParams["CART_PRODUCTS"] == "Y" ? $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite())->getOrderableItems() : "")) {
	if($arParams["AJAX_CALL"] == "Y" && $arParams["GEOLOCATION_LOCATION_ID"] > 0) {		
		$arProduct = CCatalogProduct::GetByID($arParams["ELEMENT_ID"]);
		
		$rsRatios = CCatalogMeasureRatio::getList(
			array(),
			array("PRODUCT_ID" => $arProduct["ID"]),
			false,
			false,
			array("PRODUCT_ID", "RATIO")
		);
		if($arRatio = $rsRatios->Fetch()) {			
			$intRatio = intval($arRatio["RATIO"]);
			$dblRatio = doubleval($arRatio["RATIO"]);
			$mxRatio = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
			if(CATALOG_VALUE_EPSILON > abs($mxRatio))
				$mxRatio = 1;
			elseif(0 > $mxRatio)
				$mxRatio = 1;
			if($arParams["ELEMENT_COUNT"] <= 0)
				$arParams["ELEMENT_COUNT"] = $mxRatio;
			$arResult["CATALOG_MEASURE_RATIO"] = $mxRatio;
		}
		if($arParams["ELEMENT_COUNT"] <= 0)
			$arParams["ELEMENT_COUNT"] = 1;
		if(!isset($arResult["CATALOG_MEASURE_RATIO"]))
			$arResult["CATALOG_MEASURE_RATIO"] = 1;
		
		$arPrice = CCatalogProduct::GetOptimalPrice($arProduct["ID"], $arParams["ELEMENT_COUNT"], $USER->GetUserGroupArray());
		
		$baseCurrency = Bitrix\Sale\Internals\SiteCurrencyTable::getSiteCurrency(SITE_ID);
		
		$shipmentItems[$arProduct["ID"]] = array(
			"PRODUCT_ID" => $arProduct["ID"],
			"LID" => SITE_ID,
			"QUANTITY" => $arParams["ELEMENT_COUNT"],
			"WEIGHT" => $arProduct["WEIGHT"],
			"PRICE" => $arPrice["DISCOUNT_PRICE"]
		);

		$basketPrice = 0;
		$basketWeight = 0;
		if($arParams["CART_PRODUCTS"] == "Y") {
			$basketPrice = $basket->getPrice();
			$basketWeight = $basket->getWeight();
			
			foreach($basket as $basketItem) {
				$basketItemQnt = $basketItem->getQuantity();
				if($basketItem->getProductId() == $arProduct["ID"]) {					
					unset($shipmentItems[$basketItem->getProductId()]);
					$basketItemQnt += $arParams["ELEMENT_COUNT"];
				}
				$shipmentItems[$basketItem->getProductId()] = array(					
					"MODULE" => $basketItem->getField("MODULE"),					
					"PRODUCT_ID" => $basketItem->getProductId(),					
					"ID" => $basketItem->getField("ID"),
					"LID" => $basketItem->getField("LID"),			
					"QUANTITY" => $basketItemQnt,
					"WEIGHT" => $basketItem->getWeight(),
					"PRICE" => $basketItem->getPrice(),
					"PRODUCT_PROVIDER_CLASS" => $basketItem->getField("PRODUCT_PROVIDER_CLASS")
				);
			}
		}

		if($loc = Bitrix\Sale\Location\LocationTable::getRowById($arParams["GEOLOCATION_LOCATION_ID"])) {
			$arParams["GEOLOCATION_LOCATION_ID"] = $loc["CODE"];
		}
		
		$shipment = CSaleDelivery::convertOrderOldToNew(
			array(
				"WEIGHT" => ($arProduct["WEIGHT"] * $arParams["ELEMENT_COUNT"]) + $basketWeight,
				"PRICE" => ($arPrice["DISCOUNT_PRICE"] * $arParams["ELEMENT_COUNT"]) + $basketPrice,
				"LOCATION_TO" => $arParams["GEOLOCATION_LOCATION_ID"],
				"ITEMS" => $shipmentItems,
				"CURRENCY" => $baseCurrency
			)
		);
		
		$arDeliveryServiceAll = Bitrix\Sale\Delivery\Services\Manager::getRestrictedObjectsList($shipment);
		if(!empty($arDeliveryServiceAll)) {
			foreach($arDeliveryServiceAll as $deliveryObj) {
				$calcResult = $deliveryObj->calculate($shipment);
				if($calcResult->isSuccess()) {
					$arDelivery["ID"] = $deliveryObj->getId();
					$arDelivery["NAME"] = $deliveryObj->isProfile() ? $deliveryObj->getNameWithParent() : $deliveryObj->getName();
					$arDelivery["DESCRIPTION"] = $deliveryObj->getDescription();
					$arDelivery["LOGOTIP"] = CFile::GetFileArray($deliveryObj->getLogotip());
					$arDelivery["PRICE"] = $calcResult->getPrice();
					$arDelivery["PRICE_FORMATED"] = SaleFormatCurrency($calcResult->getPrice(), $baseCurrency);
					$arDelivery["PERIOD_TEXT"] = $calcResult->getPeriodDescription();
					
					$arResult["DELIVERY"][$deliveryObj->getId()] = $arDelivery;
				}
			}
			
			Collection::sortByColumn($arResult["DELIVERY"], array("PRICE" => SORT_ASC));
		} else
			$this->abortResultCache();
	}
	$this->IncludeComponentTemplate();
}?>