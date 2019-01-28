<?php
if (empty($_SERVER['HTTP_REFERER'])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('sale');
global $APPLICATION, $USER;

$db_sales = CSaleOrderUserProps::GetList(
    array("DATE_UPDATE" => "DESC"),
    array("USER_ID" => $USER->GetID())
);
while ($ar_sales = $db_sales->Fetch())
{
    $ar_profId [$ar_sales['NAME']] = $ar_sales['ID'];
}
$profId = array_shift($ar_profId);
$db_propVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$profId));
while ($arPropVals = $db_propVals->Fetch())
{
    $propVals[$arPropVals['USER_PROPS_ID']][$arPropVals['ORDER_PROPS_ID']]['NAME'] = $arPropVals['NAME'];
    $propVals[$arPropVals['USER_PROPS_ID']][$arPropVals['ORDER_PROPS_ID']]['VALUE'] = $arPropVals['VALUE'];
}
foreach ($propVals as $props) {
    foreach ($props as $key=>$prop) {
        if ($key == '3') {
            $resultArray['PHONE'] = $prop['VALUE'];
        }
    }
}
?>
<div class="callBackOverflow"></div>
<div class="callBackModal">
    <div class="closeBtn"><i class="fa fa-times-circle"></i></div>
    <div class="callBackModal_header">Заказать расчет</div>
    <div class="callBackModal_content">
        <div class="callBackModal_content_header">Перезвонить мне:</div>
        <div class="callBackModal_content_input">
            <input id="callBack_Name" type="text" placeholder="Имя *" value="<?=$USER->GetFirstName()?>">
        </div>
        <div class="callBackModal_content_input">
            <input id="callBack_Phone" type="text" placeholder="Номер телефон *" value="<?=$resultArray['PHONE']?>">
        </div>
        <div class="callBackModal_content_input">
            <input id="callBack_Email" type="text" placeholder="E-mail" value="<?=$USER->GetEmail()?>">
        </div>
        <div class="callBackModal_content_agree">
            <div class="callBackModal_content_agree_input"><input type="checkbox" checked></div>
            <div class="callBackModal_content_agree_text">
                При отправке данной формы Вы подтверждаете свою дееспособность и согласие на обработку персональных
                данных согласно <a href="/payments/terms_of_use/">пользовательскому соглашению</a>, <a
                        href="/payments/processing_of_personal_data/">политике конфиденциальности</a> и <a
                        href="/payments/offer/">оферте</a> интернет магазина
                    Апельсин.
            </div>
        </div>
        <div class="callBackModal_content_btn">Отправить</div>
    </div>
</div>