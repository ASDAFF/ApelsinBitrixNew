/**
 * Вернет jQuery объект основного Wrapper для размещения контента
 * @returns {jQuery|HTMLElement}
 */
function AdminPromotionsImageTypeWrapper() {
    return $("#AplsAdminWrapper");
}

/**
 * вернет путь к папке шаблона в виде строки
 * @returns путь кпапке шаблона
 */
function AdminPromotionsImageTypeTemplateFolder() {
    return AdminPromotionsImageTypeWrapper().attr("templateFolder");
}

/**
 * вернет путь к папке компоненты в виде строки
 * @returns путь кпапке компоненты
 */
function AdminPromotionsImageTypeComponentFolder() {
    return AdminPromotionsImageTypeWrapper().attr("componentFolder");
}

function AdminPromotionsImageTypeShow() {
    var data = [];
    data["templateFolder"] = AdminPromotionsImageTypeTemplateFolder();
    data["componentFolder"] = AdminPromotionsImageTypeComponentFolder();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/Show.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            AdminPromotionsImageTypeWrapper().html(rezult);
            AdminPromotionsImageTypeGetList();
            $("#ImageTypeAdd").click(AdminPromotionsImageTypeAdd);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsUiShowMain()");
        },
    });
}
function AdminPromotionsImageTypeGetList() {
    var data = [];
    data["templateFolder"] = AdminPromotionsImageTypeTemplateFolder();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/GetList.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $("#ImageTypeList").html(rezult);
            $("#ImageTypeList .DellButton").click(AdminPromotionsImageTypeDelete);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsImageTypeGetList()");
        },
    });
}

function AdminPromotionsImageTypeAdd() {
    var data = [];
    data["templateFolder"] = AdminPromotionsImageTypeTemplateFolder();
    data["name"] = $("#ImageTypeNameInput").val();
    data["alias"] = $("#ImageTypeAliasInput").val()
    BX.ajax({
        url: data["templateFolder"] + "/ajax/Add.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            if(rezult!=="error") {
                alert("Добавлен новый тип изображений для акций");
                $("#ImageTypeNameInput").val("");
                $("#ImageTypeAliasInput").val("");
                AdminPromotionsImageTypeGetList();
            } else {
                alert("Новый тип не был создан, возможно вы не заполнили обязательные поля или такой тип уже существует.");
            }
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsImageTypeAdd()");
        },
    });
}

function AdminPromotionsImageTypeDelete() {
    var data = [];
    data["templateFolder"] = AdminPromotionsImageTypeTemplateFolder();
    data["typeId"] = $(this).attr('typeId');
    var confirmVal = confirm("Вы уверены что хотите удалить этот тип картино?\n\nВНИМАНИЕ:\nбудут безвозвратно удалены все картинки этого типа");
    if (confirmVal === true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/Delete.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (result) {
                alert("Выбранный тип и все связанные с ним изображения были удалены");
                AdminPromotionsImageTypeGetList();
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}


$(document).ready(function () {
    AdminPromotionsImageTypeShow();
});