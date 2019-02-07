<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/model/GeolocationRegionsContacts.php";

$textError = '';
if ($_REQUEST['name'] == '') {
    $textError = 'true';
}else {
    $updateShop = new GeolocationRegionsContacts($_REQUEST['shopid']);

    $html = '';
    $html .= '<div class="sort-handle"><i class="fa fa-align-justify"></i></div>';
    $html .= '<div class="shopElementName">';
    $html .= '<div class="shop_element_title">'.$updateShop->getFieldValue("name").'</div>';
    $html .= '<div class="shop_element_address">'.$updateShop->getFieldValue("address").'</div>';
    $html .= '<div class="shop_element_mail">'.$updateShop->getFieldValue("email").'</div>';
    $html .= '<div class="shop_element_phone">';
    $html .= '<div class="shop_element_phone1">'.$updateShop->getFieldValue("phone_number_1").'</div>';
    $html .= '<div class="shop_element_addphone1">'.$updateShop->getFieldValue("additional_number_1").'</div>';
    $html .= '</div>';
    $html .= '<div class="shop_element_phone">';
    $html .= '<div class="shop_element_phone2">'.$updateShop->getFieldValue("phone_number_2").'</div>';
    $html .= '<div class="shop_element_addphone2">'.$updateShop->getFieldValue("additional_number_2").'</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div class="shopElementFeature">';
    if ($updateShop->getFieldValue("credit_department") == '1') {
        $html .= '<div class="featureCred">Кредитный отдел</div>';
    }
    if ($updateShop->getFieldValue("wholesale_department") == '1') {
        $html .= '<div class="featureCred">Оптовый отдел</div>';
    }
    if ($updateShop->getFieldValue("receipt_of_goods") == '1') {
        $html .= '<div class="featureCred">Пункт выдачи</div>';
    }
    if ($updateShop->getFieldValue("round_the_clock") == '1') {
        $html .= '<div class="featureCred">24 часа</div>';
    }
    $html .= '</div>';

    $html .= '<div class="shopElementTime">';
    $html .= '<div class="elementTimeClock elementTimeClockMon">';
    $html .= '<div class="elementTimeClockHeader">Пн</div>';
    $html .= '<div class="elementTimeClockStart">'.$updateShop->getFieldValue("mon_start").'</div>';
    $html .= '<div class="elementTimeClockStop">'.$updateShop->getFieldValue("mon_stop").'</div>';
    $html .= '</div>';
    $html .= '<div class="elementTimeClock elementTimeClockTue">';
    $html .= '<div class="elementTimeClockHeader">Вт</div>';
    $html .= '<div class="elementTimeClockStart">'.$updateShop->getFieldValue("tue_start").'</div>';
    $html .= '<div class="elementTimeClockStop">'.$updateShop->getFieldValue("tue_stop").'</div>';
    $html .= '</div>';
    $html .= '<div class="elementTimeClock elementTimeClockWed">';
    $html .= '<div class="elementTimeClockHeader">Ср</div>';
    $html .= '<div class="elementTimeClockStart">'.$updateShop->getFieldValue("wed_start").'</div>';
    $html .= '<div class="elementTimeClockStop">'.$updateShop->getFieldValue("wed_stop").'</div>';
    $html .= '</div>';
    $html .= '<div class="elementTimeClock elementTimeClockThu">';
    $html .= '<div class="elementTimeClockHeader">Чт</div>';
    $html .= '<div class="elementTimeClockStart">'.$updateShop->getFieldValue("thu_start").'</div>';
    $html .= '<div class="elementTimeClockStop">'.$updateShop->getFieldValue("thu_stop").'</div>';
    $html .= '</div>';
    $html .= '<div class="elementTimeClock elementTimeClocFri">';
    $html .= '<div class="elementTimeClockHeader">Пт</div>';
    $html .= '<div class="elementTimeClockStart">'.$updateShop->getFieldValue("fri_start").'</div>';
    $html .= '<div class="elementTimeClockStop">'.$updateShop->getFieldValue("fri_stop").'</div>';
    $html .= '</div>';
    $html .= '<div class="elementTimeClock elementTimeClockSat">';
    $html .= '<div class="elementTimeClockHeader">Сб</div>';
    $html .= '<div class="elementTimeClockStart">'.$updateShop->getFieldValue("sat_start").'</div>';
    $html .= '<div class="elementTimeClockStop">'.$updateShop->getFieldValue("sat_stop").'</div>';
    $html .= '</div>';
    $html .= '<div class="elementTimeClock elementTimeClockSun">';
    $html .= '<div class="elementTimeClockHeader">Вс</div>';
    $html .= '<div class="elementTimeClockStart">'.$updateShop->getFieldValue("sun_start").'</div>';
    $html .= '<div class="elementTimeClockStop">'.$updateShop->getFieldValue("sun_stop").'</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div class="shopElementCoords">';
    $html .= '<div class="elementCoordsSet elementCoordsLong">';
    $html .= '<div class="elementCoordsSetHeader">Долгота</div>';
    $html .= '<div class="elementCoordsSetValue">'.$updateShop->getFieldValue("longitude").'</div>';
    $html .= '</div>';
    $html .= '<div class="elementCoordsSet elementCoordsLat">';
    $html .= '<div class="elementCoordsSetHeader">Широта</div>';
    $html .= '<div class="elementCoordsSetValue">'.$updateShop->getFieldValue("latitude").'</div>';
    $html .= '</div>';
    $html .= '<div class="elementCoordsSet elementCoordsZoom">';
    $html .= '<div class="elementCoordsSetHeader">Зум</div>';
    $html .= '<div class="elementCoordsSetValue">'.$updateShop->getFieldValue("zoom").'</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div class="shopElementSetting">';
    $html .= '<div class="shopElementBtnUpd">Изменить</div>';
    $html .= '<div class="shopElementBtnDel">Удалить</div>';
    $html .= '</div>';

}

$error = array(
    "success" => array(
        "error" => $textError,
        "html" => $html,
        "shopId" => $_REQUEST['shopid'],
    )
);

echo Bitrix\Main\Web\Json::encode($error);