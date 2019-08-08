<?
if(empty($_SERVER["HTTP_REFERER"]))
	die();

define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Application,
	Bitrix\Main\Config\Option,
	Bitrix\Sale,
	Bitrix\Sale\Order,
	Bitrix\Sale\DiscountCouponsManager;

if(!Loader::IncludeModule("sale"))
	return;

Loc::loadMessages(__FILE__);

global $USER, $APPLICATION;

$request = Application::getInstance()->getContext()->getRequest();

$paramsString = $request->getPost("PARAMS_STRING");
if(!empty($paramsString))
	$params = unserialize(base64_decode(strtr($paramsString, "-_,", "+/=")));

$name = $request->getPost("NAME");
$phone = $request->getPost("PHONE");
$email = $request->getPost("EMAIL");
$message = $request->getPost("MESSAGE");

$captchaWord = $request->getPost("CAPTCHA_WORD");
$captchaSid = $request->getPost("CAPTCHA_SID");

$id = $request->getPost("ID");
$props = $request->getPost("PROPS");
$selectProps = $request->getPost("SELECT_PROPS");	
$qnt = $request->getPost("QUANTITY");

$buyMode = $request->getPost("BUY_MODE");

//CHECKS//
foreach($params["REQUIRED"] as $arCode) {
	$post = $request->getPost($arCode);
	if(empty($post))
		$error .= Loc::getMessage($arCode."_NOT_FILLED")."<br />";
}

//CHECKS_PERSONAL_DATA//
$personalData = $request->getPost("PERSONAL_DATA");
if($personalData === "N") {
	$error .= Loc::getMessage("FIELD_NOT_FILLED_PERSONAL_DATA")."<br />";
}

//VALIDATE_PHONE_MASK//
if(!empty($phone)) {
	if(!preg_match($params["VALIDATE_PHONE_MASK"], $phone)) {
		$error .= Loc::getMessage("PHONE_INVALID")."<br />";
	}
}

if(!empty($captchaSid) && !$APPLICATION->CaptchaCheckCode($captchaWord, $captchaSid))
	$error .= Loc::getMessage("WRONG_CAPTCHA")."<br />";

if(!empty($error)) {
	$result = array(
		"error" => array(
			"text" => $error,
			"captcha_code" => !empty($captchaSid) ? $APPLICATION->CaptchaGetCode() : ""
		)
	);
	echo Bitrix\Main\Web\Json::encode($result);
	return;
}

//PROPERTIES//
$name = iconv("UTF-8", SITE_CHARSET, strip_tags(trim($name)));
$phone = iconv("UTF-8", SITE_CHARSET, strip_tags(trim($phone)));
$email = iconv("UTF-8", SITE_CHARSET, strip_tags(trim($email)));
$message = iconv("UTF-8", SITE_CHARSET, strip_tags(trim($message)));

//USER//	
if($params["IS_AUTHORIZED"] != "Y") {
	$rsUser = $USER->GetByLogin("technical_boc");
	if($arUser = $rsUser->Fetch()) {
		$registeredUserID = $arUser["ID"];
	} else {
		$newLogin = "technical_boc";
		$newEmail = $newLogin."@".$newLogin.".com";
		$newPass = randString(10);
		
		$arFields = Array(
			"LOGIN" => $newLogin,
			"NAME" => Loc::getMessage("NEW_USER_NAME"),
			"EMAIL" => $newEmail,
			"PASSWORD" => $newPass,
			"CONFIRM_PASSWORD" => $newPass,
			"ACTIVE" => "Y",
			"LID" => SITE_ID
		);
		$registeredUserID = $USER->Add($arFields);
	}
} else {
	$registeredUserID = $USER->GetID();
}

//BASKET//
$basketUserID = Sale\Fuser::getId();

DiscountCouponsManager::init();

if($buyMode == "ONE") {
	$basket = Sale\Basket::loadItemsForFUser($basketUserID, Bitrix\Main\Context::getCurrent()->getSite())->getOrderableItems();
	foreach($basket as $basketItem) {
		\CSaleBasket::Delete($basketItem->getId());
	}
	$item = $basket->createItem("catalog", $id);

	$xmlId = "";
    $arSelect = Array();
    $arFilter = Array("ID"=>$id );
    $res = CIBlockElement::GetList( Array(), $arFilter, false, Array(), $arSelect );
    if ( $ob = $res->GetNextElement() ) {
        $arFields = $ob->GetFields();
        $xmlId = $arFields[ "XML_ID" ];
        $xmlIdCatalog = $arFields[ "IBLOCK_EXTERNAL_ID" ];
    }

	$item->setFields(array(
        "PRODUCT_XML_ID" => $xmlId,
        "CATALOG_XML_ID" => $xmlIdCatalog,
		"QUANTITY" => $qnt,
		"CURRENCY" => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
		"LID" => \Bitrix\Main\Context::getCurrent()->getSite(),
		"PRODUCT_PROVIDER_CLASS" => "CCatalogProductProvider"
	));
	$basket->save();
	
	if(!empty($props)) {
		$arProps = unserialize(base64_decode(strtr($props, "-_,", "+/=")));
		foreach($arProps as $arProp) {
			$arBasketProps[] = $arProp;
		}
	}
	if(!empty($selectProps)) {
		$arSelectProps = explode("||", $selectProps);
		foreach($arSelectProps as $arSelProp) {
			$arBasketProps[] = unserialize(base64_decode(strtr($arSelProp, "-_,", "+/=")));
		}
	}
	if(isset($arBasketProps) && !empty($arBasketProps)) {
		$basketPropertyCollection = $item->getPropertyCollection();
		$basketPropertyCollection->setProperty($arBasketProps);
		$basketPropertyCollection->save();
	}
}

