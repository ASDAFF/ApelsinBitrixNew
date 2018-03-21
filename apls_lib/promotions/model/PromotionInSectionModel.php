<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionInSectionModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_in_sections";
    protected static $requiredFields = array('revision', 'section');

    /**
     * вернет массив объектов PromotionInSectionModel у которых ревизия совпадает с указанной
     * @param string $revisionId - идентификатор ревизии
     * @return array - массив объектов PromotionInSectionModel
     */
    public static function searchByRevision(string $revisionId): array
    {
        return PromotionInSectionModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $revisionId
            )
        );
    }

    /**
     * вернет массив объектов PromotionInSectionModel у которых секция совпадает с указанным
     * @param string $sectionId - идентификатор секции
     * @return array - массив объектов PromotionInRegionModel
     */
    public static function searchBySection(string $sectionId): array
    {
        return PromotionInSectionModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'section',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $sectionId
            )
        );
    }

    /**
     * вернет идентификатор существующей записи по соответствию ревизии и секции
     * @param string $revisionId - идентификатор ревизии
     * @param string $sectionId - идентификатор секции
     * @return string - идентификатор или пустая строка если запись не найдена
     */
    public static function getRevisionInSectionId(string $revisionId, string $sectionId): string
    {
        $whereObj = new MySQLWhereString(MySQLWhereString::AND_BLOCK);
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $revisionId
            )
        );
        $whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                'section',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $sectionId
            )
        );
        $sql = MySQLTrait::selectSQL(static::$tableName, array(static::$pk), $whereObj);
        $record = static::getConnection()->query($sql)->fetch();
        if (isset($record[static::$pk])) {
            return $record[static::$pk];
        }
        return "";
    }

    /**
     * Првоеряет наличие записи по ревизии и секции
     * @param string $revisionId - идентификатор ревизии
     * @param string $sectionId - идентификатор секции
     * @return bool
     */
    public static function isRevisionInSection(string $revisionId, string $sectionId): bool
    {
        return static::getRevisionInSectionId($revisionId, $sectionId) !== "";
    }
}