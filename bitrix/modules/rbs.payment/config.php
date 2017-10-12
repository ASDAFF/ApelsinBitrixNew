<?
/**
 * Комментарии при установке и при настройке
 */
$mess["module_name"] = "Прием платежей через Сбербанк";
$mess["module_description"] = "Сбербанк - http://www.sberbank.ru/";
$mess["partner_name"] = "Сбербанк";
$mess["partner_uri"] = "http://www.sberbank.ru/";

/**
 * URL API
 */
if(!defined('PROD_URL'))
	define('PROD_URL', 'https://securepayments.sberbank.ru/payment/rest/'); // Продакшн/Бой
if(!defined('TEST_URL'))
	define('TEST_URL', 'https://3dsec.sberbank.ru/payment/rest/'); // Тест

/**
 * Версия плагина
 */
if(!defined('VERSION'))
	define(VERSION, '2.11');
if(!defined('VERSION_DATE'))
	define(VERSION_DATE, '2016-12-16 00:00:00');

/**
 * Версия банка
*/
//if(!defined('OPENBANK'))
//	define(OPENBANK, true);
if(!defined('OPENBANK'))
    define(OPENBANK, false);

/**
 * Если utf-8, то true. Если cp1251 - false (специфично для Bitrix)
*/
if (LANG_CHARSET == 'UTF-8') {
	if(!defined('ENCODING'))
		define(ENCODING, true);
} else {
	if(!defined('ENCODING'))
		define(ENCODING, false);
}

$status = COption::GetOptionString("rbs.payment", "result_order_status", "P");
if(!defined('RESULT_ORDER_STATUS'))
	define('RESULT_ORDER_STATUS',$status);

$arDefaultIso = array(
	'USD' => 840,
	'EUR' => 978,
	'CNY' => 643
);

if (OPENBANK)
{
	$arDefaultIso['RUB'] = 810;
	$arDefaultIso['RUR'] = 810;
	if(!defined('DEFAULT_ISO'))
		define(DEFAULT_ISO,serialize($arDefaultIso));
}
else
{
	$arDefaultIso['RUB'] = 643;
	$arDefaultIso['RUR'] = 643;
	if(!defined('DEFAULT_ISO'))
		define(DEFAULT_ISO,serialize($arDefaultIso));
}
