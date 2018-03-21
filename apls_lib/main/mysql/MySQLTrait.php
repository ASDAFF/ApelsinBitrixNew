<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLWhereString.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLOrderByString.php";

trait MySQLTrait
{
    protected static $connectionName = "";

    public static final function getConnection($connectionName = "")
    {
        if ($connectionName === "") {
            $connectionName = static::$connectionName;
        }
        return \Bitrix\Main\Application::getConnection($connectionName);
    }

    public static final function getSqlHelper()
    {
        return self::getConnection()->getSqlHelper();
    }

    public static final function mysqlDateTime()
    {
        return date("Y-m-d H:i:s");
    }

    public static final function mysqlDate()
    {
        return date("Y-m-d");
    }

    public static final function mysqlTime()
    {
        return date("H:i:s");
    }

    /**
     * @param string $tableName
     * @param array $fields
     * @param null|MySQLWhereString|MySQLWhereElementString $whereObj
     * @param null|int $limitRows
     * @param null|int $limitOffset
     * @param null|MySQLOrderByString $orderByObj
     * @param null|string $groupByField
     * @return string
     */
    public static function selectSQL(string $tableName, array $fields = array(), $whereObj = null, $limitRows = null, $limitOffset = null, $orderByObj = null, $groupByField = null): string
    {
        // fieldsString
        $fieldsString = "";
        foreach ($fields as $field) {
            $fieldsString .= "`$field`,";
        }
        if ($fieldsString != "") {
            $fieldsString = substr($fieldsString, 0, -1);;
        } else {
            $fieldsString = "*";
        }
        // whereString
        if ($whereObj instanceof MySQLWhereString) {
            $whereString = $whereObj->getWhereString();
        } elseif ($whereObj instanceof MySQLWhereElementString) {
            $whereString = $whereObj->getWhereString();
        } else {
            $whereString = "";
        }
        // groupByString
        if ($groupByField !== null) {
            $groupByString = "GROUP BY `$groupByField`";
        } else {
            $groupByString = "";
        }
        // orderByString
        if ($orderByObj instanceof MySQLOrderByString) {
            $orderByString = $orderByObj->getString();
        } else {
            $orderByString = "";
        }
        // limitString
        if (is_int($limitRows) && $limitRows > 0) {
            if (is_int($limitOffset) && $limitOffset > 0) {
                $limitString = "LIMIT $limitOffset, $limitRows";
            } else {
                $limitString = "LIMIT $limitRows";
            }
        } else {
            $limitString = "";
        }
        return "SELECT $fieldsString FROM `$tableName` $whereString $groupByString $orderByString $limitString";
    }
}