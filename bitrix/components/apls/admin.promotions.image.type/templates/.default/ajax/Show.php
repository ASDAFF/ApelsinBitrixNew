<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>
<div ID="ImageTypeList"></div>
<div class="PromotionImageTypeAddPanel">
    <input type="text" id="ImageTypeNameInput" placeholder="Тип">
    <input type="text" id="ImageTypeAliasInput" placeholder="Псевдоним">
    <div id="ImageTypeAdd" class="Button Green">Добавить</div>
</div>