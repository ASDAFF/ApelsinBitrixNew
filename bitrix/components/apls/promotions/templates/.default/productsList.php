<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogSectionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementModel.php";

$revision = $arResult["revision"];
if($revision instanceof PromotionRevisionModel) {
    $resArray["section"] = $revision->getCatalogSections();
    $resArray["products"] = $revision->getCatalogProducts();
    $resArray["exceptions"] = $revision->getCatalogExceptions();
}
if(!empty($resArray["section"])) {
    foreach ($resArray["section"] as $section) {
        $sectionXml[] = $section->getFieldValue("section");
    }
    $children = array();
    foreach ($sectionXml as $section) {
        if(empty(APLS_CatalogSections::getAllChildrenListForSection($section))) {
            $children[] = $section;
        } else {
            $children = APLS_CatalogSections::getAllChildrenListForSection($section);
            foreach ($children as $key => $val) {
                if(!empty(APLS_CatalogSections::getAllChildrenListForSection($val))) {
                    unset($children[$key]);
                }
            }
        }
    }
    foreach ($children as $child) {
        $sectionId[] = GetSectionIdByXml($child);
    }
    $elementObj = array();
    foreach ($sectionId as $id) {
        $elementObj = array_merge($elementObj,CatalogElementModel::searchBySectionId($id));
        $catalogElementIdList = array();
        foreach ($elementObj as $catalogElement) {
            if($catalogElement instanceof  CatalogElementModel) {
                $catalogElementIdList[] = $catalogElement->getFieldValue("XML_ID");
            }
        }
    }
} else {
    $catalogElementIdList = array();
}
if(!empty($resArray["products"])) {
    foreach ($resArray["products"] as $product) {
        $productElementIdList[] = $product->getFieldValue("product");
    }
} else {
    $productElementIdList = array();
}

if(!empty($resArray["exceptions"])) {
    foreach ($resArray["exceptions"] as $exception) {
        $exceptionElementIdList[] = $exception->getFieldValue("product");
    }
} else {
    $exceptionElementIdList = array();
}

$resultArrayFilterId = array();
if(!empty($catalogElementIdList) && empty($productElementIdList)){
    $resultArrayFilterId = array_diff($catalogElementIdList, $exceptionElementIdList);
}elseif(!empty($catalogElementIdList) && !empty($productElementIdList)) {
    $resultArrayFilterId = array_merge($catalogElementIdList,$productElementIdList);
    $resultArrayFilterId = array_diff($resultArrayFilterId, $exceptionElementIdList);
} elseif (empty($catalogElementIdList) && !empty($productElementIdList)) {
    $resultArrayFilterId = array_diff($productElementIdList, $exceptionElementIdList);
}

global $arPromProdPrFilter;
$arPromProdPrFilter = array("XML_ID" => $resultArrayFilterId);

function GetSectionIdByXml ($catalogXml) {
    $whereObj = new MySQLWhereString();
    $whereObj->addElement(
        MySQLWhereElementString::getBinaryOperationString(
            "XML_ID",
            MySQLWhereElementString::OPERATOR_B_EQUAL,
            $catalogXml)
    );
    $result = CatalogSectionModel::getElementList($whereObj);
    foreach ($result as $catalog) {
        if ($catalog instanceof CatalogSectionModel) {
            $catalogId = $catalog->getId();
        }
    }
    return $catalogId;
}

if(!empty($resultArrayFilterId)):
?>
<div class="promotions-detail__products">
    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
        array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR."include/promotions_products.php"
        ),
        false,
        array("HIDE_ICONS" => "Y")
    );?>
</div>
<?endif;?>