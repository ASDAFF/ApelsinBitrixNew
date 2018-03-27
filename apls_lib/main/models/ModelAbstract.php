<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLTrait.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";

abstract class ModelAbstract
{
    use MySQLTrait;

    /**
     * @var string|int - идентификатор текущей записи
     */
    protected $id;
    /**
     * @var array - даныне текущей записи
     */
    protected $data = array();
    protected $originalData = array();

    /**
     * @var string - tableName - название таблицы
     */
    protected static $tableName = "";

    /**
     * @var string - pk - название столбца который является PRIMARY_KEY
     */
    protected static $pk = 'id';

    /**
     * @var bool - autoincrement - true если полей PRIMARY_KEY является целочисленным и автоинкриментным
     */
    protected static $autoincrement = false;

    /**
     * @var array - privateFields - список првиатных полей таблицы за исключением PRIMARY_KEY
     *      Приватные поля, это те поля, значение которых не позволяется устанавлвиать вне методов класса.
     *      Попытка установить значение этих полей через методы содания записи,
     *      редактирваония полей записи или редактирвоания записи ни к чему не приведут
     *      Заполнить эти поля можно например переопределив методы before*() котоыре вызываются в начале методов
     *      изменения и создания записей.
     */
    protected static $privateFields = array();

    /**
     * @var array - requiredFields - список обязательных полей таблицы за исключением PRIMARY_KEY и privateFields.
     */
    protected static $requiredFields = array();

    /**
     * @var array - optionalFields - спсиок не обязательных полей таблицы за privateFields.
     *      Если для такого поля не будет установлено значений, то будет использоваться значение по умолчанию или null.
     */
    protected static $optionalFields = array();

    /* PUBLIC МЕТОДЫ */

    /**
     * Объект записи из таблицы по идентификатору.
     * @param $id - идентификатор записи
     */
    public final function __construct($id)
    {
        $this->id = $id;
        if (!$this->refreshElement()) {
            exit();
        }
        $this->cancelChanges();
    }

    /**
     * Вернет идентификатор записи
     * @return int|string
     */
    public final function getId()
    {
        return $this->id;
    }

    /**
     * Вернет массив с данными записи, с внесенными изменениями
     * Внесенные изменения - изменения данных записи котоыре были внесены в объект но не сохранены в базу.
     * @return array
     */
    public final function getData(): array
    {
        if ($this->data) {
            return $this->data;
        } else {
            return array();
        }
    }

    /**
     * Вернет массив с данными записи, с примененными изменениями
     * Примененные изменения - изменения данных записи котоыре были внесены в объект и сохранены в базу.
     * @return array
     */
    public final function getOriginalData(): array
    {
        if ($this->originalData) {
            return $this->originalData;
        } else {
            return array();
        }
    }

    /**
     * Вернет массив с данными записи котоыре были изменены, но изменение которых не было применено.
     * Внесенные изменения - изменения данных записи котоыре были внесены в объект но не сохранены в базу.
     * Примененные изменения - изменения данных записи котоыре были внесены в объект и сохранены в базу.
     * @return array
     */
    public final function getChangesData(): array
    {
        return array_diff_assoc($this->data, $this->originalData);
    }

    /**
     * Отменяет не примененные изменения записи
     * Внесенные изменения - изменения данных записи котоыре были внесены в объект но не сохранены в базу.
     * Примененные изменения - изменения данных записи котоыре были внесены в объект и сохранены в базу.
     */
    public final function cancelChanges()
    {
        $this->data = $this->originalData;
    }

    /**
     * Отменяет изменения для указанных полей
     * @param array $fields
     */
    public final function cancelThisFieldsChanges(array $fields)
    {
        foreach ($fields as $field) {
            if (isset($this->originalData[$field])) {
                $this->data[$field] = $this->originalData[$field];
            }
        }
    }

    /**
     * Отменяет изменения для всех полей крмое указанных
     * @param array $fields
     */
    public final function cancelAllChangesExceptFields(array $fields)
    {
        foreach (array_keys($this->data) as $field) {
            if (!in_array($field, $fields)) {
                $this->data[$field] = $this->originalData[$field];
            }
        }
    }

