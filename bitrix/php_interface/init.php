<?php
define("DEFAULT_REGISTER_USER_PRICE_ID", "6");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/EventHandlers/EventHandlers.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/agents/PromotionsReportAgent.php";
//
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";

require __DIR__ . "/functions.php";

function weekendDiscount($apply) {
    $userGroup = array(1, 12);
    if($apply) {
        $userGroup[] = 2;
    }
    $dbPriceType = CCatalogGroup::GetList(
        array("SORT" => "ASC"),
        array("XML_ID" => "feff0695-99ab-11db-937f-000e0c431b59")
    );
    while ($arPriceType = $dbPriceType->Fetch())
    {
        $arFields = array(
            "USER_GROUP" => $userGroup,
            "USER_GROUP_BUY" => $userGroup,
        );
        CCatalogGroup::Update($arPriceType["ID"], $arFields);
    }
}
function weekendDiscountApply() {
    weekendDiscount(true);
    return "weekendDiscountApply();";
}
function weekendDiscountCancel() {
    weekendDiscount(false);
    return "weekendDiscountCancel();";
}

function updateActiveCatalog() {
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
    return "updateActiveCatalog();";
}

AddEventHandler("sale", "OnSaleBeforeStatusOrder", "SUD");
function SUD($ID, $val)
{
    CModule::IncludeModule('sale');
    if($val)
    {
        $arSelect = array(
            'ID',
            'STATUS_ID',
        );
        $arFilter = Array(
            'ID' => $ID,
        );
        $rsSales = CSaleOrder::GetList(array('DATE_INSERT' => 'DESC'), $arFilter, false, false, $arSelect);
        $statusId = "N";
        while ($arSales = $rsSales->Fetch())
        {
            $statusId = $arSales['STATUS_ID'];
        }
        if($statusId == 'F')
        {
            GLOBAL $APPLICATION;
            $APPLICATION->throwException("Статус не изменен. Нельзя сменить статус заказа который был завершен!");
            return false;
        }
    }
}

/** Агент удаляет товары из папки "Товары на удаление с сайта", при условии что товаров в этой папке менее 2000
 * Если товаров более 2000, отправляет уведомление на почту о превышении
 * @return string
 */
function deleteUnusedProducts() {
    $arSelect = Array("ID",);
    $arFilter = Array("IBLOCK_ID"=>APLS_CatalogHelper::getShopIblockId(),"SECTION_CODE"=>"tovar_na_udalenie_s_sayta");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
    while($ar = $res->Fetch())
    {
        $resAr[] = $ar["ID"];
    }
    if (!empty($resAr)) {
        $countAr = count($resAr);
        if ($countAr <= "2000") {
            foreach ($resAr as $element) {
                CIBlockElement::Delete($element);
            }
        } else {
            CEvent::Send("DELETE_ERROR","s1",array("COUNT"=>$countAr));
        }
    }
    return "deleteUnusedProducts();";
}