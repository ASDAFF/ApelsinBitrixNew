<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION, $USER;

$aMenuLinks = Array(
	Array(
		"Заказы",
		"/personal/orders/",
		Array(),
		Array(),
		""
	),
	Array(
		"Моя корзина",
		"/personal/cart/",
		Array(),
		Array(),
		""
	),
	Array(
		"Отложенные товары",
		"/personal/cart/?delay=Y",
		Array(),
		Array(),
		""
	),
//	Array(
//		"Ожидаемые товары",
//		"/personal/subscribe/",
//		Array(),
//		Array(),
//		""
//	),
//	Array(
//		"Архив заказов",
//		"/personal/orders/?filter_history=Y",
//		Array(),
//		Array(),
//		""
//	),
	Array(
		"Профиль",
		"/personal/private/",
		Array(), 
		Array(), 
		"" 
	),
//	Array(
//		"Личный счет",
//		"/personal/account/",
//		Array(),
//		Array(),
//		"CBXFeatures::IsFeatureEnabled('SaleAccounts')"
//	),
//	Array(
//		"Профили заказов",
//		"/personal/profiles/",
//		Array(),
//		Array(),
//		""
//	),
//	Array(
//		"Email рассылки",
//		"/personal/mailings/",
//		Array(),
//		Array(),
//		""
//	),
//    Array(
//        "Пользовательское соглашение",
//        "/payments/terms_of_use/",
//        Array(),
//        Array(),
//        ""
//    ),
//    Array(
//        "Политика конфиденциальности",
//        "/payments/processing_of_personal_data/",
//        Array(),
//        Array(),
//        ""
//    ),
//    Array(
//        "Оферта интернет магазина",
//        "/payments/offer/",
//        Array(),
//        Array(),
//        ""
//    ),
);?>