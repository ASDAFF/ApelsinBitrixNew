<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$html = '';
    $html.='<div class="shopElementName">';
        $html.='<div class="shopElementNameTitle">Адреса и контакты</div>';
        $html.='<div class="shop_element_title"><input type="text" value="'.$_REQUEST["name"].'"></div>';
        $html.='<div class="shop_element_address"><input type="text" value="'.$_REQUEST["address"].'"></div>';
        $html.='<div class="shop_element_mail"><input type="text" value="'.$_REQUEST["mail"].'"></div>';
        $html.='<div class="shop_element_phone">';
            $html.='<div class="shop_element_phone1"><input type="text" value="'.$_REQUEST["phone1"].'"></div>';
            $html.='<div class="shop_element_addphone1"><input type="text" value="'.$_REQUEST["addphone1"].'"></div>';
        $html.='</div>';
        $html.='<div class="shop_element_phone">';
            $html.='<div class="shop_element_phone2"><input type="text" value="'.$_REQUEST["phone2"].'"></div>';
            $html.='<div class="shop_element_addphone2"><input type="text" value="'.$_REQUEST["addphone2"].'"></div>';
        $html.='</div>';
        $html.='<div class="shop_element_imgs">';
            $html.='<div class="shop_element_imgs_header">Картинки</div>';
            if ($_REQUEST["b_img"] != 'undefined') {
                $html.='<div class="shop_element_b_img"><div class="shop_element_img_header green">Большая</div><input imgType="b_img" type="file"><div class="shop_element_img_save"><i class="fa fa-floppy-o" aria-hidden="true"></i></div></div>';
            } else {
                $html.='<div class="shop_element_b_img"><div class="shop_element_img_header">Большая</div><input imgType="b_img" type="file"><div class="shop_element_img_save"><i class="fa fa-floppy-o" aria-hidden="true"></i></div></div>';

            }
            if ($_REQUEST["s_img"] != 'undefined') {
                $html.='<div class="shop_element_s_img"><div  class="shop_element_img_header green">Маленькая</div><input imgType="s_img" type="file"><div class="shop_element_img_save"><i class="fa fa-floppy-o" aria-hidden="true"></i></div></div>';
            } else {
                $html.='<div class="shop_element_s_img"><div  class="shop_element_img_header">Маленькая</div><input imgType="s_img" type="file"><div class="shop_element_img_save"><i class="fa fa-floppy-o" aria-hidden="true"></i></div></div>';

            }
        $html.='</div>';
    $html.='</div>';
    $html.='<div class="shopElementFeature">';
        $html.='<div class="shopElementFeatureTitle">Особенности магазина</div>';
        $html.='<div class="featureCred">';
        $html.='<div class="featureCredTitle">Кредитный отдел</div>';
        if ($_REQUEST["featureCred"] == '') {
            $html.='<div><input type="checkbox"></div>';
        } else {
            $html.='<div><input type="checkbox" checked></div>';
        }
    $html.='</div>';
        $html.='<div class="featureW-sale">';
        $html.='<div class="featureW-saleTitle">Оптовый отдел</div>';
        if ($_REQUEST["featureW-sale"] == '') {
            $html.='<div><input type="checkbox" checked></div>';
        } else {
            $html.='<div><input type="checkbox" checked></div>';
        }
    $html.='</div>';
        $html.='<div class="featurer-of_goods">';
        $html.='<div class="featurer-of_goodsTitle">Пункт выдачи</div>';
        if ($_REQUEST["featurer-of_goods"] == '') {
            $html.='<div><input type="checkbox"></div>';
        } else {
            $html.='<div><input type="checkbox" checked></div>';
        }
    $html.='</div>';
        $html.='<div class="featureR-the_clock">';
        $html.='<div class="featureR-the_clockTitle">24 часа</div>';
        if ($_REQUEST["featureR-the_clock"] == '') {
            $html.='<div><input type="checkbox"></div>';
        } else {
            $html.='<div><input type="checkbox" checked></div>';
        }
    $html.='</div>';
    $html.='</div>';
    $html.='<div class="shopElementTime">';
    $html.='<div class="shopElementTimeTitle">Время работы</div>';
    $html.='<div class="elementTimeClock elementTimeClockMon">';
    $html.='<div class="elementTimeClockHeader">Пн</div>';
    $html.='<div class="elementTimeClockStart"><input id="monStart" type="text" value="'.$_REQUEST["monStart"].'"></div>';
    $html.='<div class="elementTimeClockStop"><input id="monStop" type="text" value="'.$_REQUEST["monStop"].'"></div>';
    $html.='</div>';
    $html.='<div class="elementTimeClock elementTimeClockTue">';
    $html.='<div class="elementTimeClockHeader">Вт</div>';
    $html.='<div class="elementTimeClockStart"><input id="tueStart" type="text" value="'.$_REQUEST["tueStart"].'"></div>';
    $html.='<div class="elementTimeClockStop"><input id="tueStop" type="text" value="'.$_REQUEST["tueStop"].'"></div>';
    $html.='</div>';
    $html.='<div class="elementTimeClock elementTimeClockWed">';
    $html.='<div class="elementTimeClockHeader">Ср</div>';
    $html.='<div class="elementTimeClockStart"><input id="wedStart" type="text" value="'.$_REQUEST["wedStart"].'"></div>';
    $html.='<div class="elementTimeClockStop"><input id="wedStop" type="text" value="'.$_REQUEST["wedStop"].'"></div>';
    $html.='</div>';
    $html.='<div class="elementTimeClock elementTimeClockThu">';
    $html.='<div class="elementTimeClockHeader">Чт</div>';
    $html.='<div class="elementTimeClockStart"><input id="thuStart" type="text" value="'.$_REQUEST["thuStart"].'"></div>';
    $html.='<div class="elementTimeClockStop"><input id="thuStop" type="text" value="'.$_REQUEST["thuStop"].'"></div>';
    $html.='</div>';
    $html.='<div class="elementTimeClock elementTimeClockFri">';
    $html.='<div class="elementTimeClockHeader">Пт</div>';
    $html.='<div class="elementTimeClockStart"><input id="friStart" type="text" value="'.$_REQUEST["friStart"].'"></div>';
    $html.='<div class="elementTimeClockStop"><input id="friStop" type="text" value="'.$_REQUEST["friStop"].'"></div>';
    $html.='</div>';
    $html.='<div class="elementTimeClock elementTimeClockSat">';
    $html.='<div class="elementTimeClockHeader">Сб</div>';
    $html.='<div class="elementTimeClockStart"><input id="satStart" type="text" value="'.$_REQUEST["satStart"].'"></div>';
    $html.='<div class="elementTimeClockStop"><input id="satStop" type="text" value="'.$_REQUEST["satStop"].'"></div>';
    $html.='</div>';
    $html.='<div class="elementTimeClock elementTimeClockSun">';
    $html.='<div class="elementTimeClockHeader">Вс</div>';
    $html.='<div class="elementTimeClockStart"><input id="sunStart" type="text" value="'.$_REQUEST["sunStart"].'"></div>';
    $html.='<div class="elementTimeClockStop"><input id="sunStop" type="text" value="'.$_REQUEST["sunStop"].'"></div>';
    $html.='</div>';
    $html.='</div>';
    $html.='<div class="shopElementCoords">';
    $html.='<div class="shopElementCoordsTitle">Координаты магазина</div>';
    $html.='<div class="elementCoordsSet elementCoordsLond">';
    $html.='<div class="elementCoordsSetHeader">Долгота</div>';
    $html.='<div class="elementCoordsSetValue"><input id="long" type="text" value="'.$_REQUEST["lond"].'"></div>';
    $html.='</div>';
    $html.='<div class="elementCoordsSet elementCoordsLat">';
    $html.='<div class="elementCoordsSetHeader">Широта</div>';
    $html.='<div class="elementCoordsSetValue"><input id="lat" type="text" value="'.$_REQUEST["lat"].'"></div>';
    $html.='</div>';
    $html.='<div class="elementCoordsSet elementCoordsZoom">';
    $html.='<div class="elementCoordsSetHeader">Зум</div>';
    $html.='<div class="elementCoordsSetValue"><input id="zoom" type="text" value="'.$_REQUEST["zoom"].'"></div>';
    $html.='</div>';
    $html.='</div>';
    $html.='<div class="shopElementSetting">';
    $html.='<div class="shopElementBtnSave">Сохранить</div>';
    $html.='<div class="shopElementBtnCancel">Отменить</div>';
    $html.='</div>';

$result = array(
    "success" => array(
        "html" => $html,
        "shopid" => $_REQUEST['shopid'],
    )
);
echo Bitrix\Main\Web\Json::encode($result);?>