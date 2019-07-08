<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";
global $DB;
$strSql = "SELECT * FROM `duplicates`";
$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

while ($ar = $db_res->Fetch()) {
    $arResult[ID_GENERATOR::generateID()] = $ar["ARTNUMBER"];
}
?>
<div class="duplicate_buttons">
    <div class="duplicate_buttons_delete">Удалить выбранное</div>
    <div class="duplicate_buttons_refresh">Обновить выборку</div>
</div>
<?foreach ($arResult as $key=>$code):?>
    <div class="duplicate_element" id="<?=$key?>" code="<?=$code?>">
        <div class="duplicate_element_title">
            <div class="duplicate_element_sign"><i class="fa fa-plus"></i></div>
            <div class="duplicate_element_code"><b>Код товара: </b><?=$code?></div>
        </div>
        <div class="duplicate_element_result"></div>
    </div>
<?endforeach;?>
