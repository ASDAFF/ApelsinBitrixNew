<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionCatalogSection extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_revision_catalog_sections";
    protected static $requiredFields = array('revision', 'section');

    public static function searchByRevision(string $revisionId): array
    {
        return PromotionCatalogSection::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $revisionId
            )
        );
    }
}