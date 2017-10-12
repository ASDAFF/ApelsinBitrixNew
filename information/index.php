<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Информация");
?>
	<div class="NavigatorBigIcon">
        <a href="customers_memo/" class="NavigatorBigIconElementHref">
            <div class="NavigatorBigIconElement">
                <img class="main" src="img/icon/service.svg">
                <img class="hover" src="img/icon/service_hover.svg">
                <div class="title">Наши услуги</div>
            </div>
        </a>
        <a href="customers_memo/" class="NavigatorBigIconElementHref">
            <div class="NavigatorBigIconElement">
                <img class="main" src="img/icon/loyalty_programs.svg">
                <img class="hover" src="img/icon/loyalty_programs_hover.svg">
                <div class="title">Программы лояльности</div>
            </div>
        </a>
		<a href="customers_memo/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
			<img class="main" src="img/icon/customers_memo.svg">
			<img class="hover" src="img/icon/customers_memo_hover.svg">
			<div class="title">Памятка покупателю</div>
			</div>
		</a>
		<a href="service_centers/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
				<img class="main" src="img/icon/service_centers.svg">
				<img class="hover" src="img/icon/service_centers_hover.svg">
				<div class="title">Сервисные центры</div>
			</div>
		</a>
		<a href="vacancies/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
				<img class="main" src="img/icon/vacancies.svg">
				<img class="hover" src="img/icon/vacancies_hover.svg">
				<div class="title">Вакансии</div>
			</div>
		</a>
		<a href="features_products/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
				<img class="main" src="img/icon/features_products.svg">
				<img class="hover" src="img/icon/features_products_hover.svg">
				<div class="title">Особенности товаров</div>
			</div>
		</a>
		<div class="clear"></div>
	</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>