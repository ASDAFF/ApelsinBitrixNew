<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include_once($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/APLSContactsDateTimeTable.php");
?>
<div class="contacts_wrapper"
     highload_shops_name = "ContactsBuildings"
     highload_region_name = "ContactsRegions"
     templateFolder="<?=$templateFolder?>">
    <div class="APLS_contacts_regions">
        <div class="APLS_contacts_region_list">
        <?
        $currentTime = new DateTime(date("G:i"));
        $thisDay = date(D);
        foreach ($arResult['region'] as $regionData):
            ?><div class="APLS_contacts_regions_block" regionID = "<?=$regionData["ID"]?>"><?=$regionData["UF_REGION"]?></div><?
        endforeach;?>
        </div>
        <div class="contact_settings">
            <div id="hideMap" class="contacts_settings_map">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <strong>Карта</strong>
            </div>
            <div id="hideList" class="contacts_edit_list">
                <i class="fa fa-bars" aria-hidden="true"></i>
                <strong>Список</strong>
            </div>
        </div>
    </div>
<!--    <div class="info_celebrate_time"><span>ВНИМАНИЕ!</span> Время работы магазинов в праздничные дни может отличаться. <a href="/contacts/shops/?holiday=Y">Смотреть график...</a></div>-->
    <div class="cities_list">
        <div class="shop_element_header" >
            <div class="element_header_name">Адрес и контакты магазина</div>
            <div class="element_header_feature">Особенности</div>
            <div class="element_header_time">Время работы</div>
            <div class="element_header_feedback">Обратная связь</div>
        </div>
        <div class="shop_element_list">
        <?foreach ($arResult["shops"] as $rowData):?>
            <div class="shop_element" id="shop_element_<?=$rowData["ID"]?>" regionId="<?=$rowData["UF_REGION"]?>">
                <div class="shop_element_name">
                    <div class="shop_element_title"><?= $rowData["UF_SHORT_ADDRESS"] ?></div>
                    <div class="shop_element_address"><?= $rowData["UF_LONG_ADDRESS"] ?></div>
                    <div class="shop_element_mail"><i class="fa fa-envelope-o"></i> <a href="mailto:<?= $rowData["UF_EMAIL"] ?>"><?= $rowData["UF_EMAIL"] ?></a></div>
                    <div class="shop_element_phone1"><i class="fa fa-phone"></i> <?= $rowData["UF_PHONE_NUMBER_1"] ?></div>
                    <?if ($rowData["UF_PHONE_NUMBER_2"] != ""):?>
                        <div class="shop_element_phone2"><i class="fa fa-phone"></i> <?= $rowData["UF_PHONE_NUMBER_2"] ?></div>
                    <?endif;?>
                </div>
                <div class="shop_element_feature">
                    <div class="shop_element_feature_table">
                        <?if($rowData["UF_CREDIT_DEPARTMENT"] == "1"):?>
                            <div class="shop_element_icon"><img src="<?=$templateFolder?>/icon/shop_procent.svg"> Кредитный отдел</div>
                        <?endif;?>
                        <?if($rowData["UF_WHOLESALE_DEP"] == "1"):?>
                            <div class="shop_element_icon"><img src="<?=$templateFolder?>/icon/shop_optov.svg"> Оптовый отдел</div>
                        <?endif;?>
                        <?if($rowData["UF_DELIVERY_GOOD"] == "1"):?>
                            <div class="shop_element_icon"><img src="<?=$templateFolder?>/icon/shop_korzina.svg"> Пункт выдачи</div>
                        <?endif;?>
                        <?if($rowData["UF_24_HOUR"] == "1"):?>
                            <div class="shop_element_icon"><img src="<?=$templateFolder?>/icon/shop_chaci.svg"> 24 часа</div>
                        <?endif;?>
                        <?if($rowData["UF_CREDIT_DEPARTMENT"] == "0"&& $rowData["UF_WHOLESALE_DEP"] == "0" && $rowData["UF_DELIVERY_GOOD"] == "0" &&$rowData["UF_24_HOUR"] == "0"):?>
                            <div class="shop_element_empty">-</div>
                        <?endif;?>
                    </div>
                </div>
                <div class="shop_element_time">
                    <?if ($currentTime >= new DateTime(date($rowData["UF_" . strtoupper($thisDay) . "_S"])) && $currentTime <= new DateTime(date($rowData["UF_" . strtoupper($thisDay) . "_E"]))):?>
                        <div class="APLS_contacts_buildings_time_Y"><i class="fa fa-clock-o"></i> Сейчас открыто</div>
                    <?else:?>
                        <div class="APLS_contacts_buildings_time_N"><i class="fa fa-clock-o"></i> Закрыто</div>
                    <?endif;?>
                    <div class="ContactData">
                        <?
                        $timeTable = new APLSContactsDateTimeTable($rowData['TimeTable']);
                        $timeTable->get();
                        ?>
                    </div>
                </div>
                <div class="shop_element_feedback">
                    <div id="feedback_dir_<?=$rowData['ID']?>"
                         shopName="<?=$rowData["UF_SHORT_ADDRESS"]?>"
                         mail = "<?=$rowData["UF_EMAIL"]?>"
                         class="shop_element_feedback_dir"
                         title="Написать управляющему магазина">Оставить отзыв
                    </div>
                    <div shopId="<?=$rowData["ID"]?>" class="shop_element_feedback_map_scheme">Схема проезда</div>
<!--                    <div id="feedback_shopmap_--><?//=$rowData['ID']?><!--" class="shop_element_feedback_shopmap" title="Показать карту магазина">Карта магазина</div>-->
                </div>
            </div>
        <?endforeach;?>
        </div>
    </div>
    <div id="ymap_contacts" class="contacts_map"></div>
</div>
<?
$count = max(array_keys($arResult['shops']));
$coords = '[';
foreach ($arResult['shops'] as $key=>$shop) {
    if ($key !== $count) {
        $coords .= '['.$arResult['shops'][$key]['UF_COORDS'].'],';
    } else {
        $coords .= '['.$arResult['shops'][$key]['UF_COORDS'].']';
    }
}
$coords .= ']';
?>

<script type="text/javascript">
    ymaps.ready(init);
    var myMap, myPlacemark;
    var folder = $('.contacts_wrapper').attr('templatefolder');
    function init() {
        myMap = new ymaps.Map("ymap_contacts", {
            center: [<?=$arResult["shops"][1]['UF_COORDS']?>],
            zoom: <?=$arResult["shops"][1]['UF_ZOOM']?>
        });
        myCollection = new ymaps.GeoObjectCollection({}, {});
        var coords = <?=$coords?>;
        <? for ($i = 0; $i < $count; $i++) :?>
                myCollection.add(new ymaps.Placemark(coords[<?=$i?>],{
                    hintContent:"<?=$arResult['shops'][$i+1]['UF_SHORT_ADDRESS']?>",
                    balloonContent:"<?=$arResult['shops'][$i+1]['UF_LONG_ADDRESS']?>"
                  }, {
                        iconLayout: 'default#image',
                        iconImageHref: folder+'/icon/label.svg',
                        iconImageSize: [40, 52]
                     }));
            <?endfor;?>
//        for (var i = 1; i < coords.length; i++) {
//            myCollection.add(new ymaps.Placemark(coords[i],{
//                hintContent: '<?//=$arResult['shops'][1]['UF_SHORT_ADDRESS']?>//',
//                balloonContent: '<?//=$arResult['shops'][1]['UF_LONG_ADDRESS']?>//'
//            }, {
//                iconLayout: 'default#image',
//                iconImageHref: folder+'/icon/label.svg',
//                iconImageSize: [40, 52]
//            }));
//        }
        myMap.geoObjects.add(myCollection);
    }
</script>