<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$html = '<input id="regionLongitudeValue" type="text" placeholder="Дологота: '.$_POST["longitudeValue"].'">';
$html .= '<input id="regionLatitudeValue" type="text" placeholder="Широта: '.$_POST["latitudeValue"].'">';
$html .= '<input id="regionZoomValue" type="text" placeholder="Зум: '.$_POST["zoomValue"].'">';
$html .= '<div class="regionSave">Сохранить</div>';
echo $html;
