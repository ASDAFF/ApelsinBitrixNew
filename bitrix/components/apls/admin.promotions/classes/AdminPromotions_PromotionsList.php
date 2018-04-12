<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_Tabs/APLS_Tabs.php";

class AdminPromotions_PromotionsList
{
    private $promotions = array();

    public function __construct(
        $revisionType = PromotionModel::REVISION_TYPE_DEFAULT,
        $sortingField = PromotionModel::SORT_FIELD_DEFAULT,
        $sortingType = PromotionModel::SORT_ASC,
        $searchString = "",
        $location = "",
        $section = "",
        $publishedOn = array()
    ) {
        $this->promotions = PromotionModel::promotionSearch(
            $revisionType,
            $sortingField,
            $sortingType,
            $searchString,
            $location,
            $section,
            $publishedOn
        );
    }

    public function showList():string {
        $html = "";
        if(!empty($this->promotions)) {
            $html .= "<div class='ListOfElements'>";
            foreach ($this->promotions as $promotion) {
                if($promotion instanceof PromotionModel) {
                    $html .= $this->showElement($promotion);
                }
            }
            $html .= "</div>";
        } else {
            $html .= "По данному запросу ничего не найдено";
        }
        return $html;
    }

    private function showElement(PromotionModel $promotion):string {
        $tabs = new APLS_Tabs();
        $stile = "";
        $revisionStatus = "<div class='revision-status-panel'>";
        if($promotion->getNextRevisionId() !== "") {
            $stile .= " coming";
            $revisionStatus .= "<div class='coming'>coming</div>";
        }
        if($promotion->getCurrentRevisionId() !== "") {
            $stile .= " current";
            $revisionStatus .= "<div class='current'>current</div>";
        }
        if($promotion->getPreviousRevisionId() !== "") {
            $stile .= " past";
            $revisionStatus .= "<div class='past'>past</div>";
        }
        $revisionStatus .= "</div>";


        $html = "";
        $html .= "<div class='ElementBlock PromotionListElement $stile ID-".$promotion->getId()."'>";
        $html .= "<div class='ElementBlockContent' promotionId='".$promotion->getId()."'>";
        $html .= "<div class='content'>";
        $html .= $promotion->getFieldValue('title');
        $html .= "</div>";
        $html .= "</div>";
//        $html .= $revisionStatus;
//        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    private function getRevisionHtml(PromotionRevisionModel $revision) {
        $revision->getFieldValue('apply_from');
    }
}