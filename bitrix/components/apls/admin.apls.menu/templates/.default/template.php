<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$adminPath = "/bitrix/admin/";
$menuArray = array(
    array(
        "name" => "Настройка акций",
        "items" => array(
            "apls_promotion_region"=>"Настройка локаций проведения акций",
            "apls_promotion_section"=>"Настройка секций для акций",
        ),
    ),
    array(
        "name" => "Настройка каталога",
        "items" => array(
            "apls_properties_modul_control"=>"Сортировка свойств товаров",
            "apls_smart_filter_setting"=>"Детальная настрйока свойств для умного фильтра",
            "apls_substitution_dimensions"=>"Импорт характеристик товаров из свойств",
            "apls_upload_catalogs_img"=>"Импорт картинок для каталога",
            "apls_upload_items_img"=>"Импорт картинок для товаров",
        ),
    ),
    array(
        "name" => "Инспекторы каталога",
        "items" => array(
            "apls_catalog_sections_no_image"=>"Каталоги без картинок",
        ),
    ),
    array(
        "name" => "Ликвидирвоание последствий",
        "items" => array(
            "apls_update_all_products"=>"Обновление статуса активности у товаров",
        ),
    ),
);
?>
<div class="apls-admin-menu">
<?foreach ($menuArray as $block):?>
    <div class="delimiter"><span><?=$block['name']?></span></div>
    <div class="itemsBlock">
    <?foreach ($block['items'] as $file => $name):?>
        <div class="item"><a href="<?=$adminPath?><?=$file?>.php"><?=$name?></a></div>
    <?endforeach;?>
    </div>
<?endforeach;?>
</div>