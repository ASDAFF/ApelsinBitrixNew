
/*-------------------*/
/* HELPERS FUNCTIONS */
/*-------------------*/

/**
 * Вернет jQuery объект основного Wrapper для размещения контента
 * @returns {jQuery|HTMLElement}
 */
function AdminPromotionsWrapper() {
    return $("#AplsAdminWrapper.PromotionWrapper .content");
}

/**
 * вернет путь к папке шаблона в виде строки
 * @returns путь кпапке шаблона
 */
function AdminPromotionsTemplateFolder() {
    return $("#AplsAdminWrapper.PromotionWrapper").attr("templateFolder");
}

/**
 * вернет путь к папке компоненты в виде строки
 * @returns путь кпапке компоненты
 */
function AdminPromotionsComponentFolder() {
    return $("#AplsAdminWrapper.PromotionWrapper").attr("componentFolder");
}


/*----------------------*/
/* AJAX UI - /ajax/ui/  */
/*----------------------*/

function AdminPromotionsUiShowMain(promotionId, revisionId) {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowMain.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $("#AplsAdminWrapper .PromotionFilterPanel").show();
            AdminPromotionsWrapper().html(rezult);
            AdminPromotionsUiShowPromotionsListMain(promotionId, revisionId);
            // $('.PromotionListWrapper .ListOfElements .PromotionListElement').click(AdminPromotionsUiShowPromotion);
            // aplsTabsAddClickEvent();
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsUiShowMain()");
        },
    });
}

function AdminPromotionsUiShowPromotionsList() {
    AdminPromotionsUiShowPromotionsListMain();
}

function AdminPromotionsUiShowPromotionsListMain(promotionId, revisionId) {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["FILTER_SEARCH_STRING"] = $("#FILTER_SEARCH_STRING").val();
    data["FILTER_REVISION_TYPE"] = $("#FILTER_REVISION_TYPE").val();
    data["FILTER_SECTION"] = $("#FILTER_SECTION").val();
    data["FILTER_REGION"] = $("#FILTER_REGION").val();
    data["FILTER_SORT_FIELD"] = $("#FILTER_SORT_FIELD").val();
    data["FILTER_SORT_TYPE"] = $("#FILTER_SORT_TYPE").val();
    data["FILTER_PUBLISHED_ON"] = $("#FILTER_PUBLISHED_ON").val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowPromotionsList.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $('.PromotionMainWrapper .PromotionList').html(rezult);
            $('.PromotionMainWrapper .PromotionList .PromotionListElement .ElementBlockContent').click(AdminPromotionsUiShowPromotion);
            if (typeof(promotionId) !== "undefined") {
                AdminPromotionsUiShowPromotionMain(promotionId, revisionId);
            } else {
                $(".PromotionMainWrapper .PromotionShow").html("Выберите акцию для быстрого просмотра");
            }
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsUiShowPromotionsList()");
        },
    });
}

function AdminPromotionsUiShowPromotion() {
    AdminPromotionsUiShowPromotionMain($(this).attr("promotionId"));
}

function AdminPromotionsUiShowPromotionMain(promotionId, revisionId) {
    $('.PromotionMainWrapper .PromotionList .PromotionListElement .ElementBlockContent').removeClass("selected");
    $('.PromotionMainWrapper .PromotionList .ID-'+promotionId+' .ElementBlockContent').addClass("selected");
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["promotionId"] = promotionId;
    // alert(revisionId);
    if (typeof(revisionId) !== "undefined") {
        data["revisionId"] = revisionId;
    }
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowPromotion.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $(".PromotionMainWrapper .PromotionShow").html(rezult);
            $(".PromotionMainWrapper .PromotionShow .promotion-button-panel .add").click(AdminPromotionsActionAddRevision);
            $(".PromotionMainWrapper .PromotionShow .promotion-button-panel .del").click(AdminPromotionsActionDeletePromotion);
            $('.PromotionMainWrapper .PromotionShow .promotion-button-panel .rename').click(AdminPromotionsActionRenamePromotion);
            $(".PromotionMainWrapper .PromotionShow .RevisionsList .ButtonPanel .disable").click(AdminPromotionsActionDisableRevision);
            $(".PromotionMainWrapper .PromotionShow .RevisionsList .ButtonPanel .enable").click(AdminPromotionsActionEnableRevision);
            $(".PromotionMainWrapper .PromotionShow .RevisionsList .ButtonPanel .del").click(AdminPromotionsActionDeleteRevision);
            $(".PromotionMainWrapper .PromotionShow .RevisionsList .ButtonPanel .edit").click(AdminPromotionsUiEditRevision);
            aplsTabsAddClickEvent();
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsUiShowPromotionMain()");
        },
    });
}

