<?
/**
 * Необходимо подключить в файле /bitrix/modules/main/include/prolog_after.php после в 70 строке
 *  require($_SERVER["DOCUMENT_ROOT"]."/include/closeSiteForNoAuthorized.php");
 */
if(!CUser::IsAuthorized()) {
    if(($siteClosed = getLocalPath("php_interface/".LANG."/site_closed.php", BX_PERSONAL_ROOT)) !== false)
    {
        include($_SERVER["DOCUMENT_ROOT"].$siteClosed);
    }
    elseif(($siteClosed = getLocalPath("php_interface/include/site_closed.php", BX_PERSONAL_ROOT)) !== false)
    {
        include($_SERVER["DOCUMENT_ROOT"].$siteClosed);
    }
    else
    {
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/site_closed.php");
    }
    die();
}