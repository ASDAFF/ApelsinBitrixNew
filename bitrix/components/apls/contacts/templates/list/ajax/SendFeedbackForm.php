<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>
<?
$response = array();
if (
    $_REQUEST['formName'] !== '' &&
    $_REQUEST['formPhone'] !== '' &&
    $_REQUEST['formText'] !== '' &&
    $_REQUEST['formAgreement'] !== "false"
) {
    $text = '';
    $text .= '<p>Имя: ';
    $text .= $_REQUEST['formName'];
    $text .= '</p>';
    $text .= '<p>Телефон: ';
    $text .= $_REQUEST['formPhone'];
    $text .= '</p>';
    $text .= '<p>Сообщение: ';
    $text .= $_REQUEST['formText'];
    $text .= '</p>';
    $title = 'Новый отзыв с сайта apelsin.ru по точке ' . $_REQUEST['shopName'];
    if (mail($_REQUEST['mail'], $title, $text)) {
        $close = 'true';
    } else {
        $close = 'false';
    }
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