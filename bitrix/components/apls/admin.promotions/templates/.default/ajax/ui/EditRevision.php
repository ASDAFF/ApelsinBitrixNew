<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_Tabs/APLS_Tabs.php";
include_once $_SERVER["DOCUMENT_ROOT"] . $_REQUEST['componentFolder'] ."classes/AdminPromotions_Revision.php";
$revision = new PromotionRevisionModel($_REQUEST['revisionId']);
$promotion = new PromotionModel($revision->getFieldValue('promotion'));
if($revision->getFieldValue('disable') > 0) {
    $boolSelect['disable'][0] = '';
    $boolSelect['disable'][1] = 'selected';
} else {
    $boolSelect['disable'][0] = 'selected';
    $boolSelect['disable'][1] = '';
}
if($revision->getFieldValue('global_activity') > 0) {
    $boolSelect['global_activity'][0] = '';
    $boolSelect['global_activity'][1] = 'selected';
} else {
    $boolSelect['global_activity'][0] = 'selected';
    $boolSelect['global_activity'][1] = '';
}
if($revision->getFieldValue('local_activity') > 0) {
    $boolSelect['local_activity'][0] = '';
    $boolSelect['local_activity'][1] = 'selected';
} else {
    $boolSelect['local_activity'][0] = 'selected';
    $boolSelect['local_activity'][1] = '';
}
if($revision->getFieldValue('vk_activity') > 0) {
    $boolSelect['vk_activity'][0] = '';
    $boolSelect['vk_activity'][1] = 'selected';
} else {
    $boolSelect['vk_activity'][0] = 'selected';
    $boolSelect['vk_activity'][1] = '';
}
$revisionImagesWrapper = '<div class="RevisionImagesWrapper"></div>';
$revisionSectionsWrapper = '<div class="RevisionSectionsWrapper"></div>';
$revisionLocationsWrapper = '<div class="RevisionLocationsWrapper"></div>';
$catalogSectionsWrapper = '
<div class="CatalogSectionsWrapper">
<div class="content"></div>
<div class="Settings">
<div class="search">
<input type="text" class="SearchInput" id="ShowLiveSearchSection">
</div>
<div class="downloadFail">
<input id="CatalogUploadFile" class="downloadFailBtn" type="file" name="content" value="Загрузить XML" />
</div>
<div class="AcceptBtn">
<div id="CatalogAcceptBtnOK" class="AcceptBtnOK">Загрузить</div>
</div>
<div class="delete">
<div id="SectionDeleteBtn" class="deleteBtn">Удалить все секции</div>
</div>
</div>
<div class="search-result"></div>
</div>';
$catalogProductsWrapper = '
<div class="CatalogProductsWrapper">
<div class="content"></div>
<div class="Settings">
<div class="search">
<input type="text" class="SearchInput" id="ShowLiveSearchProduct">
</div>
<div class="settingsSelect">
'.APLS_CatalogSections::getSelectBox("",". ","ID","NAME", 1, true).'
</div>
<div class="downloadFail">
<input id="ProductUploadFile" class="downloadFailBtn" type="file" name="content" value="Загрузить XML" />
</div>
<div class="AcceptBtn">
<div id="ProductAcceptBtnOK" class="AcceptBtnOK">Загрузить</div>
</div>
<div class="delete">
<div id="ProductDeleteBtn" class="deleteBtn">Удалить все секции</div>
</div>
</div>
<div class="search-result"></div>
</div>';
$catalogExceptionsWrapper = '
<div class="CatalogExceptionsWrapper">
<div class="content"></div>
<div class="Settings">
<div class="search">
<input type="text" class="SearchInput" id="ShowLiveSearchException">
</div>
<div class="settingsSelect">
'.APLS_CatalogSections::getSelectBox("",". ","ID","NAME", 1, true).'
</div>
<div class="downloadFail">
<input id="ExceptionUploadFile" class="downloadFailBtn" type="file" name="content" value="Загрузить XML" />
</div>
<div class="AcceptBtn">
<div id="ExceptionAcceptBtnOK" class="AcceptBtnOK">Загрузить</div>
</div>
<div class="delete">
<div id="ExceptionDeleteBtn" class="deleteBtn">Удалить все секции</div>
</div>
</div>
<div class="search-result"></div>
</div>';
$tabs = new APLS_Tabs();
$tabs->addTab("Изображения",$revisionImagesWrapper,"AdminPromotionsUiShowRevisionImages");
$tabs->addTab("Разделы",$revisionSectionsWrapper,"AdminPromotionsUiShowRevisionSections");
$tabs->addTab("Локации",$revisionLocationsWrapper,"AdminPromotionsUiShowRevisionLocations");
$tabs->addTab("Каталоги учавствующие в акции",$catalogSectionsWrapper);
$tabs->addTab("Товары учавствующие в акции",$catalogProductsWrapper);
$tabs->addTab("Исключенные из акции товары",$catalogExceptionsWrapper);
$editingRights = $revision->verificationOfEditingRights();
$rsUser = CUser::GetByLogin($revision->getFieldValue('created_user'));
$arUser = $rsUser->Fetch();
if(!$arUser) {
    $name = $revision->getFieldValue('created_user');
}else if($arUser['NAME'] !== "" && $arUser['LAST_NAME'] !== "") {
    $name = $arUser['LOGIN']." (".$arUser['NAME']." ".$arUser['LAST_NAME'].")";
} else if($arUser['NAME'] !== "" || $arUser['LAST_NAME'] !== "") {
    $name = $arUser['LOGIN']." (".$arUser['NAME'].$arUser['LAST_NAME'].")";
} else if($arUser['LOGIN'] !== "") {
    $name = $arUser['LOGIN'];
}
?>
<div class="EditRevisionMainWrapper" promotionId="<?=$promotion->getId()?>" revisionId="<?=$revision->getId()?>">
    <div class="PromotionTitle">
        Ревизия акции: <?=$promotion->getFieldValue('title')?>
        <div class="BackButton Button Yellow" revisionId="<?=$revision->getId()?>" promotionId="<?=$promotion->getId()?>">Назад</div>
    </div>
    <?if(!$editingRights):?>
        <div class="EditingRightsMessage">
            <b>ВНИМАНИЕ!</b> Данная ревизия создана пользователем <span class="user"><?=$name?></span>.<br>
            У вас нет прав на редактирование ревизии. Все изменения по этой ревизии будут недействительны.
        </div>
    <?endif?>
    <div class="TitleText">
        <div class="InputField">
            <div class="text">Заголовок ревизии</div>
            <div class="input">
                <input id="RevisionTitleField" type="text" class="TitleField" value="<?=$revision->getFieldValue("title")?>" name="title">
                <div class="TitleTextDelete" field="title"></div>
            </div>
        </div>
    </div>
    <div class="MainFields">
        <div class="InputField">
            <div class="text">Вступает в силу</div>
            <div class="input">
                <input type="text" class="DateTime" value="<?=$revision->getFieldValue("apply_from")?>" name="apply_from" onclick="BX.calendar({node: this, field: this, bTime: true} );">
            </div>
        </div>
        <div class="InputField">
            <div class="text">Отображать с</div>
            <div class="input">
                <input type="text" class="DateTime" value="<?=$revision->getFieldValue("show_from")?>" name="show_from" onclick="BX.calendar({node: this, field: this, bTime: true} );">
                <div class="DateTimeClear" field="show_from"></div>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Начинается</div>
            <div class="input">
                <input type="text" class="DateTime" value="<?=$revision->getFieldValue("start_from")?>" name="start_from" onclick="BX.calendar({node: this, field: this, bTime: true} );">
                <div class="DateTimeClear" field="start_from"></div>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Заканчивается</div>
            <div class="input">
                <input type="text" class="DateTime" value="<?=$revision->getFieldValue("stop_from")?>" name="stop_from" onclick="BX.calendar({node: this, field: this, bTime: true} );">
                <div class="DateTimeClear" field="stop_from"></div>
            </div>
        </div>
    </div>
    <div class="PromotionText">
        <div class="InputField">
            <div class="text">Превью текст</div>
            <div class="input">
                <div id="PreviewPromotionTextWrapper">
                    <textarea id="PreviewPromotionText" name="PreviewPromotionText" class="TestHtmlEditor" field="preview_text"><?=$revision->getFieldValue("preview_text")?></textarea>
                </div>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Основной текст</div>
            <div class="input">
                <div id="MainPromotionTextWrapper">
                    <textarea id="MainPromotionText" name="MainPromotionText" class="TestHtmlEditor" field="main_text"><?=$revision->getFieldValue("main_text")?></textarea>
                </div>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Текст для VK</div>
            <div class="input">
                <div id="VkPromotionTextWrapper">
                    <textarea id="VkPromotionText" name="VkPromotionText" class="TestHtmlEditor" field="vk_text"><?=$revision->getFieldValue("vk_text")?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="SharesPlacement">
        <div class="InputField">
            <div class="text">Активность ревизии</div>
            <div class="input">
                <select name="disable">
                    <option value="0" <?=$boolSelect['disable'][0]?>>Активна</option>
                    <option value="1" <?=$boolSelect['disable'][1]?>>Не активна</option>
                </select>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Глобальный</div>
            <div class="input">
                <select name="global_activity">
                    <option value="1" <?=$boolSelect['global_activity'][1]?>>Разместить</option>
                    <option value="0" <?=$boolSelect['global_activity'][0]?>>Не размещать</option>
                </select>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Локальный</div>
            <div class="input">
                <select name="local_activity">
                    <option value="1" <?=$boolSelect['local_activity'][1]?>>Разместить</option>
                    <option value="0" <?=$boolSelect['local_activity'][0]?>>Не размещать</option>
                </select>
            </div>
        </div>
        <div class="InputField">
            <div class="text">ВКонтакте</div>
            <div class="input">
                <select name="vk_activity">
                    <option value="1" <?=$boolSelect['vk_activity'][1]?>>Разместить</option>
                    <option value="0" <?=$boolSelect['vk_activity'][0]?>>Не размещать</option>
                </select>
            </div>
        </div>
    </div>
    <div class="CatalogElements">
        <?=$tabs->getTabsHtml()?>
    </div>
    <div class="ButtonPanel">
        <div class="BackButton Button Yellow" revisionId="<?=$revision->getId()?>" promotionId="<?=$promotion->getId()?>">Назад</div>
    </div>
</div>