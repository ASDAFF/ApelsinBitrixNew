<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $arSetting;

if (strlen($arResult["ERROR_MESSAGE"]) <= 0):?>
    <div class="cart-items" style="margin:0px 0px 10px 0px;">
        <div class="equipment-order detail">
            <div class="thead">
                <div class="cart-item-number-date"><?= GetMessage("SPOD_ORDER_NUMBER_DATE") ?></div>
                <div class="cart-item-status"><?= GetMessage("SPOD_ORDER_STATUS") ?></div>
                <div class="cart-item-payment"><?= GetMessage("SPOD_ORDER_PAYMENT") ?></div>
                <div class="cart-item-payed"><?= GetMessage("SPOD_ORDER_PAYED") ?></div>
                <div class="cart-item-summa"><?= GetMessage("SPOD_ORDER_SUMMA") ?></div>
            </div>
            <div class="tbody">
                <div class="tr">
                    <div class="tr_into">
                        <div class="cart-item-number-date">
                            <span class="cart-item-number"><?= $arResult["ACCOUNT_NUMBER"] ?></span>
                            <?= $arResult["DATE_INSERT_FORMATED"]; ?>
                        </div>
                        <div class="cart-item-status">
                            <? if ($arResult["CANCELED"] == "N"):?>
                                <span class="item-status-<?= toLower($arResult["STATUS"]["ID"]) ?>">
									<?= $arResult["STATUS"]["NAME"]; ?>
								</span>
                            <? elseif ($arResult["CANCELED"] == "Y"):?>
                                <span class="item-status-d">
									<?= GetMessage("SPOD_ORDER_DELETE"); ?>
								</span>
                            <? endif; ?>
                        </div>
                        <div class="cart-item-payment">
                            <? if (IntVal($arResult["PAY_SYSTEM_ID"]) > 0):
                                echo $arResult["PAY_SYSTEM"]["NAME"];
                                if ($arResult["CAN_REPAY"] == "Y"):
                                    /* APLS - Кнопка оплаты активна только при определенном статусе */
