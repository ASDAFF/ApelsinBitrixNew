<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLWhereElementString.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";

class MySQLWhereString
{
    const AND_BLOCK = 'AND';
    const OR_BLOCK = 'OR';

    const TYPE_ARRAY = array(self::AND_BLOCK, self::OR_BLOCK);

    private $type;
    private $elements = array();

    public function __construct(string $type = self::AND_BLOCK)
    {
        $this->setType($type);
    }

    public function getString()
    {
        $operationString = "";
        foreach ($this->elements as $element) {
            $elString = "";
            if ($element instanceof MySQLWhereElementString) {
                $elString = $element->getString();
            } elseif ($element instanceof MySQLWhereString) {
                $elString = "(" . $element->getString() . ")";
            }
            if ($elString !== "" && $elString !== "()") {
                $operationString .= $elString . " " . $this->type . " ";
            }
        }
        if ($operationString !== "") {
            $operationString = substr($operationString, 0, (strlen($this->type) + 2) * -1);
        }
        return $operationString;
    }

    public function getWhereString()
    {
        $operationString = $this->getString();
        if ($operationString !== "") {
            return "WHERE " . $this->getString();
        } else {
            return "";
        }
    }

    public function addElement(MySQLWhereElementString $element)
    {
        return $this->add($element);
    }

    public function removeElement(string $id)
    {
        $this->remove($id);
    }

    public function setElement(string $id, MySQLWhereElementString $element)
    {
        return $this->set($id, $element);
    }

    public function addBlock(MySQLWhereString $elementsBlock)
    {
        return $this->add($elementsBlock);
    }

    public function removeBlock(string $id)
    {
        return $this->remove($id);
    }

    public function setBlock(string $id, MySQLWhereString $elementsBlock)
    {
        return $this->set($id, $elementsBlock);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if (in_array($type, self::TYPE_ARRAY)) {
            $this->type = $type;
        } else {
            $this->type = self::AND_BLOCK;
        }
    }

    private function add($element)
    {
        $id = ID_GENERATOR::generateID();
        return $this->set($id, $element);
    }

    private function remove(string $id)
    {
        if (isset($this->elements[$id])) {
            unset($this->elements[$id]);
            return true;
        }
        return false;
    }

    private function set(string $id, $element)
    {
        $this->elements[$id] = $element;
        return $id;
    }
}