<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $DB;
CModule::IncludeModule("sale");
$result = \Bitrix\Main\UserTable::getList(array(
    'select' => array("ID","XML_ID","ACTIVE","LOGIN","PASSWORD","NAME","SECOND_NAME","LAST_NAME","EMAIL","PERSONAL_PHONE"),
    'order' => array('ID'=>'ASC'),
    'limit' => 10
));
$allUserData = array();
while ($arUser = $result->fetch()) {
    $rez = $DB->Query("SELECT UF_CARD_NUMBER FROM b_uts_user WHERE VALUE_ID = ".$arUser["ID"]);
    $prop = $rez->fetch();
    $arUser["CARD_NUMBER"] = $prop["UF_CARD_NUMBER"];
    $arResult = CSaleOrderUserProps::DoLoadProfiles($arUser["ID"]);
    if(!empty($arResult)) {
        $list = array_pop($arResult);
        if(!empty($list)) {
            $data = array_pop($list);
            if(isset($data['VALUES_ORIG']['3'])) {
                $arUser['PERSONAL_PHONE'] = preg_replace('/[^0-9]/', '', $data['VALUES_ORIG']['3']);
            }
        }
    }
    $allUserData[] = $arUser;
}
if($_GET["debug"]=="Y") {
    echo "<pre>";
    print_r($allUserData);
    echo "</pre>";
} else {
    echo json_encode($allUserData);
}