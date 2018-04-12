<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";

$fields = array(
    "PreviewPromotionTextWrapper"=>"preview_text",
    "MainPromotionTextWrapper"=>"main_text",
    "VkPromotionTextWrapper"=>"vk_text",
);
$input = array(
    "PreviewPromotionTextWrapper"=>"PreviewPromotionText",
    "MainPromotionTextWrapper"=>"MainPromotionText",
    "VkPromotionTextWrapper"=>"VkPromotionText",
);


$content = "";
$revision = new PromotionRevisionModel($_REQUEST['revisionId']);
if(isset($fields[$_REQUEST['divId']])) {
    $content = $revision->getFieldValue($fields[$_REQUEST['divId']]);
    $APPLICATION->IncludeComponent("bitrix:fileman.light_editor","",Array(
            "CONTENT" => $content,
            "INPUT_NAME" => $input[$_REQUEST['divId']],
            "INPUT_ID" => $input[$_REQUEST['divId']],
            "WIDTH" => "100%",
            "HEIGHT" => "300px",
            "RESIZABLE" => "N",
            "AUTO_RESIZE" => "Y",
            "VIDEO_ALLOW_VIDEO" => "N",
            "USE_FILE_DIALOGS" => "N",
            "ID" => "",
            "JS_OBJ_NAME" => ""
        )
    );
} else {
    echo "Поле не найдено";
}






