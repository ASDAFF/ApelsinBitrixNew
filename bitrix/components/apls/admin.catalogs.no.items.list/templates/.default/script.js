$(document).ready(function () {
    $('.sections-no-image-btn').click(function () {
        var isConfirm = confirm("Вы уверены, что хотите деактивировать все каталоги?");
        if (isConfirm === true) {
            var data = [];
            data["templateFolder"] = $('.sections-no-image-header').attr('templateFolder');
            BX.ajax({
                url: data["templateFolder"] + "/templates/.default/ajax.php",
                method: 'POST',
                dataType: 'html',
                onsuccess: function (rezult) {
                    location.reload();
                }
            });
        }
    });
});

function APLS_copyToClipboardSectionGUID(guid, sectionName) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(guid).select();
    document.execCommand("copy");
    $temp.remove();
    alert("GUID каталога <" + sectionName + "> скопирован в буфер обмена");
}
