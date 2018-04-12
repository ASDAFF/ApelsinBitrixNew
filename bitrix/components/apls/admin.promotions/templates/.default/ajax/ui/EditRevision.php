<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/sections/APLS_CatalogSections.php";
include_once $_SERVER["DOCUMENT_ROOT"] . $_REQUEST['componentFolder'] ."classes/AdminPromotions_Revision.php";
$revision = new PromotionRevisionModel($_REQUEST['revisionId']);
$promotion = new PromotionModel($revision->getFieldValue('promotion'));
?>
<div class="EditRevisionMainWrapper" promotionId="<?=$promotion->getId()?>" revisionId="<?=$revision->getId()?>">
    <div class="PromotionTitle">Ревизия акции: <?=$promotion->getFieldValue('title')?></div>
    <div class="MainFields">
        <div class="InputField">
            <div class="text">Вступает в силу</div>
            <div class="input">
                <input type="text" class="DateTime" value="<?=$revision->getFieldValue("apply_from")?>" name="apply_from" onclick="BX.calendar({node: this, field: this, bTime: true} );">
                <div class="DateTimeClear"></div>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Отображать с</div>
            <div class="input">
                <input type="text" class="DateTime" value="<?=$revision->getFieldValue("show_from")?>" name="show_from" onclick="BX.calendar({node: this, field: this, bTime: true} );">
                <div class="DateTimeClear"></div>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Начинается</div>
            <div class="input">
                <input type="text" class="DateTime" value="<?=$revision->getFieldValue("start_from")?>" name="start_from" onclick="BX.calendar({node: this, field: this, bTime: true} );">
                <div class="DateTimeClear"></div>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Заканчивается</div>
            <div class="input">
                <input type="text" class="DateTime" value="<?=$revision->getFieldValue("stop_from")?>" name="stop_from" onclick="BX.calendar({node: this, field: this, bTime: true} );">
                <div class="DateTimeClear"></div>
            </div>
        </div>
    </div>
    <div class="PromotionText">
        <div class="InputField">
            <div class="text">Превью текст</div>
            <div class="input">
                <textarea rows="10" cols="45" name="preview_text"><?=$revision->getFieldValue("preview_text")?></textarea>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Основной текст</div>
            <div class="input">
                <textarea rows="10" cols="45" name="preview_text"><?=$revision->getFieldValue("main_text")?></textarea>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Текст для VK</div>
            <div class="input">
                <textarea rows="10" cols="45" name="preview_text"><?=$revision->getFieldValue("vk_text")?></textarea>
            </div>
        </div>
    </div>
    <div class="SharesPlacement">
        <div class="InputField">
            <div class="text">Активность ревизии</div>
            <div class="input">
                <select>
                    <option>Активна</option>
                    <?if($revision->getFieldValue("disable") > "0"):?>
                        <option selected>Не активна</option>
                    <?else:?>
                        <option>Не активна</option>
                    <?endif;?>
                </select>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Глобальный</div>
            <div class="input">
                <select>
                    <option>Разместить</option>
                    <option>Не размещать</option>
                </select>
            </div>
        </div>
        <div class="InputField">
            <div class="text">Локальный</div>
            <div class="input">
                <select>
                    <option>Разместить</option>
                    <option>Не размещать</option>
                </select>
            </div>
        </div>
        <div class="InputField">
            <div class="text">ВКонтакте</div>
            <div class="input">
                <select>
                    <option>Разместить</option>
                    <option>Не размещать</option>
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

    <div class="BackButton Button" revisionId="<?=$revision->getId()?>" promotionId="<?=$promotion->getId()?>">Назад</div>
</div>