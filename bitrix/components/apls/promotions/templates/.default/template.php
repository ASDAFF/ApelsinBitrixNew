<?
if(isset($arResult["pageType"])) {
    if($arResult["pageType"] === "promotion") {
        include_once $_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/promotion.php";
    } else if ($arResult["pageType"] === "promotionsList") {
        include_once $_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/promotionsList.php";
    }
}