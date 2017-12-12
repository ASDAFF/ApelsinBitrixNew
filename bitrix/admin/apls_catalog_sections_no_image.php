<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/iblock.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/prolog.php");
$APPLICATION->SetTitle("Каталоги без картинок");

$APPLICATION->IncludeComponent(
    "apls:sections.no.image.list",
    ".default"
);