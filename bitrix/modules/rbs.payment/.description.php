<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * Подключение файла настроек
 */
require($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/rbs.payment/config.php");

$psTitle = $mess["partner_name"];
$psDescription = 'Оплата через ' . $mess["partner_name"];
$user_name_name = "Логин";
$password_name = "Пароль";
$two_stage_name = "Стадийность платежа";
$two_stage_descr = "Если значение 'Y', будет производиться двухстадийный платеж. При пустом значении будет производиться одностадийный платеж.";
$test_mode_name = "Тестовый режим";
$test_mode_descr = "Если значение 'Y', плагин будет работать в тестовом режиме. При пустом значении будет стандартный режим работы.";
$logging_name = "Логирование";
$logging_descr = "Если значение 'Y', плагин будет логировать свою работу в файл. При пустом значении логирование происходить не будет.";
$order_number_name = "Уникальный идентификатор заказа в магазине";
$amount_name = "Сумма заказа";
$shipment_name = "Разрешить отгрузку";
$shipment_descr = "Если значение 'Y', то после успешной оплаты будет автоматически разрешена отгрузка заказа.";

if(!ENCODING) {
	$psTitle = iconv("utf-8", "windows-1251", $psTitle);
	$psDescription = iconv("utf-8", "windows-1251", 'Оплата через '.$psDescription);
	$user_name_name = iconv("utf-8", "windows-1251", $user_name_name);
	$password_name = iconv("utf-8", "windows-1251", $password_name);
	$two_stage_name = iconv("utf-8", "windows-1251", $two_stage_name);
	$two_stage_descr = iconv("utf-8", "windows-1251", $two_stage_descr);
	$test_mode_name = iconv("utf-8", "windows-1251", $test_mode_name);
	$test_mode_descr = iconv("utf-8", "windows-1251", $test_mode_descr);
	$logging_name = iconv("utf-8", "windows-1251", $logging_name);
	$logging_descr = iconv("utf-8", "windows-1251", $logging_descr);
	$order_number_name = iconv("utf-8", "windows-1251", $order_number_name);
	$amount_name = iconv("utf-8", "windows-1251", $amount_name);
	$shipment_name = iconv("utf-8", "windows-1251", $shipment_name);
	$shipment_descr = iconv("utf-8", "windows-1251", $shipment_descr);
}
	
$arPSCorrespondence = array(
	"USER_NAME" => array(
		"NAME" => $user_name_name
	),
	"PASSWORD" => array(
		"NAME" => $password_name
	),
	"TWO_STAGE" => array(
		"NAME" => $two_stage_name, 
		"DESCR" => $two_stage_descr
	),
	"TEST_MODE" => array(
		"NAME" => $test_mode_name, 
		"DESCR" => $test_mode_descr, 
		"VALUE" => "Y"
	),
	"LOGGING" => array(
		"NAME" => $logging_name, 
		"DESCR" => $logging_descr
	),
	"ORDER_NUMBER" => array(
		"NAME" => $order_number_name,
		"VALUE" => "ID", 
		"TYPE" => "ORDER"
	),
	"AMOUNT" => array(
		"NAME" => $amount_name,
		"VALUE" => "SHOULD_PAY", 
		"TYPE" => "ORDER"
	),
	"SHIPMENT_ENABLE" => array(
		"NAME" => $shipment_name,
		"DESCR" => $shipment_descr, 
		"TYPE" => "VALUE"
	),
);