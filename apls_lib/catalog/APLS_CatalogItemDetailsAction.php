<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";

class APLS_CatalogItemDetailsAction {

	private $html = "";
    const PROMOTIONS_PAGE = "/promotions/";

	public function __construct(array $promotionsId) {
	    foreach ($promotionsId as $promotionId) {
            $this->generatePromotionHtml($promotionId);
        }
	}

    private function generatePromotionHtml(string $promotionId) {
        $promotion = new PromotionModel($promotionId);
        $revisionId = $promotion->getCurrentRevisionId();
        if($revisionId !== "") {
            $revision = new PromotionRevisionModel($revisionId);
            $mainText = $revision->getFieldValue('main_text');
            $this->html .= "<div class='CatalogItemPromo'>";
            $this->html .= "<i class='fa fa-scissors CatalogItemPromoIcon'></i>";
            $this->html .= "<div class='CatalogItemPromoData'>";
            $this->html .= "<div class='CatalogItemPromoTitle'>".$revision->getFieldValue('title')."</div>";
            $this->html .= "<div class='CatalogItemPromoText'>".$revision->getFieldValue('preview_text')."</div>";
            if($mainText !== "" && $mainText !== null) {
                $this->html .= "<a 
                target='_blank' 
                class='content_button btn_buy apuo show_promo' 
                href='" . self::PROMOTIONS_PAGE."id/".$promotionId . "/'>подробнее</a>";
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