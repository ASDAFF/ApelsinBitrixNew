<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogProperties.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", ["APLS_ActivateUpdater", "init"]);
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", ["APLS_ActivateUpdater", "init"]);

class APLS_ActivateUpdater
{
    const SITE_DEL_SECTION_XML_ID = "98557c36-f99a-11e6-80ec-00155dfef48a";
    const AKTIVNOST_XML_ID = "26e05687-c602-4c36-8b63-debb1b4e0250";
    const CHECKEDPRODUCT_XML_ID = "26e05687-c602-4c36-8b63-desc5g0XN322";
    const KOEFF_XML_ID = "26e05687-c602-4c36-8b63-debb3g4eu248";
    const XML_ARRAY = array(
        "KOEFF" => "26e05687-c602-4c36-8b63-debb3g4eu248",
        "LENGTH" => "26e05687-c602-4c36-8b63-debb3g4eu245",
        "WIDTH" => "26e05687-c602-4c36-8b63-debb3g4eu246",
        "HEIGHT" => "26e05687-c602-4c36-8b63-debb3g4eu247");
    const arCODE = array(
        "DOSTAVKADLINA" => "LENGTH",
        "DOSTAVKASHIRINA" => "WIDTH",
        "DOSTAVKAVYSOTA" => "HEIGHT"
    );
    private static $instance = null;
    private static $entity_data_class;
    private static $aktivnost_id;
    private static $aktivnost_code;
    private static $checkedProduct_code;
    private static $checkedProduct_id;
    private static $dimensions_koeff_id = array();
    private static $dimensions_id = array();
    private static $siteDelSectionId;
    private static $options = array();

    private function __construct()
    {
        try {
            self::$entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("UpdateDimentions");
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
        }
        self::$aktivnost_id = APLS_CatalogProperties::convertPropertyXMLIDtoID(self::AKTIVNOST_XML_ID);
        self::$aktivnost_code = APLS_CatalogProperties::convertPropertyXMLIDtoCODE(self::AKTIVNOST_XML_ID);
        self::$checkedProduct_id = APLS_CatalogProperties::convertPropertyXMLIDtoID(self::CHECKEDPRODUCT_XML_ID);
        self::$checkedProduct_code = APLS_CatalogProperties::convertPropertyXMLIDtoCODE(self::CHECKEDPRODUCT_XML_ID);
        self::$dimensions_koeff_id = APLS_CatalogProperties::convertPropertyXMLIDtoID(self::KOEFF_XML_ID);
        foreach (self::XML_ARRAY as $key => $code) {
            self::$dimensions_id[$key] = APLS_CatalogProperties::convertPropertyXMLIDtoID($code);
        }
        self::$siteDelSectionId = self::getNonActiveProducts();
        $property_enums = CIBlockPropertyEnum::GetList(
            Array("DEF"=>"DESC", "SORT"=>"ASC"),
            Array("IBLOCK_ID"=>APLS_CatalogHelper::getShopIblockId(), "CODE"=>self::$checkedProduct_code)
        );
        while($enum_fields = $property_enums->GetNext())
        {
            self::$options[$enum_fields["ID"]] = $enum_fields["XML_ID"];
        }
    }

