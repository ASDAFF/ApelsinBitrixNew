<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageInRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionHelper.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageTypeModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionImageHelper.php";

function getPromotionListRegion($regionAlias) {
    $elementList = PromotionRegionModel::getElementList(
        MySQLWhereElementString::getBinaryOperationString(
            'alias',
            MySQLWhereElementString::OPERATOR_B_EQUAL,
            $regionAlias
        )
    );
    if(!empty($elementList)) {
        return array_shift($elementList);
    } else {
        return null;
    }
}

function getPromotionListSection($sectionAlias) {
    $elementList = PromotionSectionModel::getElementList(
        MySQLWhereElementString::getBinaryOperationString(
            'alias',
            MySQLWhereElementString::OPERATOR_B_EQUAL,
            $sectionAlias
        )
    );
    if(!empty($elementList)) {
        return array_shift($elementList);
    } else {
        return null;
    }
}

if(isset($_GET['p1']) && $_GET['p1']==="id" && isset($_GET['p2']) && $_GET['p2']!=="") {
    $promotion = new PromotionModel($_GET['p2']);
    $revision = $promotion->getCurrentRevision();
    $arResult = array(
        "pageType" => "promotion",
        "promotion" => $promotion,
        "revision" => $revision,
        "bannerImageType"=> "-",
        "bigImageType"=> "-",
    );
    $bannerImageType = PromotionImageTypeModel::searchTypeByAlias("banner");
    if($bannerImageType instanceof PromotionImageTypeModel) {
        $arResult["bannerImageType"] = $bannerImageType->getId();
    }
    $bigImageType = PromotionImageTypeModel::searchTypeByAlias("big");
    if($bigImageType instanceof PromotionImageTypeModel) {
        $arResult["bigImageType"] = $bigImageType->getId();
    }
    $title = $revision->getFieldValue("title");
    if($title === "" || $title === null) {
        $title = "Акциия";
    }
    $APPLICATION->AddChainItem($title, "");
    $APPLICATION->SetTitle($title);
} else {
    // Задаем дефолтный массив параметров
    $arResult = array(
        "pageType" => "promotionsList",
        "region" => "",
        "regionName" => "",
        "urlRegion" => "",
        "section" => "",
        "sectionName" => "",
        "sections" => array(),
        "promotions" => array(),
        "promotionsInSections" => array(),
        "sefFolder" => $arParams["SEF_FOLDER"],
        "pImgType" => "-",
        "bImgType" => "-"
    );
    // поулчаем id типов изображений
    $previewImageType = PromotionImageTypeModel::searchTypeByAlias("preview");
    $bigImageType = PromotionImageTypeModel::searchTypeByAlias("big");
    if($previewImageType instanceof PromotionImageTypeModel) {
        $arResult["pImgType"] = $previewImageType->getId();
    }
    if($bigImageType instanceof PromotionImageTypeModel) {
        $arResult["bImgType"] = $bigImageType->getId();
    }
    // вспомогательные временные переменные
    $region = null;
    $section = null;
    // определение региона и секции по ссылке
    if (isset($_GET['p1']) && $_GET['p1']!=="" && isset($_GET['p2']) && $_GET['p2']!=="") {
        $region = getPromotionListRegion($_GET['p1']);
        $section = getPromotionListSection($_GET['p2']);
    } else if(isset($_GET['p1']) && $_GET['p1']!=="") {
        $region = getPromotionListRegion($_GET['p1']);
        $section = getPromotionListSection($_GET['p1']);
    }
    // данные по региону
    if($region instanceof PromotionRegionModel) {
        $arResult["region"] = $region->getId();
        $arResult["regionName"] = $region->getFieldValue("region");
        $arResult["urlRegion"] = $region->getFieldValue("alias");
    } else {
        $region = PromotionRegionModel::getUserRegion();
        $arResult["region"] = $region->getId();
        $arResult["regionName"] = $region->getFieldValue("region");
    }
    // данные по секции
    if($section instanceof PromotionSectionModel) {
        $arResult["section"] = $section->getId();
        $arResult["sectionName"] = $section->getFieldValue("section");
    }
    // данные по акциям
    $PromotionsData = PromotionHelper::getActualPromotionsDataForRegion(
        $arResult["region"],
        PromotionHelper::GLOBAL_ACTIVITY
    );
    // сязь секций и акций
    $arResult["promotionsInSections"] = $PromotionsData["promotionsInSections"];
    // получаем список id акций
    if($arResult["section"] === "") {
        $promotionsId = $PromotionsData["promotions"];
    } else {
        $promotionsId = $PromotionsData["promotionsInSections"][$arResult["section"]];
    }
    // получаем список акций
    $promotionsTemp = array();
    $promotionsSort = array();
    foreach ($promotionsId as $promotionId) {
        $promotion = new PromotionModel($promotionId);
        $promotionsTemp[$promotion->getId()] = $promotion;
        $promotionsSort[$promotion->getId()] = $promotion->getFieldValue('sort');
    }
    asort($promotionsSort);
    foreach (array_keys($promotionsSort) as $promotionId) {
        $arResult["promotions"][] = $promotionsTemp[$promotionId];
    }
    // поулчаем список секций
    foreach ($PromotionsData['sections'] as $sectionsId) {
        $arResult["sections"][] = new PromotionSectionModel($sectionsId);
    }
    $sectionUrl = $arResult["sefFolder"];
    if($arResult["urlRegion"] !== "") {
        $sectionUrl .= $arResult["urlRegion"]."/";
        $APPLICATION->AddChainItem($arResult["regionName"], $sectionUrl);
    }
    if($arResult["section"] !== "") {
        $APPLICATION->AddChainItem($arResult["sectionName"], $sectionUrl.$arResult["section"]."/");
    }
    $APPLICATION->SetTitle("Акции. ".$arResult["regionName"].".");
}
$this->IncludeComponentTemplate();