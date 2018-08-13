<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$updateArray = [
    'email' => [
        "USER_PROPS_ID" => $_REQUEST['user_prop_id'],
        "ORDER_PROPS_ID" => $_REQUEST['E-Mail']['order_prop_id'],
        "VALUE" => $_REQUEST['E-Mail']['value'],
    ],
    'phone' => [
        "USER_PROPS_ID" => $_REQUEST['user_prop_id'],
        "ORDER_PROPS_ID" => $_REQUEST['Контактный_телефон']['order_prop_id'],
        "VALUE" => $_REQUEST['Контактный_телефон']['value'],
    ],
];

global $DB;

foreach ($updateArray as $key=>$updateValue) {
    $USER_PROPS_ID = $updateValue['USER_PROPS_ID'];
    $ORDER_PROPS_ID = $updateValue['ORDER_PROPS_ID'];
    $DB->Update(
            '`b_sale_user_props_value`',
            array('VALUE'=>"'".$updateValue['VALUE']."'"),
            'WHERE `USER_PROPS_ID`="'.$USER_PROPS_ID.'" AND `ORDER_PROPS_ID`="'.$ORDER_PROPS_ID.'"'
    );
}