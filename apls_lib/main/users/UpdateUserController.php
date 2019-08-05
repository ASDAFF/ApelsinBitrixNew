<?php

class UpdateUserController
{
    const SMALL_WHOLESALE_GROUP = "9";      // мелкий опт
    const AVERAGE_WHOLESALE_GROUP = "10";   // средний опт
    const WHOLESALE_GROUP = "11";           // опт
    const BIG_WHOLESALE_GROUP = "12";       // крупный опт
    const DEFAULT_USER_GROUP_FOR_REGISTR_USER = "6";

    private $APLS_USER;
    private $userID;
    private $groupID;
    private $userData;
    private $userUpdateData = array();
    private $groupUpdateData = array();
    private $arTypePrice = [
        "86157e22-e56b-11dc-8b6b-000e0c431b58" => self::SMALL_WHOLESALE_GROUP,      // мелкий опт
        "feff0693-99ab-11db-937f-000e0c431b59" => self::AVERAGE_WHOLESALE_GROUP,    // средний опт
        "feff0694-99ab-11db-937f-000e0c431b59" => self::WHOLESALE_GROUP,            // опт
        "feff0695-99ab-11db-937f-000e0c431b59" => self::BIG_WHOLESALE_GROUP         // крупный опт
    ];
    private $arTypePriceGroups = [
        self::SMALL_WHOLESALE_GROUP,
        self::AVERAGE_WHOLESALE_GROUP,
        self::WHOLESALE_GROUP,
        self::BIG_WHOLESALE_GROUP
    ];
    private $timecreate;

    public function __construct(UpdatedUserModel $updatedUserModel)
    {
        $mtime = microtime(true);
        $time = floor($mtime);
        $ms = $mtime - $time;
        $this->timecreate = date('\\hG\\mi\\ss', $time) . 'ms' . $ms;
//        $this->timecreate = DateTime::format("H:i:s:u:v");
        $this->APLS_USER = new CUser;
        $this->userID = $updatedUserModel->getUserId();
        $this->userData = $updatedUserModel->getUserData();
        $this->userUpdateData = $updatedUserModel->getUserUpdateArray();
        $this->groupID = $this->getUserGroup($this->userData['UF_1C_TYPE_PRICE']);
    }

    /**
     * Страхуемся на случай вознкновения неизвестного типа цены
     * @param $typePrice
     * @return mixed|string
     */
    private function getUserGroup($typePrice)
    {
        return isset($this->arTypePrice[$typePrice]) ?
            $this->arTypePrice[$typePrice] :
            self::DEFAULT_USER_GROUP_FOR_REGISTR_USER;
    }

    /**
     * Подготавливаем данные о группах пользователя для Update
     */
    private function userGroupDataUpdate()
    {
        // Данные о текущих группах пользователя
        $arCurrentGroup = CUser::GetUserGroup($this->userID);
        // данные о нужных группах пользователя
        if (!isset($this->groupID) || $this->groupID === self::DEFAULT_USER_GROUP_FOR_REGISTR_USER) {
            $newUserGroup = array(self::DEFAULT_USER_GROUP_FOR_REGISTR_USER);
        } else {
            $newUserGroup = array($this->groupID, self::DEFAULT_USER_GROUP_FOR_REGISTR_USER);
        }
        // удаляем из массива соответствий типов цен нужные группы
        $groupArr = array_diff($this->arTypePriceGroups, $newUserGroup);
        // соединяем нужные с текущими группами пользователя
        $newUserGroupArr = array_unique(array_merge($arCurrentGroup, $newUserGroup));
        // удаляем, если они были, другие "ценовые" группы у пользователя
        $newUserGroupArr = array_diff($newUserGroupArr, $groupArr);
        // заносим в массив изменений
        if (
            !empty(array_diff($arCurrentGroup, $newUserGroupArr)) ||
            !empty(array_diff($newUserGroupArr, $arCurrentGroup))
        ) {
            $this->groupUpdateData = $newUserGroupArr;
        }
    }

    private function getEmailMessage() {
        $headers = "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "From: no-reply@beta.apelsin.ru \r\n";
        $message = "Уважаемый клиент, Ваша карта заблокирована, обратитесь по номеру 240-220\r\n";
        mail($this->userData["EMAIL"], "Карта заблокирована", $message, $headers);

    }

    public function executeUpdate()
    {
        if (isset($this->userUpdateData["MESSAGE_EMAIL"]) && $this->userUpdateData["MESSAGE_EMAIL"]) {
            $this->getEmailMessage();
        }
        $this->userGroupDataUpdate();
        // данные по группам
        if ($this->userID != null && !empty($this->groupUpdateData)) {
            CUser::SetUserGroup($this->userID, $this->groupUpdateData);
            if ($this->APLS_USER->GetID() == $this->userID) {
                $this->APLS_USER->SetUserGroupArray($this->groupUpdateData);
            }
            $mtime = microtime(true);
            $time = floor($mtime);
            $ms = $mtime - $time;
            $timecreate2 = date('\\hG\\mi\\ss', $time) . 'ms' . $ms;
        }
        if ($this->userID != null && !empty($this->userUpdateData)) {
            $this->APLS_USER->Update($this->userID, $this->userUpdateData);
        }
    }

}