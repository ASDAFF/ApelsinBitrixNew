<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$VIEW_HTML = "";
require_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/textgenerator/ID_GENERATOR.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";

function GetUnitsIMG($code) {
    $serviceCentersLogosPath = APLS_GetGlobalParam::getParams("ServiceCentersLogosPath");
    $IMG_URL = $serviceCentersLogosPath.$code.".png";
    if(!file_exists($_SERVER["DOCUMENT_ROOT"].$IMG_URL)) {
        $IMG_URL = $serviceCentersLogosPath.$code.".jpg";
        if(!file_exists($_SERVER["DOCUMENT_ROOT"].$IMG_URL)) {
            $IMG_URL = null;
        }
    }
    if($IMG_URL!=null) {
        $logo = "<img class='ServiceCentersLogo' src='".$IMG_URL."'>";
    } else {
        $logo = '';
    }
    return $logo;
}

foreach ($arResult['SERVICE_CENTERS'] as $parent => $servicecenters) {
    $columnOne = ID_GENERATOR::generateID("APLS");
    $columnTwo = ID_GENERATOR::generateID("APLS");
    $columnMain = ID_GENERATOR::generateID("APLS");
    $VIEW_HTML .= "<div class='ServiceCentersBlock'>";
    $VIEW_HTML .= "<h1 class='ServiceCentersBlockTitle'>".GetMessage($parent)."</h1>";
    $VIEW_HTML .= "<div class='ServiceCentersColumnMain' id='$columnMain'>";
    foreach ($servicecenters as $unit) {
        $img = GetUnitsIMG($unit['UF_KOD']);
        $VIEW_HTML .= "<div class='ServiceCentersUnit'>";
        $VIEW_HTML .= "<div class='ServiceCentersUnitLeft'>";
            $VIEW_HTML .= "<div class='ServiceCentersUnitTitle'>".$unit['UF_NAME']."</div>";
            $VIEW_HTML .= "<div class='ServiceCentersUnitAdres'>".$unit['UF_ADRES']."</div>";
        $VIEW_HTML .= "</div>";
        if($img) {
            $VIEW_HTML .= "<div class='ServiceCentersUnitRight'>";
            $VIEW_HTML .= "<div class='ServiceCentersUnitLogo'>".$img."</div>";
            $VIEW_HTML .= "</div>";
        }
        $VIEW_HTML .= "</div>";
    }
    $VIEW_HTML .= "</div>";
    $VIEW_HTML .= "<div class='ServiceCentersColumnOne'><div class='Column' id='$columnOne'></div></div>";
    $VIEW_HTML .= "<div class='ServiceCentersColumnTwo'><div class='Column' id='$columnTwo'></div></div>";
    $VIEW_HTML .= "<div class='Clear'></div>";
    $VIEW_HTML .= '<script type="text/javascript">';
    $VIEW_HTML.= '$(document).ready(function() {';
    $VIEW_HTML .= "ServiceCentersToColumns('#$columnMain',['#$columnOne','#$columnTwo']);";
    $VIEW_HTML .= '});';
    $VIEW_HTML .= '</script>';
    $VIEW_HTML .= "</div>";
}