<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/classes/PromotionImageHelper.php";
?>
<?if(isset($_REQUEST['imageId']) && $_REQUEST['imageId'] !== ""):?>
    <img class="Image" src="<?=PromotionImageHelper::getSmallImagePath($_REQUEST['imageId'])?>" imageId="<?=$_REQUEST['imageId']?>">
<?else:?>
    <div class="Image noImage"></div>
<?endif;?>