    /**
     * Вернет даныне из соответствующего поля текущей записи
     * @param $field - поле записи для поулчения данных
     * @return mixed
     */
    public final function getFieldValue(string $field)
    {
        return $this->data[$field];
    }

    /**
     * Вносим изменения в данные записи без их применения.
     * Внесенные изменения - изменения данных записи котоыре были внесены в объект но не сохранены в базу.
     * @param string $field - поле записи
     * @param $value - новое значение
     * @return bool - false в случае ошибки и true в случае успеха
     */
    public final function setFieldValue(string $field, $value): bool
    {
        if (in_array($field, static::getPublicFields())) {
            $this->data[$field] = $value;
            return true;
        }
        return false;
    }

    /**
     * Применяет изменения, сохраняя их в базу.
     * После успешного выполнения обновляет данные записи из базы и затем выполняет метод afterSaveElement()
     * @return bool|mixed - false в случае ошибки или результат выполнения метода afterSaveElement()
     */
    public final function saveElement()
    {
        $this->cancelAllChangesExceptFields(static::getPublicFields());
        $ok = $this->beforeSaveElement();
        if ($ok) {
            $changes = $this->getChangesData();
            $changeString = "";
            foreach ($changes as $field => $change) {
                $change = static::getSqlHelper()->forSql($change);
                $changeString .= "`$field`='$change',";
            }
            if ($changeString !== "") {
                try {
                    static::getConnection()->queryExecute(
                        "UPDATE `" . static::$tableName . "` SET " .
                        substr($changeString, 0, -1) .
                        " WHERE `" . static::$pk . "` = '" . $this->id . "'"
                    );
                    $this->refreshElement();
                    $this->cancelChanges();
                    return $this->afterSaveElement();
                } catch (Exception $e) {
                }
            }
        }
        return false;
    }

    /**
     * Обновляет данные записи, не затирая непримененные изменения
     * @return bool - true в случае успеха и false в случае ошибки
     */
    public final function refreshElement(): bool
    {
        $oldData = $this->originalData;
        if ($this->beforeGetElementData()) {
            try {
                $this->originalData = static::getConnection()->query(
                    "SELECT * FROM `" . static::$tableName . "` WHERE `" . static::$pk . "` = '" .
                    static::getSqlHelper()->forSql($this->id) . "'"
                )->fetch();
                if ($this->afterGetElementData()) {
                    return true;
                }
            } catch (Exception $e) {
            }
        }
        $this->originalData = $oldData;
        return false;
    }

    /**
     * Возвращает елемент по идентификатору в виде объекта
     * @param $id - идентификатор записи
     * @return ModelAbstract
     */
    public static final function getElementById($id): ModelAbstract
    {
        $class = get_called_class();
        return new $class($id);
    }

    /**
     * Создает запись в таблице
     * Все входные параметры передаются по сылке в метод beforCreateElement,
     * @param array $fieldsValue - массив ключ-значение где ключ это поле, а значение это значение поля.
     * @param array $attr - дополнительные атрибуиы если нужно.
     *      Используется только для передачи этого параметра в методы beforeCreateElement и afterCreateElement
     * @return bool|int|string - идентификатор созданной записи или false в случае ошибки
     */
    public static final function createElement(array $fieldsValue = array(), array $attr = array())
    {
        static::unsetAllExcept($fieldsValue, static::getPublicFields());
        $ok = static::beforeCreateElement($fieldsValue, $attr);
        // Если beforeCreateElement вернула true и дял всех обязательных полей есть значения
        if ($ok && static::fieldsInFieldsValue($fieldsValue, static::$requiredFields)) {
            // Если ID не автоинкрементный, то генерируем строковый идентификатор
            if (!static::$autoincrement) {
                $id = ID_GENERATOR::generateID(); // генериурем id записи
                $fieldsString = "`" . static::$pk . "`,"; // часть sql строки с полями
                $valueString = "'$id',"; // часть sql строки со значениями
            } else {
                $id = false;
                $fieldsString = ""; // часть sql строки с полями
                $valueString = ""; // часть sql строки со значениями
            }
            // генериурем SQL части с полями и значениями
            foreach ($fieldsValue as $field => $val) {
                $fieldsString .= "`$field`,";
                $val = static::getSqlHelper()->forSql($val);
                $valueString .= "'$val',";
            }
            // удаляем лишнюю запятую
            if ($fieldsString != "" && $valueString != "") {
                $fieldsString = "(" . substr($fieldsString, 0, -1) . ")";
                $valueString = "(" . substr($valueString, 0, -1) . ")";
            } else {
                $fieldsString = "()";
                $valueString = "()";
            }
            // пытаемся выполнить запрос
            try {
                static::getConnection()->queryExecute(
                    "INSERT INTO `" . static::$tableName . "` " . $fieldsString . " VALUES " . $valueString
                );
                if (static::$autoincrement) {
                    $id = static::getConnection()->getInsertedId();
                }
                // пытаемся выполнить дополнительные операции
                if (static::afterCreateElement($id, $attr)) {
                    return $id; // в случае успеха возвращаем id новой записи
                } else {
                    // в случае провала выполнения дополнительных операций удаляем созданную запись
                    static::deleteElement($id);
                }
            } catch (Exception $e) {
            }
        }
        return false;
    }

