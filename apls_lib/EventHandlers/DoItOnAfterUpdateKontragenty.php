<?php

require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/EventHandlersClasses/DoItOnAfterUpdateKontragenty.php");

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('', 'KontragentyOnAfterUpdate', 'KontragentyDoItOnAfterUpdate');

function KontragentyDoItOnAfterUpdate ($event) {
    $update = new DoItOnAfterUpdateKontragenty($event);
    $update->executeUpdate();
}