    private static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public static function init(&$arFields)
    {
        if($arFields["IBLOCK_ID"] != APLS_CatalogHelper::getShopIblockId()) {
            return;
        }
        self::getInstance();
        $rs = CIBlockElement::GetList(array(), array("ID" => $arFields["ID"]), false, array("nTopCount" => 1));
        $res_arr = $rs->Fetch();
        $activeValue = isset($arFields["ACTIVE"]) ? $arFields["ACTIVE"] : $res_arr["ACTIVE"];
        if ($res_arr['IBLOCK_SECTION_ID'] === self::$siteDelSectionId && $res_arr["ACTIVE"] === "Y") {
            $arFields["ACTIVE"] = "N";
        } else {
            if (self::$options[self::getArrayPropertyValue($arFields, self::$checkedProduct_id)] == "true") {
                $arr_akt = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], array(), array("CODE" => self::$aktivnost_code));
                if ($prop_akt = $arr_akt->Fetch()) {
                    if (
                        self::getArrayPropertyValue($arFields, self::$aktivnost_id) !== $prop_akt["VALUE"] &&
                        self::getArrayPropertyValue($arFields, self::$aktivnost_id) !== NULL
                    ) {
                        $yes = "false";
                        $no = "true";
                    } else {
                        $yes = "true";
                        $no = "false";
                    }
                    if ($activeValue === "N" && $prop_akt["VALUE_XML_ID"] === $yes) {
                        $arFields["ACTIVE"] = "Y";
                    } elseif ($activeValue === "Y" && $prop_akt["VALUE_XML_ID"] === $no) {
                        $arFields["ACTIVE"] = "N";
                    }
                }
            } else {
                $arFields["ACTIVE"] = "N";
            }
        }

        $updateArray = array();
        //Получение значений размеров из торгового каталога товара
        $dimensionsCatalogValues = self::getCatalogValue($arFields["ID"]);
        $dimensionsCatalogValues["KOEFF"] = self::getCoefficientValue($arFields["IBLOCK_ID"], $arFields["ID"]);
        $dimensionsHighloadValue = self::getHLbValue($arFields["XML_ID"]);
        //Проверка необходимости обновления размеров и коэффициэнта
        foreach (self::XML_ARRAY as $key => $value) {
            $ufkey = "UF_" . $key;
            $dimensionsEditValue = self::getArrayPropertyValue($arFields, self::$dimensions_id[$key]);
            if (self::checkEmptyValue($dimensionsEditValue)) {
                if (
                    (!self::checkEmptyValue($dimensionsHighloadValue[$ufkey]) && !self::checkEmptyValue($dimensionsCatalogValues[$key])) ||
                    (!self::checkEmptyValue($dimensionsHighloadValue[$ufkey]) && $dimensionsCatalogValues[$key] !== $dimensionsEditValue) ||
                    (!self::checkEmptyValue($dimensionsCatalogValues[$key]) && $dimensionsHighloadValue[$ufkey] !== $dimensionsEditValue) ||
                    ($dimensionsCatalogValues[$key] !== $dimensionsEditValue && $dimensionsHighloadValue[$ufkey] !== $dimensionsEditValue)
                ) {
//                    var_dump($dimensionsEditValue);
                    $updateArray["UF_XMLID"] = $arFields["XML_ID"];
                    $updateArray[$ufkey] = $dimensionsEditValue;
                } elseif (
                    ($dimensionsCatalogValues[$key] === $dimensionsEditValue && $dimensionsHighloadValue[$ufkey] === $dimensionsEditValue) ||
                    ($dimensionsCatalogValues[$key] === $dimensionsEditValue && $dimensionsHighloadValue[$ufkey] !== $dimensionsEditValue)
                ) {
                    $updateArray["UF_XMLID"] = $arFields["XML_ID"];
                    $updateArray[$ufkey] = null;
                }
            }
        }
        self::setHLbValue($updateArray, $arFields["XML_ID"]);
    }

    private static function checkEmptyValue($val)
    {
        return $val !== NULL && $val != 0 && trim($val) !== "";
    }

    private static function getArrayPropertyValue($arFields, $key)
    {
        $val = array_shift(array_values($arFields["PROPERTY_VALUES"][$key]));
        return $val["VALUE"];
    }

    private static function setHLbValue($updateArray, $xmlId)
    {
        if (!empty($updateArray)) {
            $rsData = self::$entity_data_class::getList(array(
                "select" => array('ID'), //выбираем все поля
                "filter" => ["UF_XMLID" => $xmlId],
                "order" => array("ID" => "ASC") // сортировка по полю UF_SORT, будет работать только, если вы завели такое поле в hl'блоке
            ));
            while ($arRes = $rsData->Fetch()) {
                return self::$entity_data_class::update($arRes["ID"], $updateArray);
            }
            return self::$entity_data_class::add($updateArray);
        }
    }

    private static function getHLbValue($xmlId)
    {
        $rsData = self::$entity_data_class::getList(array(
            "select" => array('*'), //выбираем все поля
            "filter" => ["UF_XMLID" => $xmlId],
            "order" => array("ID" => "ASC") // сортировка по полю UF_SORT, будет работать только, если вы завели такое поле в hl'блоке
        ));
        while ($arRes = $rsData->Fetch()) {
            return $arRes;
        }
        return array();
    }

    private static function getNonActiveProducts()
    {
        if (CModule::IncludeModule("iblock")) {
            $rs = CIBlockSection::GetList(array(), array("EXTERNAL_ID" => self::SITE_DEL_SECTION_XML_ID), false);
            if ($obRes = $rs->GetNextElement()) {
                $arRes = $obRes->GetFields();
                if ($arRes['ACTIVE'] === "Y") {
                    $sec = new CIBlockSection;
                    $sec->Update($arRes['ID'], ["ACTIVE" => "N"]);
                }
                return $arRes["ID"];
            }
        }
        return null;
    }

    private static function getCatalogValue($elementID)
    {
        $getCatalogValue = new CCatalogProduct;
        $arCatalogValue = $getCatalogValue->GetList(array("ID" => $elementID), array(), self::arCODE);
        $res_arr = $arCatalogValue->Fetch();
        $dimensionsArray = array();
        foreach (self::arCODE as $key => $dimension) {
            $dimensionsArray[self::arCODE[$key]] = $res_arr[$dimension];
        }
        return $dimensionsArray;
    }

    private static function getCoefficientValue($iblock_ID, $elementID)
    {
        $coefficientArray = CIBlockElement::GetProperty($iblock_ID, $elementID, array("sort" => "asc"), array("CODE" => "KOEFFITSIENT"));
        while ($res_arr = $coefficientArray->Fetch()) {
            return $res_arr["VALUE"];
        }
        return null;
    }
}
