<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionImageModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_image";
    protected static $requiredFields = array('b_file_nam','s_file_nam','name');
}