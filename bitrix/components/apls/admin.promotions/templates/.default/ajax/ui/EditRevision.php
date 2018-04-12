<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
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
?>
<div class="EditRevisionMainWrapper" promotionId="<?=$promotion->getId()?>" revisionId="<?=$revision->getId()?>">
    <div class="PromotionTitle">
        Ревизия акции: <?=$promotion->getFieldValue('title')?>
        <div class="BackButton Button Yellow" revisionId="<?=$revision->getId()?>" promotionId="<?=$promotion->getId()?>">Назад</div>
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
                <div id="PreviewPromotionTextWrapper"></div>
                <div class="PromotionTextSave Button Small Green" inputId="PreviewPromotionText">применить</div>
                <div class="PromotionTextReset Button Small Red" inputId="PreviewPromotionText">отменить</div>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Основной текст</div>
            <div class="input">
                <div id="MainPromotionTextWrapper"></div>
                <div class="PromotionTextSave Button Small Green" inputId="MainPromotionText">применить</div>
                <div class="PromotionTextReset Button Small Red" inputId="MainPromotionText">отменить</div>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Текст для VK</div>
            <div class="input">
                <div id="VkPromotionTextWrapper"></div>
                <div class="PromotionTextSave Button Small Green" inputId="VkPromotionText">применить</div>
                <div class="PromotionTextReset Button Small Red" inputId="VkPromotionText">отменить</div>
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
    <div class="CatalogSectionsWrapper">
        <div class="title">Каталоги учавствующие в акции</div>
        <div class="content"></div>
        <div class="search">
            <input type="text" class="SearchInput">
        </div>
    </div>
    <div class="CatalogProductsWrapper">
        <div class="title">Товары учавствующие в акции</div>
        <div class="content"></div>
        <div class="search">
            <input type="text" class="SearchInput">
            <?=APLS_CatalogSections::getSelectBox("",". ","ID","NAME", 1, true)?>
        </div>
    </div>
    <div class="CatalogExceptionsWrapper">
        <div class="title">Исключенные из акции товары</div>
        <div class="content"></div>
        <div class="search">
            <input type="text" class="SearchInput">
            <?=APLS_CatalogSections::getSelectBox("",". ","ID","NAME", 1, true)?>
        </div>
    </div>
    <div class="ButtonPanel">
        <div class="BackButton Button Yellow" revisionId="<?=$revision->getId()?>" promotionId="<?=$promotion->getId()?>">Назад</div>
    </div>
</div>