    /**
     * Обновление записи по идентификатору
     * @param $id - идентификатор обновляемой записи
     * @param array $updateFieldsValue - массив ключ-значение с изменениями которые необходимо внести в запись
     * @param array $attr - дополнительные атрибуиы если нужно.
     *      Используется только для передачи этого параметра в методы beforeUpdateElement и afterUpdateElement
     * @return bool - должна вернуть true в случае успешного выполнения и false в случае ошибки
     */
    public static final function updateElement($id, array $updateFieldsValue, array $attr = array()): bool
    {
        static::unsetAllExcept($fieldsValue, static::getPublicFields());
        $ok = static::beforeUpdateElement($id, $updateFieldsValue, $attr);
        if ($ok) {
            $fields = static::getPublicFields();
            $changeString = "";
            foreach ($updateFieldsValue as $field => $change) {
                if (in_array($field, $fields)) {
                    $change = static::getSqlHelper()->forSql($change);
                    $changeString .= "`$field`='$change',";
                }
            }
            if ($changeString !== "") {
                try {
                    static::getConnection()->queryExecute(
                        "UPDATE `" . static::$tableName . "` SET " .
                        substr($changeString, 0, -1) .
                        " WHERE `" . static::$pk . "` = '" . $id . "'"
                    );
                    return static::afterUpdateElement($attr);
                } catch (Exception $e) {
                }
            }
        }
        return false;

    }

    /**
     * Удаляет запись по идентификатору
     * @param $id - идентификатор обновляемой записи
     * @param array $attr - дополнительные атрибуиы если нужно.
     *      Используется только для передачи этого параметра в методы beforeDeleteElement и afterDeleteElement
     * @return bool - должна вернуть true в случае успешного выполнения и false в случае ошибки
     */
    public static final function deleteElement($id, array $attr = array()): bool
    {
        $ok = static::beforeDeleteElement($id, $attr);
        if ($ok && $id != "" && $id != null && (is_string($id) || is_int($id))) {
            try {
                static::getConnection()->queryExecute(
                    "DELETE FROM `" . static::$tableName . "` WHERE `" . static::$pk . "` = '" . $id . "'"
                );
                return static::afterDeleteElement($attr);
            } catch (Exception $e) {
            }
        }
        return false;
    }

    /**
     * @param null|MySQLWhereString|MySQLWhereElementString $whereObj
     * @param null|int $limitRows
     * @param null|int $limitOffset
     * @param null|MySQLOrderByString $orderByObj
     * @param null|string $groupByField
     * @return array
     */
    public static final function getElementList($whereObj = null, $limitRows = null, $limitOffset = null, $orderByObj = null, $groupByField = null): array
    {
        $elementList = array();
        $sql = MySQLTrait::selectSQL(static::$tableName, array(static::$pk), $whereObj, $limitRows, $limitOffset, $orderByObj, $groupByField);
        $recordset = static::getConnection()->query($sql);
        while ($record = $recordset->fetch()) {
            $key = $record[static::$pk];
            $elementList[$key] = static::getElementById($key);
        }
        return $elementList;
    }

    /**
     * Возвращает имя таблицы текущей модели
     * @return string - имя таблицы
     */
    public static final function getTableName()
    {
        return static::$tableName;
    }

