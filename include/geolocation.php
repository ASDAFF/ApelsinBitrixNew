<?//$APPLICATION->IncludeComponent(
//	"altop:geolocation",
//	".default",
//	array(
//		"IBLOCK_TYPE" => "content",
//		"IBLOCK_ID" => "7",
//		"SHOW_CONFIRM" => "Y",
//		"SHOW_DEFAULT_LOCATIONS" => "Y",
//		"SHOW_TEXT_BLOCK" => "N",
//		"SHOW_TEXT_BLOCK_TITLE" => "Y",
//		"TEXT_BLOCK_TITLE" => "",
//		"CACHE_TYPE" => "A",
//		"CACHE_TIME" => "36000000",
//		"COOKIE_TIME" => "36000000",
//		"COMPONENT_TEMPLATE" => ".default",
//		"MODE_OPERATION" => "YANDEX"
//	),
//	false
//);?>
<?$APPLICATION->IncludeComponent(
    "apls:geolocation",
    ".default",
    array(
        "IBLOCK_TYPE" => "content",
        "IBLOCK_ID" => "7",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "COOKIE_TIME" => "36000000",
    ),
    false
);?>