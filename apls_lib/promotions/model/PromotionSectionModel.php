<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionSectionModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_sections";
    protected static $requiredFields = array('section','alias');
    protected static $optionalFields = array('sort');
}