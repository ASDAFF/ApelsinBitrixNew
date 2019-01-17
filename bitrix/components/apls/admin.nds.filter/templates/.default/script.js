$(document).ready(function () {
    $(".headerBlock_btn").click(function () {
        var data = [];
        data["templateFolder"] = $('.mainWrapper').attr("templateFolder");
        data["ndsId"] = $(".nds_list").val();
        data["ndsVal"] = $(".nds_list option:selected").text();
        BX.ajax({
            url: data["templateFolder"] + "/ajax.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                $(".resultWrapper").html(rezult);
            }
        });
    });
});