<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
class APLS_CatalogItemDetailsPropertiesBlock {

    private $error = array();

    private $property = array();
    private $requisites = array();
    private $html = "";

	const TRUE_VALUE = "Да";

    public function __construct(array $property) {
        $this->property = $property;
        $this->getData();
        $this->generateHtml();
    }

    public function get() {
        echo $this->html;
    }

    public function getHtml() {
        return $this->html;
    }

    private function getData() {
        try {
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("CatalogRequisites");
            $rsData = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array("UF_SEQUENCE" => "ASC")
            ));
            while ($arData = $rsData->Fetch()) {
                if(isset($this->property[$arData['UF_CODE']]["VALUE"]) && $this->property[$arData['UF_CODE']]["VALUE"] == self::TRUE_VALUE) {
                    $this->requisites[$arData['UF_CODE']] = $arData;
                    $this->requisites[$arData['UF_CODE']]['UF_ICON'] = CFile::GetPath($arData["UF_ICON"]);
                }
            }
        } catch (Exception $e) {
            $this->error[] = 'Выброшено исключение: ' . $e->getMessage() . "<br>";
        }
    }

    private function generateHtml() {
        $this->html = "";
        foreach ($this->error as $err) {
            $this->html .= $err."<br>";
        }
        if(!empty($this->requisites)) {
            $this->html .= "<div class='ProductAvailableServicesWrapper advantages'>";
            $this->html .= "<div class='ProductAvailableServicesTitle'>Доступные услуги:</div>";
            foreach ($this->requisites as $value) {
                $this->html .= "<div class='ProductAvailableServicesIconBlock'>";
                if($value['UF_URL']) {
                    $this->html .= "<a href='".$value['UF_URL']."'>";
                }
                $this->html .= "<span class='ProductAvailableServicesIconText'>".$value['UF_NAME']."</span>";
                $this->html .= "<img class='ProductAvailableServicesIcon' src='".$value['UF_ICON']."' alt='".$value['UF_NAME']."'>";
                if($value['UF_URL']) {
                    $this->html .= "</a>";
                }
                $this->html .= "</div>";
            }
            $this->html .= "</div>";
        }
    }
}