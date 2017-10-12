<?php

use Bitrix\Highloadblock as HL;

class UpdatedUserModel
{
    const HLBL_ID = "2"; // ID Highload-блока "Kontragenty"
    const DEFAULT_PRICE_TYPE_FOR_REGISTR_USER = "86157e22-e56b-11dc-8b6b-000e0c431b58"; // мелкий опт (1с тип цен)

    private $userData = array();
    private $counterpartiesData = array();

    private $userUpdateData = array();

    public function __construct()
    {
    }

    private function userDataById($userId)
    {
        $rsUser = CUser::GetByID($userId);
        $this->userData = $rsUser->Fetch();
    }

    private function userDataByCard($card)
    {
        $this->userDataByCards('UF_CARD_NUMBER', $card);
    }

    private function userDataByLastCard($card)
    {
        $this->userDataByCards('UF_LAST_CARD_NUMBER', $card);
    }


    private function userDataByCards($field, $card)
    {
        $arFilter = Array($field => $card);
        $arParams["SELECT"] = array(
            'UF_CARD_NUMBER',
            "UF_1C_TYPE_PRICE",
            "UF_MESSAGE_ERROR",
            "UF_LAST_CARD_NUMBER"
        );
        $rsUser = CUser::GetList($by = 'ID', $order = 'ASC', $arFilter, $arParams);
        $this->userData = $rsUser->Fetch();
    }

    /**
     * @param $counterpartiesData - array(UF_NAME,UF_ZABLOKIROVAN,UF_OSNOVNOYTIPTSEN,UF_NOMERKARTYKLIENTA)
     * @param bool $cutCardNumber - если TRUE то от номера карты будет отрезан последний символ
     */
    private function getCounterpartiesByData($counterpartiesData, $cutCardNumber = true)
    {
        if (
            isset($counterpartiesData['UF_NAME']) &&
            isset($counterpartiesData['UF_ZABLOKIROVAN']) &&
            isset($counterpartiesData['UF_OSNOVNOYTIPTSEN']) &&
            isset($counterpartiesData['UF_NOMERKARTYKLIENTA']) &&
            $counterpartiesData['UF_NAME'] !== null &&
            $counterpartiesData['UF_ZABLOKIROVAN'] !== null &&
            $counterpartiesData['UF_OSNOVNOYTIPTSEN'] !== null &&
            $counterpartiesData['UF_NOMERKARTYKLIENTA'] !== null
        ) {
            $this->counterpartiesData['UF_NAME'] = $counterpartiesData['UF_NAME'];
            $this->counterpartiesData['UF_ZABLOKIROVAN'] = $counterpartiesData['UF_ZABLOKIROVAN'];
            $this->counterpartiesData['UF_OSNOVNOYTIPTSEN'] = $counterpartiesData['UF_OSNOVNOYTIPTSEN'];
            if ($cutCardNumber) {
                $this->counterpartiesData['UF_NOMERKARTYKLIENTA'] = substr($counterpartiesData['UF_NOMERKARTYKLIENTA'], 0, -1);
            } else {
                $this->counterpartiesData['UF_NOMERKARTYKLIENTA'] = $counterpartiesData['UF_NOMERKARTYKLIENTA'];
            }
        }
    }

    /**
     * @param string $cardNumber - номер карты
     * @param bool $cutCardNumber - если TRUE то от номера карты будет отрезан последний символ
     */
    private function getCounterpartiesByCardNumber($cardNumber, $cutCardNumber = true)
    {
        if ($cutCardNumber) {
            $cardNumber = $cardNumber . "%";
        }
        CModule::IncludeModule("highloadblock");
        $hlblock = HL\HighloadBlockTable::getById(self::HLBL_ID)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(
            array(
                'select' => array('*'),
                'filter' => array('UF_NOMERKARTYKLIENTA' => $cardNumber)
            )
        );
        $counterpartiesData = $rsData->fetch();
        $this->getCounterpartiesByData($counterpartiesData, $cutCardNumber);
    }

