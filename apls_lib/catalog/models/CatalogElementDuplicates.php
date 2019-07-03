<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/models/ModelAbstract.php";

/**
 * Class CatalogElementDuplicates
 *
 * @package ${NAMESPACE}
 * @version 1.0.0
 * @author Max Zaitsev
 * @created 2019-07-03 12:36
 * @modified Max Zaitsev,
 * @latestupdate 2019-07-03 12:36
 *
 * @copyright (c) 2010-2019, COMPUPROJECT
 * @email <info@compuproject.com>
 * @website <https://compuproject.com>
 *
 * @license GPL
 */
class CatalogElementDuplicates extends ModelAbstract
{
    protected static $tableName = "duplicates";
    protected static $pk = 'ID';
    protected static $autoincrement = true;
    protected static $privateFields = array();
    protected static $requiredFields = array(
        "ARTNUMBER",
    );
    protected static $optionalFields = array();

}