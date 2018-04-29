<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionCatalogProduct extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_revision_catalog_products";
    protected static $requiredFields = array('revision', 'product');

    public static function searchByRevision(string $revisionId): array
    {
        return PromotionCatalogProduct::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $revisionId
            )
        );
    }
}