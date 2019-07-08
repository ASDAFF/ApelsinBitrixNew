$(document).ready(function () {
    var CHECKER = false;
    getDuplicates();
    refreshDuplicates();

    function getDuplicates() {
        $('.duplicate_element_title').click(function () {
            var parent = $(this).closest('.duplicate_element');
            if ($(this).find('i').hasClass("fa-plus")) {
                $(this).find('i').removeClass("fa-plus").addClass("fa-minus");
                var code = parent.attr("code");
                var data = [];
                data["templateFolder"] = $('.duplicateWrapper').attr('templatePath');
                data["code"] = code;
                parent.find(".duplicate_element_result").show();
                parent.find(".duplicate_element_result").html("<div class='duplicate_element_result_download'>Загружается</div>");
                BX.ajax({
                    url: data["templateFolder"] + "/ajax/getDuplicates.php",
                    data: data,
                    method: 'POST',
                    dataType: 'html',
                    onsuccess: function (rezult) {
                        parent.find(".duplicate_element_result").html(rezult);
                        deleteDuplicates();
                        checkRow(parent.attr("id"));
                    }
                });
            } else {
                $(this).find('i').removeClass("fa-minus").addClass("fa-plus");
                parent.find(".duplicate_element_result").hide();
                parent.find(".duplicate_element_result").empty();
            }
        });
    }
    
    function deleteDuplicates() {
        $('.duplicate_buttons_delete').click(function () {
            if (CHECKER == false) {
                CHECKER = true;
                var deleteArray = "";
                var data = [];
                $('.duplicate_element_result_element').each(function () {
                    if ($(this).find("input").prop("checked"))  {
                        deleteArray += ''+$(this).attr("id")+',';
                    }
                });
                deleteArray = deleteArray.substring(0,deleteArray.length-1);
                data["templateFolder"] = $('.duplicateWrapper').attr('templatePath');
                data["delete_data"] = deleteArray;
                BX.ajax({
                    url: data["templateFolder"] + "/ajax/deleteDuplicates.php",
                    data: data,
                    method: 'POST',
                    dataType: 'html',
                    onsuccess: function (rezult) {
                        if (rezult == "true") {
                            $.each(deleteArray.split(","),function (i, item) {
                                $("#"+item).remove();
                            });
                        }
                        CHECKER = false;
                    }
                });
            }

        });
    }

    function refreshDuplicates() {
        $(".duplicate_buttons_refresh").click(function () {
            var data = [];
            data["templateFolder"] = $('.duplicateWrapper').attr('templatePath');
            $('.duplicateWrapper').empty().html("<div class='duplicate_element_result_download'>Загружается</div>");
            BX.ajax({
                url: data["templateFolder"] + "/ajax/refreshDuplicates.php",
                data: data,
                method: 'POST',
                dataType: 'html',
                onsuccess: function (rezult) {
                    $('.duplicateWrapper').html(rezult);
                    getDuplicates();
                    refreshDuplicates();
                    deleteDuplicates();
                }
            });
        });
    }
    
    function checkRow(id) {
        $('#'+id+' .duplicate_element_result_element').click(function () {
            if ($(this).find("input").is(':checked')) {
                $(this).find("input").prop('checked', false);
            } else {
                $(this).find("input").prop('checked', true);
            }
        });
    }
});