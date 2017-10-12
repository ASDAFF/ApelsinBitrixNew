<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/APLS_GetGlobalParam.php";
$TRAUR = APLS_GetGlobalParam::getParams(array("APLS_LOGO"));
?>
<?
switch ($TRAUR) {
    case 'gray':
        ?><a href="<?=SITE_DIR?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/apls_logo_gray.svg" alt="logo" class="apls_futer_logo"/></a><?
        break;
    case 'newYear':
        ?><a href="<?=SITE_DIR?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/apls_logo_newYear.svg" alt="logo" class="apls_futer_logo"/></a><?
        break;
    default:
        ?><a href="<?=SITE_DIR?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/apls_logo.svg" alt="logo" class="apls_futer_logo"/></a><?
}
?>