<?php

/**
 * Created by PhpStorm.
 * User: olga
 * Date: 08.11.16
 * Time: 9:30
 */

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class DoItAfterUpdateUser
{
    private $updatedUserModel;
    private $updateUserController;

    public function __construct(&$arFields)
    {
        $this->updatedUserModel = new UpdatedUserModel();
        $this->APLS_USER = new CUser;
        $userID = isset($arFields['USER_ID']) ? $arFields['USER_ID'] : (isset($arFields['ID']) ? $arFields['ID'] : $this->APLS_USER->GetID());
        $this->updatedUserModel->updateUser($userID);
        $this->updateUserController = new UpdateUserController($this->updatedUserModel);
    }

    public function executeUpdate() {
        $this->updateUserController->executeUpdate();
    }
}