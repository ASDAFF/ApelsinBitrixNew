<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

unset($_SESSION["APLS_ORDER"]["DELIVERY"]["IT_CITY"]);
if ($_POST["itCity"] === "true") {
    $_SESSION["APLS_ORDER"]["DELIVERY"]["IT_CITY"] = true;
} else {
    $_SESSION["APLS_ORDER"]["DELIVERY"]["IT_CITY"] = false;
}