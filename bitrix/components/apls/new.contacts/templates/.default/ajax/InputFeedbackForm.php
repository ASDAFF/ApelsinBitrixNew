<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>
<div id="feedback" class="feedback">
    <div id="feedback_mail" class="feedback_mail">
        <div class="feedback_mail_header">Написать управляющему <?=$_REQUEST['shopName']?></div>
        <div class="feedback_mail_form">
            <div class="feedback_mail_name">
                <div class="feedback_mail_name_header">Имя*</div>
                <div class="feedback_mail_name_text"><input type="text" name="author"></div>
            </div>
            <div class="feedback_mail_phone">
                <div class="feedback_mail_phone_header">Телефон*</div>
                <div class="feedback_mail_phone_text"><input id="feedback_mail_phone" type="text" name="PHONE"></div>
            </div>
            <div class="feedback_mail_text">
                <div class="feedback_mail_text_header">Сообщение*</div>
                <div class="feedback_mail_text_text"><textarea name="text" rows="3"></textarea></div>
            </div>
            <div class="feedback_mail_agreement">
                <div class="feedback_mail_agreement_checkbox"><input id="feedback_mail_agreement" type="checkbox"></div>
                <div class="feedback_mail_agreement_label">	При отправке данной формы Вы подтверждаете свою дееспособность и согласие на обработку персональных данных согласно <a href="/payments/terms_of_use/">пользовательскому соглашению</a>, <a href="/payments/processing_of_personal_data/">политике конфиденциальности</a> и <a href="/payments/offer/">оферте</a> интернет магазина Апельсин.</div>
            </div>
            <div class="feedback_mail_submit">
                <button
                    shopName = "<?=$_REQUEST['shopName']?>"
                    mail = "<?=$_REQUEST['mail']?>"
                    id="feedback_button"
                    class="feedback_mail_submit_button">Отправить</button>
            </div>
        </div>
        <div class="feedback_mail_close"><i class="fa fa-times"></i></div>
    </div>
</div>
