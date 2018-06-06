<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
$regions = PromotionRegionModel::getElementList();
$url = $request->getPost("arParams");
?>
<div class="CityChangeList">
    <?foreach ($regions as $region):?>
        <a class="CityChangeElement" href="<?=$url?><?=$region->getFieldValue("alias")?>">
            <?if ($region instanceof PromotionRegionModel):?>
                <?=$region->getFieldValue("region")?>
            <?endif;?>
        </a>
    <?endforeach;?>
</div>