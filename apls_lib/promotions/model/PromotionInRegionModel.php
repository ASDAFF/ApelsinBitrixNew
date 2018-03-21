<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionInRegionModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_in_region";
    protected static $requiredFields = array('revision', 'region');

    /**
     * вернет массив объектов PromotionInRegionModel у которых ревизия совпадает с указанной
     * @param string $revisionId - идентификатор ревизии
     * @return array - массив объектов PromotionInRegionModel
     */
    public static function searchByRevision(string $revisionId): array
    {
        return PromotionInRegionModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'revision',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $revisionId
            )
        );
    }

    /**
     * вернет массив объектов PromotionInRegionModel у которых регион совпадает с указанным
     * @param string $regionId - идентификатор региона
     * @return array - массив объектов PromotionInRegionModel
     */
    public static function searchByRegion(string $regionId): array
    {
        return PromotionInRegionModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'region',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $regionId
            )
        );
    }

    /**
     * вернет идентификатор существующей записи по соответствию ревизии и региона
     * @param string $revisionId - идентификатор ревизии
     * @param string $regionId - идентификатор региона
     * @return string - идентификатор или пустая строка если запись не найдена
     */
    public static function getRevisionInRegionId(string $revisionId, string $regionId): string
    {
        if ($revisionId !== "" && $regionId !== "") {
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
                    'region',
                    MySQLWhereElementString::OPERATOR_B_EQUAL,
                    $regionId
                )
            );
            $sql = MySQLTrait::selectSQL(static::$tableName, array(static::$pk), $whereObj);
            $record = static::getConnection()->query($sql)->fetch();
            if (isset($record[static::$pk])) {
                return $record[static::$pk];
            }
        }
        return "";
    }

    /**
     * Првоеряет наличие записи по ревизии и региону
     * @param string $revisionId - идентификатор ревизии
     * @param string $regionId - идентификатор региона
     * @return bool
     */
    public static function isRevisionInRegion(string $revisionId, string $regionId): bool
    {
        return static::getRevisionInRegionId($revisionId, $regionId) !== "";
    }
}