<?
if(empty($_SERVER["HTTP_REFERER"]))
    die();

define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Highloadblock,
    Bitrix\Main\Entity;

Loc::loadMessages(__FILE__);
$request = Application::getInstance()->getContext()->getRequest();
$templateFolder = $request->getPost("templateFolder");
$highloadId = $request->getPost("highloadId");
$exceptionRoditel = $request->getPost("exceptionRoditel");
$q = $request->getPost("q");
$VIEW_HTML = "";

$hlblock = Highloadblock\HighloadBlockTable::getById($highloadId)->fetch();
$entity = Highloadblock\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rsData = $entity_data_class::getList(array(
    "select" => array('ID','UF_NAME','UF_ADRES','UF_KOD','UF_RODITEL'),
    "order" => array("UF_NAME" => "ASC"),
    "filter" => array('UF_VYGRUZHATNASAYT'=>'да','UF_POMETKAUDALENIYA'=>'нет','!UF_RODITEL'=>$exceptionRoditel,'!UF_ADRES'=>"", 'UF_NAME'=>"%$q%")
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
require_once($_SERVER["DOCUMENT_ROOT"].$templateFolder."/view.php");

if(isset($arResult['SERVICE_CENTERS']) && count($arResult['SERVICE_CENTERS']) > 0) {
    $result = array(
        "success" => array(
            "html" => $VIEW_HTML
        )
    );
} else {
    $result = array(
        "error" => array(
            "html" => Loc::getMessage("NO_ITEMS")
        )
    );
}

echo Bitrix\Main\Web\Json::encode($result);?>