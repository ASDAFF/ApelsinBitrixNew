<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ($_POST["img_type"] == "s_img") {
    $file = [
        "name" => $_POST["shop_id"].".png",
        "size" => $_FILES["file"]["size"],
        "tmp_name" => $_FILES['file']['tmp_name'],
        "type" => $_FILES['file']['type'],
        "del" => "Y",
        "MODULE_ID" => "iblock",
    ];
} elseif ($_POST["img_type"] == "b_img") {
    $file = [
        "name" => $_POST["shop_id"].".jpg",
        "size" => $_FILES["file"]["size"],
        "tmp_name" => $_FILES['file']['tmp_name'],
        "type" => $_FILES['file']['type'],
        "del" => "Y",
        "MODULE_ID" => "iblock",
    ];
}


$fileId = CFile::SaveFile($file, "iblock");

$updateShop = new GeolocationRegionsContacts($_POST["shop_id"]);

$updateShop->setFieldValue($_POST["img_type"],$fileId);
$updateShop->saveElement();

echo $fileId;