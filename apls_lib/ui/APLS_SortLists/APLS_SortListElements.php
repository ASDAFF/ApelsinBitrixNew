<?php

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs("/apls_lib/ui/APLS_SortLists/APLS_SortLists.js");
Asset::getInstance()->addCss("/apls_lib/ui/APLS_SortLists/APLS_SortLists.css");
Asset::getInstance()->addJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
Asset::getInstance()->addCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElement.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";

class APLS_SortListElements implements Iterator
{
    private $sortListElements = array();

    public function getSortListElement(string $elementUniqueId) {
        if($this->validSortListElement($elementUniqueId)) {
            return $this->sortListElements[$elementUniqueId];
        } else {
            return NULL;
        }
    }

    public function validSortListElement(string $elementUniqueId) {
        return isset($this->sortListElements[$elementUniqueId]);
    }

    public function clearSortListElements() {
        $this->sortListElements = array();
    }

    public function addSortListElement(APLS_SortListElement $sortListElement)
    {
        $this->sortListElements[$sortListElement->getUniqueId()] = $sortListElement;
    }

    public function addSortListElements(APLS_SortListElements $sortListElements)
    {
        foreach ($sortListElements as $sortListElement) {
            $this->addSortListElement($sortListElement);
        }
    }

    public function addSortListElementsFromArray(array $sortListElements)
    {
        foreach ($sortListElements as $sortListElement) {
            if($sortListElement instanceof APLS_SortListElement) {
                $this->addSortListElement($sortListElement);
            }
        }
    }

    public function setSortListElements(APLS_SortListElements $sortListElements)
    {
        $this->clearSortListElements();
        $this->addSortListElements($sortListElements);
    }

    public function setSortListElementsFromArray(array $sortListElements)
    {
        $this->clearSortListElements();
        $this->addSortListElementsFromArray($sortListElements);
    }

    public function removeSortListElement(string $elementUniqueId)
    {
        if($this->validSortListElement($elementUniqueId)) {
            unset($this->sortListElements[$elementUniqueId]);
        }
    }

    public function current()
    {
        return current($this->sortListElements);
    }

    public function next()
    {
        return next($this->sortListElements);
    }

    public function key()
    {
        return key($this->sortListElements);
    }

    public function valid()
    {
        $key = key($this->sortListElements);
        return $key !== NULL && $key !== FALSE;
    }

    public function rewind()
    {
        reset($this->sortListElements);
    }

    public function __clone()
    {
        $sortListElements = $this->sortListElements;
        $this->sortListElements = array();
        foreach ($sortListElements as $key => $element) {
            $newElement = clone $element;
            $this->sortListElements[$newElement->getUniqueId()] = $newElement;
        }
    }
}