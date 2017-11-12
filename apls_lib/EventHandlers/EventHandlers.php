<?php
# Вспомогательыне классы
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/textgenerator/APLS_TextGenerator.php");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/textgenerator/ID_GENERATOR.php");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/inspections/APLS_TextInspections.php");
require $_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/APLS_GetGlobalParam.php";


# Классы событий
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/users/UpdatedUserModel.php");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/main/users/UpdateUserController.php");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/EventHandlers/DoItAfterUpdateElement.php");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/EventHandlers/DoItAfterUpdateUser.php");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/EventHandlers/DoItOnAfterUpdateKontragenty.php");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/EventHandlers/DoItOnAfterSaveSaleOrder.php");
require($_SERVER["DOCUMENT_ROOT"]."/apls_lib/EventHandlers/DoItOnAfterIBlockPropertyAdd.php");

