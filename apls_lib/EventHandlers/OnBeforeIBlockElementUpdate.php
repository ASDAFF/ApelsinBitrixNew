<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogProperties.php";

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", ["APLS_ActivateUpdater", "init"]);

class APLS_ActivateUpdater
{
    const SITE_DEL_SECTION_XML_ID = "98557c36-f99a-11e6-80ec-00155dfef48a";
    const AKTIVNOST_XML_ID = "26e05687-c602-4c36-8b63-debb1b4e0250";
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
    private static $dimensions_koeff_id = array();
    private static $dimensions_id = array();
    private static $siteDelSectionId;

    private function __construct()
    {
        try {
            self::$entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("UpdateDimentions");
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
        }
        self::$aktivnost_id = APLS_CatalogProperties::convertPropertyXMLIDtoID(self::AKTIVNOST_XML_ID);
        self::$aktivnost_code = APLS_CatalogProperties::convertPropertyXMLIDtoCODE(self::AKTIVNOST_XML_ID);
        self::$dimensions_koeff_id = APLS_CatalogProperties::convertPropertyXMLIDtoID(self::KOEFF_XML_ID);
        foreach (self::XML_ARRAY as $key => $code) {
            self::$dimensions_id[$key] = APLS_CatalogProperties::convertPropertyXMLIDtoID($code);
        }
        self::$siteDelSectionId = self::getNonActiveProducts();
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
        self::getInstance();
        $rs = CIBlockElement::GetList(array(), array("ID" => $arFields["ID"]), false, array("nTopCount" => 1));
        $res_arr = $rs->Fetch();
        $activeValue = isset($arFields["ACTIVE"]) ? $arFields["ACTIVE"] : $res_arr["ACTIVE"];
        if ($res_arr['IBLOCK_SECTION_ID'] === self::$siteDelSectionId && $res_arr["ACTIVE"] === "Y") {
            $arFields["ACTIVE"] = "N";
        } else {
            $arr = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], array(), array("CODE" => self::$aktivnost_code));
            if ($props = $arr->Fetch()) {
                if (
                    self::getArrayPropertyValue($arFields,self::$aktivnost_id) !== $props["VALUE"] &&
                    self::getArrayPropertyValue($arFields,self::$aktivnost_id) !== NULL
                ) {
                    $yes = "false"; $no = "true";
                } else {
                    $yes = "true"; $no = "false";
                }
                if ($activeValue === "N" && $props["VALUE_XML_ID"] === $yes) {
                    $arFields["ACTIVE"] = "Y";
                } elseif ($activeValue === "Y" && $props["VALUE_XML_ID"] === $no) {
                    $arFields["ACTIVE"] = "N";
                }
            }
        }
        $updateArray = array();
        //Получение значений размеров из торгового каталога товара
        $dimensionsCatalogValues = self::getCatalogValue($arFields["ID"]);
        $dimensionsCatalogValues["KOEFF"] = self::getCoefficientValue($arFields["IBLOCK_ID"], $arFields["ID"]);
        $dimensionsHighloadValue = self::getHLbValue($arFields["XML_ID"]);
//        echo "<pre>";
//        var_dump($dimensionsHighloadValue);
//        die;
        //Проверка необходимости обновления размеров и коэффициэнта
        foreach (self::XML_ARRAY as $key => $value) {
            $ufkey = "UF_".$key;
            $dimensionsEditValue = self::getArrayPropertyValue($arFields,self::$dimensions_id[$key]);

//            echo "<pre>";
//            var_dump($dimensionsEditValue);
//            var_dump($dimensionsCatalogValues[$key]);
//            var_dump($dimensionsHighloadValue[$ufkey]);

            if (self::checkEmptyValue($dimensionsEditValue)) {
                if(
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
//                    var_dump(null);
                    $updateArray["UF_XMLID"] = $arFields["XML_ID"];
                    $updateArray[$ufkey] = null;
                }
                /*
                 * HL
                 * a1 не быть
                 * a2 быть такимже как и в изменении
                 * a3 быть не таким как в изменении
                 *
                 * Товар
                 * b1 не быть
                 * b2 быть как в изменении
                 * b3 быть не таким как в изменении
                 *
                 * a1b1 - изменения
                 * a1b3 - изменения
                 * a3b1 - изменение
                 * a3b3 - изменение
                 *
                 * a2b2 - пустота
                 * a3b2 - пустота
                 *
                 * a1b2 - игнорим
                 * a2b1 - игнорим
                 * a2b3 - игнорим
                 *
                 * */


//
//                $updateArray["UF_XMLID"] = $arFields["XML_ID"];
//                $updateArray["UF_" . $key] = $dimensionsEditValue;
            }
        }
//        var_dump($updateArray);
//        die;
        self::setHLbValue($updateArray,$arFields["XML_ID"]);
    }

    private static function checkEmptyValue($val) {
        return $val !== NULL && $val != 0 && trim($val) !== "";
    }

    private static function getArrayPropertyValue($arFields,$key)
    {
        $val = array_shift(array_values($arFields["PROPERTY_VALUES"][$key]));
        return $val["VALUE"];
    }

    private static function setHLbValue($updateArray,$xmlId)
    {
        if (!empty($updateArray)) {
            $rsData = self::$entity_data_class::getList(array(
                "select" => array('ID'), //выбираем все поля
                "filter" => ["UF_XMLID"=>$xmlId],
                "order" => array("ID"=>"ASC") // сортировка по полю UF_SORT, будет работать только, если вы завели такое поле в hl'блоке
            ));
            while($arRes = $rsData->Fetch()){
                return self::$entity_data_class::update($arRes["ID"], $updateArray);
            }
            return self::$entity_data_class::add($updateArray);
        }
    }

    private static function getHLbValue($xmlId) {
        $rsData = self::$entity_data_class::getList(array(
            "select" => array('*'), //выбираем все поля
            "filter" => ["UF_XMLID"=>$xmlId],
            "order" => array("ID"=>"ASC") // сортировка по полю UF_SORT, будет работать только, если вы завели такое поле в hl'блоке
        ));
        while($arRes = $rsData->Fetch()){
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
