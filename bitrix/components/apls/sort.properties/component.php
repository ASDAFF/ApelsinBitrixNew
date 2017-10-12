<?
use Bitrix\Highloadblock\HighloadBlockTable as HLBT,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Highloadblock,
    Bitrix\Main\Entity;

Loc::loadMessages(__FILE__);
CJSCore::Init(array("jquery2"));
CJSCore::Init(array('ajax'));

$IBLOCK_ID =16;
$res = CIBlock::GetProperties($IBLOCK_ID,Array("NAME"=>"ASC"));
$PropertiesXMLID = array("all" => array(), "before" => array(),"after" => array(), "noSort");
$AllProperties = array();
while($res_arr = $res->Fetch()){
    $AllProperties[$res_arr['XML_ID']]['ID'] = $res_arr['ID'];
    $AllProperties[$res_arr['XML_ID']]['NAME'] = $res_arr['NAME'];
    $AllProperties[$res_arr['XML_ID']]['XML_ID'] = $res_arr['XML_ID'];
    $PropertiesXMLID['all'][] = $res_arr['XML_ID'];
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";

CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLID("7");
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
}
$rsData = $entity_data_class::getList(array(
    "select" => array('UF_XML_ID', 'UF_SORT', 'UF_NAME'),
    "order" => array("UF_SORT" => "ASC")
));
while ($arData = $rsData->Fetch()) {
    $key1 = $arData['UF_SORT']<= 2000 ? 'HLBeforeProperties' : 'HLAfterProperties';
    $key2 = $arData['UF_SORT']<= 2000 ? 'before' : 'after';
    $arResult[$key1][$arData['UF_XML_ID']]['SORT'] = $arData['UF_SORT'];
    $arResult[$key1][$arData['UF_XML_ID']]['XML_ID'] = $arData['UF_XML_ID'];
    $arResult[$key1][$arData['UF_XML_ID']]['NAME'] = $arData['UF_NAME'];
    $PropertiesXMLID[$key2][] = $arData['UF_XML_ID'];
}

$PropertiesXMLID['noSort'] = array_diff ( $PropertiesXMLID['all'] , $PropertiesXMLID["before"], $PropertiesXMLID["after"] );
foreach ($PropertiesXMLID['noSort'] as $xml_id) {
    $arResult['AllProperties'][$xml_id] = $AllProperties[$xml_id];
}
foreach ($PropertiesXMLID['before'] as $xml_id) {
    if(isset($AllProperties[$xml_id])) {
        $arResult['HLBeforeProperties'][$xml_id]['ID'] = $AllProperties[$xml_id]['ID'];
    } else {
        $arResult['HLBeforeProperties'][$xml_id]['ID'] = "-";
    }
}
foreach ($PropertiesXMLID['after'] as $xml_id) {
    if(isset($AllProperties[$xml_id])) {
        $arResult['HLAfterProperties'][$xml_id]['ID'] = $AllProperties[$xml_id]['ID'];
    } else {
        $arResult['HLAfterProperties'][$xml_id]['ID'] = "-";
    }
}

$this->IncludeComponentTemplate();
?>