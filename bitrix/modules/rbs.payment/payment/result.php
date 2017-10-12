<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * Поддержка сессий
 */
session_start();

/**
 * Подключение файла настроек
 */
if (!CModule::IncludeModule('sale')) return;
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/rbs.payment/config.php");

/**
 * Вывод ошибок
 */
// error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);
// ini_set('display_errors', 1);


if(isset($_GET["orderId"])) {
	$order_id = $_GET["ORDER_ID"];
	$order_number = isset($_SESSION['ORDER_NUMBER']) ? $_SESSION['ORDER_NUMBER'] : $_REQUEST["ID"];
	$arOrder = CSaleOrder::GetByID($order_number);
	CSalePaySystemAction::InitParamArrays($arOrder, $arOrder["ID"]);
	
	/**
	 * Подключение класса RBS
	 */
	require_once("rbs.php");
	if (CSalePaySystemAction::GetParamValue("TEST_MODE") == 'Y') {$test_mode = true;} else {$test_mode = false;}
	if (CSalePaySystemAction::GetParamValue("TWO_STAGE") == 'Y') {$two_stage = true;} else {$two_stage = false;}
	if (CSalePaySystemAction::GetParamValue("LOGGING") == 'Y') {$logging = true;} else {$logging = false;}
	$rbs = new RBS(CSalePaySystemAction::GetParamValue("USER_NAME"), CSalePaySystemAction::GetParamValue("PASSWORD"), $two_stage, $test_mode, $logging);
	
	$response = $rbs->get_order_status_by_orderId($orderId);
	
    if(($response['errorCode'] == 0) && (($response['orderStatus'] == 1) || ($response['orderStatus'] == 2))) {
	    // Сохранение ифнормации о заказе
		$arOrderFields = array(
			"PS_SUM" => $response["amount"]/100,
			"PS_CURRENCY" => $response["currency"],
			"PS_RESPONSE_DATE" => Date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG))),
			"PS_STATUS" => "Y",
			"PS_STATUS_DESCRIPTION" => $response["cardAuthInfo"]["pan"].";".$response['cardAuthInfo']["cardholderName"],
			"PS_STATUS_MESSAGE" => $response["paymentAmountInfo"]["paymentState"],
			"PS_STATUS_CODE" => "Y",
		);
		
		// Статус заказа
		CSaleOrder::StatusOrder($order_number, RESULT_ORDER_STATUS);
		CSaleOrder::PayOrder($order_number, "Y", true, true);
		if(CSalePaySystemAction::GetParamValue("SHIPMENT_ENABLE") == 'Y')
			CSaleOrder::DeliverOrder($order_number, "Y");
        
		// Вывод на экран
		$title = "Спасибо за покупку!";
		if ($response['orderStatus'] == 1) {
//			$message = "Проведена авторизация суммы заказа №". $order_number .".";
			$message = "Проведена авторизация суммы заказа №". $order_number .".";
		} else {
//			$message = "Проведена полная авторизация суммы заказа №". $order_number .".";
			$message = "Заказ <span class='pageFinalPaymentOrderNunber'> №". $order_number ." </span>полностью оплачен.";

			$message .= "<div class='wrapperPageFinalPayment'>";

			$message .= "<a href='../../../../personal/order' class='pageFinalPayment'>";
			$message .= "<div class='btn_buy myOrder'>Мои заказы";
			$message .= "</div>";
			$message .= "</a>";

			$message .= "<a href='../../../../catalog' class='pageFinalPayment'>";
			$message .= "<div class='btn_buy myCatalog'>Продолжить покупки";
			$message .= "</div>";
			$message .= "</a>";

			$message .= "</div>";

			$message .= "";
		}
        if(!ENCODING) {
	        $title = iconv("utf-8", "windows-1251", $title);
			$message = iconv("utf-8", "windows-1251", $message);
        } 
    } else if ($response['errorCode'] == 0) {
		$arOrderFields["PS_STATUS_MESSAGE"] = "[".$response["orderStatus"]."] ".$response["actionCodeDescription"];
		$title = "Оплата заказа №".$order_number;
		$message = "Статус заказа №".$response["orderStatus"].": ".$response["actionCodeDescription"];
		if(!ENCODING) {
			$arOrderFields["PS_STATUS_MESSAGE"] = iconv("utf-8", "windows-1251", $arOrderFields["PS_STATUS_MESSAGE"]);
	        $title = iconv("utf-8", "windows-1251", $title);
			$message = iconv("utf-8", "windows-1251", $message);
        }
    } else {
		$arOrderFields["PS_STATUS_MESSAGE"] = "[".$response["errorCode"]."] ".$response["errorMessage"];
		$title = "Оплата заказа №".$order_number;
		$message = "Код ошибки №".$response["errorCode"].": ".$response["errorMessage"];
		if(!ENCODING) {
			$arOrderFields["PS_STATUS_MESSAGE"] = iconv("utf-8", "windows-1251", $arOrderFields["PS_STATUS_MESSAGE"]);
	        $title = iconv("utf-8", "windows-1251", $title);
			$message = iconv("utf-8", "windows-1251", $message);
        }
	}
	CSaleOrder::Update($order_number, $arOrderFields);
} else {
	$title = "Ошибка!";
	$message = 'Заказ №'.htmlspecialchars(\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->get('ORDER_ID'), ENT_QUOTES).' не найден!';
	if(!ENCODING) {
	$title = iconv("utf-8", "windows-1251", $title);
	$message = iconv("utf-8", "windows-1251", $message);
	}
}
$APPLICATION->SetTitle($title);
echo $message;
?>


