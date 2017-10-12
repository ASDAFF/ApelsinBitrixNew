<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";

// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class APLS_CatalogItemDetailsServiceCenters {

    private $serviceCenter;
    private $serviceData = array();
    private $html = "";

    public function __construct($serviceCenter) {
        $this->serviceCenter = $serviceCenter;
        $this->getData();
        $this->generateHtml();
    }

    public function getHtml() {
        return $this->html;
    }

    public function get() {
        echo $this->html;
    }

    private function getData() {
        try {
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("ServisnyeTSentryIM");
            $rsData = $entity_data_class::getList(array(
                "select" => array('ID','UF_NAME','UF_ADRES','UF_KOD','UF_RODITEL'),
                'filter' => array('UF_XML_ID' => $this->serviceCenter)
            ));
            while($arData = $rsData->Fetch())
            {
                $this->serviceData['UF_NAME'] = $arData['UF_NAME'];
                $Adress = str_replace("---// ","",$arData['UF_ADRES']);
                $Adress = str_replace("--- // ","",$Adress);
                $Adress = str_replace("// ","<br />",$Adress);
                $this->serviceData['UF_ADRES'] = nl2br($Adress);
                $this->serviceData['UF_KOD'] = $arData['UF_KOD'];
            }
        } catch (Exception $e) {
            $this->html = 'Выброшено исключение: ' . $e->getMessage() . "<br>";
        }
    }

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

    private function generateHtml() {
        if($this->serviceData['UF_NAME'] != "") {
            $img = $this->GetUnitsIMG($this->serviceData['UF_KOD']);
            $this->html .= "<div class='ServiceCentersCatalogDetail'>";
            $this->html .= "<div class='ServiceCentersUnitTitle'>".$this->serviceData['UF_NAME']."</div>";
            $this->html .= "<div class='ServiceCentersUnitAdres'>".$this->serviceData['UF_ADRES']."</div>";
            if($img) {
                $this->html .= "<div class='ServiceCentersUnitLogo'>".$img."</div>";
            }
            $this->html .= "</div>";
        }
    }

}
