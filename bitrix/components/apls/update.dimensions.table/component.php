<?php
CJSCore::Init(array("jquery2"));
CJSCore::Init(array('ajax'));

if (!$this->InitComponentTemplate())
    return;

$template = &$this->GetTemplate();
$templatePath = $template->GetFile();
$templateFolder = $template->GetFolder();

try {
    $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("UpdateDimentions");
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
}
$rsData = $entity_data_class::getList(array(
    "select" => array('ID', 'UF_XMLID', 'UF_KOEFF', 'UF_LENGTH', 'UF_WIDTH', 'UF_HEIGHT'),
));
while ($arData = $rsData->Fetch()) {
    $arResult[$arData["UF_XMLID"]]["ID"] = $arData["ID"];
    $arResult[$arData["UF_XMLID"]]["KOEFFITSIENT"] = $arData["UF_KOEFF"];
    $arResult[$arData["UF_XMLID"]]["LENGTH"] = $arData["UF_LENGTH"];
    $arResult[$arData["UF_XMLID"]]["WIDTH"] = $arData["UF_WIDTH"];
    $arResult[$arData["UF_XMLID"]]["HEIGHT"] = $arData["UF_HEIGHT"];
}
$this->IncludeComponentTemplate();