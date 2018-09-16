function APLS_copyToClipboardSectionGUID(guid,sectionName) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(guid).select();
    document.execCommand("copy");
    $temp.remove();
    alert("GUID каталога <" + sectionName + "> скопирован в буфер обмена");
}