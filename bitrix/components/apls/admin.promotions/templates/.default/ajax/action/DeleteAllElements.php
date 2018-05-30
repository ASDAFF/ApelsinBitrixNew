<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/apls_lib/promotions/model/PromotionModelAbstract.php");
?>
<?
switch ($_REQUEST['elementType']) {
    case 'Sections':
        PromotionModelAbstract::getConnection()->queryExecute('
        DELETE
        FROM `apls_promotions_revision_catalog_sections`
        WHERE `revision` = "'.$_REQUEST['revisionId'].'"'
        );
        break;
    case 'Products':
        PromotionModelAbstract::getConnection()->queryExecute('
        DELETE
        FROM `apls_promotions_revision_catalog_products`
        WHERE `revision` = "'.$_REQUEST['revisionId'].'"'
        );
        break;
    case 'Exceptions':
        PromotionModelAbstract::getConnection()->queryExecute('
        DELETE
        FROM `apls_promotions_revision_catalog_exceptions`
        WHERE `revision` = "'.$_REQUEST['revisionId'].'"'
        );
        break;
}
?>