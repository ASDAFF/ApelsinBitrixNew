<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";

class APLS_CatalogItemDetailsAction {

	private $html = "";
    private $promoFields = array();
    private $promoTitle = "";
    private $promoText = "";
    private $promoImg = "";
    private $promoUrl = "";
    private $globalParams = array();
    const YES_VALUE = array("true", "Y", "Да");

	public function __construct(array $property) {
        $this->globalParams = APLS_GetGlobalParam::getParams(array("PROMO","PROMO_TEXT","PROMO_IBLOCK_ID"));
        if (isset($property[$this->globalParams["PROMO_TEXT"]]["VALUE"]) && $property[$this->globalParams["PROMO_TEXT"]]["VALUE"] != "" &&
            (in_array($property[$this->globalParams["PROMO"]]["VALUE"], self::YES_VALUE))) {
            $this->promoTitle = $property[$this->globalParams["PROMO_TEXT"]]["VALUE"];
            $this->getPromoData();
        }
	}

	private function getPromoData() {
        $arSelect = Array("PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PAGE_URL");
        $arFilter = Array("IBLOCK_ID"=>$this->globalParams["PROMO_IBLOCK_ID"], "=NAME"=>$this->promoTitle, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(),$arFilter,false,false,$arSelect);
        while($ob = $res->GetNextElement())
        {
            $this->promoFields = $ob->GetFields();
            if(isset($this->promoFields["PREVIEW_TEXT"])) {
                $this->promoText = $this->promoFields["PREVIEW_TEXT"];
            }
            if(isset($this->promoFields["DETAIL_PAGE_URL"])) {
                $this->promoUrl = $this->promoFields["DETAIL_PAGE_URL"];
            }
            if(isset($this->promoFields["PREVIEW_PICTURE"])) {
                $this->promoImg = CFile::GetPath($this->promoFields["PREVIEW_PICTURE"]);
            }
        }
        $this->generateHtml();
    }

    private function generateHtml() {
	    if($this->promoTitle != "") {
            $this->html .= "<div class='CatalogItemPromo'>";
            $this->html .= "<i class='fa fa-scissors CatalogItemPromoIcon'></i>";
            $this->html .= "<div class='CatalogItemPromoData'>";
            $this->html .= "<div class='CatalogItemPromoTitle'>".$this->promoTitle."</div>";
            if($this->promoText != "") {
                $this->html .= "<div class='CatalogItemPromoText'>".$this->promoText."</div>";
            }
            if($this->promoUrl != "") {
                $this->html .= "<a class='content_button btn_buy apuo show_promo' href='" . $this->promoUrl . "'>подробнее об акции</a>";
            }
            $this->html .= "</div>";
            $this->html .= "</div>";
        }
    }

    public function get() {
		echo $this->html;
	}

    public function getHtml() {
		return $this->html;
	}
}