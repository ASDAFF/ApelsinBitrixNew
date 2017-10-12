<?
/*
	Если в оформлении заказа при выборе ПВЗ затеняется экран, и виджет оказывается "под" маской - нужно раскомментить скрипт с меткой // BLACK MASK FIX и закомментить (или удалить) html виджета.
*/
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.CDeliveryapiship::$MODULE_ID.'/jsloader.php');
// if(coption::GetOptionString(CDeliveryapiship::$MODULE_ID,'addJQ','Y')=='Y')
	// CJSCore::init('jquery');

global $APPLICATION;
CModule::IncludeModule(CDeliveryapiship::$MODULE_ID);
if($arParams['NOMAPS']!='Y')
	$APPLICATION->AddHeadString('<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>');

$APPLICATION->AddHeadString('<link href="/bitrix/js/'.CDeliveryapiship::$MODULE_ID.'/jquery.jscrollpane.css" type="text/css"  rel="stylesheet" />');

if(apishipHelper::isConverted()){
	$arDelivs = apishipHelper::getDeliveryProfilesIDs();
	$htmlId = 'ID_DELIVERY_ID_'.$arDelivs["pickup"];
}else
	$htmlId = 'ID_DELIVERY_apiship_pickup';
?>
<script>
// находит первого попавшегося родителя со свойством position: relative
// var maxIter = 0;
if (typeof IPOLapiship_pvzFindRelativeParent == "undefined")
	function IPOLapiship_pvzFindRelativeParent(obj)
	{
		if (obj.is("body"))
			return -1;
		if (obj.css("position") == "relative")
			return obj;
		else
			return IPOLapiship_pvzFindRelativeParent(obj.parent());
	}

