<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>
<div id="feedback" class="feedback">
    <div id="big_image_wrapper" class="big_image_wrapper">
        <img src="<?=$_POST['bigImg']?>">
        <div class="feedback_mail_close"><i class="fa fa-times"></i></div>
    </div>
</div>
