<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
			<!-- ***************** END CONTENT  ********************-->
		</div>
		<div class="futter" style='font-family: "Open Sans", sans-serif;
                                   height: 80px;
                                   padding: 0 20px;'
        >
			<div class="copy" style='width: 300px;
                              height: 80px;
                              float: left;
                              font-size: 12px;
                              line-height: 15px;
                              padding: 25px 0;
                              font-weight: 300'
            >&copy; 2011-2017 компания АПЕЛЬСИН<br>Россия, Рязань</div>
			<!--
			<div class="contacts" style='float: right'>
				<div class="phone" style='height: 80px;
                                   line-height: 80px;
                                   font-family: "Open Sans", sans-serif;
                                   text-align: right;
                                   float: right;
                                   font-weight: 300;
                                   font-size: 16px;
                                   width: 150px'
                >7 (4912) 240 220</div>
				<div class="phone" style='height: 80px;
                                   line-height: 80px;
                                   font-family: "Open Sans", sans-serif;
                                   text-align: right;
                                   float: right;
                                   font-weight: 300;
                                   font-size: 16px;
                                   width: 150px'
                >7 (4912) 502 020</div>
				<div class="email" style='height: 80px;
                                   line-height: 80px;
                                   font-family: "Open Sans", sans-serif;
                                   text-align: right;
                                   float: right;
                                   font-weight: 300;
                                   font-size: 16px;
                                   width: 150px'
                >info@apelsin.ru</div>
			</div>
			-->
		</div>
	</div>
</div>
<? if (\Bitrix\Main\Loader::includeModule('mail')) : ?>
<?=\Bitrix\Mail\Message::getQuoteEndMarker(true); ?>
<? endif; ?>
</body>
</html>