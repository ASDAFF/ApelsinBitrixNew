<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/* ОБЩАЯ CSS */
$this->addExternalCss("/apls_lib/css/AdminCSS.css");

/* JQUERY */
$this->addExternalJs("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js");

/* COMPONENT_FOLDER */
$componentFolder = $this->getComponent()->getPath()."/";
?>
<div type=""
     id='AplsAdminWrapper'
     class='AplsAdminWrapper PromotionImageTypeWrapper'
     templateFolder="<?= $templateFolder ?>"
     componentFolder="<?= $componentFolder ?>"
></div>
