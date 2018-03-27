<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

if(isset($_REQUEST['regionId']) && isset($_REQUEST['city']) && $_REQUEST['city'] != "") {
    $region = new PromotionRegionModel($_REQUEST['regionId']);
    $add = $region->addCity($_REQUEST['city']);
    if(!$add) {
        ?>
        <script>
            alert("Не удолось добавить территорию.\nВозможно она уже используется в другом месте.");
        </script>
        <?
    }
}
?>
