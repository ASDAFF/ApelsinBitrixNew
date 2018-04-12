<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";

$whereObj = MySQLWhereElementString::getBinaryOperationString(
    'promotion',
    MySQLWhereElementString::OPERATOR_B_EQUAL,
    $_REQUEST['promotionId']
);
$orderByObj = new MySQLOrderByString();
$orderByObj->add('apply_from',MySQLOrderByString::DESC);
$result = PromotionRevisionModel::getElementList($whereObj,1,null, $orderByObj);

if(!empty($result)) {
    $revision = array_shift($result);
    if($revision instanceof PromotionRevisionModel && $revision->getFieldValue('apply_from') instanceof \Bitrix\Main\Type\DateTime) {
        $applyFrom = $revision->getFieldValue('apply_from');
        $applyFrom->add("1 day");
        $applyFromString = $applyFrom->format("Y-m-d H:i:s");
    }
}
if(!isset($applyFromString)) {
    $applyFromString = MySQLTrait::mysqlDateTime();
}
echo PromotionRevisionModel::createElement(
    array(
        'promotion'=>$_REQUEST['promotionId'],
        'apply_from'=>$applyFromString,
        'disable'=>'1'
    )
);