    /**
     * Вернет true если поле PRIMARY_KEY автоинкриментно и false если нет
     * @return bool
     */
    public static final function isPrimaryKeyAutoincrement(): bool
    {
        return static::$autoincrement;
    }

    /**
     * Вернет названеи поля PRIMARY_KEY
     * @return string
     */
    public static final function getPrimaryKeyField(): string
    {
        return static::$pk;
    }

    /**
     * Возвращает массив всех полей записи за исключением PRIMARY_KEY
     * @return array
     */
    public static final function getAllFields(): array
    {
        return array_merge(static::$privateFields, static::$requiredFields, static::$optionalFields);
    }

    /**
     * Возвращает массив всех приватных полей записи
     * @return array
     */
    public static final function getPrivateFields(): array
    {
        return static::$privateFields;
    }

    /**
     * Возвращает массив всех публичных полей записи
     * @return array
     */
    public static final function getPublicFields(): array
    {
        return array_merge(static::$requiredFields, static::$optionalFields);
    }

    /**
     * Возвращает массив всех обязательных для заполнения полей записи
     * @return array
     */
    public static final function getRequiredFields(): array
    {
        return static::$requiredFields;
    }

    /**
     * Возвращает массив всех необязательных для заполнения полей записи
     * @return array
     */
    public static final function getOptionalFields(): array
    {
        return static::$optionalFields;
    }

    public static final function issetElement($id)
    {
        try {
            $rezult = static::getConnection()->query(
                "SELECT `" . static::$pk . "` FROM `" . static::$tableName . "` WHERE `" . static::$pk . "` = '" .
                static::getSqlHelper()->forSql($id) . "'"
            )->fetch();
            return !empty($rezult);
        } catch (Exception $e) {
        }
        return false;
    }

    /* PROTECTED МЕТОДЫ */

    /**
     * Выполянется перед получением данных по элементу в методе refreshElement
     * Если вернет false, то прерывает выполнение метода refreshElement с результатом false
     * @return bool - true в случае успеха и false в случае провала
     */
    protected function beforeGetElementData()
    {
        return true;
    }

    /**
     * Выполянется после получения данных по элементу в методе refreshElement
     * Если вернет false, то прерывает выполнение метода refreshElement с результатом false
     * @return bool - true в случае успеха и false в случае провала
     */
    protected function afterGetElementData()
    {
        return true;
    }

    /**
     * Выполняется в начале метода saveElement.
     * Если вернет false, то прерывает выполнение метода saveElement с результатом false
     * @return bool - true в случае успеха и false в случае провала
     */
    protected function beforeSaveElement(): bool
    {
        return true;
    }

    /**
     * Выполянется в конце применения изменений записи. Выполнение не влияет на результат применения.
     * Возвращенное значение испольузется в качестве результата выполнения метода saveElement
     * @return bool - true в случае успеха и false в случае провала
     */
    protected function afterSaveElement(): bool
    {
        return true;
    }

    /**
     * Метод вызывается автоматически в начале выполнения метода deleteElement.
     * Если данные метод вернет false в ходе своего выполнения, это мгновенно прервет выполнение метода deleteElement
     * ВНИМАНИЕ - $attr - передается по ссылке.
     * @param $id - id записи для которой выполняется deleteElement
     * @param array $attr - дополнительные атрибуиы.
     * @return bool - должна вернуть true в случае успешного выполнения и false в случае ошибки
     */
    protected static function beforeDeleteElement($id, array &$attr): bool
    {
        return true;
    }

    /**
     * Метод вызывается автоматически в конце выполнения метода deleteElement.
     * Крайне не рекомендуется вызывать данный метод в других методах.
     * В случае переопределения функционала данного метода, стоит учитывать, что параметры $attr является массивом.
     * @param $attr - дополнительные атрибуиы.
     *      Если метод был вызван внутри метода deleteElement,
     *      то данные атрибуты являются входным параметром $attr метода deleteElement
     * @return bool - должна вернуть true в случае успешного выполнения и false в случае ошибки
     */
    protected static function afterDeleteElement(array $attr): bool
    {
        return true;
    }

