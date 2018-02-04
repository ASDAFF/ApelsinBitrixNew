<?php
/**
 * Created by PhpStorm.
 * Date: 04.02.2018
 * Time: 23:31
 *
 * @author dev@dermanov.ru
 */


/**
 * Получает случайные ИД элементов из иблока.
 *
 * @param int $count - максимальное кол-во случайных элементов
 * @param int $iblockId - ИД иблока
 * @param array $filter - дополнительный фильтр по элементам, среди которых получаем случайные
 *
 * @return array - случайные ИД элементов из иблока
 * */
function getRandomElementId ($iblockId, $count = 10, $filter = []) {
    if (!$iblockId)
        throw new Exception ( '$iblockId can not be void' );
    
    \CModule::IncludeModule("iblock");
    
    $arSelect = ["ID"];
    $arOrder  = ["RAND" => "ASC"];
    
    $arFilter = array(
        "IBLOCK_ID"   => $iblockId,
        "ACTIVE"      => "Y",
    );
    
    if ($filter)
        $arFilter = array_merge($arFilter, $filter);
    
    $pageNav = array("iNumPage" => 1, "nPageSize" => $count);
    
    $iterator = \CIBlockElement::GetList( $arOrder, $arFilter, false, $pageNav, $arSelect);
    
    $result = [];
    
    while($ob = $iterator->Fetch()) {
        $result[] = $ob["ID"];
    }
    
    // fix of empty filter by ID returned to client code
    if (!$result)
        $result[] = -1;
    
    return $result;
}