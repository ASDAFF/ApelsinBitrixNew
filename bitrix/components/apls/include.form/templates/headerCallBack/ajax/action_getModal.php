<?php
if (empty($_SERVER['HTTP_REFERER'])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>
<div class="callBackOverflow"></div>
<div class="callBackModal">
    <div class="closeBtn"><i class="fa fa-times-circle"></i></div>
    <div class="callBackModal_header">Заказать расчет</div>
    <div class="callBackModal_content">
        <div class="callBackModal_content_header">Перезвонить мне:</div>
        <div class="callBackModal_content_input">
            <input id="callBack_Name" type="text" placeholder="Имя *">
        </div>
        <div class="callBackModal_content_input">
            <input id="callBack_Phone" type="text" placeholder="Номер телефон *">
        </div>
        <div class="callBackModal_content_input">
            <input id="callBack_Email" type="text" placeholder="E-mail">
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