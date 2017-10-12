<?
/*
	Дефолтный шаблон для вывода карты на пустой странице
*/
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.CDeliveryapiship::$MODULE_ID.'/jsloader.php');
if(coption::GetOptionString(CDeliveryapiship::$MODULE_ID,'addJQ','N')=='Y')
	CJSCore::init('jquery');
global $APPLICATION;
CModule::IncludeModule(CDeliveryapiship::$MODULE_ID);
if($arParams['NOMAPS']!='Y')
{
	$prot = (strpos(CDeliveryapiship::toUpper($_SERVER['SERVER_PROTOCOL']),'HTTPS')!==false || strpos(CDeliveryapiship::toUpper($_SERVER['HTTP_X_FORWARDED_PROTO']),'HTTPS')!==false || $_SERVER['HTTPS'] == 'on')?'https':'http';
	global $APPLICATION;
	$APPLICATION->AddHeadString('<script src="'.$prot.'://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>');
}
/*$APPLICATION->AddHeadString('<script src="/bitrix/js/'.CDeliveryapiship::$MODULE_ID.'/jquery.mousewheel.js" type="text/javascript"></script>');
$APPLICATION->AddHeadString('<script src="/bitrix/js/'.CDeliveryapiship::$MODULE_ID.'/jquery.jscrollpane.js" type="text/javascript"></script>');*/
$APPLICATION->AddHeadString('<link href="/bitrix/js/'.CDeliveryapiship::$MODULE_ID.'/jquery.jscrollpane.css" type="text/css"  rel="stylesheet" />');

$CityInput = "IPOLAPISHIP_CITY_INPUT";

$TemplateFolder = $this->GetFolder();

