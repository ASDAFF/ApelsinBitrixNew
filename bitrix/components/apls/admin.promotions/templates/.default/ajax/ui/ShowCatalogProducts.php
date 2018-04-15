<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementModel.php";
$revision = new PromotionRevisionModel($_REQUEST['revisionId']);
$revisionProducts = array();
if($_REQUEST['type'] === "product") {
    $revisionProducts = $revision->getCatalogProducts();
} elseif($_REQUEST['type'] === "exception") {
    $revisionProducts = $revision->getCatalogExceptions();
}
?>
<div class="ListOfElements TwoColumns">
    <?foreach ($revisionProducts as $revisionProduct):?>
        <?if($revisionProduct instanceof PromotionCatalogProduct || $revisionProduct instanceof PromotionCatalogException):?>
            <?$productXmlId = $revisionProduct->getFieldValue('product');?>
            <?$products = CatalogElementModel::getElementList(
                    MySQLWhereElementString::getBinaryOperationString(
                            'XML_ID',
                            MySQLWhereElementString::OPERATOR_B_EQUAL,
                            $productXmlId
                    )
            )?>
            <?$product = array_shift($products);?>
            <div class="ElementBlock">
                <div class="ElementBlockContent" tableId="<?=$revisionProduct->getId()?>" type="<?=$_REQUEST['type']?>">
                    <?if($product instanceof CatalogElementModel):?>
                        <div class="content"><?=$product->getFieldValue("NAME")?></div>
                    <?else:?>
                        <div class="content"><?=$productXmlId?></div>
                    <?endif;?>
                    <div class="DellButton"></div>
                </div>
            </div>
        <?endif;?>
    <?endforeach;?>
</div>