function AdminPromotionsUiEditRevision() {
    AdminPromotionsUiEditRevisionMain($(this).attr('revisionId'))
}

function AdminPromotionsUiEditRevisionMain(revisionId) {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["revisionId"] = revisionId;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/EditRevision.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $("#AplsAdminWrapper .PromotionFilterPanel").hide();
            AdminPromotionsWrapper().html(rezult);
            AdminPromotionsActionLoadText("PreviewPromotionTextWrapper");
            AdminPromotionsActionLoadText("MainPromotionTextWrapper");
            AdminPromotionsActionLoadText("VkPromotionTextWrapper");
            AdminPromotionsUiShowCatalogSections();
            AdminPromotionsUiShowCatalogProducts("products","CatalogProductsWrapper");
            AdminPromotionsUiShowCatalogProducts("exceptions","CatalogExceptionsWrapper");
            $(".EditRevisionMainWrapper .DateTime").change(AdminPromotionsActionChangeTime);
            $(".EditRevisionMainWrapper .DateTimeClear").click(AdminPromotionsActionClearTime);
            $(".EditRevisionMainWrapper .PromotionTextSave").click(AdminPromotionsActionSaveText);
            $(".EditRevisionMainWrapper .PromotionTextReset").click(AdminPromotionsActionResetText);
            $(".EditRevisionMainWrapper .SharesPlacement select").change(AdminPromotionsActionChangeBool);
            $(".EditRevisionMainWrapper .BackButton").click(function () {
                var promotionId = $(this).attr('promotionId');
                var revisionId = $(this).attr('revisionId');
                AdminPromotionsUiShowMain(promotionId, revisionId);
            });
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsUiEditRevision()");
        },
    });
}

