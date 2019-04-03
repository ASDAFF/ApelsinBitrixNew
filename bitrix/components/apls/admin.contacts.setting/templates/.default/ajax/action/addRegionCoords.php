<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$html = '<input id="regionLongitudeValue" type="text" coordsValue="'.$_POST["longitudeValue"].'" placeholder="Дологота: '.$_POST["longitudeValue"].'">';
$html .= '<input id="regionLatitudeValue" type="text" coordsValue="'.$_POST["latitudeValue"].'" placeholder="Широта: '.$_POST["latitudeValue"].'">';
$html .= '<input id="regionZoomValue" type="text" coordsValue="'.$_POST["zoomValue"].'" placeholder="Зум: '.$_POST["zoomValue"].'">';
$html .= '<div class="regionSave">Сохранить</div>';
echo $html;
