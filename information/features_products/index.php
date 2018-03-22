<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Особенности товаров");
?>
	<p class="TextP">Уважаемые покупатели, для Вашего удобства все товары имеющие ограничения или особенности в предоставлении услуг отмечены специальными значками:</p>

    <div class="FeaturesProductsWrapper">
        <img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/terms_of_sale.svg">
        <div class="FeaturesProductsTextBlock">
            <div class="FeaturesProductsName">Условия продажи</div>
            <div class="FeaturesProductsText">
                Товар имеет особые условия продажи (кратность, комплектность, мин. количество и т.п.), поэтому возможно
                изменение стоимости заказа при обработке оператором.
            </div>
        </div>
        <div class="clear"></div>
    </div>

	<div class="FeaturesProductsWrapper">
		<img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/truck_long.svg">
		<div class="FeaturesProductsTextBlock">
			<div class="FeaturesProductsName">Длинномер</div>
			<div class="FeaturesProductsText">
				Из-за габаритов товара для доставки требуется а/м с длиною кузова более 4 м.
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="FeaturesProductsWrapper">
		<img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/manipulator.svg">
		<div class="FeaturesProductsTextBlock">
			<div class="FeaturesProductsName">Манипулятор</div>
			<div class="FeaturesProductsText">
				Для доставки требуется манипулятор.
			</div>
		</div>
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
		<div class="FeaturesProductsTextBlock">
			<div class="FeaturesProductsName">Установка / Монтаж</div>
			<div class="FeaturesProductsText">
				Возможен заказ установки или монтажа.
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="FeaturesProductsWrapper">
		<img class="FeaturesProductsIconBlock" src="../../apls_resources/DeliveryIcons/color.svg">
		<div class="FeaturesProductsTextBlock">
			<div class="FeaturesProductsName">Колеровка</div>
			<div class="FeaturesProductsText">
				Данный материал можно заколеровать в выбранный вами цвет.
			</div>
		</div>
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