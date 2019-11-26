<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description","Особенности транспортировки товаров в интернет-магазине Апельсин.");
$APPLICATION->SetTitle("Особенности товаров");
?>
	<p class="TextP">Уважаемые покупатели, для Вашего удобства все товары имеющие ограничения или особенности в предоставлении услуг отмечены специальными значками:</p>

    <div class="FeaturesProductsWrapper">
        <img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/terms_of_sale.svg">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."include/services/USLOVIYAPRODAZHI.php",
                "AREA_FILE_RECURSIVE" => "N",
                "EDIT_MODE" => "html",
            ),
            false,
            array("HIDE_ICONS" => "Y")
        );?>
        <div class="clear"></div>
    </div>

	<div class="FeaturesProductsWrapper">
		<img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/truck_long.svg">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."include/services/DLINNOMER.php",
                "AREA_FILE_RECURSIVE" => "N",
                "EDIT_MODE" => "html",
            ),
            false,
            array("HIDE_ICONS" => "Y")
        );?>
		<div class="clear"></div>
	</div>

	<div class="FeaturesProductsWrapper">
		<img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/manipulator.svg">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."include/services/MANIPULYATOR.php",
                "AREA_FILE_RECURSIVE" => "N",
                "EDIT_MODE" => "html",
            ),
            false,
            array("HIDE_ICONS" => "Y")
        );?>
		<div class="clear"></div>
	</div>

<!--	<div class="FeaturesProductsWrapper">-->
<!--		<img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/climb.svg">-->
<!--		<div class="FeaturesProductsTextBlock">-->
<!--			<div class="FeaturesProductsName">Спеццена подъема</div>-->
<!--			<div class="FeaturesProductsText">-->
<!--				Стоимость подъема товаров данной категории определена <a href="../../delivery/#listClimb" target="_blank">отдельным списком.</a>-->
<!--			</div>-->
<!--		</div>-->
<!--		<div class="clear"></div>-->
<!--	</div>-->

	<div class="FeaturesProductsWrapper">
		<img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/assembling.svg">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."include/services/USTANOVKA.php",
                "AREA_FILE_RECURSIVE" => "N",
                "EDIT_MODE" => "html",
            ),
            false,
            array("HIDE_ICONS" => "Y")
        );?>
		<div class="clear"></div>
	</div>

	<div class="FeaturesProductsWrapper">
		<img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/color.svg">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."include/services/KOLEROVKA.php",
                "AREA_FILE_RECURSIVE" => "N",
                "EDIT_MODE" => "html",
            ),
            false,
            array("HIDE_ICONS" => "Y")
        );?>
		<div class="clear"></div>
	</div>



	<!--<div class="FeaturesProductsWrapper">-->
	<!--	<img class="FeaturesProductsIconBlock" src="truck_1_5.png">-->
	<!--	<div class="FeaturesProductsTextBlock">-->
	<!--		<div class="FeaturesProductsName">Доставка а/м грузоподъёмностью до 1.5 т</div>-->
	<!--		<div class="FeaturesProductsText">-->
	<!--			Из-за габаритов товара для доставки требуется а/м грузоподъемностью не менее 1.5 т, даже при условии что вес товара незначителен.-->
	<!--		</div>-->
	<!--	</div>-->
	<!--	<div class="clear"></div>-->
	<!--</div>-->

	<!--<div class="FeaturesProductsWrapper">-->
	<!--	<img class="FeaturesProductsIconBlock" src="truck_3_5.png">-->
	<!--	<div class="FeaturesProductsTextBlock">-->
	<!--		<div class="FeaturesProductsName">Доставка а/м грузоподъёмностью до 3.5 т</div>-->
	<!--		<div class="FeaturesProductsText">-->
	<!--			Из-за габаритов товара для доставки требуется а/м грузоподъемностью не менее 3.5 т, даже при условии что вес товара менее 1.5 т.-->
	<!--		</div>-->
	<!--	</div>-->
	<!--	<div class="clear"></div>-->
	<!--</div>-->

	<!--<div class="FeaturesProductsWrapper">-->
	<!--	<img class="FeaturesProductsIconBlock" src="truck_5.png">-->
	<!--	<div class="FeaturesProductsTextBlock">-->
	<!--		<div class="FeaturesProductsName">Доставка а/м грузоподъёмностью до 5 т</div>-->
	<!--		<div class="FeaturesProductsText">-->
	<!--			Из-за габаритов товара для  доставки  требуется а/м грузоподъемностью не менее 5 т, даже при условии что вес товара менее 3.5 т.-->
	<!--		</div>-->
	<!--	</div>-->
	<!--	<div class="clear"></div>-->
	<!--</div>-->

	<!--<div class="FeaturesProductsWrapper">-->
	<!--	<img class="FeaturesProductsIconBlock" src="truck_10.png">-->
	<!--	<div class="FeaturesProductsTextBlock">-->
	<!--		<div class="FeaturesProductsName">Доставка а/м грузоподъёмностью до 10 т</div>-->
	<!--		<div class="FeaturesProductsText">-->
	<!--			Из-за габаритов товара для доставки требуется а/м грузоподъемностью не менее 10 т, даже при условии что вес товара менее 5 т.-->
	<!--		</div>-->
	<!--	</div>-->
	<!--	<div class="clear"></div>-->
	<!--</div>-->

	<!--<div class="FeaturesProductsWrapper">-->
	<!--	<img class="FeaturesProductsIconBlock" src="truck_20.png">-->
	<!--	<div class="FeaturesProductsTextBlock">-->
	<!--		<div class="FeaturesProductsName">Доставка а/м грузоподъёмностью до 20 т</div>-->
	<!--		<div class="FeaturesProductsText">-->
	<!--			Из-за габаритов товара для доставки требуется а/м грузоподъемностью не менее 20 т, даже при условии что вес товара менее 5 т.-->
	<!--		</div>-->
	<!--	</div>-->
	<!--	<div class="clear"></div>-->
	<!--</div>-->

	<!--<div class="FeaturesProductsWrapper">-->
	<!--	<img class="FeaturesProductsIconBlock" src="only_unloading.png">-->
	<!--	<div class="FeaturesProductsTextBlock">-->
	<!--		<div class="FeaturesProductsName">Доставка без подъема</div>-->
	<!--		<div class="FeaturesProductsText">-->
	<!--			Услуга подъем не предоставляется, в виду сложности гарантирования сохранности груза либо его тяжести.-->
	<!--		</div>-->
	<!--	</div>-->
	<!--	<div class="clear"></div>-->
	<!--</div>-->
	<!--<div class="FeaturesProductsWrapper">-->
	<!--	<img class="FeaturesProductsIconBlock" src="/cut.png">-->
	<!--	<div class="FeaturesProductsTextBlock">-->
	<!--		<div class="FeaturesProductsName">Распил материала</div>-->
	<!--		<div class="FeaturesProductsText">-->
	<!--			Возможен распил товара.-->
	<!--		</div>-->
	<!--	</div>-->
	<!--	<div class="clear"></div>-->
	<!--</div>-->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>