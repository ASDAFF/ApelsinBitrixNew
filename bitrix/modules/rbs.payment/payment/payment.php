<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/update_class.php');

/**
 * Поддержка сессий
 */
session_start();

/**
 * Подключение файла настроек
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/rbs.payment/config.php");

/**
 * Подключение класса RBS
 */
require_once("rbs.php");
if (CSalePaySystemAction::GetParamValue("TEST_MODE") == 'Y') {$test_mode = true;} else {$test_mode = false;}
if (CSalePaySystemAction::GetParamValue("TWO_STAGE") == 'Y') {$two_stage = true;} else {$two_stage = false;}
if (CSalePaySystemAction::GetParamValue("LOGGING") == 'Y') {$logging = true;} else {$logging = false;}
$rbs = new RBS(CSalePaySystemAction::GetParamValue("USER_NAME"), CSalePaySystemAction::GetParamValue("PASSWORD"), $two_stage, $test_mode, $logging);

$app = \Bitrix\Main\Application::getInstance();
$request = $app->getContext()->getRequest();

/**
 * Запрос register.do или regiterPreAuth.do в ПШ
 */

$order_number = CSalePaySystemAction::GetParamValue("ORDER_NUMBER");

$entityId = CSalePaySystemAction::GetParamValue("ORDER_PAYMENT_ID");

if(CUpdateSystem::GetModuleVersion('sale') <= "16.0.11")
{
	$orderId = $order_number;
}
else
{
	list($orderId, $paymentId) = \Bitrix\Sale\PaySystem\Manager::getIdsByPayment($entityId);
}

$arOrder = CSaleOrder::GetByID($orderId);

$currency = $arOrder['CURRENCY'];

$amount = CSalePaySystemAction::GetParamValue("AMOUNT") * 100;
$return_url = 'http://' . $_SERVER['SERVER_NAME'] . '/sale/payment/result.php?ID='.$order_number;
for ($i = 0; $i <= 10; $i++) {
	$response = $rbs->register_order($order_number.'_'.$i, $amount, $return_url, $currency, $arOrder['USER_DESCRIPTION']);
	if ($response['errorCode'] != 1) break;
}

/**
 * Разбор ответа
 */
if (in_array($response['errorCode'], array(1,2,3,4,5,7))) {
// 	if (!ENCODING)
// 		$response = $APPLICATION->ConvertCharsetArray($response, "utf-8", "windows-1251");

	$error = 'Ошибка №'.$response['errorCode'].': '.$response['errorMessage'];
	if(ENCODING) {echo $error;} else {echo iconv("utf-8", "windows-1251", $error);}
// 	if(!ENCODING) {echo $error;} else {echo iconv("windows-1251", "utf-8", $error);}
// 	echo $error;
} elseif ($response['errorCode'] == 0){
	$_SESSION['ORDER_NUMBER'] = $order_number;
    if($request->get('GOTOPAY') && $request->get('GOTOPAY')=="yes") {
        if ($request->get('ORDER_ID') && $request->get('PAYMENT_ID'))
            echo '<script>window.location="' . $response['formUrl'] . '"</script>';
        else
            header("Location:" . $response['formUrl']);
    }
} else {
// 	if (!ENCODING)
// 		$response = $APPLICATION->ConvertCharsetArray($response, "utf-8", "windows-1251");
	$error = "Неизвестная ошибка. Попробуйте оплатить заказ позднее.";
	if(ENCODING) {echo $error;} else {echo iconv("utf-8", "windows-1251", $error);}
// 	if(!ENCODING) {echo $error;} else {echo iconv("windows-1251", "utf-8", $error);}
// 	echo $error;
}