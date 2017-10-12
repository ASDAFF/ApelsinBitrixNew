<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";

class APLS_GetGlobalParam
{
    /**
     * Возвращает глобальные значения из HL-блока GlobalParams по входным ключам
     * @param $paramCode - array или string
     * @return array или string в зависимости от @param
     */
    public static function getParams($paramCode)
    {
        $val = "";
        $rezult = array();
        try {
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("GlobalParams");
            $rsData = $entity_data_class::getList(array(
                "select" => array("UF_PARAM","UF_VAL"),
                "order" => array("UF_PARAM" => "ASC"),
                "filter" => array("UF_PARAM" => $paramCode)
            ));
            while ($arData = $rsData->Fetch()) {
                $rezult[$arData["UF_PARAM"]]=$val=$arData["UF_VAL"];
            }
            if(count($rezult) > 1 ) {
                return $rezult;
            } else {
                return $val;
            }
        } catch (Exception $e) {
            return $html = 'Выброшено исключение: ' . $e->getMessage() . "<br>";
        }
    }
}