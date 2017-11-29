<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSectionsTree.php";
$this->addExternalCss("/apls_lib/ui/APLS_Tabs/APLS_Tabs.css");
$this->addExternalCss("/bitrix/templates/elektro_flat_APLS/apls_css/section-menu.css");
$this->addExternalJs("/apls_lib/ui/APLS_Tabs/APLS_Tabs.js");
$this->addExternalJs("/apls_lib/ui/APLS_SortLists/APLS_SortLists.js");
$this->addExternalCss("/apls_lib/ui/APLS_SortLists/APLS_SortLists.css");
$this->addExternalJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
$this->addExternalCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
?>

<div class='SmartFilterSettingWrapper' templateFolder="<?= $templateFolder ?>">
    <div class="SmartFilterSettingButtonPanel">
        <button class="SmartFilterSettingSaveButton">Сохранить</button>
        <button class="SmartFilterSettingRefreshButton">Отменить</button>
        <button class="SmartFilterSettingDefaultButton">Настройки по умолчанию</button>
    </div>
    <div class='SmartFilterSettingMainWrapper'>
        <div class='SmartFilterSettingSectionsTreeWrapper'>
            <?=APLS_CatalogSectionsTree::getSectionsTreeHtml("SmartFilterSettingSectionsTree")?>
            <?$treeID = APLS_CatalogSectionsTree::getLastSectionsTreeID();?>
        </div>
        <div class='SmartFilterSettingPropertiesWrapper'>
            <!-- Сюда грузим столбцы со свойствами -->
            <div class='SmartFilterSettingNoData'><?=GetMessage('NO_DATA')?></div>
        </div>
    </div>
</div>
