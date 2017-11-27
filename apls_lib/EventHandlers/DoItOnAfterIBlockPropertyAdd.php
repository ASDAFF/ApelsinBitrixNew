<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogConfigurator.php";

function OnAfterIBlockPropertyAddUpdateHandler (&$arFields) {
    $sort = 3000;
    if (isset($arFields) && !empty($arFields)) {
        try {
            if(isset($arFields['XML_ID'])) {
                $xmlID = $arFields['XML_ID'];
            } else {
                $xmlID = APLS_CatalogConfigurator::getPropertiesXMLIDfromID($arFields['ID']);
            }
            $entity_data_class = APLS_GetHighloadEntityDataClass::getByHLName("PropertyGoods");
            $rsData = $entity_data_class::getList(array(
                "select" => array('UF_SORT'),
                "filter" => array('UF_XML_ID' => $xmlID)
            ));
            while ($arData = $rsData->Fetch()) {
                if (isset($arData['UF_SORT'])) {
                    $sort = $arData['UF_SORT'];
                }
            }
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "<br>";
        }
        $arFields['SORT'] = $sort;
    }
}
AddEventHandler('iblock', "OnBeforeIBlockPropertyAdd", "OnAfterIBlockPropertyAddUpdateHandler");
AddEventHandler('iblock', "OnBeforeIBlockPropertyUpdate", "OnAfterIBlockPropertyAddUpdateHandler");