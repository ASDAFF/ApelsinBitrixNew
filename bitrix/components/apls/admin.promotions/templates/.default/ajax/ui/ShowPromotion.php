<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_Tabs/APLS_Tabs.php";
include_once $_SERVER["DOCUMENT_ROOT"] . $_REQUEST['componentFolder'] ."classes/AdminPromotions_Revision.php";

function getDateTimeString($dateTime) {
    if($dateTime instanceof \Bitrix\Main\Type\DateTime) {
        return $dateTime->toString();
    }
    return "";
}

$promotion = new PromotionModel($_REQUEST['promotionId']);
$currentRevisionId = $promotion->getCurrentRevisionId();
if($_REQUEST['revisionId'] && $_REQUEST['revisionId']!=="") {
    $openRevisionId = $_REQUEST['revisionId'];
} else {
    $openRevisionId = $currentRevisionId;
}
$disableRevisionIdArr = array();

$whereObj = MySQLWhereElementString::getBinaryOperationString("promotion",MySQLWhereElementString::OPERATOR_B_EQUAL, $_REQUEST['promotionId']);
$orderByObj = new MySQLOrderByString();
$orderByObj->add('apply_from',MySQLOrderByString::DESC);
$revisions = PromotionRevisionModel::getElementList($whereObj,null,null,$orderByObj);

$tabs = new APLS_Tabs();
foreach ($revisions as $revision) {
    if($revision instanceof PromotionRevisionModel) {
        $applyFrom = $revision->getFieldValue('apply_from');
        if($applyFrom instanceof \Bitrix\Main\Type\DateTime) {
            $applyFrom = "<span class='date'>".$applyFrom->format("d.m.y")."</span> - (<span class='time'>".$applyFrom->format("H:i")."</span>)";
        }
        $revisionUi = new AdminPromotions_Revision($revision->getId());
        $content = $revisionUi->show();
        $tabs->addTab($applyFrom,$content,"aplsOpenTheInnerTab",$revision->getId());
        if($revision->getFieldValue('disable') > 0) {
            $disableRevisionIdArr[] = $revision->getId();
        }
    }
}
?>


<div class="promotion" promotionId="<?=$promotion->getId()?>">
    <div class="title"><?=$promotion->getFieldValue('title')?></div>

    <div class="promotion-button-panel">
        <div class="Button Small Green add" promotionId="<?=$promotion->getId()?>">Добавить ревизию</div>
        <div class="Button Small Yellow rename" promotionId="<?=$promotion->getId()?>">Переименовать акцию</div>
        <div class="Button Small Red del" promotionId="<?=$promotion->getId()?>">Удалить акцию</div>
    </div>

    <?if(count($revisions) > 0):?>
        <div class="revisions RevisionsList"><?=$tabs->getTabsHtml(APLS_Tabs::TABS_ORIENTATION_LEFT_START)?></div>
    <?else:?>
        <div class="revisions">Ревизий не найдено</div>
    <?endif;?>
</div>

<?if($currentRevisionId != ""):?>
    <script>
        $(".RevisionsList [tabkey='<?=$currentRevisionId?>']").addClass("current");
//        var tabId = $(".RevisionsList .apls-tab-name.current").attr('tabId');
//        var tabsWrapperId = $(".RevisionsList .apls-tab-name.current").attr('tabsWrapperId');
//        var tabFunction = $(".RevisionsList .apls-tab-name.current").attr('tabFunction');
//        aplsTabsOpenTab(tabId,tabsWrapperId,tabFunction);
    </script>
<?endif;?>
<?if($openRevisionId != ""):?>
    <script>
        var tabId = $(".RevisionsList [tabkey='<?=$openRevisionId?>']").attr('tabId');
        var tabsWrapperId = $(".RevisionsList [tabkey='<?=$openRevisionId?>']").attr('tabsWrapperId');
        var tabFunction = $(".RevisionsList [tabkey='<?=$openRevisionId?>']").attr('tabFunction');
        aplsTabsOpenTab(tabId,tabsWrapperId,tabFunction);
    </script>
<?endif;?>
<?if(!empty($disableRevisionIdArr)):?>
    <script>
        <?foreach ($disableRevisionIdArr as $revisionId): ?>
            $(".RevisionsList [tabkey='<?=$revisionId?>']").addClass("disable");
        <?endforeach;?>
    </script>
<?endif;?>