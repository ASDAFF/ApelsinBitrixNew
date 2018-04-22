<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionImageTypeModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_image_type";
    protected static $requiredFields = array('alias','type');

    public static function searchTypeByAlias($alias) {
        $list = PromotionImageTypeModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'alias',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $alias
            )
        );
        if(empty($list)) {
            return null;
        } else {
            return array_shift($list);
        }
    }
}