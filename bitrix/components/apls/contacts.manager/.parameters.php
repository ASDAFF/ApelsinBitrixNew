<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock\HighloadBlockTable as HLBT,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Highloadblock,
    Bitrix\Main\Entity;

Loc::loadMessages(__FILE__);
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";

try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLID(APLS_GetGlobalParam::getParams("HIGHLOAD_DEPARTAMENT_ID"));
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
}
$rsData = $entity_data_class::getList(array(
    'select' => array('ID','UF_NAME'),
));

$departamentType = array();
while ($arData = $rsData->Fetch()) {
    $departamentType[$arData['ID']] = $arData['UF_NAME'];
}

$arComponentParameters = array(
    "GROUPS" => Array(
        'HIGHLOAD_ID_PARAMETERS' => array(
            'NAME' => GetMessage('APLS_HIGHLOAD_ID_PARAMETERS'),
        ),
        'CONTACT_MANAGERS' => array(
            'NAME' => GetMessage('APLS_CONTACT_MANAGERS'),
        ),
    ),
    'PARAMETERS' => array(
        'HIGHLOAD_DEPARTAMENT_ID' => array(
            'NAME' => GetMessage('APLS_HIGHLOAD_DEPARTAMENT_ID'),
            'TYPE' => 'STRING',
        ),
        'HIGHLOAD_STAFF_ID' => array(
            'NAME' => GetMessage('APLS_HIGHLOAD_STAFF_ID'),
            'TYPE' => 'STRING',
        ),
        'DEPARTAMENT_TYPE' => array(
            'NAME' => GetMessage('APLS_DEPARTAMENT_TYPE'),
            'TYPE' => 'LIST',
            "MULTIPLE" => "N",
            "VALUES" => $departamentType,
        ),
    ),
)
?>