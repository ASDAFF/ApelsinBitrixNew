<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";

use Bitrix\Main\Localization\Loc;

if($arParams["SET_TITLE"] == "Y") {
	$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
}

if(!empty($arResult["ORDER"])) {?>
	<p><?=Loc::getMessage("SOA_ORDER_SUC", array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]));?></p>
	<?if(!empty($arResult["ORDER"]["PAYMENT_ID"])) {?>
<!--		<p>--><?//=Loc::getMessage("SOA_PAYMENT_SUC", array("#PAYMENT_ID#" => $arResult["PAYMENT"][$arResult["ORDER"]["PAYMENT_ID"]]["ACCOUNT_NUMBER"]));?><!--</p>-->
	<?}?>
	<p><?=Loc::getMessage("SOA_ORDER_SUC1", array("#LINK#" => $arParams["PATH_TO_PERSONAL"]))?></p>

	<?if($arResult["ORDER"]["IS_ALLOW_PAY"] === "Y") {
		if(!empty($arResult["PAYMENT"])) {
			foreach($arResult["PAYMENT"] as $payment) {
				if($payment["PAID"] != "Y") {
					if(!empty($arResult["PAY_SYSTEM_LIST"]) && array_key_exists($payment["PAY_SYSTEM_ID"], $arResult["PAY_SYSTEM_LIST"])) {
						$arPaySystem = $arResult["PAY_SYSTEM_LIST"][$payment["PAY_SYSTEM_ID"]];
						if(empty($arPaySystem["ERROR"])) {?>
							<table class="sale_order_full_table">
								<tr>
									<td class="ps_logo">
<!--										<div class="pay_name">--><?//=Loc::getMessage("SOA_PAY")?><!--</div>-->
<!--										--><?//=CFile::ShowImage($arPaySystem["LOGOTIP"], 100, 100, "border=0\" style=\"width:100px\"", "", false);?>
<!--										<div class="paysystem_name">--><?//=$arPaySystem["NAME"]?><!--</div>-->
										<br/>
									</td>
								</tr>
								<tr>
									<td>
										<?if(strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y") {
											$orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
											$paymentAccountNumber = $payment["ACCOUNT_NUMBER"];?>
											<script>
												window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
											</script>
											<?=Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&PAYMENT_ID=".$paymentAccountNumber));
											if(CSalePdf::isPdfAvailable() && $arPaySystem["IS_AFFORD_PDF"]) {?>
												<br/>
												<?=Loc::getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&pdf=1&DOWNLOAD=Y"));
											}
										} else {
											echo $arPaySystem["BUFFERED_OUTPUT"];
										}?>
									</td>
								</tr>
							</table>
						<?} else {
							ShowError(Loc::getMessage("SOA_ORDER_PS_ERROR"));
						}
					} else {
						ShowError(Loc::getMessage("SOA_ORDER_PS_ERROR"));
					}
				}
			}
		}
	} else {
		ShowNote($arParams["MESS_PAY_SYSTEM_PAYABLE_ERROR"], "infotext");
	}
} else {
	ShowError(Loc::getMessage("SOA_ERROR_ORDER")."<br />".Loc::getMessage("SOA_ERROR_ORDER_LOST", array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))."<br />".Loc::getMessage("SOA_ERROR_ORDER_LOST1"));
}?>

<?

$dbBasket = CSaleBasket::GetList(Array("ID"=>"ASC"), Array("ORDER_ID"=>$arResult['ORDER']['ID']));
$resultArray = [];
while ($arItems = $dbBasket->Fetch())
{
    $resultArray[$arItems["ORDER_ID"]][$arItems["PRODUCT_ID"]]["NAME"] = $arItems["NAME"];
    $resultArray[$arItems["ORDER_ID"]][$arItems["PRODUCT_ID"]]["PRICE"] = $arItems["PRICE"];
    $resultArray[$arItems["ORDER_ID"]][$arItems["PRODUCT_ID"]]["QUANTITY"] = $arItems["QUANTITY"];
    $resultArray[$arItems["ORDER_ID"]][$arItems["PRODUCT_ID"]]["ARTICUL"] = getArticulValue($arItems["PRODUCT_ID"]);
    $resultArray[$arItems["ORDER_ID"]][$arItems["PRODUCT_ID"]]["SECTION_NAME"] = getSectionNameBySymbolCode(chunkUrkPath($arItems["DETAIL_PAGE_URL"]));
}
?>
    <script>
        <? foreach ($resultArray as $orderId=>$productsArray):?>
        dataLayer = [{
            'transactionId':'<?=$orderId?>',
            'transactionTotal':'<?=$arResult["PAYMENT"][$arResult["ORDER_ID"]]["SUM"]?>',
            <? if (isset($arResult["ORDER"]["PRICE_DELIVERY"]) || $arResult["ORDER"]["PRICE_DELIVERY"] != ''):?>
            'transactionShipping':'<?=$arResult["ORDER"]["PRICE_DELIVERY"]?>',
            <?endif;?>
            'transactionProducts': [
                <? foreach ($productsArray as $productId=>$product):?>
                {
                    'sku':'<?=$product["ARTICUL"]?>',
                    'name':'<?=$product["NAME"]?>',
                    'category':'<?=$product["SECTION_NAME"]?>',
                    'price':'<?=$product["PRICE"]?>',
                    'quantity':'<?=$product["QUANTITY"]?>'
                },
                <?endforeach;?>
            ]
        }];
        <?endforeach;?>
        console.log(dataLayer);
    </script>
<?
function getArticulValue ($productId) {
    $arProduct = CIBlockElement::GetProperty(APLS_CatalogHelper::getShopIblockId(), $productId,array("sort" => "asc"),Array("CODE"=>"ARTNUMBER"));
    while ($arItems = $arProduct->Fetch()) {
        if (isset($arItems['VALUE'])) {
            $artValue = $arItems['VALUE'];
        }
    }
    return $artValue;
}
function getSectionNameBySymbolCode ($symbolCode) {
    $section = CIBlockSection::GetList(Array("ID"=>"ASC"), Array("CODE"=>$symbolCode));
    while ($arItems = $section->Fetch()) {
        if (isset($arItems["NAME"])) {
            $sectionName = $arItems["NAME"];
        }
    }
    return $sectionName;
}
function chunkUrkPath ($urlPath) {
    $urlArray = explode("/",$urlPath);
    array_pop($urlArray);
    array_pop($urlArray);
    $sectionSymbolCode = array_pop($urlArray);
    return $sectionSymbolCode;
}