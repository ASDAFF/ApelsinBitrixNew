<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сайт закрыт");
?>

<h1>На сайте проводятся технические работы. Доступ закрыт.</h1>
    <h2>По всем вопросам обращайтесь по номеру телефона 8 (4912) 240-220</h2>
<?
$APPLICATION->IncludeComponent("bitrix:system.auth.form",".default",Array(
        "REGISTER_URL" => "",
        "FORGOT_PASSWORD_URL" => "",
        "PROFILE_URL" => "",
        "SHOW_ERRORS" => "Y"
    )
);?>