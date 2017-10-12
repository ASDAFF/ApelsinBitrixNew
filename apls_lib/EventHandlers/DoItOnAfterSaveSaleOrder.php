<?php
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");


use Bitrix\Main;
Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderBeforeSaved',
    'SaleOrderDoItOnSave'
);

function SaleOrderDoItOnSave (Main\Event $event) {
    $CARD_NUMBER_ID = 25;
    $TYPE_PRICE_ID = 26;
    $UP_TO_FLOR_ID = 27;
    $FOR_TIME_ID = 28;
    $KILOMETRS_ID = 29;

    $ES_UP_TO_FLOR = array(9,11);
    $ES_FOR_TIME = array(10,12);
    $ES_KILOMETRS = array(13);

    $EXTRA_SERVICES = array();

    $parameters = $event->getParameters();
    $order = $parameters['ENTITY'];
    if ($order instanceof \Bitrix\Sale\Order)
    {
        $user = $order->getUserId();
        $rsUser = CUser::GetByID($user);
        $rsUserArr = $rsUser->Fetch();
        if($rsUserArr["UF_1C_TYPE_PRICE"] == "") {
            $rsUserArr["UF_1C_TYPE_PRICE"] = APLS_GetGlobalParam::getParams("DEFAULT_PRICE_TYPE");
        }
        $shipmentCollection = $order->getShipmentCollection();
        foreach ($shipmentCollection as $shipment){
            $extraServices = $shipment->getExtraServices(); //массив значений доп.услуги, типа array([10]=>'Y')
            if(!empty($extraServices)){
                foreach ($extraServices as $key => $extra) {
                    if(in_array($key,$ES_UP_TO_FLOR)) {
                        $EXTRA_SERVICES["UP_TO_FLOR"] = $extra;
//                        AddMessage2Log("Подъем: ".$extra, "SaleOrderDoItOnSave");
                    }
                    if(in_array($key,$ES_FOR_TIME)) {
                        $EXTRA_SERVICES["FOR_TIME"] = $extra;
//                        AddMessage2Log("Ко времени: ".$extra, "SaleOrderDoItOnSave");
                    }
                    if(in_array($key,$ES_KILOMETRS)) {
                        $EXTRA_SERVICES["KILOMETRS"] = $extra;
//                        AddMessage2Log("Километраж: ".$extra, "SaleOrderDoItOnSave");
                    }
                }
            }
        }

        $properties = $order->getPropertyCollection();
        $CARD_NUMBER = $properties->getItemByOrderPropertyId($CARD_NUMBER_ID);
        $CARD_NUMBER->setValue($rsUserArr["UF_CARD_NUMBER"]);

        $TYPE_PRICE = $properties->getItemByOrderPropertyId($TYPE_PRICE_ID);
        $TYPE_PRICE->setValue($rsUserArr["UF_1C_TYPE_PRICE"]);

        if(isset($EXTRA_SERVICES["UP_TO_FLOR"])) {
            $UP_TO_FLOR = $properties->getItemByOrderPropertyId($UP_TO_FLOR_ID);
            $UP_TO_FLOR->setValue($EXTRA_SERVICES["UP_TO_FLOR"]);
        }

        if(isset($EXTRA_SERVICES["FOR_TIME"])) {
            $FOR_TIME = $properties->getItemByOrderPropertyId($FOR_TIME_ID);
            $FOR_TIME->setValue($EXTRA_SERVICES["FOR_TIME"]);
        }

        if(isset($EXTRA_SERVICES["KILOMETRS"])) {
            $KILOMETRS = $properties->getItemByOrderPropertyId($KILOMETRS_ID);
            $KILOMETRS->setValue($EXTRA_SERVICES["KILOMETRS"]);
        }
    }
}