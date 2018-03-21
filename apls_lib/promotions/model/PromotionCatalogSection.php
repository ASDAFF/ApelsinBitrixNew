<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionCatalogSection extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_revision_catalog_sections";
    protected static $requiredFields = array('revision', 'section');
}