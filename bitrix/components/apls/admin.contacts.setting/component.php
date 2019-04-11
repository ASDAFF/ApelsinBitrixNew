<?php
CJSCore::Init(array("jquery2"));
CJSCore::Init(array('ajax'));
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/geolocation/model/GeolocationRegionsContacts.php";

$regions = PromotionRegionModel::getElementList(null, null, null, null);
$arResult = array();
foreach ($regions as $region) {
    if($region instanceof PromotionRegionModel) {
        $arResult["REGIONS"][$region->getId()]["NAME"] = $region->getFieldValue('region');
        $arResult["REGIONS"][$region->getId()]["longitude"] = $region->getFieldValue('longitude');
        $arResult["REGIONS"][$region->getId()]["latitude"] = $region->getFieldValue('latitude');
        $arResult["REGIONS"][$region->getId()]["zoom"] = $region->getFieldValue('zoom');
        $regionObj = new PromotionRegionModel($region->getId());
        $shopsList = $regionObj->getContacts(true);
        foreach ($shopsList as $shop) {
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["NAME"] = $shop->getFieldValue("name");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["ADDRESS"] = $shop->getFieldValue("address");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["EMAIL"] = $shop->getFieldValue("email");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["PHONE_NUMBER_1"] = $shop->getFieldValue("phone_number_1");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["ADDITIONAL_NUMBER_1"] = $shop->getFieldValue("additional_number_1");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["PHONE_NUMBER_2"] = $shop->getFieldValue("phone_number_2");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["ADDITIONAL_NUMBER_2"] = $shop->getFieldValue("additional_number_2");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["MON_START"] = $shop->getFieldValue("mon_start");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["MON_STOP"] = $shop->getFieldValue("mon_stop");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["TUE_START"] = $shop->getFieldValue("tue_start");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["TUE_STOP"] = $shop->getFieldValue("tue_stop");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["WED_START"] = $shop->getFieldValue("wed_start");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["WED_STOP"] = $shop->getFieldValue("wed_stop");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["THU_START"] = $shop->getFieldValue("thu_start");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["THU_STOP"] = $shop->getFieldValue("thu_stop");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["FRI_START"] = $shop->getFieldValue("fri_start");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["FRI_STOP"] = $shop->getFieldValue("fri_stop");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["SAT_START"] = $shop->getFieldValue("sat_start");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["SAT_STOP"] = $shop->getFieldValue("sat_stop");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["SUN_START"] = $shop->getFieldValue("sun_start");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["SUN_STOP"] = $shop->getFieldValue("sun_stop");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["CREDIT_DEPARTMENT"] = $shop->getFieldValue("credit_department");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["WHOLESALE_DEPARTMENT"] = $shop->getFieldValue("wholesale_department");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["RECEIPT_OF_GOODS"] = $shop->getFieldValue("receipt_of_goods");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["ROUND_THE_CLOCK"] = $shop->getFieldValue("round_the_clock");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["LONGITUDE"] = $shop->getFieldValue("longitude");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["LATITUDE"] = $shop->getFieldValue("latitude");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["ZOOM"] = $shop->getFieldValue("zoom");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["SORT"] = $shop->getFieldValue("sort");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["B_IMG"] = $shop->getFieldValue("b_img");
            $arResult["REGIONS"][$region->getId()]["SHOPS"][$shop->getId()]["S_IMG"] = $shop->getFieldValue("s_img");
        }
    }
}
$this->IncludeComponentTemplate();