    private function checkXML_ID()
    {
        if (APLS_TextInspections::isEmpty($this->userData['XML_ID'])) {
            $this->userUpdateData["XML_ID"] = ID_GENERATOR::generateID();
        }
    }

    /**
     * Проверка карты пользователя на дубликат
     * @param $userID
     * @return bool - усли true то не дубликат
     */
    private function noCardDuplicate($userID)
    {
        $filter = Array("UF_CARD_NUMBER" => $this->userData["UF_CARD_NUMBER"] . "%");
        $by = "ID";
        $order = "desc";
        $rsUsers = CUser::GetList($by, $order, $filter, array("SELECT" => array("UF_*"))); // выбираем пользователей
        while ($el = $rsUsers->fetch()) {
            if ($el['ID'] != $userID) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $counterpartiesData - array(UF_NAME,UF_ZABLOKIROVAN,UF_OSNOVNOYTIPTSEN,UF_NOMERKARTYKLIENTA)
     * @param bool $cutCardNumber - если TRUE то от номера карты будет отрезан последний символ
     */
    public function updateCounterpartiesHL($counterpartiesData, $cutCardNumber = true)
    {
        $this->getCounterpartiesByData($counterpartiesData, $cutCardNumber);
        if (!empty($this->counterpartiesData) && $this->counterpartiesData !== null) {
            $this->userDataByCard($this->counterpartiesData['UF_NOMERKARTYKLIENTA']);
            if (empty($this->userData) || $this->userData === null) {
                $this->userDataByLastCard($this->counterpartiesData['UF_NOMERKARTYKLIENTA']);
            }
            if (!empty($this->userData) && $this->userData !== null) {
                // проверка ФИО
                if (!APLS_TextInspections::chekFIOString($this->counterpartiesData["UF_NAME"], $this->userData["NAME"], $this->userData["LAST_NAME"])) {
                    $this->userUpdateData["UF_CARD_NUMBER"] = null;
                    $this->userUpdateData["UF_1C_TYPE_PRICE"] = self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER;
                    if ($this->userData["UF_LAST_CARD_NUMBER"] != $this->counterpartiesData["UF_NOMERKARTYKLIENTA"]) {
                        $this->userUpdateData["UF_LAST_CARD_NUMBER"] = $this->counterpartiesData["UF_NOMERKARTYKLIENTA"];
                    }
                } else {
                    // проверка блокировки контрагента
                    if ($this->counterpartiesData["UF_ZABLOKIROVAN"] > 0 && $this->userData["UF_CARD_NUMBER"] !== null) {
                        $this->userUpdateData["UF_MESSAGE_ERROR"] = "error2";
                        $this->userUpdateData["MESSAGE_EMAIL"] = true;
                        $this->userUpdateData["UF_CARD_NUMBER"] = null;
                        $this->userUpdateData["UF_1C_TYPE_PRICE"] = self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER;
                        if ($this->userData["UF_LAST_CARD_NUMBER"] != $this->userData["UF_CARD_NUMBER"]) {
                            $this->userUpdateData["UF_LAST_CARD_NUMBER"] = $this->userData["UF_CARD_NUMBER"];
                        }
                    }
                    if ($this->counterpartiesData["UF_ZABLOKIROVAN"] < 1 && $this->userData["UF_CARD_NUMBER"] === null) {
                        $this->userUpdateData["UF_CARD_NUMBER"] = $this->counterpartiesData["UF_NOMERKARTYKLIENTA"];
                        $this->userUpdateData["UF_1C_TYPE_PRICE"] = $this->counterpartiesData["UF_OSNOVNOYTIPTSEN"];
                        if ($this->userData["UF_LAST_CARD_NUMBER"] != $this->counterpartiesData["UF_NOMERKARTYKLIENTA"]) {
                            $this->userUpdateData["UF_LAST_CARD_NUMBER"] = $this->counterpartiesData["UF_NOMERKARTYKLIENTA"];
                        }
                    }
                    // проверка типа цен
                    if ($this->counterpartiesData["UF_ZABLOKIROVAN"] > 0 && $this->userData["UF_1C_TYPE_PRICE"] != self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER) {
                        $this->userUpdateData["UF_1C_TYPE_PRICE"] = self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER;
                    }
                    if ($this->counterpartiesData["UF_ZABLOKIROVAN"] < 1 && $this->userData["UF_1C_TYPE_PRICE"] != $this->counterpartiesData["UF_OSNOVNOYTIPTSEN"]) {
                        $this->userUpdateData["UF_1C_TYPE_PRICE"] = $this->counterpartiesData["UF_OSNOVNOYTIPTSEN"];
                    }
                }
            }
        }
    }

    public function updateUser($userId, $cutCardNumber = true)
    {
        $this->userDataById($userId);
        if (!empty($this->userData) && $this->userData !== null) {
            if ($this->userData['UF_CARD_NUMBER'] === null && $this->userData['UF_1C_TYPE_PRICE'] !== self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER) {
                $this->userUpdateData["UF_1C_TYPE_PRICE"] = self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER;
            } else if ($this->userData['UF_CARD_NUMBER'] !== null) {
                $this->getCounterpartiesByCardNumber($this->userData['UF_CARD_NUMBER'], $cutCardNumber);
                if (
                    !empty($this->counterpartiesData) &&
                    $this->counterpartiesData !== null &&
                    $this->counterpartiesData["UF_ZABLOKIROVAN"] < 1 &&
                    $this->noCardDuplicate($this->userData["ID"]) &&
                    APLS_TextInspections::chekFIOString(
                        $this->counterpartiesData["UF_NAME"],
                        $this->userData["NAME"],
                        $this->userData["LAST_NAME"])
                ) {

                    if ($this->userData["UF_LAST_CARD_NUMBER"] != $this->userData["UF_CARD_NUMBER"]) {
                        $this->userUpdateData["UF_LAST_CARD_NUMBER"] = $this->userData["UF_CARD_NUMBER"];
                    }
                    if ($this->userData['UF_1C_TYPE_PRICE'] !== $this->counterpartiesData["UF_OSNOVNOYTIPTSEN"]) {
                        $this->userUpdateData["UF_1C_TYPE_PRICE"] = $this->counterpartiesData["UF_OSNOVNOYTIPTSEN"];
                    }
                    if ($this->userData['UF_MESSAGE_ERROR'] !== null) {
                        $this->userUpdateData["UF_MESSAGE_ERROR"] = null;
                    }

                } else {
                    $this->userUpdateData["UF_CARD_NUMBER"] = null;
                    if ($this->counterpartiesData['UF_ZABLOKIROVAN'] > 0 ) {
                        $this->userUpdateData["UF_MESSAGE_ERROR"] = "error2";
                    } elseif (!$this->noCardDuplicate($this->userData["ID"])) {
                        $this->userUpdateData["UF_MESSAGE_ERROR"] = "error1";
                    } else {
                        $this->userUpdateData["UF_MESSAGE_ERROR"] = "error";
                    }
                    if ($this->userData["UF_LAST_CARD_NUMBER"] != $this->userData["UF_CARD_NUMBER"]) {
                        $this->userUpdateData["UF_LAST_CARD_NUMBER"] = $this->userData["UF_CARD_NUMBER"];
                    }
                    if ($this->userData['UF_1C_TYPE_PRICE'] !== self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER) {
                        $this->userUpdateData["UF_1C_TYPE_PRICE"] = self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER;
                    }
                }
            }
        }
    }

    public function registrUser($userId, $cutCardNumber = true)
    {
        $this->updateUser($userId, $cutCardNumber);
    }

    public function getUserUpdateArray()
    {
        $this->checkXML_ID();
        return $this->userUpdateData;
    }

    public function getUserId()
    {
        if (isset($this->userData['ID'])) {
            return $this->userData['ID'];
        } else {
            return null;
        }
    }

    public function getUserData()
    {
        return $this->userData;
    }

    public function getCounterpartiesData()
    {
        return $this->counterpartiesData;
    }

    public function var_dump()
    {
        echo "<pre>";
        var_dump($this->userData);
        var_dump($this->counterpartiesData);
        var_dump($this->userUpdateData);
        echo "</pre>";
    }
}