function APLSFilterPropertiesParams_FilterAjax() {
    var data = [];
    data["searchString"] = $(".APLSFilterPropertiesHeaderSettingsSeachingFormInput").val();
    data["sortBy"] = $("#sortBy").val();
    data["sf"] = $("#sortSF").val();
    data["dp"] = $("#sortDP").val();
    data["cp"] = $("#sortCP").val();
    data["approved"] = $("#sortA").val();
    data["templateFolder"] = $(".APLSFilterProperties").attr("templateFolder");
    BX.ajax({
        url: data["templateFolder"] + "/ajax.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $(".APLSFilterPropertiesContainer").html(rezult);
        },
        onfailure: function (rezult) {
            $(".APLSFilterPropertiesContainer").html("Произошла ошибка");
        },
    });
    var CheckBoxData = [];
    CheckBoxData["XML_ID"] = $(".APLSFilterPropertiesContainerContentBlock").attr("XML_ID")
}

$(document).ready(function () {
    $(".statusBarButton").click(function () {
        APLSFilterPropertiesParams_FilterAjax;
        $(".statusBarButton").removeClass("neadApdate");
        $(".statusBarButton").html("отфильтровано");
    });
    $('.scrollUp').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
    $("#search").keyup(APLSFilterPropertiesParams_FilterAjax);
    $(".APLSFilterPropertiesHeaderSettingsDropdown1").change(APLSFilterPropertiesParams_FilterAjax);
    $(".APLSFilterPropertiesHeaderSettingsDropdown2").change(APLSFilterPropertiesParams_FilterAjax);
});
