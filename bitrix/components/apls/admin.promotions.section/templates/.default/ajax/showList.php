<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortLists.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionSectionModel.php";
$orderByObj = new MySQLOrderByString();
$orderByObj->add('sort',MySQLOrderByString::ASC);
$sections = PromotionSectionModel::getElementList(null, null, null, $orderByObj);
$listElements = new APLS_SortListElements();
foreach ($sections as $section) {
    if($section instanceof PromotionSectionModel) {
        $editButton = "<a class='editButton' href='javascript:void(0)' sectionId='".$section->getId()."' sectionName='".$section->getFieldValue('section')."'>переименовать</a>";
        $changeAliasButton = "<a class='changeAliasButton' href='javascript:void(0)' sectionId='".$section->getId()."' alias='".$section->getFieldValue('alias')."'>изменить алиас</a>";
        $delButton = "<a class='delButton' href='javascript:void(0)' sectionId='".$section->getId()."'>удалить</a>";
        $content = "<span class='section'><b>".$section->getFieldValue('section')."</b> (".$section->getFieldValue('alias').")</span>";
        $content .= "<div class='buttonPanel'>$editButton $changeAliasButton $delButton</div>";
        $listElement = new APLS_SortListElement($content);
        $listElement->addAttribute('sectionId',$section->getId());
        $listElements->addSortListElement($listElement);
    }
}
$list = new APLS_SortList();
$list->setSortListElements($listElements);
?>
<div class='PromotionSectionsList'>
    <?=$list->getSortListHtml()?>
</div>
<div class='PromotionSectionsListButtonPanel'>
    <div class='NewSectionName'><input type='text' required placeholder='Название секции'></div>
    <div class='NewSectionAlias'><input type='text' required placeholder='Алиас'></div>
    <div class='NewSectionAdd'>Создать секции</div>
</div>
