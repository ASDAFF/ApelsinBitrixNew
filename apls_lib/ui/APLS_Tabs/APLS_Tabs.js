$(document).ready(function () {
    $(".apls-tabs-wrapper .apls-tabs-name-area .apls-tab-name").click(function () {
        var tabId = $(this).attr("tabId");
        var tabsWrapperId = $(this).attr("tabsWrapperId");
        $("#" + tabsWrapperId + " .apls-tab-name").removeClass("open-tab");
        $("#" + tabsWrapperId + " #" + tabId + "-NAME").addClass("open-tab");
        $("#" + tabsWrapperId + " .apls-tab-content").hide();
        $("#" + tabsWrapperId + " #" + tabId + "-CONTENT").show();
    });
    $(".apls-tabs-wrapper .apls-tab-content").hide();
    $(".apls-tabs-wrapper .apls-tab-content:first-child").show();
    $(".apls-tabs-wrapper .apls-tab-name:first-child").addClass("open-tab");
});