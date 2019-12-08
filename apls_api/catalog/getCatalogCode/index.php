<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$result = array();
if(isset($_GET["XML_ID"])) {
    global $DB;
    CModule::IncludeModule("sale");
    $dbItems = \Bitrix\Iblock\SectionTable::getList(array(
        'select' => array('CODE',"PREVIEW_TEXT","PREVIEW_TEXT_TYPE","DETAIL_TEXT","DETAIL_TEXT_TYPE"),
        'filter' => array('IBLOCK_ID' => 16, "XML_ID" => $_GET["XML_ID"]),
        'limit' => 1,
        'cache' => array(
            'ttl' => 3600,
            'cache_joins' => true
        ),
    ));
    $data = $dbItems->fetch();
    if($data) {
        $result = $data;
    }
}
if(empty($result)) {
    $result = array("ERROR" => "No Data");
}
if(strtolower($_GET["debug"])=="y" || strtolower($_GET["DEBUG"])=="y") {
    echo "<pre>";
    print_r($result);
    echo "</pre>";
} else {
    echo json_encode($result);
}