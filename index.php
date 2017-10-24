<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Интернет-магазин АПЕЛЬСИН");
global $arSetting;
if(in_array("CONTENT", $arSetting["HOME_PAGE"]["VALUE"])):?>
    <h1 id="pagetitle">О МАГАЗИНЕ</h1>
    <p class="TextP">Онлайн гипермаркет товаров для строительства и ремонта АПЕЛЬСИН.</p>
    <p class="TextP">Большой выбор товаров, удобный сервис и надежный магазин - вот что необходимо для отличной покупки.
        Все это предлагает интернет-гипермаркет АПЕЛЬСИН. Круглосуточно и без выходных мы работаем для Вас.
        Мы всегда открыты, чтобы Вы могли в любое время выбрать и купить понравившийся товар или оставить заявку.</p>
    <p class="TextP">АПЕЛЬСИН - официальный дилер многих известных торговых марок производителей строительных,
        отделочных материалов и инструмента. АПЕЛЬСИН - это ассортимент товаров позволяющий охватить максимальный
        круг интересов и потребностей потенциального потребителя. Складские площади и система логистики позволяют держать
        в наличии большую часть ассортимента и до минимума сократить время поставки Вашего товара.</p>
    <p class="TextP">Ассортимент продукции предлагаемой интернет-гипермаркетом «Апельсин» - это более 100 тысяч наименований.
        У нас Вы найдете товары для дома и ремонта, строительные и отделочные материалы, мебель, хозяйственные товары и
        предметы дизайна интерьера, разные виды техники и инструмента. Все  для обустройства дачи и сада Вы можете приобрести в нашем интернет-гипермаркете. </p>
    <p class="TextP">Интеллектуальная система поиска и удобный интуитивный каталог позволит Вам найти то что нужно быстро и без особого труда. Преимущество нашего
        интернет-гипермаркета - понятная и простая работа. Для того чтобы найти нужный Вам товар и осуществить заказ достаточно нескольких минут. Вы можете выбрать
        удобный для Вас вариант оплаты и доставки. Все это позволяет максимально эффективно использовать Ваше время.
    <p class="TextListName">Почему нужно выбрать нас? Нам есть что предложить Вам: </p>
    <ul class="TextList InfoText dash">
        <li>огромный выбор. Мы заинтересованы чтобы Вы нашли то, что Вам нужно, поэтому наши специалисты постоянно следят за всеми новинками и наш каталог постоянно пополняется.</li>
        <li>большой ассортимент в наличии. Мы заинтересованы чтобы Вы как можно быстрее получили то, что Вам нужно, поэтому наша логистика и склады работают круглосуточно.</li>
        <li>индивидуальный подход к каждому. Вам что-то показалось непонятным? Мы заинтересованы чтобы у Вас не осталось вопросов, поэтому профессиональные менеджеры
            проконсультируют по всем нюансам, связанным с Вашим заказом;</li>
        <li>честные цены. Мы не завышаем цены, мы стараемся предложить оптимальные условия. Наши товары имеют адекватную рыночную цену,
            а постоянные акции, специальные предложения, дисконтная и бонусная системы позволяют Вам получить то, что Вы искали по
            максимально выгодной цене. Наша ценовая политика позволяет всегда оставаться с Вами в доверительных отношениях.</li>
	</ul>
	<p class="TextP">Итак, интернет-гипермаркет АПЕЛЬСИН это удобный, надежный и современный ресурс стремящийся предоставить Вам оптимально-удобные условия для покупки.</p>

<?endif;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>