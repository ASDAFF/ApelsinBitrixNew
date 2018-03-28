<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->addExternalJs("/apls_lib/ui/APLS_SortLists/APLS_SortLists.js");
$this->addExternalCss("/apls_lib/ui/APLS_SortLists/APLS_SortLists.css");
$this->addExternalJs("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js");
$this->addExternalJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
$this->addExternalCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
?>
<div class='PromotionSectionsWrapper' templateFolder="<?= $templateFolder ?>"></div>