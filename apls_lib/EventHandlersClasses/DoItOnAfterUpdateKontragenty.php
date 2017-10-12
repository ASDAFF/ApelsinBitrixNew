<?php
/**
 * Created by PhpStorm.
 * User: ryabchikova
 * Date: 27.04.2017
 * Time: 16:09
 */

class DoItOnAfterUpdateKontragenty {

    private $updatedUserModel;
    private $updateUserController;

    public function __construct($event)
    {
        $this->updatedUserModel = new UpdatedUserModel();
        $this->updatedUserModel->updateCounterpartiesHL($event->getParameter("fields"));
        $this->updateUserController = new UpdateUserController($this->updatedUserModel);
    }

    public function executeUpdate() {
        $this->updateUserController->executeUpdate();
    }

}