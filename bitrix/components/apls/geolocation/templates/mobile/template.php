<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
?>

<div id="geolocation" class="geolocation">
    <a id="geolocationChangeCity" class="geolocation__link" href="javascript:void(0);">
        <i class="fa fa-map-marker" aria-hidden="true"></i>
        <span class="geolocation__value">
            <?=(!empty($arParams["GEOLOCATION_REGION_NAME"]) ? $arParams["GEOLOCATION_REGION_NAME"] : Loc::getMessage("GEOLOCATION_POSITIONING"));?>
        </span>
    </a>
</div>
<div class="telephone">8 (800) 550-54-43</div>
<!--<div class="email">info@apelsin.ru</div>-->
<script type="text/javascript">
    BX.message({
        GEOLOCATION_POPUP_WINDOW_TITLE: "<?=Loc::getMessage('GEOLOCATION_POPUP_WINDOW_TITLE')?>",
        GEOLOCATION_COMPONENT_TEMPLATE: "<?=$this->GetFolder();?>",
        GEOLOCATION_COMPONENT_PATH: "<?=$this->__component->__path?>",
        GEOLOCATION_PARAMS: <?=CUtil::PhpToJSObject($arParams["PARAMS_STRING"])?>,
    });

    BX.bind(BX("geolocationChangeCity"), "click", BX.delegate(BX.CityChange, BX));
</script>
