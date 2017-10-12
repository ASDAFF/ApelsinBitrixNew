<?php

require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/EventHandlersClasses/DoItAfterUpdateUser.php");

AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserUpdateHandler");
AddEventHandler("main", "OnAfterUserUpdate", "OnAfterUserUpdateHandler");

function OnAfterUserUpdateHandler (&$arFields) {
	$update = new DoItAfterUpdateUser($arFields);
    $update->executeUpdate();
}