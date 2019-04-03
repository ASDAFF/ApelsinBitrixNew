<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

/* ОБЩАЯ CSS */
$this->addExternalCss("/apls_lib/css/AdminCSS.css");

/* JQUERY */
$this->addExternalJs("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js");

/* JQUERY UI */
$this->addExternalJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
$this->addExternalCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
$this->addExternalCss("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");

/* COMPONENT_FOLDER */
$componentFolder = $this->getComponent()->getPath() . "/";

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/model/GeolocationRegionsContacts.php";
?>
<pre>
<!--    --><?// var_dump($arResult["REGIONS"])?>
</pre>
<div id="MainContactsWrapper" class="AdminContactsWrapper" templateFolder="<?= $templateFolder ?>">
    <div class="contactsSortList">
        <? foreach ($arResult["REGIONS"] as $regionId => $region): ?>
            <div class="contactsCityElement <?= $regionId ?>">
                <div class="contactsSortListElement" regionId="<?= $regionId ?>">
                    <div class="sort-handle"></div>
                    <div class="regionTitle"><?= $region['NAME'] ?></div>
                    <div class="regionCoordsValues">
                        <? if (isset($region['longitude'])) {
                            ?><div class="regionLongitude" coordsValue="<?=$region['longitude']?>"><span>Дологота: </span><?=$region['longitude']?></div><?
                        } else {
                            ?><div class="regionLongitude" coordsValue="0"><span>Дологота: </span>0</div><?
                        } ?>
                        <? if ($region['latitude'] != NULL) {
                            ?><div class="regionLatitude" coordsValue="<?=$region['latitude']?>"><span>Широта: </span><?=$region['latitude']?></div><?
                        } else {
                            ?><div class="regionLatitude" coordsValue="0"><span>Широта: </span>0</div><?
                        } ?>
                        <? if ($region['zoom'] != NULL) {
                            ?><div class="regionZoom" coordsValue="<?=$region['zoom']?>"><span>Зум: </span><?=$region['zoom']?></div><?
                        } else {
                            ?><div class="regionZoom" coordsValue="0"><span>Зум: </span>0</div><?
                        } ?>
                        <div class="regionChange">Изменить</div>
                    </div>
                    <div class="header-swap">
                        <i class="fa fa-plus"></i>
                        <i class="fa fa-minus"></i>
                    </div>
                </div>
                <div id="<?= $regionId ?>" class="shopsSortList">
                    <div class="addShop">
                        <i class="fa fa-plus"></i>
                    </div>
                    <? foreach ($region["SHOPS"] as $shopId => $shop): ?>
                        <div class="shopsSortListElement <?= $shopId ?>" shopId="<?= $shopId ?>" sort="<?=$shop['SORT']?>">
                            <div class="sort-handle"><i class="fa fa-align-justify"></i></div>
                            <div class="shopElementImgs">
                                <? if(isset($shop['B_IMG'])):?>
                                    <div class="shopElementB_Img" imgValue="<?=$shop['B_IMG']?>"><img src="<?=CFile::GetPath($shop['B_IMG'])?>"></div>
                                <?endif;?>
                                <? if(isset($shop['S_IMG'])):?>
                                    <div class="shopElementS_Img" imgValue="<?=$shop['S_IMG']?>"><img src="<?=CFile::GetPath($shop['S_IMG'])?>"></div>
                                <?endif;?>
                            </div>
                            <div class="shopElementName">
                                <div class="shop_element_title"><?= $shop["NAME"] ?></div>
                                <div class="shop_element_address"><?= $shop["ADDRESS"] ?></div>
                                <div class="shop_element_mail"><?= $shop["EMAIL"] ?></div>
                                <? if (isset($shop["PHONE_NUMBER_1"])): ?>
                                    <div class="shop_element_phone">
                                        <div class="shop_element_phone1"><?= $shop["PHONE_NUMBER_1"] ?></div>
                                        <div class="shop_element_addphone1"><?= $shop["ADDITIONAL_NUMBER_1"] ?></div>
                                    </div>
                                <? endif; ?>
                                <? if (isset($shop["PHONE_NUMBER_1"])): ?>
                                    <div class="shop_element_phone">
                                        <div class="shop_element_phone2"><?= $shop["PHONE_NUMBER_2"] ?></div>
                                        <div class="shop_element_addphone2"><?= $shop["ADDITIONAL_NUMBER_2"] ?></div>
                                    </div>
                                <? endif; ?>
                            </div>
                            <div class="shopElementFeature">
                                <? if ($shop["CREDIT_DEPARTMENT"] !== "0"): ?>
                                    <div class="featureCred">Кредитный отдел</div>
                                <? endif; ?>
                                <? if ($shop["WHOLESALE_DEPARTMENT"] !== "0"): ?>
                                    <div class="featureW-sale">Оптовый отдел</div>
                                <? endif; ?>
                                <? if ($shop["RECEIPT_OF_GOODS"] !== "0"): ?>
                                    <div class="featureR-of_goods">Пункт выдачи</div>
                                <? endif; ?>
                                <? if ($shop["ROUND_THE_CLOCK"] !== "0"): ?>
                                    <div class="featurer-the_clock">24 часа</div>
                                <? endif; ?>
                            </div>
                            <div class="shopElementTime">
                                <div class="elementTimeClock elementTimeClockMon">
                                    <div class="elementTimeClockHeader">Пн</div>
                                    <div class="elementTimeClockStart"><?= $shop["MON_START"] ?></div>
                                    <div class="elementTimeClockStop"><?= $shop["MON_STOP"] ?></div>
                                </div>
                                <div class="elementTimeClock elementTimeClockTue">
                                    <div class="elementTimeClockHeader">Вт</div>
                                    <div class="elementTimeClockStart"><?= $shop["TUE_START"] ?></div>
                                    <div class="elementTimeClockStop"><?= $shop["TUE_STOP"] ?></div>
                                </div>
                                <div class="elementTimeClock elementTimeClockWen">
                                    <div class="elementTimeClockHeader">Ср</div>
                                    <div class="elementTimeClockStart"><?= $shop["WED_START"] ?></div>
                                    <div class="elementTimeClockStop"><?= $shop["WED_STOP"] ?></div>
                                </div>
                                <div class="elementTimeClock elementTimeClockThu">
                                    <div class="elementTimeClockHeader">Чт</div>
                                    <div class="elementTimeClockStart"><?= $shop["THU_START"] ?></div>
                                    <div class="elementTimeClockStop"><?= $shop["THU_STOP"] ?></div>
                                </div>
                                <div class="elementTimeClock elementTimeClockFri">
                                    <div class="elementTimeClockHeader">Пт</div>
                                    <div class="elementTimeClockStart"><?= $shop["FRI_START"] ?></div>
                                    <div class="elementTimeClockStop"><?= $shop["FRI_STOP"] ?></div>
                                </div>
                                <div class="elementTimeClock elementTimeClockSat">
                                    <div class="elementTimeClockHeader">Сб</div>
                                    <div class="elementTimeClockStart"><?= $shop["SAT_START"] ?></div>
                                    <div class="elementTimeClockStop"><?= $shop["SAT_STOP"] ?></div>
                                </div>
                                <div class="elementTimeClock elementTimeClockSun">
                                    <div class="elementTimeClockHeader">Вс</div>
                                    <div class="elementTimeClockStart"><?= $shop["SUN_START"] ?></div>
                                    <div class="elementTimeClockStop"><?= $shop["SUN_STOP"] ?></div>
                                </div>
                            </div>
                            <div class="shopElementCoords">
                                <div class="elementCoordsSet elementCoordsLong">
                                    <div class="elementCoordsSetHeader">Долгота</div>
                                    <div class="elementCoordsSetValue"><?= $shop["LONGITUDE"] ?></div>
                                </div>
                                <div class="elementCoordsSet elementCoordsLat">
                                    <div class="elementCoordsSetHeader">Широта</div>
                                    <div class="elementCoordsSetValue"><?= $shop["LATITUDE"] ?></div>
                                </div>
                                <div class="elementCoordsSet elementCoordsZoom">
                                    <div class="elementCoordsSetHeader">Зум</div>
                                    <div class="elementCoordsSetValue"><?= $shop["ZOOM"] ?></div>
                                </div>
                            </div>
                            <div class="shopElementSetting">
                                <div class="shopElementBtnUpd">Изменить</div>
                                <div class="shopElementBtnDel">Удалить</div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>
