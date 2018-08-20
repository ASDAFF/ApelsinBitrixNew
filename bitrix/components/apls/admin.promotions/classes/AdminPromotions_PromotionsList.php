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
        $stile = "";
        $regionsArray = array();
        $sectionsArray = array();
        if($promotion->getNextRevisionId() !== "") {
            $stile .= " coming";
        }
        if($promotion->getCurrentRevisionId() !== "") {
            $stile .= " current";
            $revId = $promotion->getCurrentRevisionId();
            $revision = new PromotionRevisionModel($revId);
            // определение регионов
            if($revision->hasProblemsWithRegions()) {
                $stile .= " problems-with-regions";
                $regionsArray[] = "Не указан ни один регион";
            } else {
                if($promotion->getFieldValue("in_all_regions")) {
                    $regionsArray[] = "Во всех регионах";
                } else {
                    $regions = PromotionInRegionModel::searchByRevision($revId);
                    foreach ($regions as $promInRegion) {
                        if ($promInRegion instanceof PromotionInRegionModel) {
                            $region = new PromotionRegionModel($promInRegion->getFieldValue('region'));
                            $regionsArray[] = $region->getFieldValue('region');
                        }
                    }
                }
            }
            // определение секций
            $sections = $revision->getSections();
            foreach ($sections as $section) {
                if($section instanceof PromotionSectionModel) {
                    $sectionsArray[] = $section->getFieldValue("section");
                }
            }
            if(empty($sectionsArray)) {
                $stile .= " problems-with-sections";
                $sectionsArray[] = "Не указана ни одна секция";
            }
        }
        if($promotion->getPreviousRevisionId() !== "") {
            $stile .= " past";
        }


        $html = "";
        $html .= "<div class='ElementBlock PromotionListElement $stile ID-".$promotion->getId()."'>";
            $html .= "<div class='ElementBlockContent' promotionId='".$promotion->getId()."'>";
                $html .= "<div class='content'>";
                $html .= $promotion->getFieldValue('title');
                $html .= "</div>";
                $html .= "<div class='regions'>";
                foreach ($regionsArray as $regionName) {
                    $html .= "<div class='region'>$regionName</div>";
                }
                $html .= "</div>";
                $html .= "<div class='sections'>";
                foreach ($sectionsArray as $sectionName) {
                    $html .= "<div class='section'>$sectionName</div>";
                }
                $html .= "</div>";
            $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    private function getRevisionHtml(PromotionRevisionModel $revision) {
        $revision->getFieldValue('apply_from');
    }
}