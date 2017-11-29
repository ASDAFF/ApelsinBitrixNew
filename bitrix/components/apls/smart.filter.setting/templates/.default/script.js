function APLS_SmartFilterSettingAjax(data, xmlid) {
    data["templateFolder"] = $(".SmartFilterSettingWrapper").attr("templateFolder");
    data["xmlid"] = xmlid;
    $(".SectionsMenuBlock").removeClass("ThisSection");
    $(".BLOCK_" + xmlid).addClass("ThisSection");
    BX.ajax({
        url: data["templateFolder"] + "/ajax.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $(".SmartFilterSettingMainWrapper .SmartFilterSettingPropertiesWrapper").html(rezult);
            aplsTabsAddClickEvent();
            aplsSortListAddSelectableAndSortable();
        },
        onfailure: function (rezult) {
            $(".SmartFilterSettingMainWrapper .SmartFilterSettingPropertiesWrapper").html("<div class='SmartFilterSettingNoData'>Произошла ошибка получения данных</div>");
        },
    });
}

$(document).ready(function () {
    $(".SmartFilterSettingWrapper .SmartFilterSettingButtonPanel").hide();

    $(".SmartFilterSettingWrapper .SmartFilterSettingSaveButton").click(function () {
        var data = [];
        var show = [];
        var hide = [];
        data["view"] = "yes";
        data["edit"] = "save";
        $(".apls-sort-list.show .apls-sort-list-content .apls-sort-list-element").each(function (i,elem) {
            show[i] = $(this).attr("prop_xmlid");
        });
        $(".apls-sort-list.hide .apls-sort-list-content .apls-sort-list-element").each(function (i,elem) {
            hide[i] = $(this).attr("prop_xmlid");
        });
        data["show"] = show;
        data["hide"] = hide;
        APLS_SmartFilterSettingAjax(data, $(this).attr("xmlid"));
    });

    $(".SmartFilterSettingWrapper .SmartFilterSettingRefreshButton").click(function () {
        var data = [];
        data["view"] = "yes";
        APLS_SmartFilterSettingAjax(data, $(this).attr("xmlid"));
    });

    $(".SmartFilterSettingWrapper .SmartFilterSettingDefaultButton").click(function () {
        var r = confirm("ВНИМАНИЕ\nДанная операция необратима.\n\nВы увереня что хотите сбросить настройки умного фильтра для этого каталога до настроек по умолчанию?");
        if (r === true) {
            var data = [];
            data["view"] = "yes";
            data["edit"] = "default";
            var xmlid = $(this).attr("xmlid");
            APLS_SmartFilterSettingAjax(data, xmlid);
        } else {
            alert("Операция отменена");
        }
    });

    $("#SmartFilterSettingSectionsTree .SectionsMenuName").click(function () {
        var data = [];
        data["view"] = "yes";
        var xmlid = $(this).attr("xmlid");
        APLS_SmartFilterSettingAjax(data, xmlid);
        $(".SmartFilterSettingWrapper .SmartFilterSettingSaveButton").attr("xmlid", xmlid);
        $(".SmartFilterSettingWrapper .SmartFilterSettingRefreshButton").attr("xmlid", xmlid);
        $(".SmartFilterSettingWrapper .SmartFilterSettingDefaultButton").attr("xmlid", xmlid);
        $(".SmartFilterSettingWrapper .SmartFilterSettingButtonPanel").show();
    });
});