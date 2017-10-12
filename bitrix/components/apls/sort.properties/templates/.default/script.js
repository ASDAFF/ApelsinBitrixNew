var APLS_LOC_AJX_APDATE_PROPERTIS = false;

function APLS_ajax_load_properties() {
    if(APLS_LOC_AJX_APDATE_PROPERTIS) {
        alert("Сохранение еще идет");
        return;
    } else {
        APLS_LOC_AJX_APDATE_PROPERTIS = true;
    }
    var data = [];
    $("#APLSSortPropertiesListBefore .APLSSortableElement").each(function( index ) {
        var element = [];
        element["sortXML_ID"] = $(this).attr("sortXML_ID");
        element["sortId"] = $(this).attr("sortId");
        element["nameProperty"] = $(this).attr("nameProperty");
        element["IDProperty"] = $(this).attr("IDProperty");
        data[element["sortXML_ID"]] = element;
    });
    $("#APLSSortPropertiesListAfter .APLSSortableElement").each(function( index ) {
        var element = [];
        element["sortXML_ID"] = $(this).attr("sortXML_ID");
        element["sortId"] = $(this).attr("sortId");
        element["nameProperty"] = $(this).attr("nameProperty");
        element["IDProperty"] = $(this).attr("IDProperty");
        data[element["sortXML_ID"]] = element;
    });
    $('.resultHTML').html("Начали сохранять");
    BX.ajax({
        url: $(".APLSSortPropertiesWrapper").attr("sortScriptFile"),
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function(rezult){
            $('.resultHTML').html(rezult);
            $('.statusBarText').html("Сохранено");
            $('.statusBarText').removeClass("noSave");
            $('.statusBar').addClass("savedBar");
            $('.statusBarText').addClass("saved");
            $('.statusBarButton').hide();
        },
        onfailure: function(rezult){
            $('.statusBarText').html("ОШИБКА сохранения");
            $('.statusBarText').removeClass("noSave");
            $('.statusBarText').addClass("failed");
        },
    });
    APLS_LOC_AJX_APDATE_PROPERTIS = false;
}

$(document).ready(function () {

    $('.APLSSortPropertiesList').selectable({
        cancel: '.sort-handle',
        items: ">div",
    }).sortable({
        connectWith: ".APLSSortPropertiesList",
        handle: 'div, .sort-handle, .ui-selected',
        cancel: ">div",
        helper: function (e, item) {
            if (!item.hasClass('ui-selected')) {
                item.parent().children('.ui-selected').removeClass('ui-selected');
                item.addClass('ui-selected');
            }
            var selected = item.parent().children('.ui-selected').clone();
            ph = item.outerHeight() * selected.length;
            item.data('multidrag', selected).siblings('.ui-selected').remove();
            return $('<div/>').append(selected);
        },
        start: function (e, ui) {
            ui.placeholder.css({'height': ph});
        },
        stop: function (e, ui) {
            var selected = ui.item.data('multidrag');
            ui.item.after(selected);
            ui.item.remove();
        },
    });

    $("#APLSSortPropertiesListBefore").bind("DOMSubtreeModified",function(){
        APLSSortPropertiesListCounter();
    });

    $("#APLSSortPropertiesListAfter").bind("DOMSubtreeModified",function(){
        APLSSortPropertiesListCounter();
    });

    $("#APLSSortPropertiesListAll").bind("DOMSubtreeModified",function(){
        $("#APLSSortPropertiesListAll .APLSSortableElement").each(function () {
            $(this).attr("sortId", 3000);
        });
        $('.statusBar>.statusBarButton').show();
        $('.statusBar>.statusBarButton').css('display', 'inline-block');
        $('.statusBarText').removeClass("saved");
        $('.statusBar').removeClass("savedBar");
        $('.statusBar').css("display", "block");
        $('.statusBarText').html("Изменения не сохранены");
        $('.statusBar').addClass("noSaveBar");
        $('.statusBarText').addClass("noSave");
    });

    function APLSSortPropertiesListCounter() {
        $("#APLSSortPropertiesListBefore .APLSSortableElement").each(function( index ) {
            $(this).attr("sortId", index + 1);
        });
        $("#APLSSortPropertiesListAfter .APLSSortableElement").each(function( index ) {
            $(this).attr("sortId", index + 4000);
        });
        $('.statusBar>.statusBarButton').show();
        $('.statusBar>.statusBarButton').css('display', 'inline-block');
        $('.statusBarText').removeClass("saved");
        $('.statusBar').removeClass("savedBar");
        $('.statusBarText').html("Изменения не сохранены");
        $('.statusBar').addClass("noSaveBar");
        $('.statusBarText').addClass("noSave");
    }

    $('.statusBarButton').click(function () {
        APLS_ajax_load_properties();
    });
    $('.scrollUp').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
});