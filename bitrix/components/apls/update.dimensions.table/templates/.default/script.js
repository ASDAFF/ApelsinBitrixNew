aplsSubstitutionDimensionsProgressBar = true;
aplsSubstitutionDimensionsProgressStop = false;
aplsSubstitutionDimensionsProgressStopStap = 1;

$(document).ready(function () {
    $("#start.APLSProgressBar_Button").click(function () {
        aplsSubstitutionDimensionsProgressBar = true;
        aplsSubstitutionDimensionsProgressStop = false;
        aplsSubstitutionDimensionsProgressStopStap = 1;
        $('.APLSProgressBar .APLSProgressBar_Bar').html("0%");
        APLS_startAjax();
    });
    $("#stop.APLSProgressBar_Button").click(function () {
        if(aplsSubstitutionDimensionsProgressStop) {
            aplsSubstitutionDimensionsProgressStop = false;
            $("#stop.APLSProgressBar_Button").html("Остановить");
            APLS_startAjax();
        } else {
            aplsSubstitutionDimensionsProgressStop = true;
            $('.APLSProgressBar .APLSProgressBar_Bar').html("Выполнение приостановленно");
            $("#stop.APLSProgressBar_Button").html("Продолжить");
        }
    });
});

function APLS_getValues(data) {
    console.log(data);
    BX.ajax({
        url: $(".APLSMainWrapper").attr("scriptFile"),
        data: data,
        method: "POST",
        dataType: "json",
        onsuccess: function (rezult) {
            var complit = Math.round(data["countValue"] / data["maxValue"] * 100) + "%";
            var progressBar = $('.APLSProgressBar .APLSProgressBar_Bar');
            progressBar.css("width", complit);
            if (!!rezult.success) {
                progressBar.html(complit + " | " + data["countValue"] + " из " + data["maxValue"]);
                $('.resultHTML .APLSResultTable_String').html(rezult.success.html)

            } else {
                progressBar.html(rezult.error.html);
            }
            data["countValue"] += 1;
            if (!aplsSubstitutionDimensionsProgressStop && data["countValue"] <= data["maxValue"]) {
                APLS_getValues(data);
            } else {
                if (aplsSubstitutionDimensionsProgressStop) {
                    aplsSubstitutionDimensionsProgressStopStap = data["countValue"];
                } else {
                    aplsSubstitutionDimensionsProgressStopStap = 1;
                }
                aplsSubstitutionDimensionsProgressBar = true;
            }
        }
    });
}

function APLS_startAjax() {
    if(aplsSubstitutionDimensionsProgressBar) {
        aplsSubstitutionDimensionsProgressBar = false;
        aplsSubstitutionDimensionsProgressStop = false;
        var data = [];
        data["countValue"] = aplsSubstitutionDimensionsProgressStopStap;
        data["maxValue"] = $('.APLSProgressBar_Progress').attr("maxSteps");
        data["limit"] = $('.APLSProgressBar_Progress').attr("limit");
        APLS_getValues(data);
    }
}