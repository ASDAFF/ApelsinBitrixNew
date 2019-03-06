<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
Loc::loadMessages(__FILE__);?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />	
	<link rel="apple-touch-icon" sizes="57x57" href="<?=SITE_TEMPLATE_PATH?>/images/apelsin_touch_icon_57.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?=SITE_TEMPLATE_PATH?>/images/apelsin_touch_icon_114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH?>/images/apelsin_touch_icon_72.png" />
    <script src="//api-maps.yandex.ru/2.1/?lang=ru-RU" type="text/javascript"></script>
	<?Asset::getInstance()->addString("<meta name='viewport' content='width=device-width, initial-scale=1.0' />");?>
	<title><?$APPLICATION->ShowTitle()?></title>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K3KRF2S"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
	<?Asset::getInstance()->addCss("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");	
	Asset::getInstance()->addCss("https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic-ext");
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/colors.css");	
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/js/anythingslider/slider.css");
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/js/custom-forms/custom-forms.css");
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/js/fancybox/jquery.fancybox-1.3.1.css");	
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/js/spectrum/spectrum.css");	
	CJSCore::Init(array("jquery", "popup"));	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.cookie.js");	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/moremenu.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.inputmask.bundle.min.js");		
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/anythingslider/jquery.easing.1.2.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/anythingslider/jquery.anythingslider.min.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-forms/jquery.custom-forms.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/fancybox/jquery.fancybox-1.3.1.pack.js");	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/spectrum/spectrum.js");	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/countUp.min.js");	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/countdown/jquery.plugin.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/countdown/jquery.countdown.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/apelsin.main.js");
	Asset::getInstance()->addString("
		<script type='text/javascript'>
			$(function() {
				$.countdown.regionalOptions['ru'] = {
					labels: ['".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_DAY")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_HOUR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
					labels1: ['".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_DAY")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_HOUR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
					labels2: ['".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_DAY")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_HOUR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
					compactLabels: ['".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_DAY")."'],
					compactLabels1: ['".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS1_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_DAY")."'],
					whichLabels: function(amount) {
						var units = amount % 10;
						var tens = Math.floor((amount % 100) / 10);
						return (amount == 1 ? 1 : (units >= 2 && units <= 4 && tens != 1 ? 2 : (units == 1 && tens != 1 ? 1 : 0)));
					},
					digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
					timeSeparator: ':',
					isRTL: false
				};
				$.countdown.setDefaults($.countdown.regionalOptions['ru']);
			});
		</script>
	");	
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/main.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/script.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.printPage.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/noEnter.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/APLS_ContentToColumns.js");
	$APPLICATION->ShowHead();?>
    <?if(CUser::IsAuthorized()):?>
        <style>
            .TextIsNotAuthorized {
                display: none;
            }
        </style>
    <?endif;?>
</head>
<body>
	<?global $arSetting;?>
	<?$arSetting = $APPLICATION->IncludeComponent("altop:settings", "", array(), false, array("HIDE_ICONS" => "Y"));?>
	<div class="bx-panel<?=($arSetting['CART_LOCATION']['VALUE'] == 'TOP') ? ' clvt' : ''?>">
		<?$APPLICATION->ShowPanel();?>
	</div>
	<div class="bx-include-empty">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => ""), false);?>
	</div>
	<div class="body<?=($arSetting['CATALOG_LOCATION']['VALUE'] == 'HEADER') ? ' clvh' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'TOP') ? ' clvt' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'RIGHT') ? ' clvr' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'LEFT') ? ' clvl' : ''?>"<?=($arSetting["SITE_BACKGROUND"]["VALUE"] == "Y" && $arSetting["SITE_BACKGROUND_COLOR"]["VALUE"] ? " style='background-color:".$arSetting["SITE_BACKGROUND_COLOR"]["VALUE"].";'" : "");?>>
		<div class="page-wrapper<?=($arSetting['SITE_BACKGROUND_REPEAT_X']['VALUE'] == 'Y' ? ' bg-repeat-x' : '').($arSetting['SITE_BACKGROUND_REPEAT_Y']['VALUE'] == 'Y' ? ' bg-repeat-y' : '').($arSetting['SITE_BACKGROUND_FIXED']['VALUE'] == 'Y' ? ' bg-fixed' : '');?>"<?=$APPLICATION->ShowProperty("backgroundImage")?>>
			<?if($arSetting["SITE_BACKGROUND"]["VALUE"] == "Y"):?>
				<div class="center outer">
			<?endif;
			if($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
				<div class="top-menu">
					<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/top_menu.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>
				</div>
			<?endif;?>			
			<header>
				<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
					<div class="header_1">
						<div class="logo">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo.php"), false);?>
						</div>
					</div>
					<div class="header_2">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header_search.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>
					<div class="header_3">
						<div class="schedule">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule.php"), false);?>
						</div>
					</div>
					<div class="header_4">
						<div class="contacts">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/geolocation.php"), false, array("HIDE_ICONS" => "Y"));?>
<!--							<a id="callbackAnch" class="btn_buy apuo callback_anch" href="javascript:void(0)"><span class="cont"><i class="fa fa-phone"></i><span class="text">--><?//=Loc::getMessage("ALTOP_CALL_BACK")?><!--</span></span></a>-->
						</div>
					</div>
				</div>
			</header>
			<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
				<div class="top-menu">
					<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/top_menu.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>
				</div>
			<?elseif($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
				<div class="top-catalog">
					<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
						<?$APPLICATION->IncludeComponent("bitrix:menu", $arSetting["CATALOG_VIEW"]["VALUE"] == "FOUR_LEVELS" ? "tree" : "sections",
							array(
								"ROOT_MENU_TYPE" => "left",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "36000000",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "4",
								"CHILD_MENU_TYPE" => "left",
								"USE_EXT" => "Y",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "N",
								"CACHE_SELECTED_ITEMS" => "N"
							),
							false
						);?>
					</div>
				</div>
			<?endif;?>
			<div class="top_panel">
				<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
					<div class="panel_1">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/sections.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>
					<div class="panel_2">
						<?$APPLICATION->IncludeComponent("bitrix:menu", "panel", 
							array(
								"ROOT_MENU_TYPE" => "top",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "36000000",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "3",
								"CHILD_MENU_TYPE" => "topchild",
								"USE_EXT" => "N",
								"ALLOW_MULTI_SELECT" => "N",
								"CACHE_SELECTED_ITEMS" => "N"
							),
							false
						);?>
					</div>
					<div class="panel_3">
						<ul class="contacts-vertical">
							<li>
								<a class="showcontacts" href="javascript:void(0)"><i class="fa fa-map-marker"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel_4">
						<ul class="search-vertical">
							<li>
								<a class="showsearch" href="javascript:void(0)"><i class="fa fa-search"></i></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="content-wrapper">
				<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
					<div class="content">
						<?$inOrderPage = CSite::InDir("/personal/order/make/");
						if(!$inOrderPage):?>
							<div class="left-column">
								<?if($APPLICATION->GetDirProperty("PERSONAL_SECTION")):?>
									<div class="h3"><?=Loc::getMessage("PERSONAL_HEADER");?></div>
									<?$APPLICATION->IncludeComponent("altop:user", ".default",
										array(
											"PATH_TO_PERSONAL" => "/personal/",
											"CACHE_TYPE" => "A",
											"CACHE_TIME" => "36000000"
										),
										false
									);?>
									<?$APPLICATION->IncludeComponent("bitrix:menu", "tree",
										array(
											"ROOT_MENU_TYPE" => "personal",
											"MENU_CACHE_TYPE" => "A",
											"CACHE_SELECTED_ITEMS" => "N",
											"MENU_CACHE_TIME" => "36000000",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "1",
											"CHILD_MENU_TYPE" => "personal",
											"USE_EXT" => "Y",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N",
											"CACHE_SELECTED_ITEMS" => "N"
										),
										false
									);?>
									<?if($USER->IsAuthorized()):?>
										<a class="personal-exit" href="<?=$APPLICATION->GetCurPageParam('logout=yes', array('logout'));?>"><?=Loc::getMessage("PERSONAL_EXIT");?></a>
									<?endif;
								else:
									if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
                                        <div class="menu-header">
                                            <div class="menu_header_block">
                                                <div class="h3"><?=Loc::getMessage("BASE_HEADER");?></div>
                                                <div class="menu-header-swap">
                                                    <i class="fa fa-minus"></i>
                                                    <i class="fa fa-plus"></i>
                                                </div>
                                            </div>
                                        </div>
										<?$APPLICATION->IncludeComponent("bitrix:menu", $arSetting["CATALOG_VIEW"]["VALUE"] == "FOUR_LEVELS" ? "tree" : "sections",
											array(
												"ROOT_MENU_TYPE" => "left",
												"MENU_CACHE_TYPE" => "A",
												"MENU_CACHE_TIME" => "36000000",
												"MENU_CACHE_USE_GROUPS" => "Y",
												"MENU_CACHE_GET_VARS" => array(),
												"MAX_LEVEL" => "4",
												"CHILD_MENU_TYPE" => "left",
												"USE_EXT" => "Y",
												"DELAY" => "N",
												"ALLOW_MULTI_SELECT" => "N",
												"CACHE_SELECTED_ITEMS" => "N"
											),
											false
										);?>									
									<?endif;
								endif;
								if($arSetting["SMART_FILTER_LOCATION"]["VALUE"] == "VERTICAL"):
									$APPLICATION->ShowViewContent("filter_vertical");
								endif;
								if($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/banners_left.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>													
									<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php") {?>
										<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
											array(
												"AREA_FILE_SHOW" => "file",
												"PATH" => SITE_DIR."include/slider_left.php",
												"AREA_FILE_RECURSIVE" => "N",
												"EDIT_MODE" => "html",
											),
											false,
											array("HIDE_ICONS" => "Y")
										);?>
									<?}?>
								<?endif;?>
								<ul class="new_leader_disc">
									<li>
										<a class="new" href="<?=SITE_DIR?>catalog/newproduct/">
											<span class="icon"><?=Loc::getMessage("CR_TITLE_ICON_NEWPRODUCT")?></span>
											<span class="text"><?=Loc::getMessage("CR_TITLE_NEWPRODUCT")?></span>
										</a>
									</li>
									<li>
										<a class="hit" href="<?=SITE_DIR?>catalog/saleleader/">
											<span class="icon"><?=Loc::getMessage("CR_TITLE_ICON_SALELEADER")?></span>
											<span class="text"><?=Loc::getMessage("CR_TITLE_SALELEADER")?></span>
										</a>
									</li>
									<li>
										<a class="discount" href="<?=SITE_DIR?>catalog/discount/">
											<span class="icon"><?=Loc::getMessage("CR_TITLE_ICON_DISCOUNT")?></span>
											<span class="text"><?=Loc::getMessage("CR_TITLE_DISCOUNT")?></span>
										</a>
									</li>
								</ul>							
								<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/banners_left.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>													
									<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php") {?>
										<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
											array(
												"AREA_FILE_SHOW" => "file",
												"PATH" => SITE_DIR."include/slider_left.php",
												"AREA_FILE_RECURSIVE" => "N",
												"EDIT_MODE" => "html",
											),
											false,
											array("HIDE_ICONS" => "Y")
										);?>
									<?}?>
								<?endif;?>
								<div class="vendors">
									<div class="h3"><?=Loc::getMessage("MANUFACTURERS");?></div>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/vendors_left.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								</div>
								<div class="subscribe">
									<div class="h3"><?=Loc::getMessage("SUBSCRIBE");?></div>
									<p><?=Loc::getMessage("SUBSCRIBE_TEXT");?></p>
									<?$APPLICATION->IncludeComponent("bitrix:subscribe.form", "left", 
										array(
											"USE_PERSONALIZATION" => "Y",	
											"PAGE" => SITE_DIR."personal/mailings/",
											"SHOW_HIDDEN" => "N",
											"CACHE_TYPE" => "A",
											"CACHE_TIME" => "36000000",
											"CACHE_NOTES" => ""
										),
										false
									);?>
								</div>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/news_left.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/reviews_left.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>
							</div>
						<?endif;?>
						<div class="workarea<?=($inOrderPage ? ' workarea-order' : '');?>">
							<?if($APPLICATION->GetCurPage(true)== SITE_DIR."index.php"):
								if(in_array("SLIDER", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/slider.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;
								if(in_array("ADVANTAGES", $arSetting["HOME_PAGE"]["VALUE"])):
									global $arAdvFilter;
									$arAdvFilter = array(
										"!PROPERTY_SHOW_HOME" => false
									);?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/advantages.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;
								if(in_array("PROMOTIONS", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/promotions.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;
								if(in_array("BANNERS", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/banners_main.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;								
								if(in_array("TABS", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<div class="tabs-wrap tabs-main">
										<ul class="tabs">
											<?if(in_array("RECOMMEND", $arSetting["HOME_PAGE"]["VALUE"])):?>
												<li class="tabs__tab recommend">
													<a href="javascript:void(0)"><span><?=Loc::getMessage("CR_TITLE_RECOMMEND")?></span></a>
												</li>
											<?endif;?>
<!--											<li class="tabs__tab new">-->
<!--												<a href="javascript:void(0)"><span>--><?//=Loc::getMessage("CR_TITLE_NEWPRODUCT")?><!--</span></a>-->
<!--											</li>-->
											<li class="tabs__tab hit">
												<a href="javascript:void(0)"><span><?=Loc::getMessage("CR_TITLE_SALELEADER")?></span></a>
											</li>
<!--											<li class="tabs__tab discount">-->
<!--												<a href="javascript:void(0)"><span>--><?//=Loc::getMessage("CR_TITLE_DISCOUNT")?><!--</span></a>-->
<!--											</li>-->
										</ul>
										<?if(in_array("RECOMMEND", $arSetting["HOME_PAGE"]["VALUE"])):?>
											<div class="tabs__box recommend">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
													array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR."include/recommend.php",
														"AREA_FILE_RECURSIVE" => "N",
														"EDIT_MODE" => "html",
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
											</div>
										<?endif;?>
<!--										<div class="tabs__box new">-->
<!--											<div class="catalog-top">-->
<!--												--><?//$APPLICATION->IncludeComponent("bitrix:main.include", "",
//													array(
//														"AREA_FILE_SHOW" => "file",
//														"PATH" => SITE_DIR."include/newproduct.php",
//														"AREA_FILE_RECURSIVE" => "N",
//														"EDIT_MODE" => "html",
//													),
//													false,
//													array("HIDE_ICONS" => "Y")
//												);?>
<!--												<a class="all" href="--><?//=SITE_DIR?><!--catalog/newproduct/">--><?//=Loc::getMessage("CR_TITLE_ALL_NEWPRODUCT");?><!--</a>-->
<!--											</div>-->
<!--										</div>-->
										<div class="tabs__box hit">
											<div class="catalog-top">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
													array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR."include/saleleader.php",
														"AREA_FILE_RECURSIVE" => "N",
														"EDIT_MODE" => "html",
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
												<a class="all" href="<?=SITE_DIR?>catalog/saleleader/"><?=Loc::getMessage("CR_TITLE_ALL_SALELEADER");?></a>
											</div>
										</div>
<!--										<div class="tabs__box discount">-->
<!--											<div class="catalog-top">-->
<!--												--><?//$APPLICATION->IncludeComponent("bitrix:main.include", "",
//													array(
//														"AREA_FILE_SHOW" => "file",
//														"PATH" => SITE_DIR."include/discount.php",
//														"AREA_FILE_RECURSIVE" => "N",
//														"EDIT_MODE" => "html",
//													),
//													false,
//													array("HIDE_ICONS" => "Y")
//												);?>
<!--												<a class="all" href="--><?//=SITE_DIR?><!--catalog/discount/">--><?//=Loc::getMessage("CR_TITLE_ALL_DISCOUNT");?><!--</a>-->
<!--											</div>-->
<!--										</div>-->
									</div>
								<?endif;
							endif;?>
							<div class="body_text" style="<?=($APPLICATION->GetCurPage(true) == SITE_DIR.'index.php') ? 'padding:0px 15px;' : 'padding:0px;';?>">
								<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php"):?>
									<div class="breadcrumb-share">
										<div id="navigation" class="breadcrumb">
											<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", 
												array(
													"START_FROM" => "0",
													"PATH" => "",
													"SITE_ID" => "-"
												),
												false,
												array("HIDE_ICONS" => "Y")
											);?>
										</div>
										<div class="share">											
											<script type="text/javascript" async src="//yastatic.net/share/share.js" charset="utf-8"></script>
											<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,gplus" data-yashareTheme="counter"></div>
										</div>
									</div>
									<h1 id="pagetitle"><?=$APPLICATION->ShowTitle(false);?></h1>
									<?
									$curDir = substr($APPLICATION->GetCurDir(),0, -1);
									$bannerDir = $_SERVER["DOCUMENT_ROOT"] . "/include/banners".$curDir;
									$bannerImage = "";
									if(file_exists($bannerDir.".jpg")) {
									    $bannerImage = "/include/banners".$curDir.".jpg";
									} elseif(file_exists($bannerDir.".png")) {
									    $bannerImage = "/include/banners".$curDir.".png";
									}
									?>
									<?if($bannerImage != ""):?>
                                        <div class="page-banner">
                                            <img src="<?=$bannerImage?>">
                                        </div>
                                    <?endif;?>
								<?endif;?>