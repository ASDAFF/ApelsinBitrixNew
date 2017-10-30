<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";

class APLS_CatalogConfigurator
{

    protected static $instance = null;
    protected static $propertiesArray = array();
    const HIGHLOAD_PROPERTIES_PARAMS = "FilterPropertiesParams";
    const XML_ID_FIELD = "UF_XML_ID";
    const DETAIL_PROPERTY_FIELD = "UF_DETAIL_PROPERTY";
    const COMPARE_PROPERTY_FIELD = "UF_COMPARE_PROPERTY";
    const SMART_FILTER_FIELD = "UF_SMART_FILTER";
    const ACTIVITY_FIELD = "UF_ACTIVITY";
    const APPROVED_FIELD = "UF_APPROVED";
    const DEFAULT_DETAIL_PROPERTY_VALUE = 1;
    const DEFAULT_COMPARE_PROPERTY_VALUE = 1;
    const DEFAULT_SMART_FILTER_VALUE = 0;

    /**
     * APLS_CatalogConfigurator constructor.
     */
    protected function __construct()
    {
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
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName(static::HIGHLOAD_PROPERTIES_PARAMS);
            $rsData = $entity_data_class::getList(array(
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
                $entity_data_class::update($propertiesParamsXMLIDtoID[$item], array(static::ACTIVITY_FIELD => 0));
            }
            // активация свойств в HL-блоке
            foreach ($toActivate as $item) {
                $entity_data_class::update($propertiesParamsXMLIDtoID[$item], array(static::ACTIVITY_FIELD => 1));
            }
            // добавление свойств в HL-блоке
            foreach ($newProperties as $item) {
                $entity_data_class::add(array(
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
     *          <NAME> => Имя свойства,
     *          <XML_ID> => Внешний код свойства,
     *          <DETAIL_PROPERTY> => 1 | 0,
     *          <COMPARE_PROPERTY> => 1 | 0,
     *          <SMART_FILTER> => 1 | 0,
     *      ),]
     * )
     * @param bool $update - требует ли обновления данных в highload, по умолчанию true.
     *                  Рекомендуется всегда использовать с дефолтным вариантом
     *                  кроме случаев когда данные заведомо точно обвновлены.
     * @return array - массив значений или пустой массив в случае ошибки
     */
    public static function getHighloadPropertiesParams($update = true)
    {
        static::getInstance();
        // обновляем данные в HL-блоке если нужно
        if ($update) {
            static::updateHighloadPropertiesParams();
        }
        try {
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName(static::HIGHLOAD_PROPERTIES_PARAMS);
            // поулчаем даныне из HL-Блоке
            $rsData = $entity_data_class::getList(array(
                "select" => array(
                    'ID',
                    static::XML_ID_FIELD,
                    static::APPROVED_FIELD,
                    static::DETAIL_PROPERTY_FIELD,
                    static::COMPARE_PROPERTY_FIELD,
                    static::SMART_FILTER_FIELD
                ),
                "filter" => array(static::ACTIVITY_FIELD => 1)
            ));
            $params = array();
            // собираем результирующий массив
            while ($arData = $rsData->Fetch()) {
                $xmlId = $arData[static::XML_ID_FIELD];
                // проверяет сли свойство из HL-блока срели свойств каталога, на случай если даныне устаревшие
                if (isset(static::$propertiesArray[$xmlId])) {
                    $params[$xmlId]["ID"] = static::$propertiesArray[$xmlId]["ID"];
                    $params[$xmlId]["NAME"] = static::$propertiesArray[$xmlId]["NAME"];
                    $params[$xmlId]["XML_ID"] = $xmlId;
                    $params[$xmlId]["DETAIL_PROPERTY"] = $arData[static::DETAIL_PROPERTY_FIELD];
                    $params[$xmlId]["COMPARE_PROPERTY"] = $arData[static::COMPARE_PROPERTY_FIELD];
                    $params[$xmlId]["SMART_FILTER"] = $arData[static::SMART_FILTER_FIELD];
                }
            }
            return $params;
        } catch (Exception $e) {
            return array();
        }
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
        }
    }

    /**
     * @param string $field - ключ поля по которому будут обобраны своства из HL Properties Params
     * @return array - массив ключей свойств
     */
    private static function getPropertiesCodeFromHLPropertiesParams($field)
    {
        static::getInstance();
        try {
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName(static::HIGHLOAD_PROPERTIES_PARAMS);
            $rsData = $entity_data_class::getList(array(
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