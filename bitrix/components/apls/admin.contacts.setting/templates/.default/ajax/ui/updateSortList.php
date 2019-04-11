<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/model/GeolocationRegionsContacts.php";

foreach ($_POST['updateData'] as $key=>$element) {
    $updateShop = new GeolocationRegionsContacts($key);
    $updateShop->setFieldValue('sort',$element);
    $updateShop->saveElement();
}