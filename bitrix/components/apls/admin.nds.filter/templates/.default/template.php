<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$this->addExternalJs("//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js");
$this->addExternalCss("http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css");

//var_dump($arResult);
?>
<div class="mainWrapper" templateFolder="<?=$templateFolder?>">
    <div class="headerBlock">
        <div class="headerBlock_text">Ставка</div>
        <div class="headerBlock_select">
            <select class="nds_list">
                <?foreach ($arResult as $key=>$value):?>
                    <?if($key !== "НДС 20%"):?>
                        <option value="<?=$value?>"><?=$key?></option>
                    <?endif;?>
                <?endforeach;?>
            </select>
        </div>
        <div class="headerBlock_btn">Выбрать</div>
    </div>
    <div class="resultWrapper">

    </div>
</div>
