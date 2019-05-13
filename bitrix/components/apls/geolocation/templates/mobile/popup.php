<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

$orderByObj = new MySQLOrderByString();
$orderByObj->add("sort",  MySQLOrderByString::ASC);
$regions = PromotionRegionModel::getElementList(null, null, null, $orderByObj);
?>
<div class="geolocation-region-list">
    <?foreach ($regions as $region):?>
        <?if($region instanceof PromotionRegionModel):?>
            <div
                class="geolocation-region-item"
                regionId="<?=$region->getId()?>"
            >
                <?=$region->getFieldValue('region')?>
            </div>
        <?endif;?>
    <?endforeach;?>
</div>
<div class="geolocation-region-message"></div>

<script type="text/javascript">
    function geolocationChangeRegion() {
        var data = [];
        data["componentFolder"] = BX.message("GEOLOCATION_COMPONENT_PATH");
        data["regionAlias"] = $(this).attr('regionAlias');
        data["regionName"] = $(this).attr('regionName');
        data["regionId"] = $(this).attr('regionId');
        BX.ajax({
            url: data["componentFolder"] + "/ajax.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
//                $(".geolocation-region-message").html(rezult);
                window.location.reload();
            },
            onfailure: function (rezult) {
                $(".geolocation-region-message").html(
                    "Что-то пошло не так, приносим свои извинения. " +
                    "Если ошибка повторится, свяжитесь с администрацией сайта.");
            },
        });
    }
    $(".geolocation-region-item").click(geolocationChangeRegion);
</script>