<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/apls/admin.promotions/classes/AdminPromotions_SectionLiveSearch.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortListElement.php";



if($_REQUEST["catalogSectionLiveSearch"]) {
    $array = getSearchArray ($_REQUEST["catalogSectionLiveSearch"]);
    $arResult = AdminPromotions_SectionLiveSearch::getDBResult($array);
    ?><div class="ListOfElements BreadcrumbElements SearchBlock"><?
    foreach ($arResult as $key=>$string) { ?>
        <div class="ElementBlock">
        <div class="ElementBlockContent">
            <div class="breadcrumbWrapper"><?=AdminPromotions_SectionLiveSearch::getHTMLPath($key)?></div>
            <div class="AddElement" id="<?=$key?>">+</div>
        </div>
        </div><?
    }
    ?><div><?

}

/**
 * Получение и очистка поискового запроса
 * @param $searchString
 * @return array
 */
function getSearchArray ($searchString) {
    //Проверяем входные данные
    if (isset($searchString) && $searchString !== "" && is_string($searchString)) {
        //Разделяем строку по словам и помещаем в массив
        $unpreparedArray = explode(" ", $searchString);
        //Убираем лишние пробелы
        $searchArray = array_diff($unpreparedArray, array(""));
    }
    return $searchArray;
}