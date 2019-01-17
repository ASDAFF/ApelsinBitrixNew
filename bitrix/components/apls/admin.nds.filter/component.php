<?php
CJSCore::Init(array("jquery2"));
CJSCore::Init(array('ajax'));

$vat_res = CCatalogVat::GetList(
    array('CSORT' => 'ASC'),
    array(),
    array()
);
$arResult = [];
while ($ar_vat = $vat_res->Fetch()) {
    $arResult[$ar_vat["NAME"]] = $ar_vat["ID"];
}

$this->IncludeComponentTemplate();