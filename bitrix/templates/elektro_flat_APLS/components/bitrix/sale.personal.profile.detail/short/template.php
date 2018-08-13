<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

if(strlen($arResult["ID"]) > 0) {
    $db_sales = CSaleOrderUserProps::GetList(
        array("DATE_UPDATE" => "DESC"),
        array("USER_ID" => $USER->GetID())
    );
    while ($ar_sales = $db_sales->Fetch())
    {
        $ar_profId [$ar_sales['NAME']] = $ar_sales['ID'];
    }
    $profId = array_shift($ar_profId);
    $db_propVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$profId));
    while ($arPropVals = $db_propVals->Fetch())
    {
        $propVals[$arPropVals['USER_PROPS_ID']][$arPropVals['ORDER_PROPS_ID']]['NAME'] = $arPropVals['NAME'];
        $propVals[$arPropVals['USER_PROPS_ID']][$arPropVals['ORDER_PROPS_ID']]['VALUE'] = $arPropVals['VALUE'];
    }
    foreach ($propVals as $props) {
        foreach ($props as $key=>$prop) {
            if ($key == '2' || $key == '3') {
                $resultArray[$key]['NAME'] = $prop['NAME'];
                $resultArray[$key]['VALUE'] = $prop['VALUE'];
            }
        }
    }?>
    <div class="sale-profile-detail-block" USER_PROPS_ID="<?=$profId?>" templateFolder="<?=$templateFolder?>">
        <?foreach ($resultArray as $elKey=>$value):?>
        <div class="sale-profile-detail-form-group sale-profile-detail-form-property-text">
            <label><?=$value['NAME']?></label>
            <div name="<?=$value['NAME']?>" id="<?=$elKey?>" class="sale-profile-detail-form-property">
                <input type="text" maxlength="50" value="<?=$value['VALUE']?>">
            </div>
        </div>
        <?endforeach;?>
    </div>
    <div class="sale-profile-detail-form-btn">
        <button type="submit" class="pers_data_save">Сохранить</button>
    </div>
    <div class="rezultBlock">

    </div>
<?} else {
    ShowError($arResult["ERROR_MESSAGE"]);
}?>