<?php
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss("/apls_lib/ui/APLS_Tabs/APLS_Tabs.css");
Asset::getInstance()->addJs("/apls_lib/ui/APLS_Tabs/APLS_Tabs.js");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";

class APLS_Tabs
{
    private $tabs;
    const TABS_ORIENTATION_TOP_START = "orientation-top-start";
    const TABS_ORIENTATION_TOP_END = "orientation-top-end";
    const TABS_ORIENTATION_TOP_CENTER = "orientation-top-center";
    const TABS_ORIENTATION_LEFT_START = "orientation-left-start";
    const TABS_ORIENTATION_RIGHT_START = "orientation-right-start";
    const TABS_ORIENTATION_DEFAULT = self::TABS_ORIENTATION_TOP_START;
    const TABS_ORIENTATIONS = array(
        self::TABS_ORIENTATION_TOP_START,
        self::TABS_ORIENTATION_TOP_END,
        self::TABS_ORIENTATION_TOP_CENTER,
        self::TABS_ORIENTATION_LEFT_START,
        self::TABS_ORIENTATION_RIGHT_START,
    );

    /**
     * проверяет существует ли таб по его $tabKey
     * @param $tabKey
     * @return bool
     */
    public function issetTab($tabKey)
    {
        return isset($this->tabs[$tabKey]);
    }

    /**
     * Добавляет новый таб и возврящает его $tabKey
     * @param $name - имя
     * @param $content - содержимое
     * @param $tabKey - идентификатор таба
     * @return string - $tabKey
     */
    public function addTab($name, $content, $tabKey = null)
    {
        if($tabKey === null) {
            $tabKey = ID_GENERATOR::generateID();
        }
        $this->tabs[$tabKey]['name'] = $name;
        $this->tabs[$tabKey]['content'] = $content;
        return $tabKey;
    }

    /**
     * Перезаписывает существующий таб по его $tabKey
     * @param $tabKey
     * @param $newName
     * @param $newContent
     * @return bool
     */
    public function editTab($tabKey, $newName, $newContent)
    {
        if ($this->issetTab($tabKey)) {
            $this->tabs[$tabKey]['name'] = $newName;
            $this->tabs[$tabKey]['content'] = $newContent;
        } else {
            return false;
        }
    }

    /**
     * изменяем названеи таба
     * @param $tabKey
     * @param $newName
     * @return bool
     */
    public function editTabName($tabKey, $newName)
    {
        return $this->editTab($tabKey, $newName, $this->tabs[$tabKey]['content']);
    }

    /**
     * изменяем контент таба
     * @param $tabKey
     * @param $newContent
     * @return bool
     */
    public function editTabContent($tabKey, $newContent)
    {
        return $this->editTab($tabKey, $this->tabs[$tabKey]['name'], $newContent);
    }

    /**
     * удаляем таб
     * @param $tabKey
     * @return bool
     */
    public function delTab($tabKey)
    {
        if ($this->issetTab($tabKey)) {
            unset($this->tabs[$tabKey]);
        } else {
            return false;
        }
    }

    /**
     * получить html код табов
     * @param string $tabOrientation
     * @return string
     */
    public function getTabsHtml($tabOrientation = self::TABS_ORIENTATION_DEFAULT) {
        if(!in_array($tabOrientation,self::TABS_ORIENTATIONS)) {
            $tabOrientation = self::TABS_ORIENTATION_DEFAULT;
        }
        $tabsId = ID_GENERATOR::generateID("APLS", "TABS");
        $arId = array();
        $html = "<div id='$tabsId' class='apls-tabs-wrapper $tabOrientation'>";
        $html .= "<div class='apls-tabs-name-area'>";
        foreach ($this->tabs as $tabKey => $tab) {
            $arId[$tabKey] = $tabId = ID_GENERATOR::generateID("TAB");
            $html .= "<div id='$tabId-NAME' class='apls-tab-name' tabId='$tabId' tabsWrapperId='$tabsId'>";
            $html .= $tab['name'];
            $html .= "</div>";
        }
        $html .= "</div>";
        $html .= "<div class='apls-tabs-content-area'>";
        foreach ($this->tabs as $tabKey => $tab) {
            $tabId = $arId[$tabKey];
            $html .= "<div id='$tabId-CONTENT' class='apls-tab-content'>";
            $html .= $tab['content'];
            $html .= "</div>";
        }
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }
}