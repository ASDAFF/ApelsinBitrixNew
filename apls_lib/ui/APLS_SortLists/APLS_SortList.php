<?php

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs("/apls_lib/ui/APLS_SortLists/APLS_SortLists.js");
Asset::getInstance()->addCss("/apls_lib/ui/APLS_SortLists/APLS_SortLists.css");
Asset::getInstance()->addJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
Asset::getInstance()->addCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElement.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElements.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";

class APLS_SortList
{
    private $title = null;
    private $attributes = array();
    private $classesNames = array();
    private $sortListElements = null;
    private $uniqueId;

    public function __construct()
    {
        $this->uniqueId = ID_GENERATOR::generateID();
        $this->clearSortListElements();
    }

    public function isItThisElement(APLS_SortList $sortList) {
        return $sortList->getUniqueId() === $this->uniqueId;
    }

    public function checkUniqueId(string $uniqueId) {
        return $uniqueId === $this->uniqueId;
    }

    public function getUniqueId() {
        return $this->uniqueId;
    }

    public function setSortListTitle(string $title) {
        $this->title = $title;
    }

    public function removeSortListTitle() {
        $this->setSortListTitle(null);
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

    public function sortListElements()
    {
        return $this->sortListElements;
    }

    public function setSortListElements(APLS_SortListElements $sortListElements) {
        $this->sortListElements = $sortListElements;
    }

    public function cloneSortListElements(APLS_SortListElements $sortListElements) {
        $this->sortListElements = clone $sortListElements;
    }

    public function clearSortListElements() {
        $this->sortListElements = new APLS_SortListElements();
    }

    public function getSortListHtml() {
        $attributesHtml = "";
        foreach ($this->attributes as $attribute => $value) {
            $attributesHtml .= $attribute . "='" . $value . "' ";
        }
        $classesNamesHtml = "class='apls-sort-list ";
        foreach ($this->classesNames as $classesName) {
            $classesNamesHtml .= $classesName . " ";
        }
        $classesNamesHtml .= "'";
        $id = ID_GENERATOR::generateID();
        $html = "<div id='$id' $classesNamesHtml $attributesHtml>";
        if($this->title !== null) {
            $html .= "<div class='apls-sort-list-title'>".$this->title."</div>";
        }
        $html .= "<div class='apls-sort-list-content'>";
        foreach ($this->sortListElements as $sortListElement) {
            $html .= $sortListElement->getSortListElementHtml();
        }
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    public function __clone()
    {
        $this->uniqueId = ID_GENERATOR::generateID();
        $this->sortListElements = clone $this->sortListElements;
    }
}