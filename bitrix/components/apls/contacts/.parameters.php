<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock\HighloadBlockTable as HLBT,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Highloadblock,
    Bitrix\Main\Entity;

Loc::loadMessages(__FILE__);
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";

try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLID(APLS_GetGlobalParam::getParams("ContactsTypes"));
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
}
$rsData = $entity_data_class::getList(array(
    'select' => array('ID','UF_NAME'),
));
$contactType = array();
while ($arData = $rsData->Fetch()) {
    $contactType[$arData['UF_NAME']] = $arData['UF_NAME'];
}

$arComponentParameters = array(
    "GROUPS" => Array(
        'MAP_SETTINGS' => array(
            'NAME' => GetMessage('APLS_YA_MAP_SETTINGS'),
        ),
        'CONTACT_SETTINGS' => array(
            'NAME' => GetMessage('APLS_CONTACTS_SHOP_SETTINGS'),
        ),
    ),
    'PARAMETERS' => array(
        'INIT_MAP_TYPE' => array(
            'NAME' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => array(
                'MAP' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_MAP'),
                'SATELLITE' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_SATELLITE'),
                'HYBRID' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_HYBRID'),
                'PUBLIC' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_PUBLIC'),
                'PUBLIC_HYBRID' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_PUBLIC_HYBRID'),
            ),
            'DEFAULT' => 'MAP',
            'ADDITIONAL_VALUES' => 'N',
            'PARENT' => 'BASE',
        ),

        'CONTROLS' => array(
            'NAME' => GetMessage('MYMS_PARAM_CONTROLS'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES' => array(
                /*'TOOLBAR' => GetMessage('MYMS_PARAM_CONTROLS_TOOLBAR'),*/
                'ZOOM' => GetMessage('MYMS_PARAM_CONTROLS_ZOOM'),
                'SMALLZOOM' => GetMessage('MYMS_PARAM_CONTROLS_SMALLZOOM'),
                'MINIMAP' => GetMessage('MYMS_PARAM_CONTROLS_MINIMAP'),
                'TYPECONTROL' => GetMessage('MYMS_PARAM_CONTROLS_TYPECONTROL'),
                'SCALELINE' => GetMessage('MYMS_PARAM_CONTROLS_SCALELINE'),
                'SEARCH' => GetMessage('MYMS_PARAM_CONTROLS_SEARCH'),
            ),

            'DEFAULT' => array(/*'TOOLBAR', */
                'ZOOM', 'MINIMAP', 'TYPECONTROL', 'SCALELINE'),
            'PARENT' => 'ADDITIONAL_SETTINGS',
        ),

        'OPTIONS' => array(
            'NAME' => GetMessage('MYMS_PARAM_OPTIONS'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES' => array(
                'ENABLE_SCROLL_ZOOM' => GetMessage('MYMS_PARAM_OPTIONS_ENABLE_SCROLL_ZOOM'),
                'ENABLE_DBLCLICK_ZOOM' => GetMessage('MYMS_PARAM_OPTIONS_ENABLE_DBLCLICK_ZOOM'),
                'ENABLE_RIGHT_MAGNIFIER' => GetMessage('MYMS_PARAM_OPTIONS_ENABLE_RIGHT_MAGNIFIER'),
                'ENABLE_DRAGGING' => GetMessage('MYMS_PARAM_OPTIONS_ENABLE_DRAGGING'),
                /*'ENABLE_HOTKEYS' => GetMessage('MYMS_PARAM_OPTIONS_ENABLE_HOTKEYS'),*/
                /*'ENABLE_RULER' => GetMessage('MYMS_PARAM_OPTIONS_ENABLE_RULER'),*/
            ),


            'DEFAULT' => array('ENABLE_SCROLL_ZOOM', 'ENABLE_DBLCLICK_ZOOM', 'ENABLE_DRAGGING'),
            'PARENT' => 'ADDITIONAL_SETTINGS',
        ),
        'HIGHLOAD_SHOPS_ID' => array(
            'NAME' => GetMessage('HIGHLOAD_SHOPS_ID'),
            'TYPE' => 'STRING',
        ),
        'HIGHLOAD_REGION_ID' => array(
            'NAME' => GetMessage('HIGHLOAD_REGION_ID'),
            'TYPE' => 'STRING',
        ),
        'CONTACTS_TYPE' => array (
            'NAME' => GetMessage('TYPE_ID'),
            'TYPE' => 'LIST',
            "MULTIPLE" => "N",
            "VALUES" => $contactType,
        ),
    ),
)
?>