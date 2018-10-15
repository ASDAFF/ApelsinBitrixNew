<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/GeolocationRegionHelper.php";

$regions = PromotionRegionModel::getElementList();
$url = $request->getPost("arParams");
$regionId = GeolocationRegionHelper::getGeolocationRegionId();
?>
<div class="CityChangeList">
    <?foreach ($regions as $region):?>
        <?if($regionId == $region->getId()):?>
            <a class="CityChangeElement" href="<?=$url?>">
                <?if ($region instanceof PromotionRegionModel):?>
                    <?=$region->getFieldValue("region")?> (ваш регион)
                <?endif;?>
            </a>
        <?else:?>
            <a class="CityChangeElement" href="<?=$url?><?=$region->getFieldValue("alias")?>">
                <?if ($region instanceof PromotionRegionModel):?>
                    <?=$region->getFieldValue("region")?>
                <?endif;?>
            </a>
        <?endif;?>
    <?endforeach;?>
</div>