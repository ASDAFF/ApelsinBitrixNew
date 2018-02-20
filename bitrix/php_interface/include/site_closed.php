<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сайт закрыт");
?>

<h1>Сайт находится в разработке. Доступ закрыт.</h1>
<?
$APPLICATION->IncludeComponent("bitrix:system.auth.form",".default",Array(
        "REGISTER_URL" => "",
        "FORGOT_PASSWORD_URL" => "",
        "PROFILE_URL" => "",
        "SHOW_ERRORS" => "Y"
    )
);?>