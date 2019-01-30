<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
\Bitrix\Main\Loader::includeModule('sale');
global $USER;

$db_sales = CSaleOrderUserProps::GetList(
    array("DATE_UPDATE" => "DESC"),
    array("USER_ID" => $USER->GetID())
);
while ($ar_sales = $db_sales->Fetch()) {
    $ar_profId [$ar_sales['NAME']] = $ar_sales['ID'];
}
$profId = array_shift($ar_profId);
$db_propVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID" => $profId));
while ($arPropVals = $db_propVals->Fetch()) {
    $propVals[$arPropVals['USER_PROPS_ID']][$arPropVals['ORDER_PROPS_ID']]['NAME'] = $arPropVals['NAME'];
    $propVals[$arPropVals['USER_PROPS_ID']][$arPropVals['ORDER_PROPS_ID']]['VALUE'] = $arPropVals['VALUE'];
}
foreach ($propVals as $props) {
    foreach ($props as $key => $prop) {
        if ($key == '3') {
            $resultArray['PHONE'] = $prop['VALUE'];
        }
    }
}
?>
<div class="noSearch_wrapper" templateFolder="<?= $templateFolder ?>">
    <div class="noSearch_centerWrapper">
        <div class="noSearch_img">
            <img src="/images/Shop_Search no result.png">
        </div>
        <div class="noSearch_text">Упс, ничего не нашлось по запросу</div>
        <div class="noSearch_result"></div>
        <div class="noSearch_text">Заполните форму обратной связи и наш менеджер поможет Вам!</div>
        <div class="noSearch_form">
            <div class="noSearch_feedBack_inputBlock">
                <label class="noSearch_feedBack_label">Ваше имя*:</label>
                <input id="noSearch_Name" type="text" value="<?= $USER->GetFirstName() ?>">
            </div>
            <div class="noSearch_feedBack_inputBlock">
                <label class="noSearch_feedBack_label">Номер телефона*:</label>
                <input id="noSearch_Phone" type="text" value="<?= $resultArray['PHONE'] ?>">
            </div>
            <div class="noSearch_feedBack_textarea">
                <label class="noSearch_feedBack_label">Комментарий:</label>
                <textarea id="noSearch_text"></textarea>
            </div>
            <div class="noSearch_btn_block"><button class="noSearch_btn">Отправить</button></div>
        </div>
    </div>
</div>
<div class="noSearch_promoBlock">
    <div class="noSearch_promoBlock_header">Акционные товары</div>
    <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
        array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR . "include/discount.php",
            "AREA_FILE_RECURSIVE" => "N",
            "EDIT_MODE" => "html",
        ),
        false,
        array("HIDE_ICONS" => "Y")
    ); ?>
</div>