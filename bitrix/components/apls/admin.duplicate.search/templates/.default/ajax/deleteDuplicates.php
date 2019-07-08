<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$data = explode(",",$_POST["delete_data"]);
foreach ($data as $id) {
    $error[] = CIBlockElement::Delete($id);
}
if (in_array(false,$error)) {
    echo "false";
} else {
    echo "true";
}