?>
<script>
    // находит первого попавшегося родителя со свойством position: relative
    function findRelativeParent(obj)
    {
        if (obj.is("body"))
            return false;
        if (obj.css("position") == "relative")
            return obj;
        else
            return findRelativeParent(obj.parent());
    }

    var IPOLapiship_pvz = {
        city: '<?=$arResult['cityName']?>',//город

        pvzInputs: [<?=substr($arResult['propAddr'],0,-1)?>],//инпуты, куда грузится адрес пвз

        pvzLabel: "",
        presizion: 2,

        PVZ: {},
        DefaultVals:{},
        LoadFromAJAX: false,
        LoadInputsFromAJAX: false,

        image_url: "https://storage.apiship.ru/icons/providers/",
        arImages: {
            "b2cpl": "b2cpl-30px.png",
            "box2box" : "box2box-30px.png",
            "boxberry" : "boxberry-30px.png",
            "cdek" : "cdek-30px.png",
            "cse" : "cse-30px.png",
            "dpd" : "dpd-30px.png",
            "hermes" : "hermes-30px.png",
            "maxi" : "maxi-30px.png",
            "pickpoint" : "pickpoint-30px.png",
            "pony" : "pony-30px.png",
            "spsr" : "spsr-30px.png",
            "iml" : "iml-30px.png",
            "shoplogistic" : "shoplogistic-30px.png",

        },

        GetDefaultVals: function()
        {
            IPOLapiship_pvz.DefaultVals = <?=CUtil::PHPToJSObject($arResult["defaultVals"])?>;
        },
        GetPVZ: function()
        {
            IPOLapiship_pvz.PVZ = <?=CUtil::PHPToJSObject($arResult["PVZ"])?>;
        },

        GetAjaxPVZ: function()
        {
            if ($("#ipolapiship_pvz_list_tag_ajax").length > 0)
                IPOLapiship_pvz.PVZ = JSON.parse($("#ipolapiship_pvz_list_tag_ajax").html());

            // сбрасываем выбранный ПВЗ, если такого теперь нет, например город поменяли
            if (typeof IPOLapiship_pvz.PVZ[IPOLapiship_pvz.pvzId] == "undefined")
            {
                IPOLapiship_pvz.pvzId = false;
                IPOLapiship_pvz.chosenPVZProviderKey = false;
                IPOLapiship_pvz.chosenTariffID = false;

                IPOLapiship_pvz.UpdateChosenInputs();
            }
        },

        GetAjaxDefaultVals: function()
        {
            if ($("#ipolapiship_default_vals_tag_ajax").length > 0)
                IPOLapiship_pvz.DefaultVals = JSON.parse($("#ipolapiship_default_vals_tag_ajax").html());
        },

        init: function()
        {
            IPOLapiship_pvz.Y_init();
            IPOLapiship_pvz.onLoad();
        },

        onLoad: function()
        {
            // первый раз берем данные из компонента, далее из полей, которые приходят из буферконтент в аякс ответах в html
            if (!IPOLapiship_pvz.LoadFromAJAX)
            {
                IPOLapiship_pvz.GetPVZ();
                IPOLapiship_pvz.GetDefaultVals();
                IPOLapiship_pvz.LoadFromAJAX = true;
            }

            IPOLapiship_pvz.initCityPVZ();

        },

        selectPVZ: function()
        {
            if(!IPOLapiship_pvz.isActive){
                IPOLapiship_pvz.isActive = true;

                var hndlr = $('#apiship_pvz');

                // находим родителя со свойством position: relative и считаем поправку от его положения
                var parent = findRelativeParent(hndlr);
                // считаем поправку
                var shiftWidth = 0, shiftHeight = 0;
                if (typeof parent[0] == "object")
                {
                    shiftWidth = parent.offset().left;
                    shiftHeight = parent.offset().top;

                }

                hndlr.css({
                    'display'   : 'block',
                    'left'      : (($(window).width()-hndlr.width())/2) - shiftWidth,
                });
                hndlr.css({
                    'top'       : ($(window).height()-hndlr.height())/2+$(document).scrollTop() - shiftHeight,
                });

                $('#apiship_mask').css('display','block');

                IPOLapiship_pvz.initCityPVZ();

                IPOLapiship_pvz.Y_init();
            }
        },

        initCityPVZ: function(){ // грузим пункты самовывоза для выбранного города
            var city = IPOLapiship_pvz.city;
            var cnt = [];
            IPOLapiship_pvz.cityPVZ = IPOLapiship_pvz.PVZ;

            IPOLapiship_pvz.cityPVZHTML();//грузим html PVZ. Два раза пробегаем по массиву, но не критично.

            IPOLapiship_pvz.multiPVZ = (IPOLapiship_pvz.PVZ.length == 1)? false:true;
        },

        cityPVZHTML: function(){ // заполняем список ПВЗ города

            var arHTML = {};

            for(var i in IPOLapiship_pvz.cityPVZ)
            {
                if (typeof arHTML[IPOLapiship_pvz.cityPVZ[i].providerKey] == "undefined")
                {
                    arHTML[IPOLapiship_pvz.cityPVZ[i].providerKey] = {
                        "head": "",
                        "content": ""
                    };
                }

                if (!arHTML[IPOLapiship_pvz.cityPVZ[i].providerKey].head)
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

                    if (typeof IPOLapiship_pvz.arImages[IPOLapiship_pvz.cityPVZ[i].providerKey] != "undefined")
                        head_html += "<img class = 'apiship_provider_img' src = '"+ IPOLapiship_pvz.image_url + IPOLapiship_pvz.arImages[IPOLapiship_pvz.cityPVZ[i].providerKey] +"'>";
                    else
                        head_html += IPOLapiship_pvz.cityPVZ[i].providerKey;

                    head_html += "<?=GetMessage("IPOLapiship_DELIVERY")?><span>"+ IPOLapiship_pvz.cityPVZ[i].deliveryCost +"</span>"+"<?=GetMessage("IPOLapiship_CURRENCY_RUB")?>";
                    head_html += "<?=GetMessage("IPOLapiship_SROK_DOSTAVKI")?><span>" + days +"</span>"
                    head_html += "</div>";

                    arHTML[IPOLapiship_pvz.cityPVZ[i].providerKey].head = head_html;
                }

                arHTML[IPOLapiship_pvz.cityPVZ[i].providerKey].content += '<p id="PVZ_'+i+'" onclick="IPOLapiship_pvz.markChosenPVZ(\''+i+'\')" onmouseover="IPOLapiship_pvz.Y_blinkPVZ(\''+i+'\',true)" onmouseout="IPOLapiship_pvz.Y_blinkPVZ(\''+i+'\')">'+IPOLapiship_pvz.paintPVZ(i)+'</p>';
            }

            var html = '';
            for (var i in arHTML)
            {
                html += arHTML[i].head;
                html += arHTML[i].content;
            }

            IPOLapiship_pvz.testHTML = html;
            $('#apiship_wrapper').remove();
            $('#apiship_wrapper_block').html("<div id='apiship_wrapper'></div>");

            $('#apiship_wrapper').html(html);
            IPOLapiship_pvz.scrollPVZ=$('#apiship_wrapper').jScrollPane();
        },

        paintPVZ: function(ind){ //красим адресс пвз, если задан цвет
            var addr = '';
            if(IPOLapiship_pvz.cityPVZ[ind].color && IPOLapiship_pvz.cityPVZ[ind].Address.indexOf(',')!==false)
                addr="<span style='color:"+IPOLapiship_pvz.cityPVZ[ind].color+"'>"+IPOLapiship_pvz.cityPVZ[ind].Address.substr(0,IPOLapiship_pvz.cityPVZ[ind].Address.indexOf(','))+"</span><br>"+IPOLapiship_pvz.cityPVZ[ind].Name;
            else
                addr=IPOLapiship_pvz.cityPVZ[ind].Name;
            return addr;
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
                    IPOLapiship_pvz.cityPVZ = IPOLapiship_pvz.Y_markedCities[IPOLapiship_pvz.city];
            });
        },

        Y_markPVZ: function(){
            for(var i in IPOLapiship_pvz.cityPVZ){
                var baloonHTML = "";

                // логтип доставщика
                if (typeof IPOLapiship_pvz.arImages[IPOLapiship_pvz.cityPVZ[i].providerKey] != "undefined")
                    baloonHTML += "<img class = 'apiship_provider_baloon_img' src = '"+ IPOLapiship_pvz.image_url + IPOLapiship_pvz.arImages[IPOLapiship_pvz.cityPVZ[i].providerKey] +"'>";
                else
                    baloonHTML += "<div class = 'apiship_provider_baloon_img'>" + IPOLapiship_pvz.cityPVZ[i].providerKey + "</div>";

                baloonHTML += "<div id='apiship_baloon'>";
                baloonHTML += "<div class='apiship_iAdress'>";

                if(IPOLapiship_pvz.cityPVZ[i].color)
                    baloonHTML +=  "<span style='color:"+IPOLapiship_pvz.cityPVZ[i].color+"'>"

                baloonHTML += IPOLapiship_pvz.cityPVZ[i].Address;

                if(IPOLapiship_pvz.cityPVZ[i].color)
                    baloonHTML += "</span>";

                baloonHTML += "</div>";

                if(IPOLapiship_pvz.cityPVZ[i].Phone)
                    baloonHTML += "<div><div class='apiship_iTelephone apiship_icon'></div><div class='apiship_baloonDiv'>"+IPOLapiship_pvz.cityPVZ[i].Phone+"</div><div style='clear:both'></div></div>";
                if(IPOLapiship_pvz.cityPVZ[i].WorkTime)
                    baloonHTML += "<div><div class='apiship_iTime apiship_icon'></div><div class='apiship_baloonDiv'>"+IPOLapiship_pvz.cityPVZ[i].WorkTime+"</div><div style='clear:both'></div></div>";

                baloonHTML += "<div><div class='apiship_baloonDiv'><?=GetMessage("IPOLapiship_BALOON_DELIVERY_COST")?>"+IPOLapiship_pvz.cityPVZ[i].deliveryCost+"<?=GetMessage("IPOLapiship_CURRENCY_RUB")?></div><div style='clear:both'></div></div>";

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

                baloonHTML += "</div>";
                IPOLapiship_pvz.cityPVZ[i].placeMark = new ymaps.Placemark([IPOLapiship_pvz.cityPVZ[i].cY,IPOLapiship_pvz.cityPVZ[i].cX],{
                    balloonContent: baloonHTML
                }, {
                    iconLayout: 'default#image',
                    iconImageHref: '/bitrix/images/ipol.apiship/widjet/apishipNActive.png',
                    iconImageSize: [40, 43],
                    iconImageOffset: [-10, -31]
                });
                IPOLapiship_pvz.Y_map.geoObjects.add(IPOLapiship_pvz.cityPVZ[i].placeMark);
                IPOLapiship_pvz.cityPVZ[i].placeMark.link = i;
                IPOLapiship_pvz.cityPVZ[i].placeMark.events.add('balloonopen',function(metka){
                    IPOLapiship_pvz.markChosenPVZ(metka.get('target').link);
                });
            }
            IPOLapiship_pvz.Y_markedCities[IPOLapiship_pvz.city]=IPOLapiship_pvz.cityPVZ;
        },

        Y_selectPVZ: function(wat){
            IPOLapiship_pvz.cityPVZ[wat].placeMark.balloon.open();
            IPOLapiship_pvz.Y_map.setCenter([IPOLapiship_pvz.cityPVZ[wat].cY,IPOLapiship_pvz.cityPVZ[wat].cX]);
        },

        Y_blinkPVZ: function(wat,ifOn){
            if(typeof(ifOn)!='undefined' && ifOn)
                IPOLapiship_pvz.cityPVZ[wat].placeMark.options.set({iconImageHref:"/bitrix/images/ipol.apiship/widjet/apishipActive.png"});
            else
                IPOLapiship_pvz.cityPVZ[wat].placeMark.options.set({iconImageHref:"/bitrix/images/ipol.apiship/widjet/apishipNActive.png"});
        },

        showCitySel: function(){
            $("#apiship_citySel").show();
            $("#apiship_cityName").hide();

        },

        hideCitySel: function(){
            $("#apiship_citySel").hide();
            $("#apiship_cityName").show();

        },

        Y_markedCities: {},

        cityChange: function(){
            console.log("submit");
            $("#IPOLAPISHIP_FORM").submit();
        }
    };

    $(document).ready(function(){
        ymaps.ready(
            function(){
                IPOLapiship_pvz.init();
            }
        );

        $("#IPOLAPISHIP_FORM").on("submit", function(e){
            e.preventDefault();
            var cityNewID = $(e.currentTarget).find("#<?=$CityInput?>").val();
            var dataAJAX;
            $.when(
                $.ajax({
                    url:"<?=$TemplateFolder?>/ajax.php",

                    data:{"IPOLAPISHIP_CITY_AJAX_NEW_ID": cityNewID},
                    type:"POST",
                    dataType: "json",
                    error: function(XMLHttpRequest, textStatus){
                        console.log(XMLHttpRequest.responseText);
                        console.log(textStatus);
                    },
                    success:function(data){
                        dataAJAX = data;
                    }
                })).done(function(){
                // заменяем на введенный город
                if (dataAJAX.cityName == "")
                    dataAJAX.cityName = "<?=getMessage("IPOLapiship_CITY_NOT_FOUND")?>";
                $("#apiship_cityName").html(dataAJAX.cityName);
                IPOLapiship_pvz.hideCitySel();

                // апдейтим карту
                IPOLapiship_pvz.city = dataAJAX.cityName;
                IPOLapiship_pvz.PVZ = dataAJAX.PVZ;

                IPOLapiship_pvz.init();

                // апдейтим курьера
                // console.log(dataAJAX);
                var courier = dataAJAX.bestsTariffs.deliveryToDoorShown,
                    cost = 0,
                    days = 0;
                for(var i in courier)
                {
                    cost = courier[i].deliveryCost;
                    if (courier[i].daysMin == courier[i].daysMax)
                        days = courier[i].daysMin;
                    else
                        days = courier[i].daysMin + " - " + courier[i].daysMax;
                }
                $("#apiship_cPrice").html(cost + "<?=GetMessage("IPOLapiship_CURRENSY_RUB")?>");
                $("#apiship_cDate").html(days + "<?=GetMessage("IPOLapiship_DAY")?>");
            });
        });
    });
    IPOL_JSloader.checkScript('',"/bitrix/js/<?=CDeliveryapiship::$MODULE_ID?>/jquery.mousewheel.js");
    IPOL_JSloader.checkScript('$("body").jScrollPane',"/bitrix/js/<?=CDeliveryapiship::$MODULE_ID?>/jquery.jscrollpane.js",IPOLapiship_pvz.jquiready);
