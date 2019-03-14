<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="APLSstaffWrapper">
    <? foreach ($arResult['staffes'] as $staffesData): ?>
        <div class="APLSStaffBlock">
            <div class="APLSStaffBlockFIO"><?=$staffesData['UF_NAME']?></div>
            <div class="APLSStaffBlockPosition"><?=$staffesData['UF_POSITION'] ?></div>
            <div class="APLSStaffBlockContants">
                <div class="APLSStaffBlockContantsBlock"><a href="mailto:<?=$staffesData['UF_EMAIL']?>"><?=$staffesData['UF_EMAIL']?></a></div>
                <div class="APLSStaffBlockContantsBlock"><?=$staffesData['UF_PHONE_NUMBER_1']?></div>
                <div class="APLSStaffBlockContantsBlock"><?=$staffesData['UF_PHONE_NUMBER_2']?></div>
            </div>
            <div class="APLSStaffBlockInfo"><?=$staffesData['UF_INFO']?></div>
        </div>
    <? endforeach; ?>
</div>
<?
?>
