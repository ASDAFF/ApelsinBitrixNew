<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include_once($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/CheckBoxGenerator.php");
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">
<form action="#" id="APLSFilterPropertiesAll" method="post">
    <div class="APLSFilterProperties" templateFolder="<?= $templateFolder ?>">
        <div class="APLSFilterPropertiesHeaderHeadline">
            <div class="APLSFilterPropertiesHeaderHeadlineName">
                <div class="APLSFilterPropertiesHeaderHeadlineNameUp">ID / Имя</div>
                <div class="APLSFilterPropertiesHeaderHeadlineNameDown">
                    <input name="search" class="APLSFilterPropertiesHeaderSettingsSeachingFormInput" id="search"
                           type="text" placeholder="Поиск по свойствам">
                    <select name="dropdown_2" id="sortBy" class="APLSFilterPropertiesHeaderSettingsDropdown1">
                        <option value="name">По имени</option>
                        <option value="index">По индексу</option>
                    </select>
                </div>
            </div>
            <div class="APLSFilterPropertiesHeaderHeadlineSmartFilter">
                <div class="APLSFilterPropertiesHeaderHeadlineNameUp">Умный фильтр</div>
                <div class="APLSFilterPropertiesHeaderHeadlineNameDown">
                    <select name="dropdown_3" id="sortSF" class="APLSFilterPropertiesHeaderSettingsDropdown2">
                        <option value="all">Показать все</option>
                        <option value="1">Отмеченные</option>
                        <option value="0">Не отмеченные</option>
                    </select>
                </div>
            </div>
            <div class="APLSFilterPropertiesHeaderHeadlineDetailProperty">
                <div class="APLSFilterPropertiesHeaderHeadlineNameUp">Карта товара</div>
                <div class="APLSFilterPropertiesHeaderHeadlineNameDown">
                    <select name="dropdown_4" id="sortDP" class="APLSFilterPropertiesHeaderSettingsDropdown2">
                        <option value="all">Показать все</option>
                        <option value="1">Отмеченные</option>
                        <option value="0">Не отмеченные</option>
                    </select>
                </div>
            </div>
            <div class="APLSFilterPropertiesHeaderHeadlineCompareProperty">
                <div class="APLSFilterPropertiesHeaderHeadlineNameUp">Сравн. товаров</div>
                <div class="APLSFilterPropertiesHeaderHeadlineNameDown">
                    <select name="dropdown_5" id="sortCP" class="APLSFilterPropertiesHeaderSettingsDropdown2">
                        <option value="all">Показать все</option>
                        <option value="1">Отмеченные</option>
                        <option value="0">Не отмеченные</option>
                    </select>
                </div>
            </div>
            <div class="APLSFilterPropertiesHeaderHeadlineApproved">
                <div class="APLSFilterPropertiesHeaderHeadlineNameUp">Одобрено</div>
                <div class="APLSFilterPropertiesHeaderHeadlineNameDown">
                    <select name="dropdown_5" id="sortA" class="APLSFilterPropertiesHeaderSettingsDropdown2">
                        <option value="all">Показать все</option>
                        <option value="1">Отмеченные</option>
                        <option value="0">Не отмеченные</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="APLSFilterPropertiesContainer">
            <div class="HTMLrezult"></div>
            <? require_once($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/view.php"); ?>
            <?= $VIEW_HTML ?>
        </div>
        <div class="statusBar">
            <div class="statusBarButton">отфильтровано</div>
        </div>
    </div>
    <div class="scrollUp"><img
                src="/bitrix/components/apls/sort.properties/templates/.default/logos/up-arrow-5_icon-icons.com_69823.png"
                alt="Кнопка"></div>
</form>
