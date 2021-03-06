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
    $arOrder  = false;
    
    $arFilter = array(
        "IBLOCK_ID"   => $iblockId,
        "ACTIVE"      => "Y",
    );
    
    if ($filter)
        $arFilter = array_merge($arFilter, $filter);
    
    $pageNav = array("iNumPage" => 1, "nPageSize" => 1000);
    
    $iterator = \CIBlockElement::GetList( $arOrder, $arFilter, false, $pageNav, $arSelect);
    
    $result = [];
    
    while($ob = $iterator->Fetch()) {
        $result[] = $ob["ID"];
    }
    
    shuffle($result);
    $result = array_slice($result, 0, $count);
    
    // fix of empty filter by ID returned to client code
    if (!$result)
        $result[] = -1;
    
    return $result;
}

/**
 * Нужно ли выводить умный фильтр для указанного раздела.
 *
 * На первом уровне нет привязанных св-в и фильтр получается пустой
 * или содержить только "акция" с одним вариантов "нет".
 *
 * Будем выводить начиная со 2-го уровня.
 *
 * Хотя я бы рекомендовал начиная с 3-го.
 * Ко 2-му уровню привязано слишком мало св-в.
 * */
function allowShowSmartFilter ( $sectionId) {
    if (!$sectionId)
        throw new Exception ( '$sectionId can not be void' );
    
    $section = \Bitrix\Iblock\SectionTable::query()
        ->addFilter("ID", $sectionId)->addSelect("DEPTH_LEVEL")
        ->exec()->fetch();
    
    return $section["DEPTH_LEVEL"] >= 2;
}