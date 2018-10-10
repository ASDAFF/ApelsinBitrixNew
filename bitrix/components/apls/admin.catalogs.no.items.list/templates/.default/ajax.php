<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";

$items = array();
$shopIBlockId = APLS_CatalogHelper::getShopIblockId();
$whereIBLOCKID = MySQLWhereElementString::getBinaryOperationString('IBLOCK_ID', MySQLWhereElementString::OPERATOR_B_EQUAL, $shopIBlockId);
$whereACTIVE = MySQLWhereElementString::getBinaryOperationString('ACTIVE', MySQLWhereElementString::OPERATOR_B_EQUAL, 'Y');
$whereObj = new MySQLWhereString();
$whereObj->addElement($whereIBLOCKID);
$whereObj->addElement($whereACTIVE);
$orderByObj = new MySQLOrderByString();
$orderByObj->add('IBLOCK_SECTION_ID', MySQLOrderByString::ASC);
$items = CatalogElementModel::getElementList($whereObj, null, null, $orderByObj, 'IBLOCK_SECTION_ID');
$sectionsIDtoXMLID = APLS_CatalogSections::getSectionsIDtoXMLID();
foreach ($items as $item) {
    if (isset($sectionsIDtoXMLID[$item->getFieldValue('IBLOCK_SECTION_ID')])) {
        unset($sectionsIDtoXMLID[$item->getFieldValue('IBLOCK_SECTION_ID')]);
    }
}
$updateCatalog = new CIBlockSection();
foreach ($sectionsIDtoXMLID as $sectionId => $sectionXMLId) {
    if (!empty(APLS_CatalogSections::getAllChildrenListForSection($sectionXMLId))) {
        unset($sectionsIDtoXMLID[$sectionId]);
    } else {
        $updateCatalog->Update($sectionId, array('ACTIVE'=>'N'));
    }
}