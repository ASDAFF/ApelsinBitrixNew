<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";

class APLS_CatalogItemDetailsInfo
{
    private $html = "";
    private $globalParams = array();
    const YES_VALUE = array("true", "Y", "Да");

    public function __construct(array $property)
    {
        $this->globalParams = APLS_GetGlobalParam::getParams(array("FILLED_PRODUCT","FILLED_PRODUCT_TITLE","FILLED_PRODUCT_MESAGE"));
        if (
            !isset($property[$this->globalParams["FILLED_PRODUCT"]]["VALUE"]) ||
            $property[$this->globalParams["FILLED_PRODUCT"]]["VALUE"] == "" ||
            !in_array($property[$this->globalParams["FILLED_PRODUCT"]]["VALUE"], self::YES_VALUE)
        ) {
            $this->generateHTML();
        }
    }

    private function generateHTML() {
        $this->html = "<div class='not-filled-product'>";
        $this->html .= '<div class="CatalogItemWarningTitle">';
        $this->html .= '<i class="fa fa-warning CatalogItemWarningIcon"></i>';
        $this->html .= $this->globalParams["FILLED_PRODUCT_TITLE"];
        $this->html .= "</div>";
        $this->html .= '<div class="CatalogItemWarningText">';
        $this->html .= $this->globalParams["FILLED_PRODUCT_MESAGE"];
        $this->html .= "</div>";
        $this->html .= "</div>";
    }

    public function get() {
        echo $this->html;
    }

    public function getHtml() {
        return $this->html;
    }
}