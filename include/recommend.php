
<?$APPLICATION->IncludeComponent(
	"apls:recommend", 
	".default", 
	array(
		"IBLOCK_ID" => "16",
		"HLBLOCK_NAME" => "Recommend",
		"PRICE_CODE" => array(
			0 => "Розничная цена",
			1 => "М. оптовая",
			2 => "ИМ",
			3 => "Ср. оптовая",
			4 => "Оптовая",
			5 => "Кр. оптовая",
		),
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);



