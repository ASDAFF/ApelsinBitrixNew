<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLTrait.php";

/**
 * Class MySQLWhereElementString
 *
 * Для создания объекта класса необходимо использовать одну из статических функций:
 * getBinaryOperationString - для бинарных операция
 * getUnaryOperationString - для унарных операций
 */
class MySQLWhereElementString
{

    use MySQLTrait;

    const OPTION_WITHOUT_QUOTES = 'WITHOUT_QUOTES';
    const OPTION_IS_SQL = 'IS_SQL';
    const OPTION_IS_FIELD = 'IS_FIELD';

    const OPERATOR_B_MORE = '>';
    const OPERATOR_B_MORE_OR_EQUAL = '>=';
    const OPERATOR_B_LESS = '<';
    const OPERATOR_B_LESS_OR_EQUAL = '<=';
    const OPERATOR_B_EQUAL = '=';
    const OPERATOR_B_NOT_EQUAL = '<>';
    const OPERATOR_B_LIKE = 'LIKE';
    const OPERATOR_B_IS = 'IS';
    const OPERATOR_B_IN = 'IN';
    const OPERATOR_B_NOT_IN = 'NOT IN';
    const OPERATOR_U_NOT_NULL = 'IS NOT NULL';
    const OPERATOR_U_NULL = 'IS NULL';

    const DEFAULT_OPTION_F = array(
        self::OPTION_IS_FIELD,
    );
    const DEFAULT_OPTION_S = array();

    private $binaryOperators = array(
        self::OPERATOR_B_MORE,
        self::OPERATOR_B_MORE_OR_EQUAL,
        self::OPERATOR_B_LESS,
        self::OPERATOR_B_LESS_OR_EQUAL,
        self::OPERATOR_B_EQUAL,
        self::OPERATOR_B_NOT_EQUAL,
        self::OPERATOR_B_LIKE,
        self::OPERATOR_B_IS,
        self::OPERATOR_B_IN,
        self::OPERATOR_B_NOT_IN
    );

    private $unaryOperators = array(
        self::OPERATOR_U_NOT_NULL,
        self::OPERATOR_U_NULL,
    );

    private $operationString = "";

    /**
     * Приватный конструктор
     * MySQLWhereElementString constructor.
     * @param $fOperand
     * @param string $operator
     * @param null $sOperand
     * @param string $fPrefix
     * @param string $sPrefix
     * @param array $fOperandOptions
     * @param array $sOperandOptions
     */
    private function __construct(
        $fOperand,
        string $operator,
        $sOperand = null,
        $fPrefix = "",
        $sPrefix = "",
        array $fOperandOptions = self::DEFAULT_OPTION_F,
        array $sOperandOptions = self::DEFAULT_OPTION_S
    )
    {
        if (
            ($fOperand != null && $fOperand != "") &&
            ($sOperand != null && $sOperand != "") &&
            in_array($operator, $this->binaryOperators)
        ) { // если операция бинарна
            $fOperand = $this->valueString($fOperand, $fOperandOptions, $fPrefix);
            $sOperand = $this->valueString($sOperand, $sOperandOptions, $sPrefix);
            $this->operationString = "$fOperand $operator $sOperand";
        } else if (
            ($fOperand != null && $fOperand != "") &&
            ($sOperand === null || $sOperand == "") &&
            in_array($operator, $this->unaryOperators)
        ) { // если унарна
            $fOperand = $this->valueString($fOperand, $fOperandOptions);
            $this->operationString = "$fOperand $operator";
        }
    }

    public function getString()
    {
        return $this->operationString;
    }

    public function getWhereString()
    {
        if ($this->operationString !== "") {
            return "WHERE " . $this->getString();
        } else {
            return "";
        }
    }

    public static function getBinaryOperationString(
        $fOperand,
        string $operator,
        $sOperandB,
        string $fPrefix = "",
        string $sPrefix = "",
        array $fOperandOptions = self::DEFAULT_OPTION_F,
        array $sOperandOptions = self::DEFAULT_OPTION_S
    ): MySQLWhereElementString
    {
        return new MySQLWhereElementString($fOperand, $operator, $sOperandB, $fPrefix, $sPrefix, $fOperandOptions, $sOperandOptions);
    }

    public static function getUnaryOperationString(
        $operand,
        string $operator,
        string $fPrefix = "",
        array $operandOptions = self::DEFAULT_OPTION_F
    ): MySQLWhereElementString
    {
        return new MySQLWhereElementString($operand, $operator, null, $fPrefix, "", $operandOptions);
    }

    private function valueString($value, array $options, string $prefix = ""): string
    {
        if (in_array(self::OPTION_IS_FIELD, $options)) {
            if ($prefix != "") {
                return "$prefix.`$value`";
            }
            return "`$value`";
        } elseif (in_array(self::OPTION_IS_SQL, $options)) {
            return "($value)";
        } elseif (in_array(self::OPTION_WITHOUT_QUOTES, $options)) {
            return static::getSqlHelper()->forSql($value);
        } else {
            return "'" . static::getSqlHelper()->forSql($value) . "'";
        }
    }
}