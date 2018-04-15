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

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_Tabs/APLS_Tabs.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageTypeModel.php";

$tabs = new APLS_Tabs();
$orderBy = new MySQLOrderByString();
$orderBy->add("type",MySQLOrderByString::ASC);
$types = PromotionImageTypeModel::getElementList(null,null,null, $orderBy);
foreach ($types as $type) {
    if($type instanceof PromotionImageTypeModel) {
        $tabs->addTab($type->getFieldValue("type"), "", "AdminPromotionsImageUiLoadTypeImages", $type->getId());
    }
}
?>
<div type=""
     id='AplsAdminWrapper'
     class='AplsAdminWrapper PromotionImageWrapper'
     templateFolder="<?= $templateFolder ?>"
     componentFolder="<?= $componentFolder ?>"
>
<?=$tabs->getTabsHtml()?>
</div>
