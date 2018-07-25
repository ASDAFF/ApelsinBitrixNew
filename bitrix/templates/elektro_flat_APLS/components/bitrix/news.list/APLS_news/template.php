<!--
$arResult['ITEMS'] - список новостей
$arResult['ITEMS']['PREVIEW_TEXT'] - Превью текст
$arResult['ITEMS']['DETAIL_TEXT'] - Детальный текст
$arResult['ITEMS']['DETAIL_PAGE_URL'] - URL ссылка на детальную страницу
$arResult['ITEMS']['PREVIEW_PICTURE']['SRC'] - ссылка на картинку
$arResult['ITEMS']['PREVIEW_PICTURE']['ALT'] - подсказка к картинке
-->
<!--<pre>-->
<?//var_dump($arResult['ITEMS'])?>
<!--</pre>-->
<div class="NewsWrapper">
    <?foreach ($arResult['ITEMS'] as $newsElement):?>
        <div class="NewsElement">
            <div class="NewsImage">
                <img src="<?=$newsElement['PREVIEW_PICTURE']['SRC']?>" alt="<?=$newsElement['PREVIEW_PICTURE']['ALT']?>">
            </div>
            <div class="NewsContent">
                <div class="NewsTitle"><?=$newsElement['NAME']?></div>
                <div class="NewsText">
                    <p><?=$newsElement['PREVIEW_TEXT']?></p>
                </div>
            </div>
            <div class="NewsFooter">
                <?if($newsElement['DETAIL_TEXT'] !== ""):?>
                <a class="ButtonMore" href="<?=$newsElement['DETAIL_PAGE_URL']?>">Подробности</a>
                <?endif;?>
            </div>
        </div>
    <?endforeach;?>
</div>

