<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


if(!cmodule::includeModule('ipol.apiship'))
	return false;
if(!cmodule::includeModule('sale'))
	return false;

// определяем, куда писать адрес выбранного ПВЗ
$propAddr = Coption::GetOptionString(CDeliveryapiship::$MODULE_ID,'pvzPicker','');//определяем инпуты, куда писать адреса

$props = CSaleOrderProps::GetList(array(),array('CODE' => $propAddr));
$propAddr='';
while($prop=$props->Fetch())
	$propAddr.=$prop['ID'].',';

$arResult['propAddr'] = $propAddr;


// если вызывали CDeliveryapiship::Compability(), то флаг поднят, если нет, надо вызвать его с дефолтными данными, чтобы в переменных класса получить данные
if (!CDeliveryapiship::$CompabilityPerform)
{
	if($_SESSION['IPOLapiship_city'])
		$arResult['city'] = $_SESSION['IPOLapiship_city'];
	if ($arParams["CITY_ID"])
		$arResult['city'] = $arParams["CITY_ID"];	
	else
	{
		if (!empty($_REQUEST["IPOLAPISHIP_CITY_INPUT"]))
			$_SESSION['IPOLapiship_city'] = $_REQUEST["IPOLAPISHIP_CITY_INPUT"];
		
		if (!empty($_REQUEST["IPOLAPISHIP_CITY_AJAX_NEW_ID"]))
			$_SESSION['IPOLapiship_city'] = $_REQUEST["IPOLAPISHIP_CITY_AJAX_NEW_ID"];
		
		if($_SESSION['IPOLapiship_city'])
			$arResult['city'] = $_SESSION['IPOLapiship_city'];
	}
	
	if (!$arResult['city'])
	{
		$arResult['city'] = COption::GetOptionString(CDeliveryapiship::$MODULE_ID, "departure");
	}
	
	CDeliveryapiship::Compability(array("LOCATION_TO" => $arResult['city']));
}
else
	$arResult['city'] = CDeliveryapiship::$CityToID;

	
// preDDK($_SESSION['IPOLapiship_city']);
// preDDK($arResult['city']);
	
$arResult["cityName"] = CDeliveryapiship::$CityTo;

$arResult["bestsTariffs"] = CDeliveryapiship::$bestsTariffs;
$arResult["PVZ"] = CDeliveryapiship::$cityPVZs;
// preDDK($arResult["PVZ"]);
$arResult["defaultVals"] = CDeliveryapiship::$defaultVals;

if (empty($_REQUEST["IPOLAPISHIP_CITY_AJAX_NEW_ID"]))
	$this->IncludeComponentTemplate();
else
	echo json_encode(apishipHelper::zajsonit($arResult));

?>