<?php
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "DoItAfterUpdateElement");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "DoItAfterUpdateElement");

define("SHOP_IBLOC_ID_FOR_THIS_SCRIPT", "13");
define("ACTIVE_TRIGGER_PROPERTY_XML_ID", "26e05687-c602-4c36-8b63-debb1b4e0250");
define("ACTIVE_TRIGGER_PROPERTY_VALUE_DEACTIVE", "Нет");
define("ACTIVE_TRIGGER_PROPERTY_VALUE_ACTIVE", "Да");
define("SITE_DEL_SECTION_XML_ID","98557c36-f99a-11e6-80ec-00155dfef48a");

if(CModule::IncludeModule("iblock")) {
    $rs = CIBlockSection::GetList(array(), array("XML_ID"=>SITE_DEL_SECTION_XML_ID), false, array("nTopCount"=>1));
    if($obRes = $rs->GetNextElement())
    {
        $arRes = $obRes->GetFields();
        define("SITE_DEL_SECTION_ID", $arRes['ID']);
    }
    if($arRes['ACTIVE'] === "Y") {
        $sec = new CIBlockSection;
        $arLoadProductArray = Array("ACTIVE" => "N");
        $sec->Update(SITE_DEL_SECTION_ID, $arLoadProductArray);
    }
}

function DoItAfterUpdateElement (&$arFields) {
	if ($arFields["IBLOCK_ID"] == SHOP_IBLOC_ID_FOR_THIS_SCRIPT && $arFields["RESULT"]) {
		$rs = CIBlockElement::GetList(array(), array("ID"=>$arFields["ID"]), false, array("nTopCount"=>1));

		if($obRes = $rs->GetNextElement())
		{
			$arRes = $obRes->GetFields();
            $el = new CIBlockElement;
            $updateArray = array();
			if($arRes['IBLOCK_SECTION_ID'] === SITE_DEL_SECTION_ID) {
                if($arRes['ACTIVE'] === "Y") {
                    $updateArray["ACTIVE"] = "N";
//                    $arLoadProductArray = Array("ACTIVE" => "N");
//                    $el->Update($arFields["ID"], $arLoadProductArray);
                }
            } else {
                $arRes["PROPERTIES"] = $obRes->GetProperties();
                foreach ($arRes["PROPERTIES"] as $key => $val) {
                    if($val["XML_ID"] === ACTIVE_TRIGGER_PROPERTY_XML_ID) {
                        if($val["VALUE"] === ACTIVE_TRIGGER_PROPERTY_VALUE_DEACTIVE && $arRes["ACTIVE"] === "Y") {
                            $updateArray["ACTIVE"] = "N";
//                            $arLoadProductArray = Array("ACTIVE" => "N");
//                            $el->Update($arFields["ID"], $arLoadProductArray);
                        } elseif ($val["VALUE"] === ACTIVE_TRIGGER_PROPERTY_VALUE_ACTIVE && $arRes["ACTIVE"] === "N") {
                            $updateArray["ACTIVE"] = "Y";
//                            $arLoadProductArray = Array("ACTIVE" => "Y");
//                            $el->Update($arFields["ID"], $arLoadProductArray);
                        }
                    }
                }
            }
            if(!empty($updateArray)) {
                $el->Update($arFields["ID"], $updateArray);
            }
		}
	}
}