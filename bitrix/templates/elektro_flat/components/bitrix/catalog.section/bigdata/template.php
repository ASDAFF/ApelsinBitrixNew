<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$id = $this->randString();
$obName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $this->GetEditAreaId($id));
$containerName = "container-".$id;?>

<div class="bigdata-items" data-entity="parent-container">
	<?if($arResult["ORIGINAL_PARAMETERS"]["BIG_DATA_TITLE"] == "Y") {?>
		<div class="h3" data-entity="header" data-showed="false" style="display: none; opacity: 0;"><?=GetMessage("CT_BCS_BIGDATA_TITLE")?></div>
	<?}?>
	<div data-entity="<?=$containerName?>">
		<?if(!empty($arResult["ITEMS"]) && !empty($arResult["ITEM_ROWS"])) {
			$areaIds = array();
			foreach($arResult["ITEMS"] as $item) {
				$uniqueId = $item["ID"]."_".md5($this->randString().$component->getAction());
				$areaIds[$item["ID"]] = $this->GetEditAreaId($uniqueId);
				$this->AddEditAction($uniqueId, $item["EDIT_LINK"], $elementEdit);
				$this->AddDeleteAction($uniqueId, $item["DELETE_LINK"], $elementDelete, $elementDeleteParams);
			}?>
			<!-- items-container -->
			<?foreach($arResult["ITEM_ROWS"] as $rowData) {
				$rowItems = array_splice($arResult["ITEMS"], 0, $rowData["COUNT"]);?>		
				<div class="catalog-item-cards" data-entity="items-row">
					<?foreach($rowItems as $item) {
						$APPLICATION->IncludeComponent("bitrix:catalog.item", "bigdata",
							array(
								"RESULT" => array(
									"ITEM" => $item,
									"AREA_ID" => $areaIds[$item["ID"]],
									"TYPE" => $rowData["TYPE"]								
								),
								"PARAMS" => $arResult["ORIGINAL_PARAMETERS"] + array("SETTING" => $arResult["SETTING"])
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);
					}?>
				</div>
			<?}
			unset($rowItems);?>	
			<!-- items-container -->
		<?} else {
			// load css for bigData/deferred load
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.item", "bigdata",
				array(),
				$component,
				array("HIDE_ICONS" => "Y")
			);
		}?>
	</div>
</div>

<?$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, "catalog.section");
$signedParams = $signer->sign(base64_encode(serialize($arResult["ORIGINAL_PARAMETERS"])), "catalog.section");?>

<script type="text/javascript">
	BX.message({			
		BIGDATA_ELEMENT_FROM: "<?=GetMessageJS('CT_BCS_BIGDATA_ELEMENT_FROM')?>",
		BIGDATA_ADDITEMINCART_ADDED: "<?=GetMessageJS('CT_BCS_BIGDATA_ELEMENT_ADDED')?>",
		BIGDATA_POPUP_WINDOW_TITLE: "<?=GetMessageJS('CT_BCS_BIGDATA_ELEMENT_ADDITEMINCART_TITLE')?>",			
		BIGDATA_POPUP_WINDOW_BTN_CLOSE: "<?=GetMessageJS('CT_BCS_BIGDATA_ELEMENT_ADDITEMINCART_BTN_CLOSE')?>",
		BIGDATA_POPUP_WINDOW_BTN_ORDER: "<?=GetMessageJS('CT_BCS_BIGDATA_ELEMENT_ADDITEMINCART_BTN_ORDER')?>",
		BIGDATA_SITE_DIR: "<?=SITE_DIR?>",
		BIGDATA_POPUP_WINDOW_MORE_OPTIONS: "<?=GetMessageJS('CT_BCS_BIGDATA_ELEMENT_MORE_OPTIONS')?>",			
		BIGDATA_COMPONENT_TEMPLATE: "<?=$this->GetFolder();?>",
		BIGDATA_OFFERS_VIEW: "<?=$arResult['SETTING']['OFFERS_VIEW']?>",
		BIGDATA_COMPONENT_PARAMS: "<?=CUtil::JSEscape($signedParams)?>"
	});	
	var <?=$obName?> = new JCCatalogBigdataSectionComponent({
		siteId: "<?=CUtil::JSEscape(SITE_ID)?>",
		componentPath: "<?=CUtil::JSEscape($componentPath)?>",	
		bigData: <?=CUtil::PhpToJSObject($arResult["BIG_DATA"])?>,		
		template: "<?=CUtil::JSEscape($signedTemplate)?>",
		ajaxId: "<?=CUtil::JSEscape($arParams['AJAX_ID'])?>",			
		container: "<?=$containerName?>"
	});	
</script>