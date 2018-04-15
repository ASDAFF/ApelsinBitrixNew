<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionImageInRevisionModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_image_in_revision";
    protected static $requiredFields = array('revision','img');

    public static function searchByRevision(string $revisionId): array
    {
        return PromotionImageInRevisionModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $revisionId
            )
        );
    }

    public static function searchByImage(string $imageId): array
    {
        return PromotionImageInRevisionModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'img',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $imageId
            )
        );
    }
}