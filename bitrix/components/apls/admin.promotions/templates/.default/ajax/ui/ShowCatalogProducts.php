<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
$revision = new PromotionRevisionModel($_REQUEST['revisionId']);
$revisionProducts = array();
if($_REQUEST['type'] = "product") {
    $revisionProducts = $revision->getCatalogProducts();
} elseif($_REQUEST['type'] = "exception") {
    $revisionProducts = $revision->getCatalogExceptions();
}
?>
<div class="ListOfElements TwoColumns">
    <?foreach ($revisionProducts as $revisionProduct):
        if($revisionProduct instanceof PromotionCatalogProduct || $revisionProduct instanceof PromotionCatalogException):
            $productXmlId = $revisionProduct->getFieldValue('product');
            ?>
            <div class="ElementBlock">
                <div class="ElementBlockContent" tableId="<?=$revisionProduct->getId()?>">
                    <div class="content"><?=$productXmlId?></div>
                    <div class="DellButton"></div>
                </div>
            </div>
        <?endif;
    endforeach;?>
</div>