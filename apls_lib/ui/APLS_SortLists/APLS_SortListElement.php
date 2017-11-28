<?php

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs("/apls_lib/ui/APLS_SortLists/APLS_SortLists.js");
Asset::getInstance()->addCss("/apls_lib/ui/APLS_SortLists/APLS_SortLists.css");
Asset::getInstance()->addJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
Asset::getInstance()->addCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";

class APLS_SortListElement
{

    private $content;
    private $attributes = array();
    private $classesNames = array();
    private $uniqueId;

    public function __construct(string $content)
    {
        $this->uniqueId = ID_GENERATOR::generateID();
        $this->setContent($content);
    }

    public function isItThisElement(APLS_SortListElement $sortListElement) {
        return $sortListElement->getUniqueId() === $this->uniqueId;
    }

    public function checkUniqueId(string $uniqueId) {
        return $uniqueId === $this->uniqueId;
    }

    public function getUniqueId() {
        return $this->uniqueId;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function addAttribute(string $attribute, string $value)
    {
        $this->attributes[$attribute] = $value;
    }

    public function removeAttribute(string $attribute)
    {
        if (isset($this->attributes[$attribute])) {
            unset($this->attributes[$attribute]);
        }
    }

    public function clearAttributes()
    {
        $this->attributes = array();
    }

    public function addClassName(string $className)
    {
        $this->classesNames[$className] = $className;
    }

    public function removeClassName(string $className)
    {
        if (isset($this->classesNames[$className])) {
            unset($this->classesNames[$className]);
        }
    }

    public function clearClassName()
    {
        $this->classesNames = array();
    }

    public function getSortListElementHtml()
    {
        $attributesHtml = "";
        foreach ($this->attributes as $attribute => $value) {
            $attributesHtml .= $attribute . "='" . $value . "' ";
        }
        $classesNamesHtml = "class='apls-sort-list-element ";
        foreach ($this->classesNames as $classesName) {
            $classesNamesHtml .= $classesName . " ";
        }
        $classesNamesHtml .= "'";
        $id = ID_GENERATOR::generateID();
        $html = "<div id='$id' $classesNamesHtml $attributesHtml>";
            $html .= "<div class='sort-handle'>";
                $html .= "<div class='icon-bar'></div>";
                $html .= "<div class='icon-bar'></div>";
                $html .= "<div class='icon-bar'></div>";
            $html .= "</div>";
            $html .= "<div class='text'>";
                $html .= "<span>".$this->content."</span>";
            $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    public function __clone()
    {
        $this->uniqueId = ID_GENERATOR::generateID();
    }
}