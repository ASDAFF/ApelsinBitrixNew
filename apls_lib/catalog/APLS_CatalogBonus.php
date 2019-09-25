<?php

/**
 * Class APLS_CatalogBonus
 *
 * @author Max Zaitsev
 * @email <info@compuproject.com>
 * @website <https://compuproject.com>
 * @version 1.0
 * @copyright (c) 2010-2019, COMPUPROJECT
 * @package ${NAMESPACE}
 * @created 2019-06-18 13:36
 */
class APLS_CatalogBonus
{
    const CARD_KEY = "";
    const FIO_KEY = "";
    const BONUS_KEY = "ОстатокБонусов";
    const URL = "https://db.apelsin.ru/db/hs/BONUSINFO/ClientBonus/";
    const ERROR_NO_DATA = "Сервис временно не доступен";
    private $data = array();
    private $cardNumber = "";

    function __construct($cardNumber)
    {
        $this->getDataFromServer($cardNumber);
    }

    private function getDataFromServer($cardNumber) {
        $this->cardNumber = $cardNumber;
        $urlString = self::URL.$this->cardNumber;
        $url = file_get_contents($urlString);
        $this->data = json_decode($url, true);
    }

    public function updateCard($cardNumber) {
        $this->getDataFromServer($cardNumber);
    }


    public function getBonus($textPrefix = "", $textPostfix = "") {
        if(isset($this->data[self::BONUS_KEY]) && is_int($this->data[self::BONUS_KEY])) {
            return $textPrefix.$this->data[self::BONUS_KEY].$textPostfix;
        }
        return self::ERROR_NO_DATA;
    }


    public function getFio($textPrefix = "", $textPostfix = "") {
        if(isset($this->data[self::FIO_KEY])) {
            return $textPrefix.$this->data[self::FIO_KEY].$textPostfix;
        }
        return self::ERROR_NO_DATA;
    }


    public function getCard($textPrefix = "", $textPostfix = "") {
        if(isset($this->data[self::CARD_KEY]) && is_int($this->data[self::CARD_KEY])) {
            return $textPrefix.$this->data[self::CARD_KEY].$textPostfix;
        }
        return self::ERROR_NO_DATA;
    }

    public function getOriginalCard() {
        return $this->cardNumber;
    }

}