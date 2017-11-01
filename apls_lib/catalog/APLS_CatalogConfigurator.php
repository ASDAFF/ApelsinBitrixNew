<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";

class APLS_CatalogConfigurator
{

    protected static $instance = null;
    protected static $propertiesArray = array();
    protected static $propertiesIDtoXMLID = array();
    protected static $HLPropParamsEntityDataClass = null;
    protected static $HLPropParamsSortEntityDataClass = null;
    protected static $HLPropParamsSortArray = null;
    const HIGHLOAD_PROPERTIES_PARAMS = "FilterPropertiesParams";
    const HIGHLOAD_PROPERTIES_SORT = "PropertyGoods";
    const XML_ID_FIELD = "UF_XML_ID";
    const DETAIL_PROPERTY_FIELD = "UF_DETAIL_PROPERTY";
    const COMPARE_PROPERTY_FIELD = "UF_COMPARE_PROPERTY";
    const SMART_FILTER_FIELD = "UF_SMART_FILTER";
    const ACTIVITY_FIELD = "UF_ACTIVITY";
    const APPROVED_FIELD = "UF_APPROVED";
    const DEFAULT_DETAIL_PROPERTY_VALUE = 1;
    const DEFAULT_COMPARE_PROPERTY_VALUE = 1;
    const DEFAULT_SMART_FILTER_VALUE = 0;
    const DEFAULT_SORT_INDEX = 3000;

    /**
     * APLS_CatalogConfigurator constructor.
     */
    protected function __construct()
    {
        static::$HLPropParamsEntityDataClass = APLS_GetHighloadEntityDataClass::getByHLName(static::HIGHLOAD_PROPERTIES_PARAMS);
        static::updateСatalogActiveProperties();
    }

    /**
     * @return - реализация Singleton
     */
    protected static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * возвращает массив SMART_FILTER для bitrix:catalog
     */
    public static function getSmartFilterRezultArray(&$properies)
    {
        $smartFilter = static::getSmartFilterCode();
        foreach ($properies as $key => $propery) {
            if (!in_array($propery["CODE"], $smartFilter)) {
                unset($properies[$key]);
            }
        }
    }

    /**
     * возвращает массив SMART_FILTER для bitrix:catalog
     */
    public static function getSmartFilterCode()
    {
        return static::getPropertiesCodeFromHLPropertiesParams(static::SMART_FILTER_FIELD);
    }

    /**
     * возвращает массмов COMPARE_PROPERTY_CODE для bitrix:catalog
     */
    public static function getComparePropertyCode()
    {
        return static::getPropertiesCodeFromHLPropertiesParams(static::COMPARE_PROPERTY_FIELD);
    }

    /**
     * возвращает массмов DETAIL_PROPERTY_CODE для bitrix:catalog
     */
    public static function getDetailPropertyCode()
    {
        return static::getPropertiesCodeFromHLPropertiesParams(static::DETAIL_PROPERTY_FIELD);
    }

    /**
     * возвращает массив всех активных свойств каталога
     * @return array - массив всех активнх свойств основного каталога
     */
    public static function getAllActiveCatalogProperties()
    {
        static::getInstance();
        return static::$propertiesArray;
    }

