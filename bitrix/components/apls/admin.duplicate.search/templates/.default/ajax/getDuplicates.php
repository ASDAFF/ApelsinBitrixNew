<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$ar_obj = CIBlockElement::GetList(array("TIMESTAMP_X"=>"DESC"),array("PROPERTY_ARTNUMBER"=>$_POST["code"]),false,array(),array("ID","NAME","IBLOCK_SECTION_ID","TIMESTAMP_X"));
while ($ar = $ar_obj->Fetch()) {
    $section = CIBlockSection::GetByID($ar["IBLOCK_SECTION_ID"]);
    $sectionData = $section->Fetch();
    $result[$ar["ID"]]["NAME"] = $ar["NAME"];
    $result[$ar["ID"]]["SECTION_NAME"] = $sectionData["NAME"];
    $result[$ar["ID"]]["TIMESTAMP_X"] = $ar["TIMESTAMP_X"];
}
?>
<div class="duplicate_element_result_element_header">
    <div class="duplicate_element_result_element_checker"></div>
    <div class="duplicate_element_result_element_id">ID</div>
    <div class="duplicate_element_result_element_name">Название товара</div>
    <div class="duplicate_element_result_element_section">Каталог</div>
    <div class="duplicate_element_result_element_time">Изменен</div>
</div>
<?
foreach ($result as $key=>$value):?>
    <div class="duplicate_element_result_element" id="<?=$key?>">
        <div class="duplicate_element_result_element_checker"><input type="checkbox"></div>
        <div class="duplicate_element_result_element_id"><?=$key?></div>
        <div class="duplicate_element_result_element_name"><?=$value["NAME"]?></div>
        <div class="duplicate_element_result_element_section"><?=$value["SECTION_NAME"]?></div>
        <div class="duplicate_element_result_element_time"><?=$value["TIMESTAMP_X"]?></div>
    </div>
<?endforeach;?>