    /**
     * Метод вызывается автоматически в начале выполнения метода updateElement.
     * Если данные метод вернет false в ходе своего выполнения, это мгновенно прервет выполнение метода updateElement
     * ВНИМАНИЕ - $updateFieldsValue и $attr - передаются по ссылке.
     * @param $id - id записи для которой выполняется updateElement
     * @param array $updateFieldsValue - массив ключ-значение с изменениями которые необходимо внести в запись
     * @param array $attr - дополнительные атрибуиы.
     * @return bool - должна вернуть true в случае успешного выполнения и false в случае ошибки
     */
    protected static function beforeUpdateElement($id, array &$updateFieldsValue, array &$attr): bool
    {
        return true;
    }

    /**
     * Метод вызывается автоматически в конце выполнения метода updateElement.
     * Крайне не рекомендуется вызывать данный метод в других методах.
     * В случае переопределения функционала данного метода, стоит учитывать, что параметры $attr является массивом.
     * @param $attr - дополнительные атрибуиы.
     *      Если метод был вызван внутри метода updateElement,
     *      то данные атрибуты являются входным параметром $attr метода updateElement
     * @return bool - должна вернуть true в случае успешного выполнения и false в случае ошибки
     */
    protected static function afterUpdateElement(array $attr): bool
    {
        return true;
    }

    /**
     * Метод вызывается автоматически в начале выполнения метода createElement.
     * Если данные метод вернет false в ходе своего выполнения, это мгновенно прервет выполнение метода createElement
     * ВНИМАНИЕ - все параметры передаются по ссылке.
     * @param array $fieldsValue
     * @param array $attr
     * @return bool
     */
    protected static function beforeCreateElement(array &$fieldsValue, array &$attr): bool
    {
        return true;
    }

    /**
     * Метод вызывается автоматически в конце выполнения метода createElement.
     * Крайне не рекомендуется вызывать данный метод в других методах.
     * В случае переопределения функционала данного метода, стоит учитывать, что параметры $attr является массивом.
     * @param string|int $id - идентификатор созданной записи
     * @param $attr - дополнительные атрибуиы.
     *      Если метод был вызван внутри метода createElement,
     *      то данные атрибуты являются входным параметром $attr метода createElement
     * @return bool - должна вернуть true в случае успешного выполнения и false в случае ошибки
     */
    protected static function afterCreateElement($id, $attr): bool
    {
        return true;
    }

    /**
     * Проверяет для всех ли полей из $fields есть значения из $fieldsValue,
     * приэтом в $fieldsValue значений может быть больше чем полей, но не наоборот
     * @param array $fieldsValue - массив ключ значение, где ключ это поле
     * @param array $fields - массив полей для проверки
     * @return bool
     */
    protected static final function fieldsInFieldsValue(array $fieldsValue, array $fields): bool
    {
        return empty(
        array_diff(
            $fields,
            array_intersect(
                array_keys($fieldsValue),
                $fields
            )
        )
        );
    }

    /**
     * удаляет из входного массива все записи у которых ключи совпадают с указанными полями
     * @param $array - массив для обработки
     * @param $fields - поля для исключения
     */
    protected static final function unsetThisFields(&$array, array $fields)
    {
        foreach (array_keys($array) as $key) {
            if (in_array($key, $fields)) {
                unset($array[$key]);
            }
        }
    }

    /**
     * Удаление всех некоректных полей
     * @param $array
     * @param array $fields
     */
    protected static final function unsetAllExcept(&$array, array $fields)
    {
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $fields)) {
                unset($array[$key]);
            }
        }
    }

    /* MAGIC МЕТОДЫ */

    /**
     * переопределяем поведение при вызове неизвестного метода
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public final function __call($name, $arguments)
    {
        $getFieldsFunctions = array();
        $setFieldsFunctions = array();
        foreach (static::getAllFields() as $field) {
            $fName = mb_convert_case($field, MB_CASE_TITLE, "UTF-8");
            $fName = str_replace("_", "", $fName);
            $getFieldsFunctions["get" . $fName] = $field;
            if (in_array($field, static::getPublicFields())) {
                $setFieldsFunctions["set" . $fName] = $field;
            }
        }
        if (isset($getFieldsFunctions[$name])) {
            return $this->getFieldValue($getFieldsFunctions[$name]);
        } elseif (isset($setFieldsFunctions[$name]) && !empty($arguments)) {
            return $this->setFieldValue($getFieldsFunctions[$name], current($arguments));
        }
    }


    /* ABSTRACT МЕТОДЫ */
}