    /**
     * функция синхронизируется с HL-блоком настроки свойств и со свойствами каталога,
     * после чего производит соответствующие изменения в HL-блоке
     * @return true | false
     */
    public static function updateHighloadPropertiesParams()
    {
        static::getInstance();
        try {
            $propertiesParamsXMLIDtoID = array(); // внешние коды свойств из HL-блока
            $propertiesParamsXMLID = array(); // внешние коды свойств из HL-блока
            $propertiesParamsXMLIDnoActiv = array(); // внешние коды неактивных свойств из HL-блока
            // поулчаем данные из HL-блока
            $rsData = static::$HLPropParamsEntityDataClass::getList(array(
                "select" => array('ID', static::XML_ID_FIELD, static::ACTIVITY_FIELD),
                "filter" => array()
            ));
            while ($arData = $rsData->Fetch()) {
                // массив соответствий внешних кодов и id свойств из HL-блока
                $propertiesParamsXMLIDtoID[$arData[static::XML_ID_FIELD]] = $arData['ID'];
                // сохраняем в массив $propertiesParamsXMLID внешние ключи всех элементов из HL-блока
                $propertiesParamsXMLID[] = $arData[static::XML_ID_FIELD];
                // сохраняем в массив $propertiesParamsXMLIDnoActiv внешние ключи всех неактивных элементов из HL-блока
                if (!$arData[static::ACTIVITY_FIELD]) {
                    $propertiesParamsXMLIDnoActiv[] = $arData[static::XML_ID_FIELD];
                }
            }
            // поулчаем внешние ключи всех активных свойств основного инфоблока каталога
            $propertiesXMLID = array_keys(static::$propertiesArray);
            // получаем список всех свойств которые нужно добавить в HL-блок
            $newProperties = array_diff($propertiesXMLID, $propertiesParamsXMLID);
            // получаем список всех свойств которые нужно деактивировать в HL-блоке
            $toDeactivate = array_diff($propertiesParamsXMLID, $propertiesXMLID, $propertiesParamsXMLIDnoActiv);
            // получаем список всех свойств которые нужно активировать в HL-блоке
            $toActivate = array_diff($propertiesParamsXMLIDnoActiv, $toDeactivate);

            // деактивация свойств в HL-блоке
            foreach ($toDeactivate as $item) {
                static::$HLPropParamsEntityDataClass::update($propertiesParamsXMLIDtoID[$item], array(static::ACTIVITY_FIELD => 0));
            }
            // активация свойств в HL-блоке
            foreach ($toActivate as $item) {
                static::$HLPropParamsEntityDataClass::update($propertiesParamsXMLIDtoID[$item], array(static::ACTIVITY_FIELD => 1));
            }
            // добавление свойств в HL-блоке
            foreach ($newProperties as $item) {
                static::$HLPropParamsEntityDataClass::add(array(
                    static::XML_ID_FIELD => $item,
                    static::ACTIVITY_FIELD => 1,
                    static::APPROVED_FIELD => 0,
                    static::DETAIL_PROPERTY_FIELD => static::DEFAULT_DETAIL_PROPERTY_VALUE,
                    static::COMPARE_PROPERTY_FIELD => static::DEFAULT_COMPARE_PROPERTY_VALUE,
                    static::SMART_FILTER_FIELD => static::DEFAULT_SMART_FILTER_VALUE,
                ));
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * возвращает массив с данными для отображения в UI по настройке свойств
     * Массив имеет следующий формат
     * array(
     *      [<внешний код> => array(
     *          <ID> => id свойства,
     *          <HLID> => id в HL-блоке,
     *          <NAME> => Имя свойства,
     *          <XML_ID> => Внешний код свойства,
     *          <DETAIL_PROPERTY> => 1 | 0,
     *          <COMPARE_PROPERTY> => 1 | 0,
     *          <SMART_FILTER> => 1 | 0,
     *          <APPROVED> => 1 | 0,
     *      ),]
     * )
     * @param bool $update - требует ли обновления данных в highload, по умолчанию true.
     *                  Рекомендуется всегда использовать с дефолтным вариантом
     *                  кроме случаев когда данные заведомо точно обвновлены.
     * @param bool $sortByName - если true, то сортируем по имени, елси fakse то по sort
     * @param array $filter - массив ключ значение для отбора по полям.
     * @param string $searchString - строка поиска по имени, id в HL-блоке, id своства и внешнему коду
     * @return array - массив значений или пустой массив в случае ошибки
     */
    public static function getHighloadPropertiesParams($update = true, $sortByName = true, $filter = array(), $searchString = "")
    {
        static::getInstance();
        // обновляем данные в HL-блоке если нужно
        if ($update) {
            static::updateHighloadPropertiesParams();
        }
        try {
            $searchString = preg_replace("/\s{2,}/", " ", trim($searchString));
            if ($searchString !== "") {
                $searchArray = explode(" ", strtolower($searchString));
            }
            $filter[static::ACTIVITY_FIELD] = 1;
            // поулчаем даныне из HL-Блоке
            $rsData = static::$HLPropParamsEntityDataClass::getList(array(
                "select" => array(
                    'ID',
                    static::XML_ID_FIELD,
                    static::APPROVED_FIELD,
                    static::DETAIL_PROPERTY_FIELD,
                    static::COMPARE_PROPERTY_FIELD,
                    static::SMART_FILTER_FIELD
                ),
                "filter" => $filter
            ));
            $params = $forSort = $paramsSorted = array();
            $forSort = array();
            // собираем результирующий массив
            while ($arData = $rsData->Fetch()) {
                $xmlId = $arData[static::XML_ID_FIELD];
                $id = static::$propertiesArray[$xmlId]["ID"];
                $name = static::$propertiesArray[$xmlId]["NAME"];
                // проверяет есть-ли свойство из HL-блока среди свойств каталога, на случай если даныне устаревшие
                if (isset(static::$propertiesArray[$xmlId])) {
                    $searchTrigger = false;
                    // првоверяем есть ли у нас массив поиска по строке
                    if (isset($searchArray) && !empty($searchArray)) {
                        // ищем хотябы одно совпадение
                        foreach ($searchArray as $search) {
                            if (substr_count(strtolower($name . " " . $xmlId . " " . $id . " " . $arData['ID']), $search) > 0) {
                                $searchTrigger = true;
                            }
                        }
                    } else {
                        // если массива поиска нет, то считаем что поиск успешно пройден
                        $searchTrigger = true;
                    }
                    if ($searchTrigger) {
                        $params[$xmlId]["ID"] = $id;
                        $params[$xmlId]["HLID"] = $arData['ID'];
                        $params[$xmlId]["NAME"] = $name;
                        $params[$xmlId]["XML_ID"] = $xmlId;
                        $params[$xmlId]["DETAIL_PROPERTY"] = $arData[static::DETAIL_PROPERTY_FIELD];
                        $params[$xmlId]["COMPARE_PROPERTY"] = $arData[static::COMPARE_PROPERTY_FIELD];
                        $params[$xmlId]["SMART_FILTER"] = $arData[static::SMART_FILTER_FIELD];
                        $params[$xmlId]["APPROVED"] = $arData[static::APPROVED_FIELD];
                        // подготавливаем вспомогательный массив для сортировки
                        if ($sortByName) {
                            $forSort[$xmlId] = strtolower($name);
                        } else {
                            $forSort[$xmlId] = static::$propertiesArray[$xmlId]["SORT"];
                        }
                    }
                }
            }
            // сортируем вспомогательные массивы
            asort($forSort);
            // создаем отсортированный результирующий массив
            foreach (array_keys($forSort) as $xmlId) {
                $paramsSorted[$xmlId] = $params[$xmlId];
            }
            return $paramsSorted;
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * @param string $id - id записи в hl-Блоке
     * @param array $data - массив изменяемых полей поле=>значение
     */
    public static function setHLPropParamsFieldValue($id, $data)
    {
        static::getInstance();
        static::$HLPropParamsEntityDataClass::update($id, $data);
    }

    /**
     * устанавлвиаем значения поля APPROVED для hl-Блока настройки свойств
     * @param string $id - id записи в hl-Блоке
     * @param $value - 1|0
     */
    public static function setHLPropParamsApprovedValue($id, $value)
    {
        static::setHLPropParamsFieldValue($id, array(static::APPROVED_FIELD => $value));
    }

    /**
     * устанавлвиаем значения поля DETAIL_PROPERTY для hl-Блока настройки свойств
     * @param string $id - id записи в hl-Блоке
     * @param $value - 1|0
     */
    public static function setHLPropParamsDetailPropertyValue($id, $value)
    {
        static::setHLPropParamsFieldValue($id, array(static::DETAIL_PROPERTY_FIELD => $value));
    }

    /**
     * устанавлвиаем значения поля COMPARE_PROPERTY для hl-Блока настройки свойств
     * @param string $id - id записи в hl-Блоке
     * @param $value - 1|0
     */
    public static function setHLPropParamsComparePropertyValue($id, $value)
    {
        static::setHLPropParamsFieldValue($id, array(static::COMPARE_PROPERTY_FIELD => $value));
    }

    /**
     * устанавлвиаем значения поля SMART_FILTER для hl-Блока настройки свойств
     * @param string $id - id записи в hl-Блоке
     * @param $value - 1|0
     */
    public static function setHLPropParamsSmartFilterValue($id, $value)
    {
        static::setHLPropParamsFieldValue($id, array(static::SMART_FILTER_FIELD => $value));
    }

    /**
     * насильно обновляем данные о свойствах компоненты
     */
    public static function updateСatalogActiveProperties()
    {
        static::$propertiesArray = array();
        $catalogId = APLS_GetGlobalParam::getParams("HIGHLOAD_CATALOG_ID");
        $properties = CIBlockProperty::GetList(
            array(),
            array("ACTIVE" => "Y", "IBLOCK_ID" => $catalogId)
        );
        while ($prop_fields = $properties->GetNext()) {
            static::$propertiesArray[$prop_fields["XML_ID"]] = $prop_fields;
            static::$propertiesIDtoXMLID[$prop_fields["ID"]] = $prop_fields["XML_ID"];
        }
    }

    /**
     * обновляет индекс сортировки у всех свойств
     */
    public static function updateAllСatalogPropertySortIndex()
    {
        // получаем XML_ID свойсвта по его индексу
        $catalogId = APLS_GetGlobalParam::getParams("HIGHLOAD_CATALOG_ID");
        $properties = CIBlockProperty::GetList(
            array("ID"),
            array("IBLOCK_ID" => $catalogId)
        );
        while ($prop_fields = $properties->GetNext()) {
            static::updateСatalogPropertySortIndex($prop_fields["ID"]);
        }
    }

    /**
     * обновляет индекс сортировки у свойства по его ID
     * @param $propertyId - id свойства для обновления индекса сортировки
     * @return bool - true | false
     */
    public static function updateСatalogPropertySortIndex($propertyId)
    {
        static::getInstance();
        $xml_id = null;
        $sortIndex = static::DEFAULT_SORT_INDEX;
        // получаем XML_ID свойсвта по его индексу
        $catalogId = APLS_GetGlobalParam::getParams("HIGHLOAD_CATALOG_ID");
        $properties = CIBlockProperty::GetList(
            array("XML_ID"),
            array("ID" => $propertyId, "IBLOCK_ID" => $catalogId)
        );
        while ($prop_fields = $properties->GetNext()) {
            $xml_id = $prop_fields["XML_ID"];
        }
        // првоеряем удалось ли получить XML_ID свойсвта
        if ($xml_id !== null) {
            // проверяем создан ли список отсортированных свойств и если нет, то создаем
            if (static::$HLPropParamsSortArray === null) {
                static::$HLPropParamsSortEntityDataClass = APLS_GetHighloadEntityDataClass::getByHLName(static::HIGHLOAD_PROPERTIES_SORT);
                $rsData = static::$HLPropParamsSortEntityDataClass::getList(array(
                    "select" => array('UF_XML_ID', 'UF_SORT'),
                    "filter" => array()
                ));
                while ($arData = $rsData->Fetch()) {
                    static::$HLPropParamsSortArray[$arData["UF_XML_ID"]] = $arData["UF_SORT"];
                }
            }
            // если текущее свойство есть среди отсортированных то присваиваем известный индекс сортировки
            if (isset(static::$HLPropParamsSortArray[$xml_id])) {
                $sortIndex = static::$HLPropParamsSortArray[$xml_id];
            }
            $ibp = new CIBlockProperty;
            return $ibp->Update($propertyId, array("SORT" => $sortIndex,));
        }
        return false;
    }

    /**
     * Возвращает внешний код открытого каталога товаров
     * @return null | внешний код текущего каталога
     */
    public static function getCatalogExternalId()
    {
        $externalID = null;
        $iden = explode("/", $_SERVER['REQUEST_URI']);
        if (isset($iden[1]) && $iden[1] == "catalog" && isset($iden[2]) && $iden[2] !== "") {
            $code = $iden[2];
            $db_list = CIBlockSection::GetList(
                array("SORT" => "ASC"),
                array('CODE' => $code),
                true,
                array("EXTERNAL_ID", "NAME"),
                false
            );
            while ($ar_result = $db_list->GetNext()) {
                echo $ar_result['NAME'] . " - " . $ar_result['EXTERNAL_ID'] . '<br>';
                $externalID = $ar_result['EXTERNAL_ID'];
            }
        }
        var_dump($externalID);
        return $externalID;
    }

    /**
     * @param string $field - ключ поля по которому будут обобраны своства из HL Properties Params
     * @return array - массив ключей свойств
     */
    private static function getPropertiesCodeFromHLPropertiesParams($field)
    {
        static::getInstance();
        try {
            $rsData = static::$HLPropParamsEntityDataClass::getList(array(
                "select" => array(static::XML_ID_FIELD, $field),
                "filter" => array($field => true)
            ));
            $properties = array();
            while ($arData = $rsData->Fetch()) {
                if (isset(static::$propertiesArray[$arData[static::XML_ID_FIELD]]["CODE"])) {
                    $properties[] = static::$propertiesArray[$arData[static::XML_ID_FIELD]]["CODE"];
                }
            }
            return $properties;
        } catch (Exception $e) {
            return array();
        }
    }
}
