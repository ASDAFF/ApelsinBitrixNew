<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/* ОБЩАЯ CSS */
$this->addExternalCss("/apls_lib/css/AdminCSS.css");

/* JQUERY */
$this->addExternalJs("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js");

/* JQUERY UI */
$this->addExternalJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
$this->addExternalCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");

/* APLS_Tabs */
$this->addExternalJs("/apls_lib/ui/APLS_Tabs/APLS_Tabs.js");
$this->addExternalCss("/apls_lib/ui/APLS_Tabs/APLS_Tabs.css");

/* APLS_SortLists */
$this->addExternalJs("/apls_lib/ui/APLS_SortLists/APLS_SortLists.js");
$this->addExternalCss("/apls_lib/ui/APLS_SortLists/APLS_SortLists.css");

/* COMPONENT_FOLDER */
$componentFolder = $this->getComponent()->getPath()."/";
?>
<div
    class='AplsAdminWrapper PromotionWrapper'
    templateFolder="<?= $templateFolder ?>"
    componentFolder="<?= $componentFolder ?>"
>

</div>