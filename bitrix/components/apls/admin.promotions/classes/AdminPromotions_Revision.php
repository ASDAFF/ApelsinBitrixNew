<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageTypeModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageModel.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/classes/PromotionImageHelper.php";

class AdminPromotions_Revision
{
    private $revision;

    public function __construct($revisionId)
    {
        $this->revision = new PromotionRevisionModel($revisionId);
    }

    public function show() {
        $created = $this->getDateTimeString($this->revision->getFieldValue('created'));
        $changed = $this->getDateTimeString($this->revision->getFieldValue('changed'));
        $apply_from = $this->getDateTimeString($this->revision->getFieldValue('apply_from'));
        $show_from = $this->getDateTimeString($this->revision->getFieldValue('show_from'));
        $start_from = $this->getDateTimeString($this->revision->getFieldValue('start_from'));
        $stop_from = $this->getDateTimeString($this->revision->getFieldValue('stop_from'));
        $preview_text = $this->revision->getFieldValue('preview_text');
        $main_text = $this->revision->getFieldValue('main_text');
        $vk_text = $this->revision->getFieldValue('vk_text');
        $global_activity = $this->revision->getFieldValue('global_activity');
        $local_activity = $this->revision->getFieldValue('local_activity');
        $vk_activity = $this->revision->getFieldValue('vk_activity');
        $imageArray = array();
        $imageTypes = PromotionImageTypeModel::getElementList();
        foreach ($imageTypes as $imageType) {
            if($imageType instanceof PromotionImageTypeModel) {
                $imageArray[$imageType->getId()]['name'] = $imageType->getFieldValue('type');
                $imageArray[$imageType->getId()]['src'] = null;
            }
        }
        $images = PromotionImageInRevisionModel::searchByRevision($this->revision->getId());
        foreach ($images as $image) {
            if($image instanceof PromotionImageInRevisionModel) {
                $imageId = $image->getFieldValue('img');
                $imageObj = new PromotionImageModel($imageId);
                $imageObj->getFieldValue('type');
                $imageArray[$imageObj->getFieldValue('type')]['src'] = PromotionImageHelper::getSmallImagePath($imageId);
            }
        }
        $tabs = new APLS_Tabs();
        if($preview_text != "") {
            $tabs->addTab("Превью текст","<div class='revision-text'>$preview_text</div>");
        }
        if($main_text != "") {
            $tabs->addTab("Оснвоной текст","<div class='revision-text'>$main_text</div>");
        }
        if($vk_text != "") {
            $tabs->addTab("Текст для контакта","<div class='revision-text'>$vk_text</div>");
        }
        $activityString = "";
        if($global_activity > 0) {
            $activityString .= "<div class='revision-text'>На глобальном сайте</div>";
        }
        if($local_activity > 0) {
            $activityString .= "<div class='revision-text'>На локальном сайте</div>";
        }
        if($vk_activity > 0) {
            $activityString .= "<div class='revision-text'>В контакте</div>";
        }
        if($activityString != "") {
            $activityString = "<b>Размещение</b>".$activityString."<br />";
        }
        $imageString = "";
        $imageString .= '<div class="imageItemsList">';
        foreach ($imageArray as $imageData) {
            $imageString .= '<div class="imageItem">';
            $imageString .= '<div class="imageItemTitle">'.$imageData['name']."</div>";
            if($imageData['src'] !== null) {
                $imageString .= '<img class="Image" src="'.$imageData['src'].'">';
            } else {
                $imageString .= '<div class="Image noImage"></div>';
            }
            $imageString .= '</div>';
        }
        $imageString .= '</div>';
        $tabs->addTab("Изображения",$imageString);

        $timeInfo = "";
        $timeInfo .= "<div class='revision-date'>Ревизия создана: <b>$created</b></div>";
        $timeInfo .= "<div class='revision-date'>Отредактирована: <b>$changed</b></div>";
        $timeInfo .= "<div class='revision-date'>Вступает в силу с: <b>$apply_from</b></div>";
        if($show_from != "") {
            $timeInfo .= "<div class='revision-date'>Показывается с: <b>$show_from</b></div>";
        }
        if($start_from != "" && $stop_from != "") {
            $timeInfo .= "<div class='revision-date'>Действет с <b>$start_from по $stop_from</b></div>";
        } else if($start_from != "") {
            $timeInfo .= "<div class='revision-date'>Действет с <b>$start_from</b></div>";
        } else if($stop_from != "") {
            $timeInfo .= "<div class='revision-date'>Действет по <b>$stop_from</b></div>";
        }
        $tabs->addTab("Информация",$activityString.$timeInfo);


        $html = "<div class='PromotionRevisionMainWrapper'>";
        $html .= "<div class='PromotionRevisionBlock'>";
        $html .= "<div class='revision-text'>ID: <b>".$this->revision->getId()."</b></div>";

        $html .= "<div class='revision-text-content'>";
        $html .= $tabs->getTabsHtml();
        $html .= "</div>";




        $html .= "</div>";
        $html .= "<div class='ButtonPanel'>";
        $html .= "<div class='Button Small Green edit' revisionId='".$this->revision->getId()."'>Править</div>";
        $html .= "<div class='Button Small Green createCopy' revisionId='".$this->revision->getId()."'>Создать копию</div>";
        if($this->revision->getFieldValue('disable') > 0) {
            $html .= "<div class='Button Small Yellow enable' revisionId='".$this->revision->getId()."'>Активировать</div>";
        } else {
            $html .= "<div class='Button Small Yellow disable' revisionId='".$this->revision->getId()."'>Деактевировать</div>";
        }
        $html .= "<div class='Button Small Red del' revisionId='".$this->revision->getId()."'>Удалить</div>";
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    public function editForm() {

    }

    public function getDateTimeString($dateTime) {
        if($dateTime instanceof \Bitrix\Main\Type\DateTime) {
            return $dateTime->toString();
        }
        return "";
    }
}