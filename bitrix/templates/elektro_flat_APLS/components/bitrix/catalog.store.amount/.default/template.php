<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(strlen($arResult["ERROR_MESSAGE"])>0)
	ShowError($arResult["ERROR_MESSAGE"]);

if(count($arResult["STORES"]) > 0):
    $counter = 1;
    ?>
    <div class="catalog-detail-store header">
        <div class="title">Адрес магазина / телефон</div>
        <div class="schedule">Время работы</div>
        <div class="amount">Количество</div>
    </div>
    <?
	foreach($arResult["STORES"] as $pid => $arProperty):?>
        <?if($arProperty["AMOUNT"] > 0):?>
            <?
            if($counter++%2 !== 0) {
                $class = "first";
            } else {
                $class = "second";
            }
            ?>
            <div class="catalog-detail-store <?=$class?>">
                <div class="title"><?=$arProperty["TITLE"]?></div>
                <?if(isset($arProperty["PHONE"])):?>
                <br><div class="phone">тел: <?=$arProperty["PHONE"]?></div>
                <?endif;?>
                <?if(isset($arProperty["SCHEDULE"])):?>
                <div class="schedule"><?=$arProperty["SCHEDULE"]?></div>
                <?endif;?>
                <div class="amount"><?=$arProperty["AMOUNT"]?></div>
            </div>
        <?endif;?>
	<?endforeach;
endif;?>