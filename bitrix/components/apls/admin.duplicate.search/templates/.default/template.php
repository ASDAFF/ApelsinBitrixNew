<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/* JQUERY */
$this->addExternalJs("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js");

/* JQUERY UI */
$this->addExternalJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
$this->addExternalCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
$this->addExternalCss("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");

?>
<div class="duplicateWrapper" templatePath="<?=$templateFolder?>">
    <div class="duplicate_buttons">
        <div class="duplicate_buttons_delete">Удалить выбранное</div>
        <div class="duplicate_buttons_refresh">Обновить выборку</div>
    </div>
    <div class="duplicate_description">Всего <b><?=count($arResult)?></b> пар дубликатов товаров</div>
    <?foreach ($arResult as $key=>$code):?>
        <div class="duplicate_element" id="<?=$key?>" code="<?=$code?>">
            <div class="duplicate_element_title" >
                <div class="duplicate_element_sign"><i class="fa fa-plus"></i></div>
                <div class="duplicate_element_code"><b>Код товара: </b><?=$code?></div>
            </div>
            <div class="duplicate_element_result"></div>
        </div>
    <?endforeach;?>
</div>
