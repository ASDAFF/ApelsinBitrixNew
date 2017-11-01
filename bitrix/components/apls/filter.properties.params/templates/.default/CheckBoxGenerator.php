<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "./apls_lib/main/textgenerator/ID_GENERATOR.php";

class CheckBoxGenerator
{
    private static $tabindex = 100;

    private function __construct(){}

    static function get($bool, $idName, $attrName)
    {
        $id = ID_GENERATOR::generateID("checkbox");
        $checkBox = '<input type="checkbox"';
        if ($bool == '1') {
            $checkBox .= 'checked ';
        }
        $checkBox .= ' class="APLS-checkbox ' . $attrName . '" id="' . $id . '" tabindex="' . self::$tabindex++ . '"/>';
        $checkBox .= '<label for="' . $id . '"></label>';
        return $checkBox;
    }
}
