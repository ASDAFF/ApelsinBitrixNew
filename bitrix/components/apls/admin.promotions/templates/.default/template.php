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
$sections = PromotionSectionModel::getElementList();
$regions = PromotionRegionModel::getElementList();
?>
<div type=""
    id='AplsAdminWrapper'
    class='AplsAdminWrapper PromotionWrapper'
    templateFolder="<?= $templateFolder ?>"
    componentFolder="<?= $componentFolder ?>"
>
    <div class='PromotionFilterPanel'>
        <div class='search-block'>
            <input type="text" id="FILTER_SEARCH_STRING" placeholder="Введите текст для поиска">
        </div>
        <div class='filter-blocks'>
            <div class='filter-block'>
                <div class='filter-block-input'>
                    <select id="FILTER_REVISION_TYPE">
                        <option value="<?=PromotionModel::REVISION_TYPE_CURRENT?>" selected>Есть действущие ревизии</option>
                        <option value="<?=PromotionModel::REVISION_TYPE_COMING?>">Есть новые ревизии</option>
                        <option value="<?=PromotionModel::REVISION_TYPE_PAST?>">Есть старые ревизии</option>
                        <option value="<?=PromotionModel::REVISION_TYPE_WITHOUT?>">Без ревизий</option>
                        <option value="<?=PromotionModel::REVISION_TYPE_ALL?>">Показать все</option>
                    </select>
                </div>
            </div>
            <div class='filter-block'>
                <div class='filter-block-text'>Секция:</div>
                <div class='filter-block-input'>
                    <select id="FILTER_SECTION">
                        <option value="all" selected>все</option>
                        <?foreach ($sections as $section):?>
                            <option value="<?=$section->getId()?>"><?=$section->getFieldValue('section')?></option>
                        <?endforeach;?>
                    </select>
                </div>
            </div>
            <div class='filter-block'>
                <div class='filter-block-text'>Сеть магазинов:</div>
                <div class='filter-block-input'>
                    <select id="FILTER_REGION">
                        <option value="all" selected>все</option>
                        <?foreach ($regions as $region):?>
                            <option value="<?=$region->getId()?>"><?=$region->getFieldValue('region')?></option>
                        <?endforeach;?>
                    </select>
                </div>
            </div>
            <div class='filter-block'>
                <div class='filter-block-text'>Размещение:</div>
                <div class='filter-block-input'>
                    <select id="FILTER_PUBLISHED_ON">
                        <option value="none" selected>Без разницы</option>
                        <option value="<?=PromotionModel::PUBLISHED_ON_GLOBAL?>">Глобальный сайт</option>
                        <option value="<?=PromotionModel::PUBLISHED_ON_LOCAL?>">Локальный сайт</option>
                        <option value="<?=PromotionModel::PUBLISHED_ON_VK?>">ВКонтакте</option>
                    </select>
                </div>
            </div>
        </div>
        <div class='sort-block'>
            <div class='sort-block-text'>Сортировка:</div>
            <div class='sort-block-input'>
                <select id="FILTER_SORT_FIELD">
                    <option value="<?=PromotionModel::SORT_FIELD_PROMOTIONS_TITLE?>">Заголовок акции</option>
                    <option value="<?=PromotionModel::SORT_FIELD_REVISION_APPLY_FROM?>" selected>Вступленеи в силу</option>
                    <option value="<?=PromotionModel::SORT_FIELD_REVISION_CREATED?>">Дата создание ревизии</option>
                    <option value="<?=PromotionModel::SORT_FIELD_REVISION_CHANGED?>">Дата изменение ревизии</option>
                    <option value="<?=PromotionModel::SORT_FIELD_REVISION_SHOW_FROM?>">Дата показа</option>
                    <option value="<?=PromotionModel::SORT_FIELD_REVISION_START_FROM?>">Дата старта</option>
                    <option value="<?=PromotionModel::SORT_FIELD_REVISION_STOP_FROM?>">Дата окончания</option>
                    <option value="<?=PromotionModel::SORT_FIELD_PROMOTIONS_CREATED?>">Дата создания акции</option>
                </select>
            </div>
            <div class='sort-block-input'>
                <select id="FILTER_SORT_TYPE">
                    <option value="<?=PromotionModel::SORT_ASC?>" selected>По возрастанию</option>
                    <option value="<?=PromotionModel::SORT_DESC?>">По убыванию</option>
                </select>
            </div>
        </div>
        <div class="addPromotionPanel">
            <div class="Button Green add">Добавить акцию</div>
        </div>
    </div>
    <div class="content"></div>
<!--    <content></content>-->
</div>
<?
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>