var IPOLapiship_pvz = {
	city: '<?=$arResult['cityName']?>',//город
	cityID: '<?=$arResult['city']?>',
	
	button: '<a href="javascript:void(0);" id="apiship_selectPVZ" onclick="IPOLapiship_pvz.selectPVZ(); return false;"><?=GetMessage("IPOLapiship_FRNT_CHOOSEPICKUP")?></a>',// html кнопки "выбрать ПВЗ".
	
	pvzInputs: [<?=substr($arResult['propAddr'],0,-1)?>],//инпуты, куда грузится адрес пвз
	deliveryLink: '<?=$htmlId?>',
	deliveryIDs: <?=CUtil::PHPToJSObject($arDelivs)?>,
	
	pvzLabel: "",
	presizion: 2,
	
	pvzId: false,
	chosenPVZProviderKey: false,
	chosenPVZProviderID: false,
	chosenTariffID: false,
	
	PVZ: {},
	DefaultVals:{},
	LoadFromAJAX: false,
	LoadInputsFromAJAX: false,
	
	image_url: "<?=apishipHelper::getProviderIconsURL()?>",
	arImages: <?=CUtil::PHPToJSObject(apishipHelper::getProvderIcons())?>,
	
	relativeParent: false,
	minWindowWidth: 600,
	
	GetDefaultVals: function()
	{
		IPOLapiship_pvz.DefaultVals = <?=CUtil::PhpToJSObject($arResult["defaultVals"])?>;
	},
	
	GetPVZ: function()
	{
		IPOLapiship_pvz.PVZ = <?=CUtil::PhpToJSObject($arResult["PVZ"])?>;
	},
	
	GetAjaxPVZ: function(ajaxAns, newTemplateAjax)
	{
		if (IPOLapiship_pvz.oldTemplate)
		{
			if ($("#ipolapiship_pvz_list_tag_ajax").length > 0)
			{
				IPOLapiship_pvz.PVZ = JSON.parse($("#ipolapiship_pvz_list_tag_ajax").html());
				$("#ipolapiship_pvz_list_tag_ajax").remove();
			}
		}
		else if (newTemplateAjax && typeof ajaxAns.apiship.ipolapiship_pvz_list_tag_ajax != "undefined")
			IPOLapiship_pvz.PVZ = ajaxAns.apiship.ipolapiship_pvz_list_tag_ajax;
		
		IPOLapiship_pvz.Y_init();
		
		// сбрасываем выбранный ПВЗ, если такого теперь нет, например город поменяли
		if (typeof IPOLapiship_pvz.PVZ[IPOLapiship_pvz.pvzId] == "undefined")
		{
			IPOLapiship_pvz.pvzId = false;
			IPOLapiship_pvz.chosenPVZProviderKey = false;
			IPOLapiship_pvz.chosenPVZProviderID = false;
			IPOLapiship_pvz.chosenTariffID = false;
			
			IPOLapiship_pvz.UpdateChosenInputs();
		}
	},
	
	GetAjaxDefaultVals: function(ajaxAns, newTemplateAjax)
	{
		if (IPOLapiship_pvz.oldTemplate)
		{
			if ($("#ipolapiship_default_vals_tag_ajax").length > 0)
			{
				IPOLapiship_pvz.DefaultVals = JSON.parse($("#ipolapiship_default_vals_tag_ajax").html());
				$("#ipolapiship_default_vals_tag_ajax").remove();
			}
		}
		else if (newTemplateAjax && typeof ajaxAns.apiship.ipolapiship_default_vals_tag_ajax != "undefined")
			IPOLapiship_pvz.DefaultVals = ajaxAns.apiship.ipolapiship_default_vals_tag_ajax;
	},
	
	init: function()
	{
		IPOLapiship_pvz.orderForm = "ORDER_FORM";
		
		if ($("#"+IPOLapiship_pvz.orderForm).length > 0)
			IPOLapiship_pvz.oldTemplate = true;
		else
		{
			IPOLapiship_pvz.oldTemplate = false;
			IPOLapiship_pvz.orderForm = "bx-soa-order-form";
		}
		
		// ==== подписываемся на перезагрузку формы
		if(typeof BX !== 'undefined' && BX.addCustomEvent)
			BX.addCustomEvent('onAjaxSuccess', IPOLapiship_pvz.onLoad); 
		
		// Для старого JS-ядра
		if (window.jsAjaxUtil) // Переопределение Ajax-завершающей функции для навешивания js-событий новым эл-там
		{
			jsAjaxUtil._CloseLocalWaitWindow = jsAjaxUtil.CloseLocalWaitWindow;
			jsAjaxUtil.CloseLocalWaitWindow = function (TID, cont)
			{
				jsAjaxUtil._CloseLocalWaitWindow(TID, cont);
				IPOLapiship_pvz.onLoad();
			}
		}
		// == END
		
		IPOLapiship_pvz.onLoad();
		
		// html маски
		$('body').append("<div id='apiship_mask'></div>");
		$('#apiship_pvz').appendTo("body");
	},
	
	onLoad: function(ajaxAns)
	{
		console.log(ajaxAns);
		// место, где будет кнопка "выбрать ПВЗ"
		var tag = false;
		
		var newTemplateAjax = (typeof(ajaxAns) != 'undefined' && ajaxAns !== null && typeof(ajaxAns.apiship) == 'object') ? true : false;
		
		// первый раз берем данные из компонента, далее из полей, которые приходят из буферконтент в аякс ответах в html
		if (!IPOLapiship_pvz.LoadFromAJAX)
		{	
			IPOLapiship_pvz.GetPVZ();
			IPOLapiship_pvz.GetDefaultVals();
			IPOLapiship_pvz.LoadFromAJAX = true;
		}
		else
		{
			IPOLapiship_pvz.GetAjaxPVZ(ajaxAns, newTemplateAjax);
			IPOLapiship_pvz.GetAjaxDefaultVals(ajaxAns, newTemplateAjax);
		}
		
		IPOLapiship_pvz.CheckChosenPickUp(ajaxAns);
		// if (!IPOLapiship_pvz.oldTemplate)
		// {
			var isPickupInp = $("#apiship_isPickup");
			if (isPickupInp.length > 0)
				isPickupInp.val(IPOLapiship_pvz.isPickUpChecked);
			else
				$("#"+IPOLapiship_pvz.orderForm).append("<input type = 'hidden' name = 'apiship_isPickup' id = 'apiship_isPickup' value = '"+ IPOLapiship_pvz.isPickUpChecked +"'>");
		// }

		tag = $('#IPOLapiship_injectHere_pickup');
		if(tag.length>0 && tag.html().indexOf(IPOLapiship_pvz.button)===-1){
			IPOLapiship_pvz.pvzLabel = tag;
		}

		// console.log(ajaxAns);
		if (IPOLapiship_pvz.oldTemplate)
		{
			if($('#apiship_city').length>0){//обновляем город
				IPOLapiship_pvz.city   = $('#apiship_city').val();
				IPOLapiship_pvz.cityID   = $('#apiship_city_id').val();
			}
			
			if($('#apiship_dostav').length>0 && $('#apiship_dostav').val()=='apiship:pickup' && IPOLapiship_pvz.pvzId)
				IPOLapiship_pvz.choozePVZ(IPOLapiship_pvz.pvzId,true);
		}
		else
		{
			if (newTemplateAjax)
			{
				if (ajaxAns.apiship.apiship_city)
					IPOLapiship_pvz.city = ajaxAns.apiship.apiship_city;
				
				if (ajaxAns.apiship.apiship_city_id)
					IPOLapiship_pvz.cityID = ajaxAns.apiship.apiship_city_id;
				
				if (ajaxAns.apiship.apiship_dostav)
					if (ajaxAns.apiship.apiship_dostav == 'apiship:pickup' && IPOLapiship_pvz.pvzId)
						IPOLapiship_pvz.choozePVZ(IPOLapiship_pvz.pvzId,true);
			}
		}
		
		// IPOLapiship_pvz.UpdateChosenInputs();
		IPOLapiship_pvz.ChangeLabelHTML();
	},
	
	// показываем окошко
	selectPVZ: function()
	{
		if(!IPOLapiship_pvz.isActive){
			IPOLapiship_pvz.isActive = true;

			IPOLapiship_pvz.getWinPosition();
			
			$('#apiship_mask').css('display','block');

			IPOLapiship_pvz.initCityPVZ();

			IPOLapiship_pvz.Y_init();
		}
	},
	
	// считаем положение и размеры окна с пвз
	getWinPosition: function(){
		var hndlr = $('#apiship_pvz');
		
		// находим родителя со свойством position: relative и считаем поправку от его положения
		if (IPOLapiship_pvz.relativeParent == false)
		{
			IPOLapiship_pvz.relativeParent = IPOLapiship_pvzFindRelativeParent(hndlr);
			if (IPOLapiship_pvz.relativeParent == -1 || IPOLapiship_pvz.relativeParent == false)
				IPOLapiship_pvz.relativeParent = -1;
			else
				IPOLapiship_pvz.relativeParent = $(IPOLapiship_pvz.relativeParent[0]);
		}
		
		var shiftWidth = 0, 
			shiftHeight = 0;
		
		// считаем поправку
		if (IPOLapiship_pvz.relativeParent != -1)
		{
			shiftWidth = IPOLapiship_pvz.relativeParent.offset().left;
			shiftHeight = IPOLapiship_pvz.relativeParent.offset().top;
		}
		
		var width = $(window).width()*0.8;
		if (width < IPOLapiship_pvz.minWindowWidth)
			width = IPOLapiship_pvz.minWindowWidth;
		
		hndlr.css({
			'display'   : 'block',
			'width'     : width,
			'left'      : (($(window).width() - width)/2) - shiftWidth,
			'top'       : (($(window).height() - hndlr.height())/2) + $(document).scrollTop() - shiftHeight,
		});	
	},
	
	// обрабатываем изменение размера окна браузера
	resize: function(){
		if (IPOLapiship_pvz.isActive)
			IPOLapiship_pvz.getWinPosition();
	},
	
	initCityPVZ: function(){ // грузим пункты самовывоза для выбранного города
		var city = IPOLapiship_pvz.city;
		var cnt = [];
		// IPOLapiship_pvz.PVZ = IPOLapiship_pvz.PVZ;
		
		IPOLapiship_pvz.PVZHTML();//грузим html PVZ. Два раза пробегаем по массиву, но не критично.
		
		IPOLapiship_pvz.multiPVZ = (IPOLapiship_pvz.PVZ.length == 1)? false:true;
	},
	
	PVZHTML: function(){ // заполняем список ПВЗ города
		
		var arHTML = {};
		
		for(var i in IPOLapiship_pvz.PVZ)
		{
			if (typeof arHTML[IPOLapiship_pvz.PVZ[i].providerKey] == "undefined")
			{
				arHTML[IPOLapiship_pvz.PVZ[i].providerKey] = {
					"head": "",
					"content": ""
				};
			}
			
			if (!arHTML[IPOLapiship_pvz.PVZ[i].providerKey].head)
			{
				var head_html = "", daysMin, daysMax, days = 0;
				
				daysMin = IPOLapiship_pvz.PVZ[i].daysMin;
				daysMax = IPOLapiship_pvz.PVZ[i].daysMax;
				
				if (daysMin == 0)
					daysMin = 1;
				if (daysMax == 0)
					daysMax = 1;
				if (daysMin == daysMax)
					days = daysMin;
				else
					days = daysMin + "-" + daysMax;
				
				head_html += "<div class = 'apiship_delivInfo'>";
				
				if (typeof IPOLapiship_pvz.arImages[IPOLapiship_pvz.PVZ[i].providerKey] != "undefined")
					head_html += "<img class = 'apiship_provider_img' src = '"+ IPOLapiship_pvz.image_url + IPOLapiship_pvz.arImages[IPOLapiship_pvz.PVZ[i].providerKey] +"'>";
				else
					head_html += IPOLapiship_pvz.PVZ[i].providerKey;
				
				head_html += "<?=GetMessage("IPOLapiship_DELIVERY")?><span>"+ IPOLapiship_pvz.PVZ[i].deliveryCost +"</span>"+"<?=GetMessage("IPOLapiship_CURRENCY_RUB")?>";
				head_html += "<?=GetMessage("IPOLapiship_SROK_DOSTAVKI")?><span>" + days +"</span>"
				head_html += "</div>";
				
				arHTML[IPOLapiship_pvz.PVZ[i].providerKey].head = head_html;
			}
			
			arHTML[IPOLapiship_pvz.PVZ[i].providerKey].content += '<p id="PVZ_'+i+'" onclick="IPOLapiship_pvz.markChosenPVZ(\''+i+'\')" onmouseover="IPOLapiship_pvz.Y_blinkPVZ(\''+i+'\',true)" onmouseout="IPOLapiship_pvz.Y_blinkPVZ(\''+i+'\')">'+IPOLapiship_pvz.paintPVZ(i)+'</p>';
		}
		
		var html = '';
		for (var i in arHTML)
		{
			html += arHTML[i].head;
			html += arHTML[i].content;
		}
		
		$('#apiship_wrapper').html(html);
		IPOLapiship_pvz.scrollPVZ = window.ipol$('#apiship_wrapper').jScrollPane();
	},
	
	paintPVZ: function(ind){ //красим адресс пвз, если задан цвет
		var addr = '';
		if(IPOLapiship_pvz.PVZ[ind].color && IPOLapiship_pvz.PVZ[ind].Address.indexOf(',')!==false)
			addr="<span style='color:"+IPOLapiship_pvz.PVZ[ind].color+"'>"+IPOLapiship_pvz.PVZ[ind].Address.substr(0,IPOLapiship_pvz.PVZ[ind].Address.indexOf(','))+"</span><br>"+IPOLapiship_pvz.PVZ[ind].Name;
		else
			addr=IPOLapiship_pvz.PVZ[ind].Name;
		return addr;
	},
	
	// выбрали ПВЗ
	pvzAdress: '',
	pvzId: false,
	choozePVZ: function(pvzId,isAjax){// выбрали ПВЗ
	
		if(typeof IPOLapiship_pvz.PVZ[pvzId] == 'undefined')
			return;

		IPOLapiship_pvz.pvzAdress=IPOLapiship_pvz.city+", "+IPOLapiship_pvz.PVZ[pvzId]['Address']+" #S"+IPOLapiship_pvz.PVZ[pvzId].id;

		// пишем информацию о ПВЗ в инпуты для использования в оформлении заказа
		IPOLapiship_pvz.chosenPVZProviderKey = IPOLapiship_pvz.PVZ[pvzId].providerKey;
		IPOLapiship_pvz.chosenPVZProviderID = IPOLapiship_pvz.PVZ[pvzId].code;
		IPOLapiship_pvz.pvzId = pvzId;
		IPOLapiship_pvz.chosenTariffID = IPOLapiship_pvz.PVZ[pvzId].tariffId;
		
		IPOLapiship_pvz.UpdateChosenInputs();
		IPOLapiship_pvz.ChangeLabelHTML();
		
		var chznPnkt = false;
		if(typeof(KladrJsObj) != 'undefined')KladrJsObj.FuckKladr();
		for(var i in IPOLapiship_pvz.pvzInputs){
			chznPnkt = $('[name=ORDER_PROP_'+IPOLapiship_pvz.pvzInputs[i]+"]");
			if(chznPnkt.length>0){
				chznPnkt.val(IPOLapiship_pvz.pvzAdress);
				chznPnkt.html(IPOLapiship_pvz.pvzAdress);
				chznPnkt.css('background-color', '#eee').attr('readonly','readonly');
				break;
			}
		}
		
		if(typeof isAjax == 'undefined')
		{ // Перезагружаем форму (с применением новой стоимости доставки)
			if(typeof IPOLapiship_DeliveryChangeEvent == 'function')
				IPOLapiship_DeliveryChangeEvent();
			else
				if (IPOLapiship_pvz.oldTemplate)
				{
					if(typeof $.prop == 'undefined') // <3 jquery
				
						$('#'+IPOLapiship_pvz.deliveryLink).attr('checked', 'Y');
					else
						$('#'+IPOLapiship_pvz.deliveryLink).prop('checked', 'Y');
					$('#'+IPOLapiship_pvz.deliveryLink).click();
				}
				else
					BX.Sale.OrderAjaxComponent.sendRequest();
		}
		
		IPOLapiship_pvz.close(true);
	},
	
	ChangeLabelHTML: function(){
		// Выводим подпись о выбранном ПВЗ рядом с кнопкой "Выбрать ПВЗ"
		var tmpHTML = "<div class='apiship_pvzLair'>"+IPOLapiship_pvz.button;
		
		// если выбран ПВЗ, выводим его адрес
		if (IPOLapiship_pvz.pvzId)
		{
			tmpHTML += "<br><span class='apiship_pvzAddr'>" + IPOLapiship_pvz.PVZ[IPOLapiship_pvz.pvzId].Address+"</span>";
		}
		
		/*
		if (IPOLapiship_pvz.oldTemplate)
		{
			var daysMin, daysMax, days = 0;
			if (!IPOLapiship_pvz.pvzId)
			{
				// считаем количество дней доставки
				IPOLapiship_pvz.pvzPrice = "<?=GetMessage("IPOLapiship_STOIMOST")?>" + IPOLapiship_pvz.DefaultVals.VALUE_MIN;
				
				daysMin = IPOLapiship_pvz.DefaultVals.TRANSIT_MIN;
				daysMax = IPOLapiship_pvz.DefaultVals.TRANSIT_MAX;
				
				
			}
			else
			{
				IPOLapiship_pvz.pvzPrice = "<?=GetMessage("IPOLapiship_STOIMOST")?>" + parseFloat(IPOLapiship_pvz.PVZ[IPOLapiship_pvz.pvzId].deliveryCost).toFixed(IPOLapiship_pvz.presizion);
				
				daysMin = IPOLapiship_pvz.PVZ[IPOLapiship_pvz.pvzId].daysMin;
				daysMax = IPOLapiship_pvz.PVZ[IPOLapiship_pvz.pvzId].daysMax;
			}
			
			if (daysMin == 0)
				daysMin = 1;
			if (daysMax == 0)
				daysMax = 1;
			if (daysMin == daysMax)
				days = daysMin;
			else
				days = daysMin + "-" + daysMax;
			IPOLapiship_pvz.pvzPrice += "<br>"+ "<?=GetMessage("IPOLapiship_SROK_DOSTAVKI")?>" + days;
			
			if(IPOLapiship_pvz.pvzPrice)
				tmpHTML+="<br>"+IPOLapiship_pvz.pvzPrice;
		}*/
		
		tmpHTML+="</div>";
		
		if (typeof IPOLapiship_pvz.pvzLabel == "object")
			if (IPOLapiship_pvz.oldTemplate)
				IPOLapiship_pvz.pvzLabel.html(tmpHTML);
			else if (IPOLapiship_pvz.isPickUpChecked)
			{
				IPOLapiship_pvz.pvzLabel.html(tmpHTML);
			}
	},
	
	UpdateChosenInputs: function(){
		if (!IPOLapiship_pvz.LoadInputsFromAJAX)
		{
			var orderForm = $("#"+IPOLapiship_pvz.orderForm);
			orderForm.append("<input type = 'hidden' name = 'apiship_providerKey_first' value = '" + IPOLapiship_pvz.chosenPVZProviderKey + "'>");
			orderForm.append("<input type = 'hidden' name = 'apiship_pvzProviderID_first' value = '" + IPOLapiship_pvz.chosenPVZProviderID + "'>");
			orderForm.append("<input type = 'hidden' name = 'apiship_pvzID_first' value = '" + IPOLapiship_pvz.pvzId + "'>");
			orderForm.append("<input type = 'hidden' name = 'apiship_tariffId_first' value = '" + IPOLapiship_pvz.chosenTariffID + "'>");
			IPOLapiship_pvz.LoadInputsFromAJAX = true;
		}
		else
		{
			if (IPOLapiship_pvz.oldTemplate)
			{
				var orderForm = $("#"+IPOLapiship_pvz.orderForm);
				if ($("#apiship_providerKey").length <= 0)
					orderForm.append("<input type = 'hidden' id = 'apiship_providerKey' name = 'apiship_providerKey' value = '" + IPOLapiship_pvz.chosenPVZProviderKey + "'>");
				
				if ($("#apiship_pvzProviderID").length <= 0)
					orderForm.append("<input type = 'hidden' id = 'apiship_pvzProviderID' name = 'apiship_pvzProviderID' value = '" + IPOLapiship_pvz.chosenPVZProviderID + "'>");
				
				if ($("#apiship_pvzID").length <= 0)
					orderForm.append("<input type = 'hidden' id = 'apiship_pvzID' name = 'apiship_pvzID' value = '" + IPOLapiship_pvz.pvzId + "'>");
				
				if ($("#apiship_tariffId").length <= 0)
					orderForm.append("<input type = 'hidden' id = 'apiship_tariffId' name = 'apiship_tariffId' value = '" + IPOLapiship_pvz.chosenTariffID + "'>");
				
				if (IPOLapiship_pvz.chosenPVZProviderKey)
					$("#apiship_providerKey").val(IPOLapiship_pvz.chosenPVZProviderKey);
				else
					$("#apiship_providerKey").val("");
				
				if (IPOLapiship_pvz.pvzId)
				{
					$("#apiship_pvzID").val(IPOLapiship_pvz.pvzId);
					$("#apiship_pvzProviderID").val(IPOLapiship_pvz.PVZ[IPOLapiship_pvz.pvzId].code);
				}
				else
				{
					$("#apiship_pvzID").val("");
					$("#apiship_pvzProviderID").val("");
				}
				
				if (IPOLapiship_pvz.chosenTariffID)
					$("#apiship_tariffId").val(IPOLapiship_pvz.chosenTariffID);
				else
					$("#apiship_tariffId").val("");
			}
			else
			{
				var orderForm = $("#"+IPOLapiship_pvz.orderForm);
				orderForm.append("<input type = 'hidden' name = 'apiship_providerKey' value = '" + IPOLapiship_pvz.chosenPVZProviderKey + "'>");
				orderForm.append("<input type = 'hidden' name = 'apiship_pvzProviderID' value = '" + IPOLapiship_pvz.chosenPVZProviderID + "'>");
				orderForm.append("<input type = 'hidden' name = 'apiship_pvzID' value = '" + IPOLapiship_pvz.pvzId + "'>");
				orderForm.append("<input type = 'hidden' name = 'apiship_tariffId' value = '" + IPOLapiship_pvz.chosenTariffID + "'>");
			}
			
			$("[name=apiship_providerKey_first]").val("");
			$("[name=apiship_pvzProviderID_first]").val("");
			$("[name=apiship_pvzID_first]").val("");
			$("[name=apiship_tariffId_first]").val("");
		}
	},
	
	markChosenPVZ: function(id){
		$("#apiship_pPrice").html(parseFloat(IPOLapiship_pvz.PVZ[id].deliveryCost).toFixed(IPOLapiship_pvz.presizion));
		
		var daysMin = IPOLapiship_pvz.PVZ[id].daysMin,
			daysMax = IPOLapiship_pvz.PVZ[id].daysMax;
		if (daysMin == 0)
			daysMin = 1;
		if (daysMax == 0)
			daysMax = 1;
		var days;
		if (daysMin == daysMax)
			days = daysMin;
		else
			days = daysMin + " - " + daysMax;
		$("#apiship_pDate").html(days);
		
		if($('.apiship_chosen').attr('id')!='PVZ_'+id){
			$('.apiship_chosen').removeClass('apiship_chosen');
			$("#PVZ_"+id).addClass('apiship_chosen');
			IPOLapiship_pvz.Y_selectPVZ(id);
		}
	},
	
	close: function(fromChoose){//закрываем функционал
		<?if(COption::GetOptionString(CDeliveryapiship::$MODULE_ID,'autoSelOne','') == 'Y'){?>
			if(IPOLapiship_pvz.multiPVZ !== false && typeof(fromChoose) == 'undefined')
				IPOLapiship_pvz.choozePVZ(IPOLapiship_pvz.multiPVZ);
		<?}?>
		if(IPOLapiship_pvz.scrollPVZ && typeof(IPOLapiship_pvz.scrollPVZ.data('jsp'))!='undefined')
			IPOLapiship_pvz.scrollPVZ.data('jsp').destroy();
		$('#apiship_pvz').css('display','none');
		$('#apiship_mask').css('display','none');
		IPOLapiship_pvz.isActive = false;
	},
	// Yкарты
	Y_map: false,//указатель на y-карту

	Y_init: function(){
		
		if(typeof IPOLapiship_pvz.city == 'undefined')
			IPOLapiship_pvz.city = '<?=GetMessage('IPOLapiship_FRNT_MOSCOW')?>';
		
		ymaps.geocode("<?=GetMessage("IPOLapiship_RUSSIA")?>, "+IPOLapiship_pvz.city , {
			results: 1
		}).then(function (res) {
				var firstGeoObject = res.geoObjects.get(0);
				var coords = firstGeoObject.geometry.getCoordinates();
				coords[1]-=0.2;
				if(!IPOLapiship_pvz.Y_map){
					IPOLapiship_pvz.Y_map = new ymaps.Map("apiship_map",{
						zoom:10,
						controls: [],
						center: coords
					});
					var ZK = new ymaps.control.ZoomControl({
						options : {
							position:{
								left : 265,
								top  : 146
							}
						}
					});
					
					IPOLapiship_pvz.Y_map.controls.add(ZK);
				}
				else{
					IPOLapiship_pvz.Y_map.setCenter(coords);
					IPOLapiship_pvz.Y_map.setZoom(10);
				}
				if(!IPOLapiship_pvz.Y_markedCities[IPOLapiship_pvz.city]) //чтобы не грузились повторно
					IPOLapiship_pvz.Y_markPVZ();
				else
					IPOLapiship_pvz.PVZ = IPOLapiship_pvz.Y_markedCities[IPOLapiship_pvz.city];
		});
	},

	Y_markPVZ: function(){
		for(var i in IPOLapiship_pvz.PVZ){
			var baloonHTML = "";
			
			// логтип доставщика
			if (typeof IPOLapiship_pvz.arImages[IPOLapiship_pvz.PVZ[i].providerKey] != "undefined")
				baloonHTML += "<img class = 'apiship_provider_baloon_img' src = '"+ IPOLapiship_pvz.image_url + IPOLapiship_pvz.arImages[IPOLapiship_pvz.PVZ[i].providerKey] +"'>";
			else
				baloonHTML += "<div class = 'apiship_provider_baloon_img'>" + IPOLapiship_pvz.PVZ[i].providerKey + "</div>";
			
			baloonHTML += "<div id='apiship_baloon'>";
			baloonHTML += "<div class='apiship_iAdress'>";
			
			if(IPOLapiship_pvz.PVZ[i].color)
				baloonHTML +=  "<span style='color:"+IPOLapiship_pvz.PVZ[i].color+"'>"
			
			baloonHTML += IPOLapiship_pvz.PVZ[i].Address;
			
			if(IPOLapiship_pvz.PVZ[i].color)
				baloonHTML += "</span>";
			
			baloonHTML += "</div>";

			if(IPOLapiship_pvz.PVZ[i].Phone)
				baloonHTML += "<div><div class='apiship_iTelephone apiship_icon'></div><div class='apiship_baloonDiv'>"+IPOLapiship_pvz.PVZ[i].Phone+"</div><div style='clear:both'></div></div>";
			if(IPOLapiship_pvz.PVZ[i].WorkTime)
				baloonHTML += "<div><div class='apiship_iTime apiship_icon'></div><div class='apiship_baloonDiv'>"+IPOLapiship_pvz.PVZ[i].WorkTime+"</div><div style='clear:both'></div></div>";
			
			baloonHTML += "<div><div class='apiship_baloonDiv'><?=GetMessage("IPOLapiship_BALOON_DELIVERY_COST")?>"+IPOLapiship_pvz.PVZ[i].deliveryCost+"<?=GetMessage("IPOLapiship_CURRENCY_RUB")?></div><div style='clear:both'></div></div>";
			
			var daysMin = IPOLapiship_pvz.PVZ[i].daysMin,
				daysMax = IPOLapiship_pvz.PVZ[i].daysMax;
			if (daysMin == 0)
				daysMin = 1;
			if (daysMax == 0)
				daysMax = 1;
			var days;
			if (daysMin == daysMax)
				days = daysMin;
			else
				days = daysMin + " - " + daysMax;
		
			baloonHTML += "<div><div class='apiship_baloonDiv'><?=GetMessage("IPOLapiship_BALOON_DELIVERY_DAYS")?>"+days+"</div><div style='clear:both'></div></div>";
			
			baloonHTML += "<div><a id='apiship_button' href='javascript:void(0)' onclick='IPOLapiship_pvz.choozePVZ(\""+i+"\")'></a></div>";
			baloonHTML += "</div>";
			IPOLapiship_pvz.PVZ[i].placeMark = new ymaps.Placemark([IPOLapiship_pvz.PVZ[i].cY,IPOLapiship_pvz.PVZ[i].cX],{
				balloonContent: baloonHTML
			}, {
				iconLayout: 'default#image',
				iconImageHref: '/bitrix/images/ipol.apiship/widjet/apishipNActive.png',
				iconImageSize: [40, 43],
				iconImageOffset: [-10, -31]
			});
			IPOLapiship_pvz.Y_map.geoObjects.add(IPOLapiship_pvz.PVZ[i].placeMark);
			IPOLapiship_pvz.PVZ[i].placeMark.link = i;
			IPOLapiship_pvz.PVZ[i].placeMark.events.add('balloonopen',function(metka){
				IPOLapiship_pvz.markChosenPVZ(metka.get('target').link);
			});
		}
		IPOLapiship_pvz.Y_markedCities[IPOLapiship_pvz.city]=IPOLapiship_pvz.PVZ;
	},

	Y_selectPVZ: function(wat){
		IPOLapiship_pvz.PVZ[wat].placeMark.balloon.open();
		IPOLapiship_pvz.Y_map.setCenter([IPOLapiship_pvz.PVZ[wat].cY,IPOLapiship_pvz.PVZ[wat].cX]);
	},
	
	Y_blinkPVZ: function(wat,ifOn){
		if(typeof(ifOn)!='undefined' && ifOn)
			IPOLapiship_pvz.PVZ[wat].placeMark.options.set({iconImageHref:"/bitrix/images/ipol.apiship/widjet/apishipActive.png"});
		else
			IPOLapiship_pvz.PVZ[wat].placeMark.options.set({iconImageHref:"/bitrix/images/ipol.apiship/widjet/apishipNActive.png"});
	},
	
	Y_markedCities: {},
	
	isPickUpChecked: false,// признак, что выбран самовывоз
	
	// проверяет выбран ли самовывоз и пишет в флаг isPickUpChecked
	CheckChosenPickUp: function(ajaxAns)
	{
		var label_obj = $('#'+IPOLapiship_pvz.deliveryLink);
		
		if (label_obj.length > 0)
			if (label_obj.attr("checked") == "checked")
				IPOLapiship_pvz.isPickUpChecked = true;
			else
				IPOLapiship_pvz.isPickUpChecked = false;
			
		if (typeof ajaxAns != "undefined")
			if (typeof ajaxAns.order != "undefined")
				if (typeof ajaxAns.order.DELIVERY != "undefined")
				{
					var deliverys = ajaxAns.order.DELIVERY,
						pickUpFinded = false;
					for (var i in deliverys)
						if (deliverys[i]["ID"] == IPOLapiship_pvz.deliveryIDs["pickup"])
							pickUpFinded = true;
						
					if (!pickUpFinded)
						IPOLapiship_pvz.isPickUpChecked = false;
				}
		
	},
	
	ChooseFirstPVZ: function()
	{
		if (IPOLapiship_pvz.pvzId != false)// если выбирали уже пвз, то ставим его
			IPOLapiship_pvz.choozePVZ(IPOLapiship_pvz.pvzId);
		
		else
		{
			var firstPVZ = false;
			for (var i in IPOLapiship_pvz.PVZ)
			{
				firstPVZ = i;
				break;
			}
			if (firstPVZ != false)
				IPOLapiship_pvz.choozePVZ(firstPVZ);
			// иначе случится не может, не будет профиля самовывоз в этом случае
		}	
	},
	
	jquiready: function()
	{
		var orderForm = BX(IPOLapiship_pvz.orderForm);
	
		$(window).resize(function(){IPOLapiship_pvz.resize();});
		
		ymaps.ready(IPOLapiship_pvz.init);
		// ymaps.ready(IPOLapiship_pvz.CheckChosenPickUp);// для определения выбран ли самовывоз
		
		$(orderForm).submit(function(e){
			// нажали на кнопку оформить заказ
			if (BX('confirmorder').value == "Y")
			{
				IPOLapiship_pvz.CheckChosenPickUp();// проверяем выбран ли самовывоз
				if (IPOLapiship_pvz.isPickUpChecked)// выбран самовывоз
				{
					// если не выбрали пвз
					if (IPOLapiship_pvz.pvzId == false)
					{
						IPOLapiship_pvz.ChooseFirstPVZ();
						return true;
					}
				}
				
			}
			return true;
		});
	}
};

IPOL_JSloader.checkScript('',"/bitrix/js/<?=CDeliveryapiship::$MODULE_ID?>/jquery.mousewheel.js");
IPOL_JSloader.checkScript('$("body").jScrollPane',"/bitrix/js/<?=CDeliveryapiship::$MODULE_ID?>/jquery.jscrollpane.js",IPOLapiship_pvz.jquiready);
</script>


<div id='apiship_pvz'>
	<div id='apiship_head'>
		<div id='apiship_description'><img src = '/bitrix/images/ipol.apiship/widjet/logo2.png'></div>
		<div id='apiship_logo'><a href='http://ipolh.com' target='_blank'></a></div>
		<div id='apiship_closer' onclick='IPOLapiship_pvz.close()'></div>
		<div style = "clear: both;"></div>
	</div>
	<div id='apiship_map'></div>
	<div id='apiship_info'>
		<div id='apiship_sign'><?=GetMessage("IPOLapiship_LABELPVZ")?></div>
		<div>
			<div id='apiship_wrapper'></div>
		</div>
		<div id='apiship_ten'></div>
	</div>
</div>