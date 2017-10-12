<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
        <!-- ***************** END CONTENT  ********************-->
    </div>
    <div class="futter">
        <div class="copy">&copy; 2011-2017 компания «Апельсин»<br>Россия, Рязань</div>
        <div class="contacts">
            <div class="phone">7 (4912) 240 220</div>
            <div class="phone">7 (4912) 502 020</div>
            <div class="email">info@apelsin.ru</div>
        </div>
    </div>
</div>

<? if (\Bitrix\Main\Loader::includeModule('mail')) : ?>
<?=\Bitrix\Mail\Message::getQuoteEndMarker(true); ?>
<? endif; ?>
</body>
</html>