<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//var_dump($_REQUEST['type']);
//var_dump($_REQUEST['tableId']);
switch ($_REQUEST['type']) {
    case 'product':
        include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogProduct.php";
        $result = PromotionCatalogProduct::deleteElement($_REQUEST['tableId']);
        break;
    case 'exception':
        include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogException.php";
        $result = PromotionCatalogException::deleteElement($_REQUEST['tableId']);
        break;
    case 'section':
        include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCatalogSection.php";
        $result = PromotionCatalogSection::deleteElement($_REQUEST['tableId']);
        break;
    default:
        $result = false;
        break;
}
if($result) {
    echo "yes";
}