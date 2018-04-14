<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionImageTypeModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_image_type";
    protected static $requiredFields = array('alias','type');
}