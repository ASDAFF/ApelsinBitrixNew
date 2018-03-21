<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/models/ModelAbstract.php";

abstract class PromotionModelAbstract extends ModelAbstract
{
    protected static $connectionName = "APLS_PROMOTION";
}
