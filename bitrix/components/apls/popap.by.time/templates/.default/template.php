<?if($arParams['FILE_PATH'] != ""):?>
<?$randId = rand(1000,99999999);?>
<script>
    $( document ).ready(function() {
        var popapByTime<?=$randId?> = new BX.PopupWindow(
            "<?=$arResult['ALIAS']?>",
            null,
            {
                content: BX( 'ajax-add-answer-<?=$arResult['ALIAS']?>'),
                closeIcon: {right: "20px", top: "10px" },
                titleBar: {
                    <? if($arResult['TITLE_TEXT'] != ""):?>
                    content: BX.create(
                        "span",
                        {
                            html: '<h2><?=$arResult['TITLE_TEXT']?></h2>',
                            'props': {
                                'className': 'access-title-bar'
                            }
                        }
                    )
                    <? endif; ?>
                },
                zIndex: 9999,
                <?if($arResult['OVERLAY'] == "Y"):?>
                overlay : true,
                <?else:?>
                overlay : false,
                <?endif;?>
                <?if($arResult['AUTO_HIDE'] == "Y"):?>
                autoHide : true,
                <?else:?>
                autoHide : false,
                <?endif;?>
                offsetTop: 1,
                offsetLeft: 0,
                lightShadow : true,
                closeByEsc : true,
                draggable: {
                    restrict: false
                },
                buttons: [
                    <?if($arResult['ACCEPT_BUTTON'] == "Y"):?>
                        new BX.PopupWindowButton({
                            text: "<?=$arResult['ACCEPT_BUTTON_TEXT']?>" ,
                            className: "popup-window-button-accept" ,
                            events: {
                                click: function(){
                                    <?if($arResult['ACCEPT_BUTTON_JS'] != ""):?>
                                        eval("<?=$arResult['ACCEPT_BUTTON_JS']?>");
                                    <?endif;?>
                                    <?if($arResult['CLOSE_AFTER_ACCEPT'] == "Y"):?>
                                        this.popupWindow.close();
                                    <?endif;?>
                                }
                            }
                        }),
                    <?endif;?>
                    <?if($arResult['CLOSE_BUTTON'] == "Y"):?>
                        new BX.PopupWindowButton({
                            text: "Закрыть" ,
                            className: "webform-button-link-cancel" ,
                            events: {
                                click: function(){
                                    this.popupWindow.close();
                                }
                            }
                        })
                    <?endif;?>
                ],
                events: {
                    onAfterPopupShow: function()
                    {
                        <?if($arResult['OVERFLOW_HIDDEN'] == "Y"):?>
                        $("html,body").css("overflow","hidden");
                        <?endif;?>
                    },
                    onPopupClose: function () {
                        <?if($arResult['OVERFLOW_HIDDEN'] == "Y"):?>
                        $("html,body").css("overflow","auto");
                        <?endif;?>
                    }
                }
            }
        );

        <?if($arResult['OLD_CORE'] == "Y"):?>
            BX.ajax.insertToNode('<?=$arResult['FILE_PATH']?>', BX('ajax-add-answer-<?=$arResult['ALIAS']?>'));
        <?else:?>
            BX.ajax.insertToNode('<?=$arResult['FILE_PATH']?>', BX('popup-window-content-<?=$arResult['ALIAS']?>'));
        <?endif;?>

        function startPopapByTime() {
            popapByTime<?=$randId?>.show();
        }

        <? if($arResult['START_TIME'] > 0 && $arResult['START_TIME'] != ""):?>
            setTimeout(startPopapByTime, <?=$arResult['START_TIME']?>);
        <? else:?>
            popapByTime<?=$randId?>.show();
        <? endif;?>
    });
</script>
<?endif;?>