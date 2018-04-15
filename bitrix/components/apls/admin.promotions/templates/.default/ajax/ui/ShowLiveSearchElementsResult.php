<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/apls/admin.promotions/classes/AdminPromotions_GoodsLiveSearch.php";
?>
    <div class="ListOfElements TwoColumns SearchProduct">
        <? $result = AdminPromotions_GoodsLiveSearch::getDBResult($_REQUEST["elementLiveSearch"], $_REQUEST["selectCatalogSection"]);
        foreach ($result as $key=>$element):?>
        <div class="ElementBlock">
            <div class="ElementBlockContent">
                <div class="content"><?=$element?></div>
                <div class="button Green AddButton" id="<?=$key?>">Добавить</div>
            </div>
        </div>
        <?endforeach;?>
    </div>
<?