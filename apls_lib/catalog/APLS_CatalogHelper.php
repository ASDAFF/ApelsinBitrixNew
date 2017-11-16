<?php
class APLS_CatalogHelper
{
    private static $HIGHLOAD_CATALOG_ID = null;

    /**
     * Вернет id инфоблока каталога
     * @return string|null
     */
    public static function getShopIblockId() {
        if(!isset(self::$HIGHLOAD_CATALOG_ID)) {
            self::$HIGHLOAD_CATALOG_ID = APLS_GetGlobalParam::getParams("HIGHLOAD_CATALOG_ID");
        }
        return self::$HIGHLOAD_CATALOG_ID;
    }
}