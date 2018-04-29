<?php

class CatalogElementInSectionModel extends ModelAbstract
{
    protected static $tableName = "b_iblock_section_element";
    protected static $autoincrement = true;

    protected static $privateFields = array();
    protected static $requiredFields = array(
        "IBLOCK_SECTION_ID",
        "IBLOCK_ELEMENT_ID",
    );
    protected static $optionalFields = array(
        "ADDITIONAL_PROPERTY_ID",
    );

    public static function searchBySection(string $sectionId): array
    {
        return CatalogElementInSectionModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'IBLOCK_SECTION_ID',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $sectionId
            )
        );
    }

    protected function beforeSaveElement(): bool
    {
        return false;
    }

    protected static function beforeUpdateElement($id, array &$updateFieldsValue, array &$attr): bool
    {
        return false;
    }

    protected static function beforeCreateElement(array &$fieldsValue, array &$attr): bool
    {
        return false;
    }


}