//                                    if($arResult["STATUS_ID"]=="A" && $arResult["CAN_REPAY"]=="Y"):
                                    ?>
                                    <!--                                        <br />-->
                                    <!--                                        <a href="--><?//=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]
                                    ?><!--&GOTOPAY=yes" target="_blank">--><?//=GetMessage("SALE_REPEAT_PAY")
                                    ?><!--</a>-->
                                    <!--                                    --><?//endif;
                                    /* APLS - Кнопка оплаты активна только при определенном статусе - END */
                                    include_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/catalog/APLS_OrderCheck.php";
                                    if($arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y" && $arResult["STATUS_ID"]!="F" && ($arResult["STATUS_ID"]=="A" || APLS_OrderCheck::orderItemsPayPermission($arResult["BASKET"]))):?>
                                        <br/>
                                        <a href="<?= $arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"] ?>"
                                           target="_blank"><?= GetMessage("SALE_REPEAT_PAY") ?></a>
                                    <?endif;
                                endif;
                            else:
                                echo GetMessage("SPOD_NONE");
                            endif; ?>
                        </div>
                        <div class="cart-item-payed">
                            <? if ($arResult["PAYED"] == "Y"):
                                echo "<span class='item-payed-yes'>" . GetMessage("SALE_YES") . "</span>";
                            else:
                                echo GetMessage("SALE_NO");
                            endif; ?>
                        </div>
                        <div class="cart-item-summa">
							<span class="sum">
								<?= $arResult["PRICE_FORMATED"]; ?>
							</span>
                            <? if ($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
                                <span class="reference-sum">
									<?= CCurrencyLang::CurrencyFormat($arResult["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arResult["CURRENCY"], true); ?>
								</span>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cart-items basket">
        <div class="equipment-order basket">
            <div class="thead">
                <div class="cart-item-name"><?= GetMessage("SPOD_ORDER_NAME") ?></div>
                <div class="cart-item-price"><?= GetMessage("SPOD_ORDER_PRICE") ?></div>
                <div class="cart-item-quantity"><?= GetMessage("SPOD_ORDER_QUANTITY") ?></div>
                <div class="cart-item-summa"><?= GetMessage("SPOD_ORDER_SUMMA") ?></div>
            </div>
            <div class="tbody">
                <? $i = 1;
                foreach ($arResult["BASKET"] as $arBasketItems):?>
                    <div class="tr">
                        <div class="tr_into">
                            <div class="cart-item-number"><?= $i ?></div>
                            <div class="cart-item-image">
                                <?
                                if (is_array($arBasketItems["DETAIL_PICTURE"])):?>
                                    <img src="<?= $arBasketItems['DETAIL_PICTURE']['src'] ?>"
                                         width="<?= $arBasketItems['DETAIL_PICTURE']['width'] ?>"
                                         height="<?= $arBasketItems['DETAIL_PICTURE']['height'] ?>"/>
                                <? else:?>
                                    <img src="<?= SITE_TEMPLATE_PATH ?>/images/no-photo.jpg" width="30" height="30"/>
                                <?endif ?>
                            </div>
                            <div class="cart-item-name">
                                <?
                                if (strlen($arBasketItems["DETAIL_PAGE_URL"]) > 0): ?>
                                <a href="<?= $arBasketItems["DETAIL_PAGE_URL"] ?>">
                                    <?
                                    endif;
                                    echo $arBasketItems["NAME"];
                                    if (strlen($arBasketItems["DETAIL_PAGE_URL"]) > 0): ?>
                                </a>
                            <?
                            endif;
                            if (!empty($arBasketItems["PROPS"])) { ?>
                                <div class="item-props">
                                    <?
                                    foreach ($arBasketItems["PROPS"] as $val) {
                                        echo "<span style='display:block;'>" . $val["NAME"] . ": " . $val["VALUE"] . "</span>";
                                    } ?>
                                    <div class="clr"></div>
                                </div>
                            <?
                            } ?>
                            </div>
                            <div class="cart-item-price">
                                <div class="price">
                                    <?= $arBasketItems["PRICE_FORMATED"] ?>
                                </div>
                                <?
                                if ($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
                                    <span class="reference-price">
										<?= CCurrencyLang::CurrencyFormat($arBasketItems["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arBasketItems["CURRENCY"], true); ?>
									</span>
                                <?endif; ?>
                            </div>
                            <div class="cart-item-quantity">
                                <?= $arBasketItems["QUANTITY"];
                                if (!empty($arBasketItems["MEASURE_TEXT"])):
                                    echo " " . $arBasketItems["MEASURE_TEXT"];
                                endif; ?>
                            </div>
                            <div class="cart-item-summa">
								<span class="sum">
									<?= CCurrencyLang::CurrencyFormat($arBasketItems["PRICE"] * $arBasketItems["QUANTITY"], $arBasketItems["CURRENCY"], true); ?>
								</span>
                                <?
                                if ($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
                                    <span class="reference-sum">
										<?= CCurrencyLang::CurrencyFormat($arBasketItems["PRICE"] * $arBasketItems["QUANTITY"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arBasketItems["CURRENCY"], true); ?>
									</span>
                                <?endif; ?>
                            </div>
                        </div>
                    </div>
                    <?
                    $i++;
                endforeach;
                if (IntVal($arResult["DELIVERY_ID"]) > 0):?>
                    <div class="tr">
                        <div class="tr_into">
                            <div class="cart-itogo"><?= $arResult["DELIVERY"]["NAME"] ?></div>
                            <div class="cart-allsum">
								<span class="allsum">
									<?= $arResult["PRICE_DELIVERY_FORMATED"] ?>
								</span>
                                <? if ($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
                                    <span class="reference-allsum">
										<?= CCurrencyLang::CurrencyFormat($arResult["PRICE_DELIVERY"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arResult["CURRENCY"], true); ?>
									</span>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
            </div>
            <div class="myorders_itog<?= ($arSetting['REFERENCE_PRICE']['VALUE'] == 'Y' && !empty($arSetting['REFERENCE_PRICE_COEF']['VALUE']) ? ' reference' : ''); ?>">
                <div class="cart-itogo"><?= GetMessage("SPOD_ORDER_SUM_IT") ?></div>
                <div class="cart-allsum">
					<span class="allsum">
						<?= $arResult["PRICE_FORMATED"] ?>
					</span>
                    <? if ($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
                        <span class="reference-allsum">
							<?= CCurrencyLang::CurrencyFormat($arResult["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arResult["CURRENCY"], true); ?>
						</span>
                    <? endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?
    /*Получение склада самовывоза (при наличии) и пересортировка массива*/
    if ($arResult["DELIVERY_ID"] == '19') {
        if (CModule::IncludeModule('sale')) {
            \Bitrix\Main\Loader::IncludeModule("catalog");
            $rsShipment = \Bitrix\Sale\Internals\ShipmentTable::getList(array(
                'filter' => array('ORDER_ID' => $arResult["ID"]),
            ));
            while ($arShipment = $rsShipment->fetch()) {
                $rsExtraService = \Bitrix\Sale\Internals\ShipmentExtraServiceTable::getList(array('filter' => array(
                    'SHIPMENT_ID' => $arShipment['ID'],
                    'EXTRA_SERVICE_ID' => '14', // ID дополнительного сервиса, можно посмотреть в таблице b_sale_order_delivery_es
                )));
                while ($arExtraService = $rsExtraService->fetch()) {
                    $storeId = $arExtraService['VALUE'];
                }
            }
            $arStore = \Bitrix\Catalog\StoreTable::getRow([
                'select' => ['TITLE'],
                'filter' => [
                    'ID' => $storeId,
                ]
            ]);
        }
    }
    $orderProperties = [];


    if ($arResult["DELIVERY_ID"] == '19') {
        foreach ($arResult["ORDER_PROPS"] as $val) {
            if ($val["ID"] != "31" && $val["ID"] != "7" && $val["ID"] != "20" && $val["ID"] != "21") {
                $orderProperties[$val["NAME"]] = $val["VALUE"];
            }
        }
        $orderProperties["Адрес самовывоза"] = $arStore["TITLE"];
    } elseif ($arResult["DELIVERY_ID"] == '20') {
        foreach ($arResult["ORDER_PROPS"] as $val) {
            if ($val["ID"] != "31") {
                $orderProperties[$val["NAME"]] = $val["VALUE"];
            }
        }
    }
    ?>
    <table class="order-recipient">
        <? if (!empty($orderProperties)) {
            foreach ($orderProperties as $key=>$val) { ?>
                <tr>
                    <td class="field-name"><?
                        echo $key ?>:
                    </td>
                    <td class="field-value">
                        <?=$val
                        ?>
                    </td>
                </tr>
            <?
            }
        } ?>
        <? if (strlen($arResult["USER_DESCRIPTION"]) > 0):?>
            <tr>
                <td class="field-name"><?= GetMessage("P_ORDER_USER_COMMENT") ?></td>
                <td class="field-value"><?= $arResult["USER_DESCRIPTION"] ?></td>
            </tr>
        <? endif; ?>
    </table>
    <? if ($arResult["STATUS_ID"] == "J"):?>
        <h2>Ваш заказ</h2>
        <iframe src="//my.gdemoi.ru/pro/applications/delivery/?key=b1a6544b6ba37c0d283361e60922176e&external_id=<?= $arResult["ACCOUNT_NUMBER"] ?>&performer_type=employee&display_fields=period&panel_align=br&panel_scale=medium&map=roadmap"
                width="100%" height="400"></iframe>
    <? endif; ?>
    <div class="order-item-actions">
        <a class="btn_buy apuo order_repeat"
           href="<?= $arResult['URL_TO_LIST'] ?>?COPY_ORDER=Y&ID=<?= $arResult['ACCOUNT_NUMBER'] ?>"
           title="<?= GetMessage('SALE_REPEAT_ORDER') ?>"><i
                    class="fa fa-repeat"></i><span><?= GetMessage("SALE_REPEAT_ORDER") ?></span></a>
        <? if ($arResult["CAN_CANCEL"] == "Y"):?>
            <a class="btn_buy apuo order_delete" href="<?= $arResult["URL_TO_CANCEL"] ?>"
               title="<?= GetMessage('SALE_CANCEL_ORDER') ?>"><i
                        class="fa fa-times"></i><span><?= GetMessage("SALE_CANCEL_ORDER") ?></span></a>
        <? endif; ?>
        <a class="btn_buy apuo order_print" href="#" title="<?= GetMessage("SALE_PRINT_ORDER") ?>" onclick="print();"><i
                    class="fa fa-print"></i><span><?= GetMessage("SALE_PRINT_ORDER") ?></span></a>
        <div class="clr"></div>
    </div>
<? else:
    echo ShowError($arResult["ERROR_MESSAGE"]);
endif; ?>