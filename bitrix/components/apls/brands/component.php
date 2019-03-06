<?php
CJSCore::Init(array("jquery2"));
CJSCore::Init(array('ajax'));
$arResult['IBlockID'] = $arParams['IBlockID'];
$arResult['sefFolder'] = $arParams['SEF_FOLDER'];

if(isset($_GET['p1'])) {
    $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT", "DETAIL_PICTURE", "DETAIL_TEXT", "CODE", "XML_ID");
    $arFilter = Array("IBLOCK_ID"=>IntVal($arResult['IBlockID']), "ACTIVE"=>"Y", "CODE"=>$_GET['p1']);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
    $arResult['brand'] = array();
    while($ob = $res->GetNextElement())
    {
        $arResult['brand'] = $ob->GetFields();
        if(isset($arResult['brand']["PREVIEW_PICTURE"]) && $arResult['brand']["PREVIEW_PICTURE"] > 0) {
            $arFileTmp = CFile::ResizeImageGet(
                $arResult['brand']["PREVIEW_PICTURE"],
                array("width" => 69, "height" => 24),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
            $arResult['brand']["PREVIEW_PICTURE"] = array(
                "SRC" => $arFileTmp["src"],
                "WIDTH" => $arFileTmp["width"],
                "HEIGHT" => $arFileTmp["height"],
            );
        }
        $arResult['brand']['DETAIL_PICTURE'] = CFile::GetPath($arResult['brand']['DETAIL_PICTURE']);
        $db_props = CIBlockElement::GetProperty($arResult['IBlockID'], $arResult['brand']['ID'], array(), Array());
        while($ar_props = $db_props->Fetch()) {
            $arResult['brand']['PROPERTIES'][$ar_props['CODE']] = $ar_props;
        }
    }
} else {
    $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT", "CODE");
    $arFilter = Array("IBLOCK_ID"=>IntVal($arResult['IBlockID']), "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
    $arResult['brands'] = array();
    while($ob = $res->GetNextElement())
    {
        $arItem = $ob->GetFields();
        if(isset($arItem["PREVIEW_PICTURE"]) && $arItem["PREVIEW_PICTURE"] > 0) {
            $arFileTmp = CFile::ResizeImageGet(
                $arItem["PREVIEW_PICTURE"],
                array("width" => 69, "height" => 24),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
            $arItem["PREVIEW_PICTURE"] = array(
                "SRC" => $arFileTmp["src"],
                "WIDTH" => $arFileTmp["width"],
                "HEIGHT" => $arFileTmp["height"],
            );
        }
        $arItem["DETAIL_PAGE_URL"] = $arResult['sefFolder'].$arItem["CODE"]."";
        $arResult['brands'][] = $arItem;

    }
}
$this->IncludeComponentTemplate();