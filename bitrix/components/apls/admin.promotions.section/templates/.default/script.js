function AdminPromotionsSectionListShow() {
    var data = [];
    data["templateFolder"] = $(".PromotionSectionsWrapper").attr("templateFolder");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/showList.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $(".PromotionSectionsWrapper").html(rezult);
            aplsSortListAddSelectableAndSortable("AdminPromotionsSectionStopSort");
            $('.PromotionSectionsList .buttonPanel .delButton').click(AdminPromotionsSectionDelete);
            $('.PromotionSectionsList .buttonPanel .editButton').click(AdminPromotionsSectionEdit);
            $('.PromotionSectionsList .buttonPanel .changeAliasButton').click(AdminPromotionsSectionAliasEdit);
            $('.PromotionSectionsListButtonPanel .NewSectionAdd').click(AdminPromotionsSectionAdd);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsSectionListShow()");
        },
    });
}

function AdminPromotionsSectionDelete() {
    var data = [];
    data["templateFolder"] = $(".PromotionSectionsWrapper").attr("templateFolder");
    data["sectionId"] = $(this).attr('sectionId');
    var confirmVal = confirm("Вы уверены что хотите удалить эту секцию?");
    if (confirmVal == true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/delete.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                AdminPromotionsSectionListShow();
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsSectionEdit() {
    var data = [];
    data["templateFolder"] = $(".PromotionSectionsWrapper").attr("templateFolder");
    data["sectionId"] = $(this).attr('sectionId');
    var oldName = $(this).attr('sectionName');
    var newName = prompt('Как будет называться эта секция?', oldName);
    if(newName != null && newName != "") {
        data["newName"] = newName;
        BX.ajax({
            url: data["templateFolder"] + "/ajax/edit.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                AdminPromotionsSectionListShow();
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsSectionAliasEdit() {
    var data = [];
    data["templateFolder"] = $(".PromotionSectionsWrapper").attr("templateFolder");
    data["sectionId"] = $(this).attr('sectionId');
    var oldAlias = $(this).attr('alias');
    var newAlias = prompt('Как алиас будет у этой секции?', oldAlias);
    if(newAlias != null && newAlias != "") {
        data["newAlias"] = newAlias;
        BX.ajax({
            url: data["templateFolder"] + "/ajax/editAlias.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                AdminPromotionsSectionListShow();
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsSectionStopSort() {
    var sections = [];
    var data = [];
    $(".PromotionSectionsList .apls-sort-list-content .apls-sort-list-element").each(function (i,elem) {
        sections[i] = $(this).attr("sectionId");
    });
    data["templateFolder"] = $(".PromotionSectionsWrapper").attr("templateFolder");
    data["sections"] = sections;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/sort.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $('.PromotionSectionsList .buttonPanel .delButton').click(AdminPromotionsSectionDelete);
            $('.PromotionSectionsList .buttonPanel .editButton').click(AdminPromotionsSectionEdit);
            $('.PromotionSectionsList .buttonPanel .changeAliasButton').click(AdminPromotionsSectionAliasEdit);
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsSectionAdd() {
    var data = [];
    data["templateFolder"] = $(".PromotionSectionsWrapper").attr("templateFolder");
    data["sectionName"] = $(".PromotionSectionsListButtonPanel .NewSectionName input").val();
    data["sectionAlias"] = $(".PromotionSectionsListButtonPanel .NewSectionAlias input").val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/add.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            AdminPromotionsSectionListShow();
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}



$(document).ready(function () {
    AdminPromotionsSectionListShow();
});