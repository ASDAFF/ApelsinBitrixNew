<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";

class AdminPromotions_Revision
{
    private $revision;

    public function __construct($revisionId)
    {
        $this->revision = new PromotionRevisionModel($revisionId);
    }

    public function show()
    {
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

        $html = "<div class='PromotionRevisionMainWrapper'>";
            $html .= "<div class='PromotionRevisionBlock'>";
                $html .= "<div class='revision-text'>".$this->revision->getId()."</div>";
                $html .= "<div class='revision-date'>Ревизия создана: $created</div>";
                $html .= "<div class='revision-date'>Последнеи правки: $changed</div>";
                $html .= "<div class='revision-date'>Вступает в силу с: $apply_from</div>";
                $html .= "<div class='revision-date'>Показывается с: $show_from</div>";
                $html .= "<div class='revision-date'>Период действия с $start_from по $stop_from</div>";
                $html .= "<div class='revision-text-title'>Превью текст</div>";
                $html .= "<div class='revision-text'>$preview_text</div>";
                $html .= "<div class='revision-text-title'>Оснвоной текст</div>";
                $html .= "<div class='revision-text'>$main_text</div>";
                $html .= "<div class='revision-text-title'>Текст для контакта</div>";
                $html .= "<div class='revision-text'>$vk_text</div>";
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