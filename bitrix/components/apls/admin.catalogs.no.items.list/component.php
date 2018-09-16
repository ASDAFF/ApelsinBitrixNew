<?
CJSCore::Init(array("jquery2"));
CJSCore::Init(array('ajax'));
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortList.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElement.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElements.php";

function getAplsBreadcrumbItem($title, $href = null, $js = "")
{
    $out = "<div class='breadcrumb__item no-select-item'>";
    if($href !== null) {
        $out .= "<a class='breadcrumb__link' href='$href' title='$title' target='_blank' $js>";
        $out .= "<span class='breadcrumb__title'>$title</span>";
        $out .= "</a>";
    } else {
        $out .= "<span class='breadcrumb__title' $js>$title</span>";
    }
    $out .= "</div>";
    return $out;
}

$catalogUrl = "http://" . $_SERVER["SERVER_NAME"] . "/catalog/";
$items = array();
$sections = APLS_CatalogSections::getSections();
$shopIBlockId = APLS_CatalogHelper::getShopIblockId();
$whereIBLOCKID = MySQLWhereElementString::getBinaryOperationString('IBLOCK_ID',MySQLWhereElementString::OPERATOR_B_EQUAL, $shopIBlockId);
$whereACTIVE = MySQLWhereElementString::getBinaryOperationString('ACTIVE',MySQLWhereElementString::OPERATOR_B_EQUAL, 'Y');
$whereObj = new MySQLWhereString();
$whereObj->addElement($whereIBLOCKID);
$whereObj->addElement($whereACTIVE);
$orderByObj = new MySQLOrderByString();
$orderByObj->add('IBLOCK_SECTION_ID',MySQLOrderByString::ASC);
$items = CatalogElementModel::getElementList($whereObj,null,null,$orderByObj,'IBLOCK_SECTION_ID');
$sectionsIDtoXMLID = APLS_CatalogSections::getSectionsIDtoXMLID();


foreach ($items as $item) {
    if(isset($sectionsIDtoXMLID[$item->getFieldValue('IBLOCK_SECTION_ID')])) {
        unset($sectionsIDtoXMLID[$item->getFieldValue('IBLOCK_SECTION_ID')]);
    }
}

foreach ($sectionsIDtoXMLID as $sectionId => $sectionXMLId) {
    if(!empty(APLS_CatalogSections::getAllChildrenListForSection($sectionXMLId))){
        unset($sectionsIDtoXMLID[$sectionId]);
    }
}

$sortListElements = new APLS_SortListElements();
foreach ($sectionsIDtoXMLID as $sectionId => $sectionXMLId) {
    if($item instanceof CatalogElementModel) {
        $section = APLS_CatalogSections::getSection($sectionXMLId);
        $nodes = APLS_CatalogSections::getPathToRootForSection($section["XML_ID"]);
        $pathToRoot = "";
        if (!empty($nodes)) {
            foreach ($nodes as $node) {
                $pathToRoot = getAplsBreadcrumbItem($sections[$node]["NAME"], $catalogUrl.$sections[$node]["CODE"]) . $pathToRoot;
            }
        }
        $pathToRoot = getAplsBreadcrumbItem($section["NAME"], $catalogUrl.$section["CODE"]) . $pathToRoot;
//        $pathToRoot .= getAplsBreadcrumbItem($section["NAME"],null, "onclick='APLS_copyToClipboardSectionGUID(\"".$section["XML_ID"]."\",\"".$section["NAME"]."\")'");
        $sortListElements->addSortListElement(new APLS_SortListElement("<span class='pathToRoot'>" . $pathToRoot . "</span>"));
    }
}
$sortListNotDone = new APLS_SortList($sortListElements);
$sortListNotDone->setSortListElements($sortListElements);
$arResult['sortListNotDone'] = $sortListNotDone;


$this->IncludeComponentTemplate();