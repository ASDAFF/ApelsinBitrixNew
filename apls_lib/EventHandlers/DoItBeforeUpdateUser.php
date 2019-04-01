<?php

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");

function OnBeforeUserRegisterHandler (&$arFields) {
    AddMessage2Log($arFields);
    if ($arFields["NAME"] == '') {
        $GLOBALS['APPLICATION']->ThrowException('Не заполнено поле "Имя"');
        return false;
    }
}