<?php

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs("/apls_lib/ui/APLS_SortLists/APLS_SortLists.js");
Asset::getInstance()->addCss("/apls_lib/ui/APLS_SortLists/APLS_SortLists.css");
Asset::getInstance()->addJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
Asset::getInstance()->addCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElement.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElements.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortList.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";

class APLS_SortLists implements Iterator
{
    private $sortLists = array();

    public function getSortList(string $uniqueId) {
        if($this->validSortListElement($uniqueId)) {
            return $this->sortLists[$uniqueId];
        } else {
            return NULL;
        }
    }

    public function validSortList(string $uniqueId) {
        return isset($this->sortLists[$uniqueId]);
    }

    public function clearSortList() {
        $this->sortLists = array();
    }

    public function addSortList(APLS_SortList $sortList)
    {
        $this->sortLists[$sortList->getUniqueId()] = $sortList;
    }

    public function addSortLists(APLS_SortLists $sortLists)
    {
        foreach ($sortLists as $sortList) {
            $this->addSortList($sortList);
        }
    }

    public function addSortListsFromArray(array $sortLists)
    {
        foreach ($sortLists as $sortList) {
            if($sortList instanceof APLS_SortList) {
                $this->addSortList($sortList);
            }
        }
    }

    public function setSortLists(APLS_SortLists $sortLists)
    {
        $this->clearSortList();
        $this->addSortLists($sortLists);
    }

    public function setSortListsFromArray(array $sortLists)
    {
        $this->clearSortList();
        $this->addSortListsFromArray($sortLists);
    }

    public function removeSortList(string $uniqueId)
    {
        if($this->validSortList($uniqueId)) {
            unset($this->sortLists[$uniqueId]);
        }
    }

    public function current()
    {
        return current($this->sortLists);
    }

    public function next()
    {
        return next($this->sortLists);
    }

    public function key()
    {
        return key($this->sortLists);
    }

    public function valid()
    {
        $key = key($this->sortLists);
        return $key !== NULL && $key !== FALSE;
    }

    public function rewind()
    {
        reset($this->sortLists);
    }

    public function __clone()
    {
        $sortLists = $this->sortLists;
        $this->sortLists = array();
        foreach ($sortLists as $key => $element) {
            $newElement = clone $element;
            $this->sortLists[$newElement->getUniqueId()] = $newElement;
        }
    }
}