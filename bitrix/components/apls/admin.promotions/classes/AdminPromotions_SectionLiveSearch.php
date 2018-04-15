<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/models/CatalogSectionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLWhereString.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLOrderByString.php";

class AdminPromotions_SectionLiveSearch
{
     /** Функция по поиску секции калога по названию или XML
     * @param $searchString - поисковая строка
     * @return $result - массив вида ["XML_ID" => "NAME"]
     */
    public static function getDBResult ($searchArray) {
        $iblock_id = APLS_CatalogHelper::getShopIblockId();
        //массив слов запроса
//        $searchArray = getSearchArray ($searchString);
        //определяем параметры по которым будет отбирать запрос
        $whereObj = new MySQLWhereString();
        //IBLOCK_ID = 16
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "IBLOCK_ID",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                "$iblock_id")
        );
        //Отдельно определяем объекты, которые сравниваются через OR
        $whereWords = new MySQLWhereString(MySQLWhereString::OR_BLOCK);
        foreach ($searchArray as $word) {
            //NAME = отдельным словам поискового запроса
            $whereWords->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    "NAME",
                    MySQLWhereElementString::OPERATOR_B_LIKE,
                    "%".$word."%")
            );
            //либо XML_ID = отдельным словам поискового запроса
            $whereWords->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    "XML_ID",
                    MySQLWhereElementString::OPERATOR_B_LIKE,
                    "%".$word."%")
            );
        }
        //Добавляем сформированный запрос в основной
        $whereObj->addBlock($whereWords);
        $orderByObj = new MySQLOrderByString();
        //Сортируем по алфавиту
        $orderByObj->add("NAME",MySQLOrderByString::ASC);
        $result = CatalogSectionModel::getElementList($whereObj,50,null,$orderByObj);
        //Собираем готовый массив
        $resAr = array();
        foreach ($result as $element) {
            if ($element instanceof CatalogSectionModel) {
                $resAr[$element->getFieldValue("XML_ID")] = $element->getFieldValue("NAME");
            }
        }
        return $resAr;
    }

    /**
     * Генерация "хлебных крошек" на основании xml каталога
     * @param $xmlArray - массив xml_id
     * @return string - сгенерирует html код "хлебных крошек"
     */
    public static function getHTMLPath($xml) {
        if (isset($xml)) {
            $xmlArray = static::getNameTreeByXml($xml);
            $html = "";
            foreach ($xmlArray as $branch) {
                $html .= "<div class='breadcrumb__item no-select-item'><span class='breadcrumb__title'>$branch</span></div>";
            }
            return $html;
        }
    }

    /**
     * Функция возвращает имя секции по xml
     * @param string $xml - входящий xml_id для запроса
     * @return string - имя секции, соответсвующее этому xml_id
     */
    public static function getNameByXml ($xml) {
        if (isset($xml) && $xml !== "") {
            $iblock_id = APLS_CatalogHelper::getShopIblockId();
            //Получаем имя таблицы, в которой содержится название секций
            $tableName = \Bitrix\Iblock\SectionTable::getTableName();
            $DB = \Bitrix\Main\Application::getConnection();
            $rs = $DB->Query("
                    SELECT `NAME` 
                    FROM `$tableName` 
                    WHERE `IBLOCK_ID`='$iblock_id' 
                    AND `XML_ID` = '$xml'
                    ");
        }
        while ($ar = $rs->Fetch()) {
            $result = $ar["NAME"];
        }
        return $result;
    }

    /**
     * Получение дерева секций от запрашиваемого каталога по xml
     * @param $xml - xml запрашиваемого каталога
     * @return array - вернет нумерованный массив xml до корня каталога (начиная от корня)
     */
    private function getNameTreeByXml ($xml) {
        if (isset($xml)) {
            //Генерируем путь до корня, предварительно пересортировавая массив задом-наперёд
            $path =  array_reverse(APLS_CatalogSections::getPathToRootForSection($xml));
            $resArray = [];
            foreach ($path as $branch) {
                //Результирующий массив вида [xml=>Имя каталога]
                $resArray[$branch] = self::getNameByXml($branch);
            }
            //Добавляем искомый элемент в финальный массив
            $resArray["lastElement"] = self::getNameByXml($xml);
            return $resArray;
        }
    }


}