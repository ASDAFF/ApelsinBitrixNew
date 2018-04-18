<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionCityModel.php";

class PromotionRegionModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_region";
    protected static $privateFields = array('default');
    protected static $requiredFields = array('region','alias');
    protected static $optionalFields = array('sort');

    const DEFAULT_FIELD = 'default';

    /**
     * Список городов этого региона
     * @return array
     */
    public function getCities()
    {
        return PromotionCityModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'region',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $this->id
            )
        );
    }

    /**
     * Добавить город
     * @param $city - название города
     * @return bool|int|string - идентификатор созданной записи или false в случае ошибки
     */
    public function addCity(string $city)
    {
        return PromotionCityModel::createElement(
            array(
                'city' => $city,
                'region' => $this->id
            )
        );
    }

    /**
     * Удалить город
     * @param string $cityId - идентификатор записи города
     * @return bool
     */
    public function deleteCity(string $cityId): bool
    {
        if (PromotionCityModel::isCityInRegion($cityId, $this->id)) {
            return PromotionCityModel::deleteElement($cityId);
        }
        return false;
    }

    /**
     * Изменить город
     * @param string $cityId - идентификатор записи города
     * @param string $city - название города
     * @return bool
     */
    public function editCity(string $cityId, string $city): bool
    {
        if (PromotionCityModel::isCityInRegion($cityId, $this->id)) {
            return PromotionCityModel::updateElement($cityId, array('city' => $city));
        }
        return false;
    }

    /**
     * Установить этот регион как регион по умолчанию
     * @return bool
     */
    public function setDefault(): bool
    {
        return static::setDefaultRegion($this->id);
    }

    /**
     * Установить регион как регион по умолчанию
     * @param $id - идентификатор записи
     * @return bool
     */
    public static function setDefaultRegion($id): bool
    {
        if (static::issetElement($id)) {
            static::getConnection()->queryExecute("UPDATE `" . static::$tableName . "` SET `default`='0'");
            static::getConnection()->queryExecute(
                "UPDATE `" . static::$tableName . "` SET `" . self::DEFAULT_FIELD . "`='1' WHERE `" . static::$pk . "`='$id'"
            );
            return true;
        }
        return false;
    }

    /**
     * Вернет регион по умолчанию или регион который имеет наименьшее значение поля sort
     * Если ен существует ни одного региона вернет упстой объект PromotionRegionModel
     * @return PromotionRegionModel
     */
    public static function getDefaultRegion(): PromotionRegionModel
    {
        $arr = PromotionRegionModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                self::DEFAULT_FIELD,
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                '1'
            )
        );
        if (isset($arr[0])) {
            return $arr[0];
        } else {
            $orderByObj = new MySQLOrderByString();
            $orderByObj->add('sort', MySQLOrderByString::ASC);
            $arr = PromotionRegionModel::getElementList(
                null,
                1,
                null,
                $orderByObj
            );
            if (isset($arr[0])) {
                return $arr[0];
            } else {
                return new PromotionRegionModel("");
            }
        }
    }
}