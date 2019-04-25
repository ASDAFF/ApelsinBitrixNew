<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include_once($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/APLSContactsDateTimeTable.php");
$APPLICATION->SetTitle('Адреса магазинов в городе ' . $arResult["regionName"]);
//echo "<pre>";
//var_dump($arResult);
//echo "</pre>";
$currentTime = new DateTime(date("G:i"));
$thisDay = date(D);
?>
    <div class="contacts_wrapper"
         highload_shops_name="ContactsBuildings"
         highload_region_name="ContactsRegions"
         templateFolder="<?= $templateFolder ?>"
         longitude="<?= $arResult["longitude"] ?>"
         latitude="<?= $arResult["latitude"] ?>"
         zoom="<?= $arResult["zoom"] ?>">
        <div class="holidayWork">
            <a href="/contacts/?holiday=Y">Время работы в выходные и праздничные дни</a>
        </div>
        <div class="contact_settings">
            <div id="mapBlock" class="contacts_settings_map contacts_settings">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <strong>Карта</strong>
            </div>
            <div id="contactsShopTable" class="contacts_edit_list contacts_settings active">
                <i class="fa fa-bars" aria-hidden="true"></i>
                <strong>Список</strong>
            </div>
        </div>
        <div class="contactsShopTable changeBlock">
            <div class="contactsShopTableHeader">
                <div class="contactsShopTableHeader_element col-1">Адрес и контакты магазина</div>
                <div class="contactsShopTableHeader_element col-2">Время работы</div>
                <div class="contactsShopTableHeader_element col-3">Обратная связь</div>
            </div>
            <div class="contactsShopTable">
                <? foreach ($arResult["SHOPS"] as $key => $shop): ?>
                    <div class="contactsShopElement"
                         id="<?= $key ?>"
                         longitude="<?= $shop["longitude"] ?>"
                         latitude="<?= $shop["latitude"] ?>"
                         zoom="<?= $shop["zoom"] ?>">
                        <div class="contactsShopElement_img" bigImg="<?=CFile::GetPath($shop["b_img"])?>">
                            <img src="<?= CFile::GetPath($shop["s_img"]) ?>">
                        </div>
                        <div class="contactsShopElement_name">
                            <div class="contactsShopElement_name_header"><?= $shop["name"] ?></div>
                            <div class="contactsShopElement_name_address"><?= $shop["address"] ?></div>
                            <div class="contactsShopElement_name_email"><i
                                        class="fa fa-envelope-o"></i> <?= $shop["email"] ?></div>
                            <div class="contactsShopElement_name_phone1">
                                <div class="contactsShopElement_name_phone_number_1"><i
                                            class="fa fa-phone"></i> <?= $shop["phone_number_1"] ?></div>
                                <div class="contactsShopElement_additional_number_1"><?= $shop["additional_number_1"] ?></div>
                            </div>
                            <? if ($shop["phone_number_2"]): ?>
                                <div class="contactsShopElement_name_phone2">
                                    <div class="contactsShopElement_name_phone_number_2"><i
                                                class="fa fa-phone"></i> <?= $shop["phone_number_2"] ?></div>
                                    <div class="contactsShopElement_additional_number_2"><?= $shop["additional_number_2"] ?></div>
                                </div>
                            <? endif; ?>
                        </div>
                        <div class="contactsShopElement_features">
                            <? if ($shop["credit_department"]): ?>
                                <div class="contactsShopElement_features_icon"><img alt="Кредитный отдел"
                                                                                    title="Кредитный отдел"
                                                                                    src="<?= $templateFolder ?>/icon/shop_procent.svg">
                                </div>
                            <? endif; ?>
                            <? if ($shop["wholesale_department"]): ?>
                                <div class="contactsShopElement_features_icon"><img alt="Оптовый отдел"
                                                                                    title="Оптовый отдел"
                                                                                    src="<?= $templateFolder ?>/icon/shop_optov.svg">
                                </div>
                            <? endif; ?>
                            <? if ($shop["receipt_of_goods"]): ?>
                                <div class="contactsShopElement_features_icon"><img alt="Пункт выдачи"
                                                                                    title="Пункт выдачи"
                                                                                    src="<?= $templateFolder ?>/icon/shop_korzina.svg">
                                </div>
                            <? endif; ?>
                            <? if ($shop["round_the_clock"]): ?>
                                <div class="contactsShopElement_features_icon"><img alt="24 часа" title="24 часа"
                                                                                    src="<?= $templateFolder ?>/icon/shop_chaci.svg">
                                </div>
                            <? endif; ?>
                        </div>
                        <div class="contactsShopElement_workTime">
                            <? if ($currentTime >= new DateTime(date($shop["time_table"][strtoupper($thisDay)]["start"])) && $currentTime <= new DateTime(date($shop["time_table"][strtoupper($thisDay)]["_stop"]))): ?>
                                <div class="APLS_contacts_buildings_time_Y"><i class="fa fa-clock-o"></i> Сейчас открыто
                                </div>
                            <? else: ?>
                                <div class="APLS_contacts_buildings_time_N"><i class="fa fa-clock-o"></i> Закрыто</div>
                            <? endif; ?>
                            <div class="contactsShopElement_workTime_data">
                                <?= getDateTimeTable($shop["time_table"]) ?>
                            </div>
                        </div>
                        <div class="contactsShopElement_callBack">
                            <div id="feedback_dir_<?= $key ?>"
                                 shopName="<?= $shop["name"] ?>"
                                 mail="<?= $shop["email"] ?>"
                                 class="shop_element_feedback_dir"
                                 title="Написать управляющему магазина">Оставить отзыв
                            </div>
                            <div shopId="<?= $rowData["ID"] ?>" class="shop_element_feedback_map_scheme">Схема проезда
                            </div>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
        <div id="ymap_contacts" class="mapBlock changeBlock"></div>
    </div>
<?
function getDateTimeTable($timeTable)
{
    $nameOfDays = [
        "TUE" => "ВТ",
        "WED" => "СР",
        "THU" => "ЧТ",
        "FRI" => "ПТ",
        "SAT" => "СБ",
        "SUN" => "ВС",
    ];
    foreach ($timeTable as $day => $time) {
        if (!isset($timeStart) && !isset($timeStop)) {
            $timeStart = $time["start"];
            $timeStop = $time["stop"];
        } else {
            if ($timeStart == $time["start"] && $timeStop == $time["stop"]) {
                $html = '<div class="dayRow">';
                $html .= '<div class="dayName">' . "ПН - " . $nameOfDays[$day] . '</div>';
                $html .= '<div class="timeStart">' . substr($time["start"], 0, -3) . ' - </div>';
                $html .= '<div class="timeStart">' . substr($time["stop"], 0, -3) . '</div>';
                $html .= '</div>';
            } elseif (($time["start"] == "00:00:00" || $time["stop"] == "00:00:00") || ($time["start"] == "" || $time["stop"] == "")) {
                $html .= '<div class="dayRow">';
                $html .= '<div class="dayName">' . $nameOfDays[$day] . '</div>';
                $html .= '<div class="dayOff">Выходной</div>';
                $html .= '</div>';
            } else {
                $html .= '<div class="dayRow">';
                $html .= '<div class="dayName">' . $nameOfDays[$day] . '</div>';
                $html .= '<div class="timeStart">' . substr($time["start"], 0, -3) . ' - </div>';
                $html .= '<div class="timeStart">' . substr($time["stop"], 0, -3) . '</div>';
                $html .= '</div>';
            }
        }
    }
    return $html;
}