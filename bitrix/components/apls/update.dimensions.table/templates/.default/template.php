<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

//$this->addExternalJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
//$this->addExternalCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");
include_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/apls/update.dimensions.table/templates/.default/APLS_getUpdateArray.php";
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">

<div class='APLSMainWrapper' scriptFile='<?=$templateFolder?>/ajax.php'>
    <div class='APLS_StatusBar'>
        <div class='APLSProgressBar'>
            <div
                    class='APLSProgressBar_Progress'
                    maxSteps='<?=count(array_chunk($arResult, 10, $preserve_keys = true))?>'
                    limit='20'
            >
                <div class='APLSProgressBar_Bar'></div>
            </div>
            <div class='APLSProgressBar_Button' id='start'>Запустить</div>
            <div class='APLSProgressBar_Button' id='stop'>Остановить</div>
        </div>
    </div>
    <div class='resultHTML'>
        <div class="APLSResultTable_Count">Необходимо обновить <?=count($arResult)?> позиции товаров</div>
<!--        <div class="APLSResultTable_String">-->
<!---->
<!--        </div>-->
    </div>
</div>
