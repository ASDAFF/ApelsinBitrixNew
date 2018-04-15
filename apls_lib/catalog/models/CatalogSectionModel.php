<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/models/ModelAbstract.php";

class CatalogSectionModel extends ModelAbstract
{
    protected static $tableName = "b_iblock_section";
    protected static $autoincrement = true;

    protected static $privateFields = array();
    protected static $requiredFields = array (
        "TIMESTAMP_X",
        "IBLOCK_ID",
        "ACTIVE",
        "GLOBAL_ACTIVE",
        "SORT",
        "NAME",
        "DESCRIPTION_TYPE"
    );
    protected static $optionalFields = array(
        "MODIFIED_BY",
        "DATE_CREATE",
        "CREATED_BY",
        "IBLOCK_SECTION_ID",
        "PICTURE",
        "LEFT_MARGIN",
        "RIGHT_MARGIN",
        "DEPTH_LEVEL",
        "DESCRIPTION",
        "SEARCHABLE_CONTENT",
        "CODE",
        "XML_ID",
        "TMP_ID",
        "DETAIL_PICTURE",
        "SOCNET_GROUP_ID"
    );

    public static function searchByXmlId(string $xmlId):string {
        $whereSecObj = new MySQLWhereString();
        $whereSecObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "XML_ID",
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $xmlId)
        );
        $orderByObj = new MySQLOrderByString();
        $orderByObj->add("ID",MySQLOrderByString::ASC);
        $result = CatalogSectionModel::getElementList($whereSecObj,1);
        if(!empty($result)) {
            return key($result);
        } else {
            return "";
        }
    }
}