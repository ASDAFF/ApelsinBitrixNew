<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogProperties.php";

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", ["APLS_ActivateUpdater", "getDelCatalogValue"]);

class APLS_ActivateUpdater
{
    const SITE_DEL_SECTION_XML_ID = "98557c36-f99a-11e6-80ec-00155dfef48a";
    const AKTIVNOST_XML_ID = "26e05687-c602-4c36-8b63-debb1b4e0250";

    public function __construct()
    {

    }

    public static function getDelCatalogValue(&$arFields) {
        $aktivnost_id = APLS_CatalogProperties::convertPropertyXMLIDtoID(self::AKTIVNOST_XML_ID);
        $aktivnost_code = APLS_CatalogProperties::convertPropertyXMLIDtoCODE(self::AKTIVNOST_XML_ID);
        $rs = CIBlockElement::GetList(array(), array("ID" => $arFields["ID"]), false, array("nTopCount" => 1));
        $res_arr = $rs->Fetch();
        if(isset($arFields["ACTIVE"])) {
            $activeValue = $arFields["ACTIVE"];
        } else {
            // получаем значение из товара
            $activeValue = $res_arr["ACTIVE"];
        }
        if ($res_arr['IBLOCK_SECTION_ID'] === self::getNonActiveProducts() && $activeValue === "Y") {
            $arFields["ACTIVE"] = "N";
        } else {
            $arr = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], array(), array("CODE" => $aktivnost_code));
            if($props = $arr->Fetch()) {
                if(isset($arFields["PROPERTY_VALUES"][$aktivnost_id][0]["VALUE"]) && $arFields["PROPERTY_VALUES"][$aktivnost_id][0]["VALUE"] !== $props["VALUE"]) {
                    $yes = "false";
                    $no = "true";
                } else {
                    $yes = "true";
                    $no = "false";
                }
                if ($activeValue === "N" && $props["VALUE_XML_ID"] === $yes) {
                    $arFields["ACTIVE"] = "Y";
                } elseif ($activeValue === "Y" && $props["VALUE_XML_ID"] === $no) {
                    $arFields["ACTIVE"] = "N";
                }
            }
        }
    }

    protected static function getNonActiveProducts() {
        if (CModule::IncludeModule("iblock")) {
            $rs = CIBlockSection::GetList(array(), array("XML_ID" => self::SITE_DEL_SECTION_XML_ID), false, array("nTopCount" => 1));
            if ($obRes = $rs->GetNextElement()) {
                $arRes = $obRes->GetFields();
                if ($arRes['ACTIVE'] === "Y") {
                    $sec = new CIBlockSection;
                    $arLoadProductArray = Array("ACTIVE" => "N");
                    $sec->Update($arRes['ID'], $arLoadProductArray);
                }
                return $arRes["ID"];
            }
        }
    }
}

