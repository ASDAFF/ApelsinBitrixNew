<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html lang="ru">
<head>
<? if (\Bitrix\Main\Loader::includeModule('mail')) : ?>
<?=\Bitrix\Mail\Message::getQuoteStartMarker(true); ?>
<? endif; ?>
<?
$protocol = \Bitrix\Main\Config\Option::get("main", "mail_link_protocol", 'https', $arParams["SITE_ID"]);
$serverName = $protocol."://".$arParams["SERVER_NAME"];
?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Магазин Апельсин</title>
    <style type="text/css">
	body {
	    font-family: "Open Sans", sans-serif;
	    font-size: 13px !important;
	    font-weight: 400 !important;
	    color: #8184a1 !important;
	    text-align: left;
	    line-height: 1.3;
	    background: #f4f5fd !important;
	    width:100% !important;
	}

	a {
		color: #ef7f1a !important;
		text-decoration: none;
		display: inline-block;
		vertical-align: middle;
		text-decoration: none;
	}

	a.button_apls {
		display: inline-block;
		background: #ef7f1a!important;
		padding: 5px;
		color: #FFFFFF !important;
	}

	.wrapper {
		font-size: 12px;
	    background: #f4f5fd !important;
	    width:100% !important;
	    color: #8184a1 !important;
	}

	.clear {
	    clear: both;
	}

	.center {
	    width: 800px;
	    margin: 0px auto;
	}
	.head,
	.futter {
	    font-family: "Open Sans", sans-serif;
	    height: 80px;
	    padding: 0 20px;
	}
	
	.head .logo {
	    margin: 5px 0px;
	    height: 70px;
	}
	
	.content {
	    background: #fff;
	    padding: 20px;
	}
	
	.futter .copy {
	    width: 300px;
	    height: 80px;
	    float: left;
	    font-size: 12px;
	    line-height: 15px;
	    padding: 25px 0;
	    font-weight: 300;
	}
	
	.head .contacts,
	.futter .contacts {
	    float: right;
	}
	
	.head .contacts .phone,
	.head .contacts .email,
	.futter .contacts .phone,
	.futter .contacts .email
	{
	    height: 80px;
	    line-height: 80px;
	    font-family: "Open Sans", sans-serif;
	    text-align: right;
	    float: right;
	    font-weight: 300;
	    font-size: 16px;
	    width: 150px;
	}
	table.bx_ordercart_order_sum {
	    border-collapse: collapse;
	}
	table.bx_ordercart_order_sum td.custom_t1 {
		padding-right: 5px;
		text-align: right;
	    font-weight: 800;
	}
	table.bx_order_list_table_order {
	    border:1px solid #d3d3d3;
	    background:#fefefe;
	    border-collapse: collapse;
	    width: 100%;
	    -moz-border-radius:5px; /* FF1+ */
	    -webkit-border-radius:5px; /* Saf3-4 */
	    border-radius:5px;
	    -moz-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
	    -webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
	    margin: 20px 0px;
	}
	table.bx_order_list_table_order th,
	table.bx_order_list_table_order thead td,
	table.bx_order_list_table_order tbody td {
	    padding: 5px 2px;
	    text-align:center;
	}
	table.bx_order_list_table_order th,
	table.bx_order_list_table_order thead td{
	    font: bold 14px/20px 'Roboto', "PT Sans", helvetica, "segoe UI", arial, sans-serif;
	    padding-top:12px;
	    text-shadow: 1px 1px 1px #fff;
	    background:#e8eaeb;
	}
	table.bx_order_list_table_order tbody td {
	    font: 300 12px/20px 'Roboto', "PT Sans", helvetica, "segoe UI", arial, sans-serif;
	    border-top:1px solid #e0e0e0;
	    border-right:1px solid #e0e0e0;
	}
	table.bx_order_list_table_order tbody td a {
	    text-decoration: none;
	    color: #575b71;
	}
	table.bx_order_list_table_order tbody td a:hover {
	    color: #ef7f1a;
	}
	table.bx_order_list_table_order tr.odd-row td {
	    background:#f6f6f6;
	}
	table.bx_order_list_table_order tbody td:first-child,
	table.bx_order_list_table_order thead td:first-child,
	table.bx_order_list_table_order th:first-child {
	    padding-left: 10px;
	    text-align:left
	}
	table.bx_order_list_table_order tbody td.last {
	    border-right:none;
	}
	table.bx_order_list_table_order tbody td {
	    background: -moz-linear-gradient(100% 25% 90deg, #fefefe, #f9f9f9);
	    background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f9f9f9), to(#fefefe));
	}
	table.bx_order_list_table_order tr.odd-row td {
	    background: -moz-linear-gradient(100% 25% 90deg, #f6f6f6, #f1f1f1);
	    background: -webkit-gradient(linear, 0% 0%, 0% 25%, from(#f1f1f1), to(#f6f6f6));
	}
	table.bx_order_list_table_order th {
	    background: -moz-linear-gradient(100% 20% 90deg, #e8eaeb, #ededed);
	    background: -webkit-gradient(linear, 0% 0%, 0% 20%, from(#ededed), to(#e8eaeb));
	}
	table.bx_order_list_table_order tr:first-child th.first {
	    -moz-border-radius-topleft:5px;
	    -webkit-border-top-left-radius:5px; /* Saf3-4 */
	}
	table.bx_order_list_table_order tr:first-child th.last {
	    -moz-border-radius-topright:5px;
	    -webkit-border-top-right-radius:5px; /* Saf3-4 */
	}
	table.bx_order_list_table_order tr:last-child td.first {
	    -moz-border-radius-bottomleft:5px;
	    -webkit-border-bottom-left-radius:5px; /* Saf3-4 */
	}
	table.bx_order_list_table_order tr:last-child td.last {
	    -moz-border-radius-bottomright:5px;
	    -webkit-border-bottom-right-radius:5px; /* Saf3-4 */
	}
	table.bx_order_list_table_order tr:hover td {
	    cursor: pointer;
	    background: #F8F8F8;
	}
		
	@media (min-width:1014px) and (max-width:1253px){}
	@media (min-width:788px) and (max-width:1013px){
	    .center {
	        width: 700px;
	        margin: 0px auto;
	    }
	    .head .contacts .phone,
	    .head .contacts .email,
	    .futter .contacts .phone,
	    .futter .contacts .email
	    {
	        height: 80px;
	        line-height: 80px;
	        font-family: "Open Sans", sans-serif;
	        text-align: right;
	        float: right;
	        font-weight: 300;
	        font-size: 13px;
	        width: 120px;
	    }
	    .head .contacts .phone,
	    .head .contacts .email {
	        font-size: 15px;
	        width: 140px;
	    }
	}
	@media screen and (max-width:787px){
	    .center {
	        width: 100%;
	        margin: 0px auto;
	    }
	    .content {
	        padding: 10px;
	    }
	    .head .contacts {
	        display: none;
	    }
	    .futter .copy,
	    .futter .contacts,
	    .futter .contacts .phone,
	    .futter .contacts .email {
	        width: 100%;
	        text-align: center;
	        float: none;
	    }
	    .futter .copy {
	        height: 30px;
	        font-size: 12px;
	        line-height: 15px;
	        font-weight: 300;
	        padding: 10px 0;
	    }
	    .futter .contacts .phone,
	    .futter .contacts .email {
	        height: 20px;
	        line-height: 20px;
	        font-weight: 300;
	        font-size: 13px;
	    }
	}
    </style>
<?
/*
This is commented to avoid Project Quality Control warning
$APPLICATION->ShowHead();
$APPLICATION->ShowTitle();
$APPLICATION->ShowPanel();
*/
?>
</head>
<body  style='font-family: "Open Sans", sans-serif;
	   font-size: 13px !important;
	   font-weight: 400 !important;
	   color: #8184a1 !important;
	   text-align: left;
	   line-height: 1.3;
	   background: #f4f5fd !important;
	   width:100% !important'
>
<div class="wrapper" style='font-size: 12px;
                     background: #f4f5fd !important;
                     width:100% !important;
                     color: #8184a1 !important'
>
	<div class="center" style='width: 800px;
	                    margin: 0px auto;'
    >
		<div class="head" style='font-family: "Open Sans", sans-serif;
	                      height: 80px;
	                      padding: 0 20px;'
        >
			<a href="<?=$serverName?>" style='color: #ef7f1a !important;
                                       text-decoration: none;
                                       display: inline-block;
                                       vertical-align: middle;
                                       text-decoration: none'
            ><img class="logo" src="/bitrix/templates/elektro_flat_APLS/images/apls_logo.png"
                                            alt="Магазин Апельсин" style='margin: 5px 0px;
	                                                               height: 70px'
                >
            </a>
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
                                   width: 150px'>info@apelsin.ru
                </div>
			</div>
			-->
		</div>
		<div class="content" style='background: #fff;
	                         padding: 20px;'
        >
			<!-- ***************** CONTENT  ********************-->	
			