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
$whereDETAILPICTURE = MySQLWhereElementString::getUnaryOperationString('DETAIL_PICTURE',MySQLWhereElementString::OPERATOR_U_NULL);
$whereObj = new MySQLWhereString();
$whereObj->addElement($whereIBLOCKID);
$whereObj->addElement($whereACTIVE);
$whereObj->addElement($whereDETAILPICTURE);
$orderByObj = new MySQLOrderByString();
$orderByObj->add('IBLOCK_SECTION_ID',MySQLOrderByString::ASC);
$items = CatalogElementModel::getElementList($whereObj,500,null,$orderByObj);
$count = CatalogElementModel::getElementList($whereObj,'',null,$orderByObj);
$sortListElements = new APLS_SortListElements();
foreach ($items as $item) {
    if($item instanceof CatalogElementModel) {
        $name = $item->getFieldValue("NAME");
        $code = $item->getFieldValue("CODE");
        $sectionId = $item->getFieldValue("IBLOCK_SECTION_ID");
        $xmlId = $item->getFieldValue("XML_ID");
        $sectionXMLId = APLS_CatalogSections::convertSectionsIDtoXMLID($sectionId);
        $section = APLS_CatalogSections::getSection($sectionXMLId);
        $nodes = APLS_CatalogSections::getPathToRootForSection($section["XML_ID"]);
        $pathToRoot = "";
        if (!empty($nodes)) {
            foreach ($nodes as $node) {
                $pathToRoot = getAplsBreadcrumbItem($sections[$node]["NAME"], $catalogUrl.$sections[$node]["CODE"]) . $pathToRoot;
            }
        }
        $pathToRoot .= getAplsBreadcrumbItem($section["NAME"],$catalogUrl.$section["CODE"]);
//        $pathToRoot .= getAplsBreadcrumbItem($name,null, "onclick='APLS_copyToClipboardItemName(\"".$name."\")'");
        $pathToRoot .= getAplsBreadcrumbItem($name,$catalogUrl.$section["CODE"]."/".$code);
        $sortListElements->addSortListElement(new APLS_SortListElement("<span class='pathToRoot'>" . $pathToRoot . "</span>"));
    }
}
$sortListNotDone = new APLS_SortList($sortListElements);
$sortListNotDone->setSortListElements($sortListElements);
$arResult['sortListNotDone'] = $sortListNotDone;
$arResult['count'] = count($count);


$this->IncludeComponentTemplate();