<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/models/ModelAbstract.php";

class CatalogElementModel extends ModelAbstract
{
    protected static $tableName = "b_iblock_element";
    protected static $inSectionTableName = "b_iblock_section_element";
    protected static $autoincrement = true;

    protected static $privateFields = array();
    protected static $requiredFields = array(
        "IBLOCK_ID",
        "ACTIVE",
        "SORT",
        "NAME",
        "PREVIEW_TEXT_TYPE",
        "DETAIL_TEXT_TYPE",
        "IN_SECTIONS",
    );
    protected static $optionalFields = array(
        "TIMESTAMP_X",
        "MODIFIED_BY",
        "DATE_CREATE",
        "CREATED_BY",
        "IBLOCK_SECTION_ID",
        "ACTIVE_FROM",
        "ACTIVE_TO",
        "PREVIEW_PICTURE",
        "PREVIEW_TEXT",
        "DETAIL_PICTURE",
        "DETAIL_TEXT",
        "SEARCHABLE_CONTENT",
        "WF_STATUS_ID",
        "WF_PARENT_ELEMENT_ID",
        "WF_NEW",
        "WF_LOCKED_BY",
        "WF_DATE_LOCK",
        "WF_COMMENTS",
        "XML_ID",
        "CODE",
        "TAGS",
        "TMP_ID",
        "WF_LAST_HISTORY_ID",
        "SHOW_COUNTER",
        "SHOW_COUNTER_START"
    );

    public static function searchBySectionId($sectionId) {
        $sql = static::selectSQL(
            static::$inSectionTableName,array("IBLOCK_ELEMENT_ID"),
            MySQLWhereElementString::getBinaryOperationString(
                'IBLOCK_SECTION_ID',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $sectionId
            )
        );
        $recordSet = static::getConnection()->query($sql);
        $items = array();
        foreach ($recordSet as $item) {
            $items = new CatalogElementModel($item["IBLOCK_ELEMENT_ID"]);
        }
        return $items;
    }

}