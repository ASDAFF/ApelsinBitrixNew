<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";

class APLS_CatalogSectionsTree
{

    private static $lastTreeID = null;
    private static $treeElementID = array();

    const ROOT_SERTION_ID = "ROOT";
    const HideElementClassCSS = "HideElement";
    const IsHideIconClassCSS = "isHideIcon";
    const IsShowIconClassCSS = "isShowIcon";

    /**
     * @param $treeID - Уникальный id дерева
     * @param bool $SHButton - В свернутом или развернутом виде
     * @return string - HTML код дерева
     */
    public static function getSectionsTreeHtml($treeID = null, $SHButton = true)
    {
        if($treeID === null) {
            self::$lastTreeID = ID_GENERATOR::generateID("SectionsTreeID");
        } else {
            self::$lastTreeID = $treeID;
        }
        $html = "<div id='" . self::$lastTreeID . "' class='SectionsMenuWrapper'>";
        $html .= self::generateSectionsTreeNodeHtml(self::ROOT_SERTION_ID, APLS_CatalogSections::getSectionsTree(), $SHButton);
        $html .= "</div>";
        $html .= self::generationSectionsMenuJS();
        return $html;
    }

    /**
     * возвращает ID последнего сгенерированного дерева для работы JS
     * @return string | null - ID последнего сгенерированного дерева
     */
    public static function getLastSectionsTreeID()
    {
        return self::$lastTreeID;
    }
    /**
     * возвращает ID елемента дерева по ID дерева и XMLID секции
     * @param $treeId
     * @param $sectionXmlId
     * @return  string | null - ID елемента дерева
     */
    public static function getTreeElementIDForSectionXMLID($treeId, $sectionXmlId)
    {
        return self::$treeElementID[$treeId][$sectionXmlId];
    }

    /**
     * возвращает ID елемента дерева по ID дерева и ID секции
     * @param $treeId
     * @param $sectionId
     * @return string | null - ID елемента дерева
     */
    public static function getTreeElementIDForSectionID($treeId, $sectionId)
    {
        return self::$treeElementID[$treeId][APLS_CatalogSections::convertSectionsIDtoXMLID($sectionId)];
    }

    /**
     * генарция узла дерева каталогов
     * @param $XMLID
     * @param $arElements
     * @param bool $SHButton
     * @return string
     */
    private static function generateSectionsTreeNodeHtml($XMLID, $arElements, $SHButton)
    {
        if ($XMLID !== self::ROOT_SERTION_ID && $SHButton) {
            $hsClass = self::HideElementClassCSS;
        } else {
            $hsClass = "";
        }
        $html = "<ul class='SectionsMenuUl UL_$XMLID $hsClass'>";
        foreach ($arElements as $element) {
            $elementXMLID = $element["element"];
            $html .= "<li class='SectionsMenuLi LI_$elementXMLID'>";
            $html .= "<div class='SectionsMenuBlock BLOCK_$elementXMLID'>";
            if ($SHButton) {
                if (!empty($element["children"])) {
                    $html .= "<div class='SectionsMenuSHButton SH_BUTTON_$elementXMLID " . self::IsHideIconClassCSS . "' XMLID='$elementXMLID'></div>";
                } else {
                    $html .= "<div class='SectionsMenuNoSHButton'></div>";
                }
            }
            self::$treeElementID[self::$lastTreeID][$elementXMLID] = ID_GENERATOR::generateID("SectionTreeElementID");
            $section = APLS_CatalogSections::getSection($elementXMLID);
            $html .= "<div id='" . self::$treeElementID[self::$lastTreeID][$elementXMLID] . "' class='SectionsMenuName NAME_$elementXMLID' XMLID='$elementXMLID'>";
            $html .= $section["NAME"];
            $html .= "</div>";
            $html .= "</div>";
            if (isset($element["children"])) {
                $html .= self::generateSectionsTreeNodeHtml($elementXMLID, $element["children"], $SHButton);
            }
            $html .= "</li>";

        }
        $html .= "</ul>";
        return $html;
    }

    private static function generationSectionsMenuJS()
    {
        return "<script type='text/javascript'>
                $(document).ready(function() {
                    $('#" . self::$lastTreeID . " .SectionsMenuSHButton').click(function() {
                        var XMLID = $(this).attr('XMLID');
                        if($('.UL_' + XMLID).hasClass('HideElement')) {
                            $('.UL_' + XMLID).removeClass('HideElement');
                            $('.SH_BUTTON_' + XMLID).removeClass('" . self::IsHideIconClassCSS . "');
                            $('.SH_BUTTON_' + XMLID).addClass('" . self::IsShowIconClassCSS . "');
                        } else {
                            $('.UL_' + XMLID).addClass('HideElement');
                            $('.SH_BUTTON_' + XMLID).removeClass('" . self::IsShowIconClassCSS . "');
                            $('.SH_BUTTON_' + XMLID).addClass('" . self::IsHideIconClassCSS . "');
                        }
                    });
                });
            </script>";
    }

}