</script>

<div id='apiship_pvz'>
    <form action = "" method = "post" id = "IPOLAPISHIP_FORM">
		<??>
        <div id='apiship_title'>
			<?if ($arParams["SHOW_CITY_INPUT"] == "Y")
			{
				?>
                <div id='apiship_cityPicker'>
                    <div><?=GetMessage("IPOLapiship_YOURCITY")?></div>
                    <div>
                        <div id='apiship_cityLabel'>
                            <a id='apiship_cityName' onClick = "IPOLapiship_pvz.showCitySel();" href='javascript:void(0)'><?=empty($arResult['cityName'])?getMessge("IPOLapiship_CITY_NOT_FOUND"):$arResult['cityName']?></a>
                            <div id='apiship_citySel'>
								<?$GLOBALS["APPLICATION"]->IncludeComponent(
									"bitrix:sale.ajax.locations",
									"popup",
									array(
										"AJAX_CALL" => "N",
										"COUNTRY_INPUT_NAME" => "COUNTRY",
										"ALLOW_EMPTY_CITY"=>"N",
										"REGION_INPUT_NAME" => "REGION",
										"CITY_INPUT_NAME" => $CityInput,
										"CITY_OUT_LOCATION" => "Y",
										"ONCITYCHANGE" => "IPOLapiship_pvz.cityChange();"
									),
									null,
									array('HIDE_ICONS' => 'Y')
								);
								?>
                            </div>
                        </div>
                    </div>
                </div>
			<?}?>
			
			<?if (($arParams["SHOW_COURIER"] == "Y") && (!empty($arResult["bestsTariffs"]["deliveryToDoorShown"]))){?>
				<?
				$courier = $arResult["bestsTariffs"]["deliveryToDoorShown"];
				
				foreach ($courier as $provider => $tariff)
					$courier = $tariff;
				
				if ($tariff["daysMin"] == $tariff["daysMax"])
					$days = $tariff["daysMin"];
				else
					$days = $tariff["daysMin"]. " - ". $tariff["daysMax"];
				?>
                <div class='apiship_mark'>
                    <table>
                        <tr><td><strong><?=GetMessage("IPOLapiship_COURIER")?></strong></td><td><span id='apiship_cPrice'><?=$tariff["deliveryCost"].GetMessage("IPOLapiship_CURRENSY_RUB")?></span></td></tr>
                        <tr title='<?=GetMessage("IPOLapiship_HINT")?>'><td><?="<strong>".GetMessage("IPOLapiship_DELTERM")."</strong>"."</td><td id='apiship_cDate'>".$days.GetMessage("IPOLapiship_DAY")?></td></tr>
                    </table>
                </div>
			<?}?>
            <div style='float:none;clear:both'></div>
        </div>
		<??>
        <div id='apiship_map'></div>
        <div id='apiship_info'>
            <div id='apiship_sign'><?=GetMessage("IPOLapiship_LABELPVZ")?></div>
            <div id='apiship_delivInfo'></div>
            <div id = 'apiship_wrapper_block'>
                <div id='apiship_wrapper'></div>
            </div>
            <div id='apiship_ten'></div>
        </div>
        <div id='apiship_head'>
            <div id='apiship_logo'><a href='http://ipolh.com' target='_blank'></a></div>
        </div>
    </form>
</div>