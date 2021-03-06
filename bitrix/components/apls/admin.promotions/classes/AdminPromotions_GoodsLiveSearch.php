<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogElementModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogSectionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLWhereString.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLOrderByString.php";


class AdminPromotions_GoodsLiveSearch
{
    public static function getDBResult ($searchString, $section)
    {
        $iblock_id = APLS_CatalogHelper::getShopIblockId();
        //Определяем по каким критериям будет отбирть поисковый запрос
        $whereObj = new MySQLWhereString();
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "IBLOCK_ID",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                "$iblock_id")
        );
        //NAME LIKE $searchString
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "NAME",
                MySQLWhereElementString::OPERATOR_B_LIKE,
                "%" . $searchString . "%")
        );
        //Если $section существует, то добавляем еще кусок запроса
        if ($section !== "" && $section !== 'undefined') {
            //Нам приходит ИД, но для поиска по потомкам нужен xml - конвертируем
            $xml = APLS_CatalogSections::convertSectionsIDtoXMLID($section);
            $allChildren = APLS_CatalogSections::getAllChildrenListForSection($xml);
            $childArray = "";
            //Перебираем и записываем в строку
            foreach ($allChildren as $child) {
                $childArray .= APLS_CatalogSections::convertSectionsXMLIDtoID($child) . ",";
            }
           //Проверяем не пришла ли пустота и существуют ли потомки
            if ($childArray != "" && $childArray) {
                $childArray = "(" . substr($childArray, 0, -1) . ")";
                //Ищем совпадения по вспомагательной таблице ID Секции - ID Элемента
                $testSQL = "SELECT `IBLOCK_ELEMENT_ID` FROM `b_iblock_section_element` WHERE `IBLOCK_SECTION_ID` IN $childArray";
            }
            // Если потомков нет, то подставляем иходный ИД и немного модифицируем запрос
            else {
                $childArray = $section;
                $testSQL = "SELECT `IBLOCK_ELEMENT_ID` FROM `b_iblock_section_element` WHERE `IBLOCK_SECTION_ID` = $childArray";
            }
            //Добавляем кусочек запроса в WHERE
            $whereObj->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    "ID",
                    MySQLWhereElementString::OPERATOR_B_IN,
                    $testSQL,
                    "",
                    "",
                    MySQLWhereElementString::DEFAULT_OPTION_F,
                    array(MySQLWhereElementString::OPTION_IS_SQL)
                )
            );
        }
        //Сортируем по NAME
        $orderByObj = new MySQLOrderByString();
        $orderByObj->add("NAME", MySQLOrderByString::ASC);
        $result = CatalogElementModel::getElementList($whereObj, 50, null, $orderByObj);
        //Собираем результирующий массив
        $resAr = array();
        foreach ($result as $element) {
            if ($element instanceof CatalogElementModel) {
                $resAr[$element->getFieldValue("XML_ID")] = $element->getFieldValue("NAME");
            }
        }
        return $resAr;
    }
}