<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$this->addExternalJs("/apls_lib/ui/APLS_SortLists/APLS_SortLists.js");
$this->addExternalCss("/apls_lib/ui/APLS_SortLists/APLS_SortLists.css");
$this->addExternalJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
$this->addExternalCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
?>
<div class='sections-no-image-list-wrapper'>
    <?=$arResult['sortListNotDone']->getSortListHtml()?>
</div>
