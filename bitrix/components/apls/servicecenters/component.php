<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/textgenerator/ID_GENERATOR.php";

$arResult['EXCEPTION_RODITEL'] = 'ec9371f2-f0be-11e5-80e2-00155d410357';
$arResult['FORM_ID'] = ID_GENERATOR::generateID("APLS-SERVICECENTERS");

// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

global $USER;
$debug = $USER->IsAdmin() && ($request->get('debug') == 'y');

try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("ServisnyeTSentryIM");
    $rsData = $entity_data_class::getList(array(
        "select" => array('ID','UF_NAME','UF_ADRES','UF_KOD','UF_RODITEL'),
        "order" => array("UF_NAME" => "ASC"),
        "filter" => array('UF_VYGRUZHATNASAYT'=>true,'UF_POMETKAUDALENIYA'=>false,'!UF_RODITEL'=>$arResult['EXCEPTION_RODITEL'],'!UF_ADRES'=>"")
    ));
    while($arData = $rsData->Fetch())
    {
        $arResult['SERVICE_CENTERS'][$arData['UF_RODITEL']][$arData['ID']]['UF_NAME'] = $arData['UF_NAME'];
        $Adress = str_replace("---// ","",$arData['UF_ADRES']);
        $Adress = str_replace("--- // ","",$Adress);
        $Adress = str_replace("// ","<br />",$Adress);
        $arResult['SERVICE_CENTERS'][$arData['UF_RODITEL']][$arData['ID']]['UF_ADRES'] = nl2br($Adress);
        $arResult['SERVICE_CENTERS'][$arData['UF_RODITEL']][$arData['ID']]['UF_KOD'] = $arData['UF_KOD'];
    }
    if($debug) {
        echo "<pre>";
        print_r($arResult);
        echo "</pre>";
    }
} catch (Exception $e) {
    $html = 'Выброшено исключение: ' . $e->getMessage() . "<br>";
}

$this->IncludeComponentTemplate();
?>