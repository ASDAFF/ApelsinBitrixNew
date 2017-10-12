<?
if (empty($_SERVER["HTTP_REFERER"])) die();

define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
$APPLICATION->ShowAjaxHead();

CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Highloadblock,
    Bitrix\Main\Entity,
    Bitrix\Highloadblock\HighloadBlockTable;


try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLID("7");
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
}
$rsData = $entity_data_class::getList(array(
    "select" => array('ID', 'UF_SORT', 'UF_XML_ID', 'UF_NAME'),
));
while ($arData = $rsData->Fetch()) {
    $totalArrayXML[] = $arData ['UF_XML_ID'];
    $totalArraySORT[$arData ['UF_XML_ID']] = $arData ['UF_SORT'];
    $totalArrayID[$arData ['UF_XML_ID']] = $arData ['ID'];
}
$IBLOCK_ID = 16;
$update = new CIBlockProperty;

foreach ($_REQUEST as $resultArray) {
    $data = array("UF_SORT" => $resultArray["sortId"], "UF_XML_ID" => $resultArray["sortXML_ID"], "UF_NAME" => $resultArray["nameProperty"]);
    $array = array_diff($resultArray, $totalArrayXML);
    $totalXML[] = $resultArray["sortXML_ID"];
    if (!isset($array)) {
        $result = $entity_data_class::add($data);
        $result->getId();
    } elseif ($array['sortXML_ID']) {
        $result = $entity_data_class::add($data);
        $result->getId();
    } elseif ($resultArray['sortId'] != $totalArraySORT[$resultArray['sortXML_ID']]) {
        $result = $entity_data_class::update($totalArrayID[$resultArray['sortXML_ID']], $data);
        $result->getId();
    }
    $update->Update($resultArray["IDProperty"], array("SORT" => $resultArray["sortId"]));
}

if ($totalXML == NULL) {
    $totalXML = array();
    $array = array_diff($totalArrayXML, $totalXML);
    foreach ($array as $ar) {
        $result = $entity_data_class::Delete($totalArrayID[$ar]);
        $update->Update($resultArray["IDProperty"], array("SORT" => "3000"));
    }
} elseif ($array) {
    $array = array_diff($totalArrayXML, $totalXML);
    foreach ($array as $ar) {
        $result = $entity_data_class::Delete($totalArrayID[$ar]);
        $update->Update($resultArray["IDProperty"], array("SORT" => "3000"));
    }
}
