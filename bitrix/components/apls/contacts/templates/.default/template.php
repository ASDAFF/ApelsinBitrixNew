<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include_once($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/APLSContactsDateTimeTable.php");
?>
<div
        id="APLS_contacts_wrapper"
        class="APLS_contacts"
        regionsScriptFile="<?=$templateFolder?>/ajax.php"
        shopScriptFile="<?=$templateFolder?>/ajax.php"
        HIGHLOAD_SHOPS_ID="<?=$arParams['HIGHLOAD_SHOPS_ID']?>"
        HIGHLOAD_REGION_ID="<?=$arParams['HIGHLOAD_REGION_ID']?>"
        TYPE_ID="<?=$arResult["TYPE"]?>"
>
    <div class="APLS_contacts_regions">
        <?
        foreach ($arResult['region'] as $regionData):
            ?><div class="APLS_contacts_regions_block" regionID = "<?=$regionData["ID"]?>"><?=$regionData["UF_REGION"]?></div><?
        endforeach;?>
    </div>
    <div class="APLS_contacts_shops <?=$arResult["CSS"]?>">
        <div class="APLS_contacts_shops_list">
            <?
            $currentTime = new DateTime(date("G:i"));
            $thisDay = date(D);
            foreach ($arResult["shops"] as $rowData):
                $blockID = "APLS_contacts_shops_block_" . $rowData["ID"];
                ?>
                <div class="APLS_contacts_shops_block" regionID="<?=$rowData["UF_REGION"]?>">
                    <div class="APLS_contacts_shops_address"
                         blockID="<?= $blockID ?>"
                         shopID="<?=$rowData["ID"]?>"
                    >
                        <?= $rowData["UF_SHORT_ADDRESS"] ?>
                    </div>
                    <div class="APLS_contacts_buildings" id="<?= $blockID ?>">
                        <div class="ContactData">
                            <div class="AdressBlock"><?= $rowData["UF_LONG_ADDRESS"] ?></div>
                            <div class="AdressBlock"><a href="#APLS_map" class="GoToMapButton">Посмотреть на карте</a>
                            </div>
                            <div class="AdressBlock">
                                <div><i class="fa fa-envelope-o"></i> <?= $rowData["UF_EMAIL"] ?></div>
                                <div><i class="fa fa-phone"></i> <?= $rowData["UF_PHONE_NUMBER_1"] ?></div>
                                <? if ($rowData["UF_PHONE_NUMBER_2"] != ""): ?>
                                    <div><i class="fa fa-phone"></i> <?= $rowData["UF_PHONE_NUMBER_2"] ?></div><?
                                endif; ?>
                            </div>
                        </div>
                        <?
                        if ($currentTime >= new DateTime(date($rowData["UF_" . strtoupper($thisDay) . "_S"])) && $currentTime <= new DateTime(date($rowData["UF_" . strtoupper($thisDay) . "_E"]))):?>
                            <div class="APLS_contacts_buildings_time_Y"><i class="fa fa-clock-o"></i>Сейчас открыто
                            </div>
                        <? else:
                            ?>
                            <div class="APLS_contacts_buildings_time_N"><i class="fa fa-clock-o"></i>Закрыто
                            </div>
                        <? endif; ?>
                        <div class="ContactData">
                            <?
                            $timeTable = new APLSContactsDateTimeTable($rowData['TimeTable']);
                            $timeTable->get();
                            ?>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
        <div class="APLS_map"></div>
    </div>
</div>