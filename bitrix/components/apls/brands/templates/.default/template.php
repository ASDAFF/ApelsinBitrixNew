<?php
if(isset($arResult['brands'])) {
    include_once $_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/brandsList.php";
} else if(isset($arResult['brand'])) {
    include_once $_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/brand.php";
}