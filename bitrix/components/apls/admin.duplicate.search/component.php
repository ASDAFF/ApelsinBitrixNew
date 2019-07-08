<?php
CJSCore::Init(array("jquery2"));
CJSCore::Init(array('ajax'));

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";

global $DB;
$strSql = "SELECT * FROM `duplicates`";
$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

while ($ar = $db_res->Fetch()) {
    $arResult[ID_GENERATOR::generateID()] = $ar["ARTNUMBER"];
}
$this->IncludeComponentTemplate();

