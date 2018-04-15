/**
 * Вернет jQuery объект основного Wrapper для размещения контента
 * @returns {jQuery|HTMLElement}
 */
function AdminPromotionsImageWrapper() {
    return $("#AplsAdminWrapper");
}

/**
 * вернет путь к папке шаблона в виде строки
 * @returns путь кпапке шаблона
 */
function AdminPromotionsImageTemplateFolder() {
    return AdminPromotionsImageWrapper().attr("templateFolder");
}

/**
 * вернет путь к папке компоненты в виде строки
 * @returns путь кпапке компоненты
 */
function AdminPromotionsImageComponentFolder() {
    return AdminPromotionsImageWrapper().attr("componentFolder");
}

/*----------------------*/
/* AJAX UI - /ajax/ui/  */
/*----------------------*/

function AdminPromotionsImageUiLoadTypeImages(obj) {
    AdminPromotionsImageUiLoadTypeImagesMain($(obj).attr("tabKey"), $(obj).attr("contentId"));
}

function AdminPromotionsImageUiLoadTypeImagesMain(typeId, contentId) {
    var data = [];
    data["templateFolder"] = AdminPromotionsImageTemplateFolder();
    data["componentFolder"] = AdminPromotionsImageComponentFolder();
    data["typeId"] = typeId;
    data["contentId"] = contentId;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/LoadTypeImages.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            $("#"+contentId).html(result);
            $("#"+contentId+" #PromotionImageFile").change(AdminPromotionsImageActionUploadImage);
            $("#"+contentId+" .DeleteButton").click(AdminPromotionsImageActionDeleteImage);
        },
        onfailure: function () {
            alert("Ошибка: AdminPromotionsImageUiLoadTypeAdmin()");
        },
    });
}

function AdminPromotionsImageActionUploadImage() {
    var file_data = $(this).prop('files')[0];
    var typeId = $(this).attr('typeId');
    var contentId = $(this).attr('contentId');
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('typeId', typeId);
    $.ajax({
        url: AdminPromotionsImageTemplateFolder() + "/ajax/action/UploadImage.php",
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(result){
            AdminPromotionsImageUiLoadTypeImagesMain(typeId, contentId);
        }
    });
}

function AdminPromotionsImageActionDeleteImage() {
    var typeId = $(this).attr('typeId');
    var contentId = $(this).attr('contentId');
    var data = [];
    data["templateFolder"] = AdminPromotionsImageTemplateFolder();
    data["imageId"] = $(this).attr('imageId');
    var confirmVal = confirm("Вы уверены что хотите удалить эту картинку?");
    if (confirmVal === true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action/DeleteImage.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (result) {
                AdminPromotionsImageUiLoadTypeImagesMain(typeId, contentId);
            },
            onfailure: function () {
                alert("Ошибка: AdminPromotionsImageActionDeleteImage()");
            },
        });
    }
}