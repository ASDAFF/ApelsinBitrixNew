<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/model/GeolocationRegionsContacts.php";


$textError = '';
if ($_REQUEST['name'] == '') {
    $textError = 'true';
}else {
    $newShop = new PromotionRegionModel($_REQUEST['regionId']);
    $shopId = $newShop->addContacts(
        $_REQUEST['name'],
        array(
            'address'=>$_REQUEST['address'],
            'email'=>$_REQUEST['mail'],
            'phone_number_1'=>$_REQUEST['phone1'],
            'additional_number_1'=>$_REQUEST['addphone1'],
            'phone_number_2'=>$_REQUEST['phone2'],
            'additional_number_2'=>$_REQUEST['addphone2'],
            'mon_start'=>$_REQUEST['monStart'],
            'mon_stop'=>$_REQUEST['monStop'],
            'tue_start'=>$_REQUEST['tueStart'],
            'tue_stop'=>$_REQUEST['tueStop'],
            'wed_start'=>$_REQUEST['wedStart'],
            'wed_stop'=>$_REQUEST['wedStop'],
            'thu_start'=>$_REQUEST['thuStart'],
            'thu_stop'=>$_REQUEST['thuStop'],
            'fri_start'=>$_REQUEST['friStart'],
            'fri_stop'=>$_REQUEST['friStop'],
            'sat_start'=>$_REQUEST['satStart'],
            'sat_stop'=>$_REQUEST['satStop'],
            'sun_start'=>$_REQUEST['sunStart'],
            'sun_stop'=>$_REQUEST['sunStop'],
            'credit_department'=>$_REQUEST['featureCred'],
            'wholesale_department'=>$_REQUEST['featureW-sale'],
            'receipt_of_goods'=>$_REQUEST['featurer-of_goods'],
            'round_the_clock'=>$_REQUEST['featureR-the_clock'],
            'longitude'=>$_REQUEST['lond'],
            'latitude'=>$_REQUEST['lat'],
            'zoom'=>$_REQUEST['zoom'],
        )
    );

    $updateShop = new GeolocationRegionsContacts($shopId);
    $html = '';
    $html .= '<div class="shopsSortListElement '.$shopId.'">';
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
    $html .= '</div>';
}
$error = array(
    "success" => array(
        "error" => $textError,
        "html" => $html,
        "regionId" => $_REQUEST['regionId'],
    )
);

echo Bitrix\Main\Web\Json::encode($error);