<?php
if(empty($_SERVER["HTTP_REFERER"]))
    die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";

CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

use Bitrix\Highloadblock,
    Bitrix\Main\Entity,
    Bitrix\Highloadblock\HighloadBlockTable,
    Bitrix\Catalog\MeasureRatioTable,
    Bitrix\Catalog;

function convertProductXML_to_ID($XMLid) {
    $productIDObj = CIBlockElement::GetList(array(), array("XML_ID"=>$XMLid), false, array(), array("ID"));
    return $productIDObj->Fetch();
}

$error = array();
if($_REQUEST["countValue"] <= $_REQUEST["maxValue"]) {
    try {
        $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("UpdateDimentions");
    } catch (Exception $e) {
        $error[] = 'Выброшено исключение: '. $e->getMessage(). "<br>";
    }
    $rsData = $entity_data_class::getList(
        array(
            "select" => array('ID', 'UF_XMLID', 'UF_KOEFF', 'UF_LENGTH', 'UF_WIDTH', 'UF_HEIGHT'),
            "limit" => $_REQUEST["limit"]
        )
    );
    while ($arData = $rsData->Fetch()) {
        $arResult[$arData["UF_XMLID"]]["ID"] = $arData["ID"];
        $arResult[$arData["UF_XMLID"]]["KOEFFITSIENT"] = $arData["UF_KOEFF"];
        $arResult[$arData["UF_XMLID"]]["LENGTH"] = $arData["UF_LENGTH"];
        $arResult[$arData["UF_XMLID"]]["WIDTH"] = $arData["UF_WIDTH"];
        $arResult[$arData["UF_XMLID"]]["HEIGHT"] = $arData["UF_HEIGHT"];
    }
        if (!empty($arResult)) {
            if(CModule::IncludeModule("catalog")){
                $catalogProduct = new CCatalogProduct ();
                foreach ($arResult as $key => $update) {
                    $productID = convertProductXML_to_ID($key);
                    $updateDimentions = array(
                        "LENGTH"=>$update["LENGTH"],
                        "WIDTH"=>$update["WIDTH"],
                        "HEIGHT"=>$update["HEIGHT"]
                    );
                    $catalogProduct->Update($productID["ID"], $updateDimentions);
                    $db_ratio = CCatalogMeasureRatio::getList(array(), array("PRODUCT_ID" => $productID["ID"]));
                    $ar_ratio = $db_ratio->Fetch();
                    if($update["KOEFFITSIENT"] == "") {
                        $update["KOEFFITSIENT"] = 1;
                    }
                    $updateKoeff["PRODUCT_ID"] = $productID["ID"];
                    $updateKoeff["RATIO"] = $update["KOEFFITSIENT"];
                    if(isset($ar_ratio["ID"]) && $ar_ratio["ID"] != "" && $update["KOEFFITSIENT"] != "") {
                        Bitrix\Catalog\MeasureRatioTable::update($ar_ratio["ID"], $updateKoeff);
                    } else {
                        Bitrix\Catalog\MeasureRatioTable::add(array("PRODUCT_ID"=>$productID["ID"], "RATIO"=>$update["KOEFFITSIENT"], "IS_DEFAULT"=>"Y"));
                    }
                    try {
                        $entity_data_class::Delete($update["ID"]);
                    } catch (Exception $e) {
                        $error[] = 'Выброшено исключение: '. $e->getMessage(). "<br>";
                    }
                }
            }
        }

} else {
    $error[] = "Чет пошло не так";

}
if(!empty($error)) {
    $result = array(
        "error" => $error
    );
} else {
    $result = array(
        "success" => array(
            "html" => $html
        )
    );
}

echo Bitrix\Main\Web\Json::encode($result);?>