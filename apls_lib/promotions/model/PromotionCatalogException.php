<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionCatalogException extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_revision_catalog_exceptions";
    protected static $requiredFields = array('revision', 'product');

    public static function searchByRevision(string $revisionId): array
    {
        return PromotionCatalogException::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $revisionId
            )
        );
    }
}