//CREATE_ORDER//
$order = Order::create(Bitrix\Main\Context::getCurrent()->getSite(), $registeredUserID);

//PERSON_TYPE//
$arPersonTypes = Sale\PersonType::load(Bitrix\Main\Context::getCurrent()->getSite());
reset($arPersonTypes);
$arPersonType = current($arPersonTypes);
if(!empty($arPersonType))
	$order->setPersonTypeId($arPersonType["ID"]);

//ORDER_SET_BASKET//
$basket = Sale\Basket::loadItemsForFUser($basketUserID, Bitrix\Main\Context::getCurrent()->getSite())->getOrderableItems();	
$order->setBasket($basket);

//SHIPMENT//
$shipmentCollection = $order->getShipmentCollection();	
$shipment = $shipmentCollection->createItem();
$shipment->setField("CURRENCY", $order->getCurrency());

$shipmentItemCollection = $shipment->getShipmentItemCollection();

foreach($order->getBasket() as $item) {
	$shipmentItem = $shipmentItemCollection->createItem($item);
	$shipmentItem->setQuantity($item->getQuantity());
}

$arDeliveryServiceAll = Sale\Delivery\Services\Manager::getRestrictedObjectsList($shipment);	
reset($arDeliveryServiceAll);
$deliveryObj = current($arDeliveryServiceAll);
if(!empty($deliveryObj)) {
	$shipment->setFields(array(
		"DELIVERY_ID" => $deliveryObj->getId(),
		"DELIVERY_NAME" => $deliveryObj->isProfile() ? $deliveryObj->getNameWithParent() : $deliveryObj->getName()
	));
	$shipment->getCollection()->calculateDelivery();
} else
	$shipment->delete();

//PAYMENT//
$paymentCollection = $order->getPaymentCollection();
$extPayment = $paymentCollection->createItem();
$extPayment->setField("SUM", $order->getPrice());
$arPaySystemServiceAll = Sale\PaySystem\Manager::getListWithRestrictions($extPayment);	
reset($arPaySystemServiceAll);
$arPaySystem = current($arPaySystemServiceAll);
if(!empty($arPaySystem)) {
	$extPayment->setFields(array(
		"PAY_SYSTEM_ID" => $arPaySystem["ID"],
		"PAY_SYSTEM_NAME" => $arPaySystem["NAME"]
	));
} else
	$extPayment->delete();

$order->doFinalAction(true);

//ORDER_SET_PROPERTIES//
function getPropertyByCode($propertyCollection, $code) {
	foreach($propertyCollection as $property) {
		if($property->getField("CODE") == $code)
			return $property;
	}
}

$propertyCollection = $order->getPropertyCollection();
$fioProperty = getPropertyByCode($propertyCollection, "FIO");
if(!empty($fioProperty))
	$fioProperty->setValue($name);

$phoneProperty = getPropertyByCode($propertyCollection, "PHONE");
if(!empty($phoneProperty))
	$phoneProperty->setValue($phone);

$emailProperty = getPropertyByCode($propertyCollection, "EMAIL");
if(!empty($emailProperty))
	$emailProperty->setValue($email);

//ORDER_SET_FIELDS//
$order->setField("CURRENCY", Option::get("sale", "default_currency"));

$order->setField("USER_DESCRIPTION", $message);
$order->setField("COMMENTS", Loc::getMessage("ORDER_COMMENT"));

$order->save();

$orderId = $order->GetId();

//MESSAGE//
if($orderId > 0) {
	$result = array(
		"success" => array(
			"text" => $test.Loc::getMessage("ORDER_CREATE_SUCCESS"),
            "orderId" => $orderId,
            "fioProperty" => $phone,
		)
	);
} else {
	$result = array(
		"error" => array(
			"text" => $test.Loc::getMessage("ORDER_CREATE_ERROR"),
			"captcha_code" => !empty($captchaSid) ? $APPLICATION->CaptchaGetCode() : ""
		)
	);
}

echo Bitrix\Main\Web\Json::encode($result);?>