function AdminPromotionsUiShowCatalogSections() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowCatalogSections.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $(".CatalogSectionsWrapper .content").html(rezult);
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsUiShowCatalogProducts(type,wrapperClass) {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["type"] = type;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowCatalogProducts.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $("."+wrapperClass+" .content").html(rezult);
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

/*------------------------------*/
/* AJAX ACTIONS - /ajax/action/ */
/*------------------------------*/

function AdminPromotionsActionDeletePromotion() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["promotionId"] = $(this).attr('promotionId');
    var confirmVal = confirm("Вы уверены что хотите удалить эту акцию со всеми ее ревизиями?");
    if (confirmVal == true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action/DeletePromotion.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                if(rezult !== "yes") {
                    alert("Не удалось удалить акцию, возможно у вас нет прав.\nОбратитеть к администратору");
                } else {
                    alert("Акция и все ее ревизии были удалены");
                }
                AdminPromotionsUiShowPromotionsList();
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsActionAddPromotion() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    var title = prompt('Как будет называться новая акция?', "");
    if(title !== null && title !== "") {
        data["title"] = title;
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action/AddPromotion.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                if(rezult !== "") {
                    AdminPromotionsUiShowPromotionsListMain(rezult);
                    // AdminPromotionsUiEditPromotionMain(rezult) ;
                } else {
                    alert("Что-то пошло не так. К сожалению не удалось создать новую акцию. Возможно такая акция уже существует");
                }
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsActionRenamePromotion() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["promotionId"] = $(this).attr('promotionId');
    var oldTitle = $(".PromotionMainWrapper .PromotionShow .promotion .title").html();
    var newTitle = prompt('Как будет называться эта акция?', oldTitle);
    if(newTitle !== null && newTitle !== "") {
        data["title"] = newTitle;
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action/RenamePromotion.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                $(".PromotionMainWrapper .PromotionShow .promotion .title").html(rezult);
                $(".PromotionMainWrapper .PromotionList .ID-"+data["promotionId"]+" .content").html(rezult);
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsActionDisableRevision() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(this).attr('revisionId');
    data["disable"] = "1";
    var confirmVal = confirm("Вы уверены что хотите деактевирвоать эту ревизию?");
    if (confirmVal == true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action/EnableOrDisableRevision.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                AdminPromotionsUiShowPromotionMain(
                    $(".PromotionShowWrapper .PromotionShow .promotion").attr("promotionId"),
                    data["revisionId"]
                );
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsActionEnableRevision() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(this).attr('revisionId');
    data["disable"] = "0";
    var confirmVal = confirm("Вы уверены что хотите активировать эту ревизию?");
    if (confirmVal == true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action/EnableOrDisableRevision.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                AdminPromotionsUiShowPromotionMain(
                    $(".PromotionShowWrapper .PromotionShow .promotion").attr("promotionId"),
                    data["revisionId"]
                );
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsActionDeleteRevision() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(this).attr('revisionId');
    var confirmVal = confirm("Вы уверены что хотите удалить эту ревизию?");
    if (confirmVal == true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action/DeleteRevision.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                if(rezult !== "yes") {
                    alert("Не удалось удалить ревизию, возможно у вас нет прав.\nОбратитеть к администратору");
                } else {
                    alert("Ревизия была удалена");
                    AdminPromotionsUiShowPromotionMain(
                        $(".PromotionShowWrapper .PromotionShow .promotion").attr("promotionId")
                    );
                }
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsActionAddRevision() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["promotionId"] = $(".PromotionShowWrapper .PromotionShow .promotion").attr("promotionId");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/AddRevision.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            if(rezult !== "") {
                AdminPromotionsUiEditRevisionMain(rezult);
            } else {
                alert("Что-то пошло не так. К сожалению не удалось создать новую ревизию. Возможно такая ревизия уже существует");
            }
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsActionChangeTime() {
    AdminPromotionsActionChangeTimeMain(
        $(".EditRevisionMainWrapper").attr("revisionId"),
        $(this).attr("name"),
        $(this).val()
    );
}

function AdminPromotionsActionChangeTimeMain(revisionId,field,value) {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = revisionId;
    data["field"] = field;
    data["value"] = value;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/ChangeTime.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsActionClearTime() {
    var field = $(this).attr("field");
    $("input[name="+field+"]").val("");
    AdminPromotionsActionChangeTimeMain(
        $(".EditRevisionMainWrapper").attr("revisionId"),
        field,
        ""
    );
}

function AdminPromotionsActionSaveText() {
    var inputId = $(this).attr('inputId');
    var value = $("#"+inputId).val();
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["inputId"] = inputId;
    data["value"] = value;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/SaveText.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            if(rezult !== "") {
                alert("Сохранение прошло успешно");
            } else {
                alert("Похоже что-то пошло не так. Неудалось сохранить данные.");
            }
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsActionResetText() {
    var confirmVal = confirm("Вы уверены что хотите отменить несохраненные изменения?");
    if (confirmVal == true) {
        var divId = $(this).attr('inputId')+"Wrapper";
        AdminPromotionsActionLoadText(divId);
        alert("Изменения сброшены")
    }
}

function AdminPromotionsActionLoadText(divId) {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["divId"] = divId;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/LoadText.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $("#"+divId).html(rezult);
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsActionChangeBool() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["field"] = $(this).attr("name");
    data["value"] = $(this).val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/ChangeBool.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

/*------------------------------*/
/* AJAX HELPERS - /ajax/helper/ */
/*------------------------------*/


/*----------------------*/
/* AFTER LOAD DOCUMENTS */
/*----------------------*/
$(document).ready(function () {
    $("#FILTER_REVISION_TYPE").change(AdminPromotionsUiShowPromotionsList);
    $("#FILTER_SECTION").change(AdminPromotionsUiShowPromotionsList);
    $("#FILTER_REGION").change(AdminPromotionsUiShowPromotionsList);
    $("#FILTER_SORT_FIELD").change(AdminPromotionsUiShowPromotionsList);
    $("#FILTER_SORT_TYPE").change(AdminPromotionsUiShowPromotionsList);
    $("#FILTER_PUBLISHED_ON").change(AdminPromotionsUiShowPromotionsList);
    BX.bind(BX("FILTER_SEARCH_STRING"), "keyup", BX.delegate(AdminPromotionsUiShowPromotionsList, BX));
    $(".PromotionFilterPanel .addPromotionPanel .Button.add").click(AdminPromotionsActionAddPromotion);
    AdminPromotionsUiShowMain();
    // AdminPromotionsUiShowPromotionsList()
});