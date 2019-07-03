<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/models/ModelAbstract.php";

/**
 * Class CatalogElementProperyModel
 *
 * @version 1.0.0
 * @author Max Zaitsev
 * @created 2019-07-03 08:17
 * @modified Max Zaitsev,
 * @latestupdate 2019-07-03 08:17
 *
 * @copyright (c) 2010-2019, COMPUPROJECT
 * @email <info@compuproject.com>
 * @website <https://compuproject.com>
 *
 * @license GPL
 */
class CatalogElementPropertyModel extends ModelAbstract
{
    protected static $tableName = "b_iblock_element_property";
    protected static $pk = 'ID';
    protected static $autoincrement = true;
    protected static $privateFields = array();
    protected static $requiredFields = array(
        "IBLOCK_PROPERTY_ID",
        "IBLOCK_ELEMENT_ID",
        "VALUE",
    );
    protected static $optionalFields = array(
        "VALUE_TYPE",
        "VALUE_ENUM",
        "VALUE_NUM",
        "DESCRIPTION",
    );

}