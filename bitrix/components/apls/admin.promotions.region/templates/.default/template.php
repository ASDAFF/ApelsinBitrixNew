<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->addExternalJs("/apls_lib/ui/APLS_SortLists/APLS_SortLists.js");
$this->addExternalCss("/apls_lib/ui/APLS_SortLists/APLS_SortLists.css");
$this->addExternalJs("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js");
$this->addExternalJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
$this->addExternalCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
// для работы поиска по регионам
$this->addExternalJs('/bitrix/js/sale/core_ui_widget.js');
$this->addExternalJs('/bitrix/js/sale/core_ui_etc.js');
$this->addExternalJs('/bitrix/js/sale/core_ui_autocomplete.js');
$this->addExternalJs('/bitrix/components/bitrix/sale.location.selector.search/templates/.default/script.js');
$this->addExternalCss('/bitrix/components/bitrix/sale.location.selector.search/templates/.default/style.css');
$componentFolder = $this->getComponent()->getPath()."/";
include_once $_SERVER["DOCUMENT_ROOT"] . $componentFolder."AdminPromotionsRegion.php";
?>


<div
        class='PromotionRegionWrapper'
        templateFolder="<?= $templateFolder ?>"
        componentFolder="<?= $componentFolder ?>"
>
</div>