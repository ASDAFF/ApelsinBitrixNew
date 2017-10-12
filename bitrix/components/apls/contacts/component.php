<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Highloadblock\HighloadBlockTable as HLBT,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Highloadblock,
    Bitrix\Main\Entity;

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";

Loc::loadMessages(__FILE__);
$daysArray = array(
    "MON" => array("DayName" => "ПН", "Weekend" => false),
    "TUE" => array("DayName" => "ВТ", "Weekend" => false),
    "WED" => array("DayName" => "СР", "Weekend" => false),
    "THU" => array("DayName" => "ЧТ", "Weekend" => false),
    "FRI" => array("DayName" => "ПТ", "Weekend" => false),
    "SAT" => array("DayName" => "СБ", "Weekend" => true),
    "SUN" => array("DayName" => "ВС", "Weekend" => true),
);
$arResult["TYPE"] = $arParams['CONTACTS_TYPE'];
$arResult["CSS"] = $arParams['CSS'];

try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLID(APLS_GetGlobalParam::getParams("ContactsRegions"));
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
}
$rsData = $entity_data_class::getList(array(
    'select' => array('ID', 'UF_REGION', 'UF_TYPE'),
    'filter' => array('UF_TYPE' => $arResult["TYPE"]),
));
$regions = array();
while ($arData = $rsData->Fetch()) {
    $arResult["region"][$arData['ID']] = $arData;
    $regions[] = $arData['ID'];
}

try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLID(APLS_GetGlobalParam::getParams("ContactsBuildings"));
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
}
$rsData = $entity_data_class::getList(array(
    'select' => array('*'),
    'filter' => array('UF_REGION' => $regions)
));

while ($arData = $rsData->Fetch()) {
    $arResult["shops"][$arData['ID']] = $arData;

    $intKey = 0;
    foreach ($daysArray as $key => $day) {
        $arResult["shops"][$arData['ID']]['TimeTable'][$intKey] = $day;
        if ($arData["UF_" . $key . "_S"] == "" || $arData["UF_" . $key . "_E"] == "") {
            $arResult["shops"][$arData['ID']]['TimeTable'][$intKey]['Weekend'] = true;
            $arResult["shops"][$arData['ID']]['TimeTable'][$intKey++]['DayTime'] = Loc::getMessage("WEEKEND_TEXT");
        } else {
            $arResult["shops"][$arData['ID']]['TimeTable'][$intKey++]['DayTime'] = $arData["UF_" . $key . "_S"] . " - " . $arData["UF_" . $key . "_E"];
        }
    }
}

$this->IncludeComponentTemplate();
?>