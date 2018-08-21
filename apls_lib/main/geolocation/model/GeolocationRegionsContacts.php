<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

class GeolocationRegionsContacts extends PromotionModelAbstract
{
    protected static $tableName = "apls_geolocation_regions_contacts";
    protected static $privateFields = array();
    protected static $requiredFields = array('region','name');
    protected static $optionalFields = array(
        'address',
        'email',
        'phone_number_1',
        'additional_number_1',
        'phone_number_2',
        'additional_number_2',
        'mon_start',
        'mon_stop',
        'tue_start',
        'tue_stop',
        'wed_start',
        'wed_stop',
        'thu_start',
        'thu_stop',
        'fri_start',
        'fri_stop',
        'sat_start',
        'sat_stop',
        'sun_start',
        'sun_stop',
        'credit_department',
        'wholesale_department',
        'receipt_of_goods',
        'round_the_clock',
        'longitude',
        'latitude',
        'zoom',
        'sort',
    );

    /**
     * Првоеряем принадлежит ли контакт к региону
     * @param string $contactId - идентификатор контакт
     * @param string $regionId - идентификатор региона
     * @return bool - true если контакт в группе
     */
    public static function isContactInRegion(string $contactId, string $regionId): bool
    {
        $contact = new GeolocationRegionsContacts($contactId);
        $region = $contact->getFieldValue('region');
        return $region !== null && $region == $regionId;
    }


    /* ПЕРЕОПРЕДЕЛЕННЫЕ МЕТОДЫ */
    protected static function beforeCreateElement(array &$fieldsValue, array &$attr): bool
    {
        if (!isset($fieldsValue['credit_department'])) {
            $fieldsValue['credit_department'] = "0";
        }
        if (!isset($fieldsValue['wholesale_department'])) {
            $fieldsValue['wholesale_department'] = "0";
        }
        if (!isset($fieldsValue['receipt_of_goods'])) {
            $fieldsValue['receipt_of_goods'] = "0";
        }
        if (!isset($fieldsValue['round_the_clock'])) {
            $fieldsValue['round_the_clock'] = "0";
        }
        if (!isset($fieldsValue['sort'])) {
            $fieldsValue['sort'] = "500";
        }
        return true;
    }
}