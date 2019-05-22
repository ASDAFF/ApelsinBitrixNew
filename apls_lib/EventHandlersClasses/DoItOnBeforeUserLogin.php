<?php

AddEventHandler("main", "OnBeforeUserLogin", Array("DoItOnBeforeUserLogin", "OnBeforeUserLogin"));

class DoItOnBeforeUserLogin
{
    function OnBeforeUserLogin($arFields)
    {
        if (stripos($arFields["LOGIN"],"@") && stripos($arFields["LOGIN"],".")) {
            $filter = Array("EMAIL" => $arFields["LOGIN"]);
            $rsUsers = CUser::GetList(($by="LAST_NAME"), ($order="asc"), $filter);
            if($user = $rsUsers->GetNext()){
            $arFields["LOGIN"] = $user["LOGIN"];
            }
        }
    }
}