<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionImageInRevisionModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_image_in_revision";
    protected static $requiredFields = array('revision','img');
}