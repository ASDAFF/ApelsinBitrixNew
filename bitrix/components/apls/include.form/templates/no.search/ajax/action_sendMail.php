<?php
if (empty($_SERVER['HTTP_REFERER'])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Mail\Event;

$data = array(
    'AUTHOR' => $_REQUEST['name'],
    'AUTHOR_PHONE' => $_REQUEST['phone'],
);
if (isset($_REQUEST['comment'])) {
    $data['AUTHOR_COMMENT'] = $_REQUEST['comment'];
} else {
    $data['AUTHOR_COMMENT'] = 'Не указано';
}
Event::send(array(
    "EVENT_NAME" => "NOSEARCH_FEEDBACK_FORM",
    "LID" => "s1",
    "C_FIELDS" => $data,
));
?>