<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Заказ сформирован");
?>

<?
if(CModule::IncludeModule("sale"))
{
    $arOrder = CSaleOrder::GetByID($_GET["ORDER_ID"]);
}

?>
<style>
    .breadcrumb-share {
        display: none;
    }
    .left-column {
        display: none;
    }
</style>
<p>Ваш заказ №<?=$arOrder["ID"]?> от <?=$arOrder["DATE_INSERT"]?> успешно создан.</p>

<p>Для того чтобы в будущем следить за выполнением своих заказов <a href="/personal/private/">авторизуйтесь</a> на сайте.</p>

<?
if ($arOrder["PAY_SYSTEM_ID"] == 10) {
    ?><p>Вы можете оплатить свой заказ по <a target="_blank" href="/personal/order/payment/?ORDER_ID=<?=$arOrder["ID"]?>&PAYMENT_ID=<?=$arOrder["ID"]?>/1">ссылке</a> после авторизации.</p><?
}
?>

<?
$dbBasket = CSaleBasket::GetList(Array("ID"=>"ASC"), Array("ORDER_ID"=>$arOrder["ID"]));
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
        'transactionTotal':'<?=$arOrder["PRICE"]?>',
        <? if (isset($arResult["PRICE_DELIVERY"]) || $arResult["PRICE_DELIVERY"] != '' || $arResult["PRICE_DELIVERY"] != '0'):?>
        'transactionShipping':'<?=$arResult["PRICE_DELIVERY"]?>',
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
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
