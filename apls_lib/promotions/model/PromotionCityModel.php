<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionCityModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_cities";
    protected static $requiredFields = array('city', 'region');

    /**
     * Првоеряем принадлежит ли город к региону
     * @param string $cityId - идентификатор города
     * @param string $regionId - идентификатор региона
     * @return bool - true если город в группе
     */
    public static function isCityInRegion(string $cityId, string $regionId): bool
    {
        $city = new PromotionCityModel($cityId);
        $region = $city->getFieldValue('region');
        return $region !== null && $region == $regionId;
    }
}