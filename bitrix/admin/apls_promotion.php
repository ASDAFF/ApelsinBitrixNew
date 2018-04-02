<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/prolog.php");
$APPLICATION->SetTitle("АКЦИИ: Работа с акциями");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$APPLICATION->IncludeComponent(
    "apls:admin.promotions",
    ".default"
);