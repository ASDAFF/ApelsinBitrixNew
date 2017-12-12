<?
CJSCore::Init(array("jquery2"));
CJSCore::Init(array('ajax'));
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortList.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElement.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElements.php";

$sections = APLS_CatalogSections::getSections();
$sortListNotDone = new APLS_SortList();
$sortListNotDone->setSortListTitle("Без картинок");
$sortListIsDone = new APLS_SortList();
$sortListIsDone->setSortListTitle("Картинку сделали");
$sortListElements = new APLS_SortListElements;
$catalogUrl = "http://" . $_SERVER["SERVER_NAME"] . "/catalog/";

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

foreach ($sections as $section) {
    if ($section["PICTURE"] === null) {
        $nodes = APLS_CatalogSections::getPathToRootForSection($section["XML_ID"]);
        if (!empty($nodes)) {
            $pathToRoot = "";
            foreach ($nodes as $node) {
                $pathToRoot = getAplsBreadcrumbItem($sections[$node]["NAME"], $catalogUrl.$sections[$node]["CODE"]) . $pathToRoot;
            }
            $pathToRoot .= getAplsBreadcrumbItem($section["NAME"],null, "onclick='APLS_copyToClipboardSectionGUID(\"".$section["XML_ID"]."\",\"".$section["NAME"]."\")'");
            $sortListElements->addSortListElement(new APLS_SortListElement("<span class='pathToRoot'>" . $pathToRoot . "</span>"));
        }
    }
}
$sortListNotDone->setSortListElements($sortListElements);

$arResult['sortListNotDone'] = $sortListNotDone;
$arResult['sortListIsDone'] = $sortListIsDone;

$this->IncludeComponentTemplate();