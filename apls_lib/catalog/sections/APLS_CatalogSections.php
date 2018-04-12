<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";

class APLS_CatalogSections
{
    private static $instance = null;
    private static $sections = array();
    private static $sectionsIDtoXMLID = array();
    private static $sectionsXMLIDtoID = array();
    private static $sectionsChildren = array();
    private static $sectionsTree = array();
    private static $rootSections = array();
    private static $pathToRoot = array();
    private static $allChildrenList = array();

    public static function getSections()
    {
        static::getInstance();
        return static::$sections;
    }

    public static function getSection($xmlid)
    {
        static::getInstance();
        return static::$sections[$xmlid];
    }

    public static function getSectionsTree()
    {
        static::getInstance();
        return static::$sectionsTree;
    }

    public static function getRootSections()
    {
        static::getInstance();
        return static::$rootSections;
    }

    public static function getSectionsIDtoXMLID()
    {
        static::getInstance();
        return static::$sectionsIDtoXMLID;
    }

    public static function getSectionsXMLIDtoID()
    {
        static::getInstance();
        return static::$sectionsXMLIDtoID;
    }

    public static function getSectionsChildren()
    {
        static::getInstance();
        return static::$sectionsChildren;
    }

    public static function getSectionsChild($xmlid)
    {
        static::getInstance();
        return static::$sectionsChildren[$xmlid];
    }

    public static function getAllChildrenList()
    {
        static::getInstance();
        return static::$allChildrenList;
    }

    public static function getAllChildrenListForSection($xmlid)
    {
        static::getInstance();
        return static::$allChildrenList[$xmlid];
    }

    public static function getPathToRoot()
    {
        static::getInstance();
        return static::$pathToRoot;
    }

    public static function getPathToRootForSection($xmlid)
    {
        static::getInstance();
        return static::$pathToRoot[$xmlid];
    }

    public static function convertSectionsIDtoXMLID($id)
    {
        static::getInstance();
        return static::$sectionsIDtoXMLID[$id];
    }

    public static function convertSectionsXMLIDtoID($xmlid)
    {
        static::getInstance();
        return static::$sectionsXMLIDtoID[$xmlid];
    }

    public static function getSelectBox($className, $LevelDivisor = "-", $valueKey = "XML_ID", $nameKey = "NAME", $startLvl = 0, $addRoot = true) {
        $arr = static::getNodeArrayOptionsForSelect(static::getSectionsTree(), $LevelDivisor, $valueKey, $nameKey, $startLvl);
        $html = "<select class='SectionsTreeSelectBox $className'>";
        if($addRoot) {
            $html .= "<option value='' selected>любой</option>";
        }
        foreach ($arr as $val => $name) {
            $html .= "<option value='$val'>$name</option>";
        }
        $html .= "</select>";
        return $html;
    }

    protected function __construct()
    {
        static::getDataSections();
        foreach (static::$sectionsIDtoXMLID as $id => $xmlid) {
            // генерируем пути к корню для каждой секции
            static::$pathToRoot[$xmlid] = static::generateNodeElementPathToRoot($xmlid);
            // генериурем список вложенных потомков для каждой секции
            static::$allChildrenList[$xmlid] = static::generateNodeElementChildren($xmlid);
        }
    }

    /**
     * @return - реализация Singleton
     */
    protected static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Получаем общие данные по секциям
     */
    protected static function getDataSections()
    {
        $rsSection = CIBlockSection::GetTreeList(
            array(
                'IBLOCK_ID' => APLS_CatalogHelper::getShopIblockId(),
                'ACTIVE' => 'Y'
            ),
            array()
        );
        while ($arSection = $rsSection->Fetch()) {
            static::$sectionsIDtoXMLID[$arSection["ID"]] = $arSection["XML_ID"];
            static::$sectionsXMLIDtoID[$arSection["XML_ID"]] = $arSection["ID"];
            static::$sections[$arSection["XML_ID"]] = $arSection;
            if ($arSection["IBLOCK_SECTION_ID"] === null) {
                // сохраняем список корневых элементов через XML_ID
                static::$rootSections[] = $arSection["XML_ID"];
            } else {
                // сохраняем список потомков через XML_ID
                static::$sectionsChildren[static::$sectionsIDtoXMLID[$arSection["IBLOCK_SECTION_ID"]]][] = $arSection["XML_ID"];
            }
        }
        static::$sectionsTree = static::generateSectionTreeForXMLID(static::$rootSections);
    }

    /**
     * генерация данных в формате дерева по секциям каталога
     * @param $arElements
     * @return array
     */
    protected static function generateSectionTreeForXMLID($arElements)
    {
        $rezultArray = array();
        foreach ($arElements as $element) {
            $rezultArray[$element]["element"] = static::$sections[$element]["XML_ID"];
            if (isset(static::$sectionsChildren[$element])) {
                $rezultArray[$element]["children"] = static::generateSectionTreeForXMLID(static::$sectionsChildren[$element]);
            }
        }
        return $rezultArray;
    }

    /**
     * генерируем путь к корню поэлементно с помощью XMLID
     * @param $xmlid
     * @param array $arPath
     * @return array
     */
    protected static function generateNodeElementPathToRoot($xmlid, $arPath = array())
    {
        foreach (static::$sectionsChildren as $xmlidParent => $children) {
            if (in_array($xmlid, $children)) {
                $arPath[] = $xmlidParent;
                $arPath = static::generateNodeElementPathToRoot($xmlidParent, $arPath);
            }
        }
        return $arPath;
    }

    /**
     * генерируем список всех потомков узла по XMLID
     * @param $xmlid
     * @param array $children
     * @return array
     */
    protected static function generateNodeElementChildren($xmlid, $children = array())
    {
        if (isset(static::$sectionsChildren[$xmlid])) {
            foreach (static::$sectionsChildren[$xmlid] as $child) {
                $children[] = $child;
                $children = static::generateNodeElementChildren($child, $children);
            }
        }
        return $children;
    }

    protected static function getNodeArrayOptionsForSelect($sections, $LevelDivisor = "-", $valueKey = "XML_ID", $nameKey = "NAME", $lvl = 0, $options = array()) {
        foreach ($sections as $sectionNode) {
            $section = APLS_CatalogSections::getSection($sectionNode["element"]);
            $options[$section["ID"]] = str_repeat($LevelDivisor, $lvl).$section["NAME"];
            if(isset($sectionNode["children"]) && !empty($sectionNode["children"])) {
                $options = static::getNodeArrayOptionsForSelect($sectionNode["children"], $LevelDivisor, $valueKey, $nameKey, $lvl + 1, $options);
            }
        }
        return $options;
    }

}