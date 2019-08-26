<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if (strlen($arResult["ERROR_MESSAGE"]) > 0)
    ShowError($arResult["ERROR_MESSAGE"]);

if (count($arResult["STORES"]) > 0):?>
    <? foreach ($arResult["STORES"] as $city => $storeges): ?>
    <div class="cityBlock">
        <div class="cityHeaderBlock">
            <div class="cityHeaderBlock_main">
                <div class="cityHeaderBlock_text"><?= $city ?></div>
                <div class="cityHeaderBlock_icon"><i class="fa fa-plus"></i></div>
            </div>
            <div class="catalog-detail-store header">
                <div class="title">Адрес магазина / телефон</div>
                <div class="schedule">Время работы</div>
                <div class="amount">Количество</div>
            </div>
        </div>
        <? $counter = 1; ?>
        <? foreach ($storeges as $arProperty): ?>
            <? if ($arProperty["AMOUNT"] > 0): ?>
                <?
                if ($counter++ % 2 !== 0) {
                    $class = "first";
                } else {
                    $class = "second";
                }
                ?>
                <div class="catalog-detail-store store_row <?= $class ?>">
                    <div class="title"><?= $arProperty["TITLE"] ?></div>
                    <? if (isset($arProperty["PHONE"])): ?>
                        <br>
                        <div class="phone">тел: <?= $arProperty["PHONE"] ?></div>
                    <? endif; ?>
                    <? if (isset($arProperty["SCHEDULE"])): ?>
                        <div class="schedule"><?= $arProperty["SCHEDULE"] ?></div>
                    <? endif; ?>
                    <div class="amount"><span class="amount_mobile">Количество:&nbsp</span><span class="amount_count"><?= $arProperty["AMOUNT"] ?></span></div>
                </div>
            <? endif; ?>
        <? endforeach; ?>
    </div>
    <? endforeach; ?>
<? endif; ?>