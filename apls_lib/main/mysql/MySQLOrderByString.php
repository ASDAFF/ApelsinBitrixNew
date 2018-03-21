<?php

class MySQLOrderByString
{

    const ASC = "ASC";
    const DESC = "DESC";
    private $elements = array();

    public function getString()
    {
        $string = "";
        foreach ($this->elements as $element) {
            if ($element['prefix'] != "") {
                $string .= $element['prefix'] . ".";
            }
            $string .= "`" . $element['field'] . "` " . $element['order'] . ",";
        }
        if ($string != "") {
            return "ORDER BY " . substr($string, 0, -1);
        }
        return "";
    }

    public function add(string $field, string $order, string $prefix = "")
    {
        $id = ID_GENERATOR::generateID();
        return $this->set($id, $field, $order, $prefix);
    }

    public function remove(string $id)
    {
        if (isset($this->elements[$id])) {
            unset($this->elements[$id]);
            return true;
        }
        return false;
    }

    public function set(string $id, string $field, string $order, string $prefix = "")
    {
        if ($order === self::ASC || $order === self::DESC) {
            $this->elements[$id]['field'] = $field;
            $this->elements[$id]['order'] = $order;
            $this->elements[$id]['prefix'] = $prefix;
            return $id;
        }
        return false;
    }

}