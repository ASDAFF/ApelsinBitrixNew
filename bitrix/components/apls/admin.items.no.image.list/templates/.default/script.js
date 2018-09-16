function APLS_copyToClipboardItemName(name) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(name).select();
    document.execCommand("copy");
    $temp.remove();
    alert("Название товара скопировано в буфер обмена");
}