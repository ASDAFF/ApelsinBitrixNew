<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";

$revision = new PromotionRevisionModel($_REQUEST['revisionId']);
$revisionSections = $revision->getCatalogSections();

function getAplsBreadcrumbItem($title)
{
    $out = "<div class='breadcrumb__item no-select-item'>";
    $out .= "<span class='breadcrumb__title'>$title</span>";
    $out .= "</div>";
    return $out;
}

?>
<div class="ListOfElements BreadcrumbElements">
<?foreach ($revisionSections as $revisionSection):
    if($revisionSection instanceof PromotionCatalogSection):
        $sectionXmlId = $revisionSection->getFieldValue('section');
        $section = APLS_CatalogSections::getSection($sectionXmlId);
        $nodes = APLS_CatalogSections::getPathToRootForSection($section["XML_ID"]);
        $pathToRoot = "";
        foreach ($nodes as $node) {
            $sectionChild = APLS_CatalogSections::getSection($node);
            $pathToRoot = getAplsBreadcrumbItem($sectionChild["NAME"]) . $pathToRoot;
        }
        $pathToRoot .= getAplsBreadcrumbItem($section["NAME"]);
        $content = "<div class='breadcrumbWrapper'>" . $pathToRoot . "</div>";
        ?>
        <div class="ElementBlock">
            <div class="ElementBlockContent" tableId="<?=$revisionSection->getId()?>" type="section">
                <?=$content?>
                <div class="DellButton"></div>
            </div>
        </div>
    <?endif;
endforeach;?>
</div>