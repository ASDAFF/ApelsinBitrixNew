<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>
<?
use Bitrix\Main\Mail\Event;
$response = array();
if (
    $_REQUEST['formName'] !== '' &&
    $_REQUEST['formPhone'] !== '' &&
    $_REQUEST['formText'] !== '' &&
    $_REQUEST['formAgreement'] !== "false"
) {
    $data = array(
        'AUTHOR' => $_REQUEST['formName'],
        'AUTHOR_EMAIL' => $_REQUEST['formPhone'],
        'TEXT' => $_REQUEST['formText'],
        'EMAIL_TO' => $_REQUEST['mail'],
        'SHOP' => $_REQUEST['shopName'],
    );
    $close = Event::send(array(
        "EVENT_NAME" => "CONTACT_FEEDBACK_FORM",
        "LID" => "s1",
        "C_FIELDS" => $data,
    ));
    $close = 'true';
}

if (
    $_REQUEST['formName'] == '' ||
    $_REQUEST['formPhone'] == '' ||
    $_REQUEST['formText'] == '' ||
    $_REQUEST['formAgreement'] == "false"
) {
    $error = '';
    $error .= '<div class="feedback_mail_error">';
    $error .= '<div class="feedback_mail_error_text">';
    if ($_REQUEST['formName'] == '') {
        $error .= 'Не заполнено поле "Имя"<br>';
    }
    if ($_REQUEST['formPhone'] == '') {
        $error .= 'Не заполнено поле "Телефон"<br>';
    }
    if ($_REQUEST['formText'] == '') {
        $error .= 'Не заполнено поле "Сообщение"<br>';
    }
    if ($_REQUEST['formAgreement'] == "false") {
        $error .= 'Вы не согласились на обработку Ваших персональных данных<br>';
    }
    $error .= '</div>';
    $error .= '</div>';
}

$response = [
    'success' => array(
        'close' => $close,
        'error' => $error
    )
];

echo Bitrix\Main\Web\Json::encode($response); ?>