<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;
    \Bitrix\Main\Loader::includeModule('sale');

if (!Loader::includeModule("iblock") || !Loader::includeModule("catalog"))
    return;

if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

$arParams["ELEMENT_ID"] = intval($arParams["ELEMENT_ID"]);

$arParams["ELEMENT_AREA_ID"] = trim($arParams["ELEMENT_AREA_ID"]);
if (empty($arParams["ELEMENT_AREA_ID"]))
    return;

if (empty($arParams["REQUIRED"]))
    $arParams["REQUIRED"] = array("NAME", "PHONE");

if (empty($arParams["BUY_MODE"]))
    $arParams["BUY_MODE"] = "ONE";

global $USER;
$arParams["IS_AUTHORIZED"] = $USER->IsAuthorized() ? "Y" : "N";
$arSetting = CElektroinstrument::GetFrontParametrsValues(SITE_ID);
$arParams["USE_CAPTCHA"] = $arParams["IS_AUTHORIZED"] != "Y" && $arSetting["FORMS_USE_CAPTCHA"] == "Y" ? "Y" : "N";

$arParams["PHONE_MASK"] = $arSetting["FORMS_PHONE_MASK"];
$arParams["VALIDATE_PHONE_MASK"] = $arSetting["FORMS_VALIDATE_PHONE_MASK"];
$arParams["SHOW_PERSONAL_DATA"] = $arSetting["SHOW_PERSONAL_DATA"];
$arParams["TEXT_PERSONAL_DATA"] = $arSetting["TEXT_PERSONAL_DATA"];

$arParams["PROPERTIES"] = array("NAME", "PHONE", "EMAIL", "MESSAGE");

$arParams["PARAMS_STRING"] = array(
    "REQUIRED" => $arParams["REQUIRED"],
    "VALIDATE_PHONE_MASK" => $arParams["VALIDATE_PHONE_MASK"],
    "IS_AUTHORIZED" => $arParams["IS_AUTHORIZED"]
);
$arParams["PARAMS_STRING"] = strtr(base64_encode(serialize($arParams["PARAMS_STRING"])), "+/=", "-_,");

if ($this->StartResultCache()) {
//ELEMENT//
    if ($arParams["ELEMENT_ID"] > 0) {
        $arElement = CIBlockElement::GetList(
            array(),
            array(
                "ID" => $arParams["ELEMENT_ID"]
            ),
            false,
            false,
            array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE")
        )->Fetch();

        if (empty($arElement)) {
            $this->abortResultCache();
            return;
        }

        $arResult["ELEMENT"]["ID"] = $arElement["ID"];
        $arResult["ELEMENT"]["NAME"] = $arElement["NAME"];

        if ($arElement["PREVIEW_PICTURE"] <= 0 && $arElement["DETAIL_PICTURE"] <= 0) {
            $mxResult = CCatalogSku::GetProductInfo($arElement["ID"]);
            if (is_array($mxResult)) {
                $arElement = CIBlockElement::GetList(
                    array(),
                    array(
                        "ID" => $mxResult["ID"]
                    ),
                    false,
                    false,
                    array("ID", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PICTURE")
                )->Fetch();
            }
        }

        if ($arElement["PREVIEW_PICTURE"] > 0) {
            $arFile = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);
            if ($arFile["WIDTH"] > 178 || $arFile["HEIGHT"] > 178) {
                $arFileTmp = CFile::ResizeImageGet(
                    $arFile,
                    array("width" => 178, "height" => 178),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
                $arResult["ELEMENT"]["PREVIEW_PICTURE"] = array(
                    "SRC" => $arFileTmp["src"],
                    "WIDTH" => $arFileTmp["width"],
                    "HEIGHT" => $arFileTmp["height"],
                );
            } else {
                $arResult["ELEMENT"]["PREVIEW_PICTURE"] = $arFile;
            }
        } elseif ($arElement["DETAIL_PICTURE"] > 0) {
            $arFile = CFile::GetFileArray($arElement["DETAIL_PICTURE"]);
            if ($arFile["WIDTH"] > 178 || $arFile["HEIGHT"] > 178) {
                $arFileTmp = CFile::ResizeImageGet(
                    $arFile,
                    array("width" => 178, "height" => 178),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
                $arResult["ELEMENT"]["PREVIEW_PICTURE"] = array(
                    "SRC" => $arFileTmp["src"],
                    "WIDTH" => $arFileTmp["width"],
                    "HEIGHT" => $arFileTmp["height"],
                );
            } else {
                $arResult["ELEMENT"]["PREVIEW_PICTURE"] = $arFile;
            }
        }
    }

//USER//
    if ($arParams["IS_AUTHORIZED"] == "Y") {
        $arResult["USER"]["NAME"] = $USER->GetFullName();
        $arResult["USER"]["EMAIL"] = $USER->GetEmail();
    }
    $fUser = new CSaleOrderUserProps();
    $db_sales = $fUser->GetList(
        array("DATE_UPDATE" => "DESC"),
        array("USER_ID" => $USER->GetID())
    );
    while ($ar_sales = $db_sales->Fetch()) {
        $saleProfiles[] = $ar_sales["ID"];
    }
    $saleProfileId = max($saleProfiles);

    $db_propVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$saleProfileId, "NAME" => "Контактный телефон", "USER_ID" => $USER->GetID()));
    while ($arPropVals = $db_propVals->Fetch())
    {
        $contactValue = $arPropVals["VALUE"];
    }
    $arResult["USER"]["PHONE"] = $contactValue;
    $this->IncludeComponentTemplate();
} ?>