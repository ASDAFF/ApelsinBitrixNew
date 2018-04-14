<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageTypeModel.php";
$orderBy = new MySQLOrderByString();
$orderBy->add("type",MySQLOrderByString::ASC);
$types = PromotionImageTypeModel::getElementList(null,null,null, $orderBy);?>
<div class="ListOfElements TwoColumns">
    <?foreach ($types as $type):?>
        <?if($type instanceof PromotionImageTypeModel):?>
            <div class="ElementBlock">
                <div class="ElementBlockContent">
                    <div class="content"><?=$type->getFieldValue('type')?> (<?=$type->getFieldValue('alias')?>)</div>
                    <div class="DellButton" typeId="<?=$type->getId()?>"></div>
                </div>
            </div>
        <?endif;?>
    <?endforeach;?>
</div>