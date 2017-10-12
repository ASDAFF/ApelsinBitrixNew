<?php
// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class APLS_GetHighloadEntityDataClass
{
    private function __construct() {}

    public static function getByHLID($hlblockID) {
        $hlblock = HL\HighloadBlockTable::getById($hlblockID)->fetch();
        if(!$hlblock){
            throw new \Exception('HL-блок не найден');
        }
        return (HL\HighloadBlockTable::compileEntity($hlblock))->getDataClass();
    }

    public static function getByHLName($hlblockName) {
        $hlblock = HL\HighloadBlockTable::getList(['filter' => ['=NAME' => $hlblockName]])->fetch();
        if(!$hlblock){
            throw new \Exception('HL-блок не найден');
        }
        return (HL\HighloadBlockTable::compileEntity($hlblock))->getDataClass();
    }
}