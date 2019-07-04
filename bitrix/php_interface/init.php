<?php
define("DEFAULT_REGISTER_USER_PRICE_ID", "6");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/EventHandlers/EventHandlers.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/agents/PromotionsReportAgent.php";
//
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementPropertyModel.php";

require __DIR__ . "/functions.php";

function updateAmountInfo() {
    $CATALOG_IBLOCK = APLS_CatalogHelper::getShopIblockId();
    $FOR_SALE_PROPERTY_CODE = "SKRYVATNULEVOYOSTATOK";
    $AMOUNT_PROPERTY_CODE = "AMOUNT_STATUS";
    $AMOUNT_STATUS = array(
        "IN_STOCK" => array("ID"=>"","VALUE"=>"В наличии"),
        "NOT_FOR_SALE" => array("ID"=>"","VALUE"=>"Ожидает поставки"),
        "UNDER_THE_ORDER" => array("ID"=>"","VALUE"=>"Под заказ"),
    );
    // Устанвока соответствия значений и ID значенйи свойства
    $property_enums = CIBlockPropertyEnum::GetList(Array("ID"=>"ASC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$CATALOG_IBLOCK, "CODE"=>$AMOUNT_PROPERTY_CODE));
    while ($enum_fields = $property_enums->GetNext()) {
        foreach ($AMOUNT_STATUS as $key => $el) {
            if($enum_fields["VALUE"] == $el["VALUE"]) {
                $AMOUNT_STATUS[$key]["ID"] = $enum_fields["ID"];
            }
        }
    }
    // получаем список складов
    $stores = array();
    $select_fields = Array();
    $filter = Array("ACTIVE" => "Y");
    $resStore = CCatalogStore::GetList(array(),$filter,false,false,$select_fields);
    while($store = $resStore->Fetch()) {
        $stores[] = $store;
    }
    $storesId = array();
    foreach ($stores as $store) {
        $storesId[] = $store["ID"];
    }
    // получаем список товаров
    if(CModule::IncludeModule("iblock")) {
        $elements = CIBlockElement::GetList (
            Array("ID" => "ASC"),
            Array("IBLOCK_ID" => $CATALOG_IBLOCK),
            false,
            false,
            Array('ID','PROPERTY_'.$FOR_SALE_PROPERTY_CODE,'PROPERTY_'.$AMOUNT_PROPERTY_CODE)
        );
        while($element= $elements->GetNext())
        {
            $FOR_SALE = $element['PROPERTY_'.$FOR_SALE_PROPERTY_CODE.'_VALUE'] == "Да";
            $rsStore = CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' => $element['ID'], 'STORE_ID'=>$storesId), false, false, array('AMOUNT'));
            $sum = array();
            $amount = 0;
            while ($arStore = $rsStore->Fetch()) {
                $sum[] = $arStore['AMOUNT'];
                $amount += $arStore['AMOUNT'];
            }
            if($amount > 0) {
                $key = "IN_STOCK";
            } else if($FOR_SALE) {
                $key = "NOT_FOR_SALE";
            } else {
                $key = "UNDER_THE_ORDER";
            }
            if($AMOUNT_STATUS[$key]["VALUE"] != $element['PROPERTY_'.$AMOUNT_PROPERTY_CODE.'_VALUE']) {
                CIBlockElement::SetPropertyValues($element['ID'], $CATALOG_IBLOCK, $AMOUNT_STATUS[$key]["ID"], $AMOUNT_PROPERTY_CODE);
                $el = new CIBlockElement;
                $el->Update($element['ID'], array());
            }
        }
    }
    return "updateAmountInfo();";
}

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
 * 03.07.19 - добавление функционала удаления дублей, совпадающих по свойству Код товара
 * Если товаров более 2000, отправляет уведомление на почту о превышении
 * @return string
 */
function deleteUnusedProducts() {
    $arSelect = Array("ID","PROPERTY_ARTNUMBER_VALUE");
    $arFilter = Array("IBLOCK_ID"=>APLS_CatalogHelper::getShopIblockId(),"SECTION_CODE"=>"tovar_na_udalenie_s_sayta");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
    $keyString = "";
    $searchTrigger = false;
    while($ar = $res->Fetch())
    {
        $resAr[] = $ar["ID"];
        $keyString .= "'".$ar["PROPERTY_ARTNUMBER_VALUE"]."',";
        if(
            !$searchTrigger &&
            isset($ar["PROPERTY_ARTNUMBER_VALUE"]) &&
            $ar["PROPERTY_ARTNUMBER_VALUE"] != null &&
            $ar["PROPERTY_ARTNUMBER_VALUE"] != "") {
            $searchTrigger = true;
        }
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
    if ($searchTrigger) {
        $keyString = substr ($keyString,'0','-1');
        $w1 = MySQLWhereElementString::getBinaryOperationString('IBLOCK_PROPERTY_ID',MySQLWhereElementString::OPERATOR_B_EQUAL,'6219');
        $w2 = MySQLWhereElementString::getBinaryOperationString('VALUE',MySQLWhereElementString::OPERATOR_B_IN, $keyString,"","",array('IS_FIELD'),array("WITHOUT_QUOTES","IS_SQL"));
        $where = new MySQLWhereString(MySQLWhereString::AND_BLOCK);
        $where->addElement($w1);
        $where->addElement($w2);
        $dataArr = CatalogElementPropertyModel::getElementList($where);
        foreach ($dataArr as $element) {
            $resArray[] = $element->getFieldValue("ID");
        }
        if (!empty($resArray)) {
            foreach ($resArray as $elementId) {
                CIBlockElement::Delete($elementId);
            }
        }
    }
    return "deleteUnusedProducts();";
}