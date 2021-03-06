
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

/**
 * Вернет jQuery объект блока ответственного за вывод результатов поиска секции каталога
 * @returns {jQuery|HTMLElement}
 */
function AdminPromotionsSectionsSearchResult() {
    return $(".EditRevisionMainWrapper .CatalogSectionsWrapper .search-result");
}

/**
 * Вернет jQuery объект блока ответственного за вывод товара (элемента каталога)
 * @returns {jQuery|HTMLElement}
 */
function AdminPromotionsElementsSearchResult() {
    return $(".EditRevisionMainWrapper .CatalogProductsWrapper .search-result");
}

/**
 * Вернет jQuery объект блока ответственного за вывод исключений товаров (элементов каталога)
 * @returns {jQuery|HTMLElement}
 */
function AdminPromotionsExceptionsElementsSearchResult() {
    return $(".EditRevisionMainWrapper .CatalogExceptionsWrapper .search-result");
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
            $(".PromotionMainWrapper .PromotionShow .RevisionsList .ButtonPanel .createCopy").click(AdminPromotionsActionCreateCopyRevision);
            aplsTabsAddClickEvent();
            scroll(0,0);
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
            aplsTabsAddClickEvent();
            AdminPromotionsUiShowCatalogSections();
            AdminPromotionsUiShowCatalogProducts("product","CatalogProductsWrapper");
            AdminPromotionsUiShowCatalogProducts("exception","CatalogExceptionsWrapper");
            BX.bind(BX("RevisionTitleField"), "keyup", BX.delegate(AdminPromotionsActionSetTitle, BX));
            $(".EditRevisionMainWrapper .TitleTextDelete").click(AdminPromotionsActionClearTitle);
            $(".EditRevisionMainWrapper .DateTime").change(AdminPromotionsActionChangeTime);
            $(".EditRevisionMainWrapper .DateTimeClear").click(AdminPromotionsActionClearTime);
            BX.bind(BX("PreviewPromotionText"), "keyup", BX.delegate(AdminPromotionsActionSaveText, $("#PreviewPromotionText")));
            BX.bind(BX("MainPromotionText"), "keyup", BX.delegate(AdminPromotionsActionSaveText, $("#MainPromotionText")));
            BX.bind(BX("VkPromotionText"), "keyup", BX.delegate(AdminPromotionsActionSaveText, $("#VkPromotionText")));
            $(".EditRevisionMainWrapper .SharesPlacement select").change(AdminPromotionsActionChangeBool);
            $(".EditRevisionMainWrapper .BackButton").click(function () {
                var promotionId = $(this).attr('promotionId');
                var revisionId = $(this).attr('revisionId');
                AdminPromotionsUiShowMain(promotionId, revisionId);
            });
            BX.bind(BX("ShowLiveSearchSection"), "keyup", BX.delegate(AdminPromotionUiShowLiveSearchSection, BX));
            BX.bind(BX("ShowLiveSearchProduct"), "keyup", BX.delegate(AdminPromotionUiShowLiveSearchElement, BX));
            $(".EditRevisionMainWrapper .CatalogProductsWrapper .search .SectionsTreeSelectBox").change(AdminPromotionUiShowLiveSearchElement);
            BX.bind(BX("ShowLiveSearchException"), "keyup", BX.delegate(AdminPromotionUiShowLiveSearchExceptionElement, BX));
            $(".EditRevisionMainWrapper .CatalogExceptionsWrapper .search .SectionsTreeSelectBox").change(AdminPromotionUiShowLiveSearchExceptionElement);
            $('#CatalogAcceptBtnOK').click(AdminPromotionsUIDownloadCatalogXmlFile);
            $('#ProductAcceptBtnOK').click(AdminPromotionsUIDownloadProductXmlFile);
            $('#ExceptionAcceptBtnOK').click(AdminPromotionsUIDownloadExceptionXmlFile);
            $('#SectionDeleteBtn').click(function () {AdminPromotionsActionClearElementsWrapper('Sections');});
            $('#ProductDeleteBtn').click(function () {AdminPromotionsActionClearElementsWrapper('Products');});
            $('#ExceptionDeleteBtn').click(function () {AdminPromotionsActionClearElementsWrapper('Exceptions');});
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsUiEditRevision()");
        },
    });
}

function AdminPromotionUiShowLiveSearchSection() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["catalogSectionLiveSearch"] = $(".EditRevisionMainWrapper .CatalogSectionsWrapper .search .SearchInput").val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowLiveSearchSectionsResult.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            AdminPromotionsSectionsSearchResult().html(rezult);
            $(".EditRevisionMainWrapper .CatalogSectionsWrapper .search-result .SearchBlock .ElementBlockContent .AddElement")
                .click(AdminPromotionsActionAddSection);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionUiShowLiveSearchSection()");
        },
    });
}

function AdminPromotionUiShowLiveSearchElement() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["elementLiveSearch"] = $(".EditRevisionMainWrapper .CatalogProductsWrapper .search .SearchInput").val();
    data["selectCatalogSection"] = $(".EditRevisionMainWrapper .CatalogProductsWrapper .search .SectionsTreeSelectBox").val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowLiveSearchElementsResult.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            AdminPromotionsElementsSearchResult().html(rezult);
            $(".EditRevisionMainWrapper .CatalogProductsWrapper .search-result .SearchProduct .ElementBlockContent .AddButton")
                .click(AdminPromotionsActionAddProduct);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionUiShowLiveSearchSection()");
        },
    });
}

function AdminPromotionUiShowLiveSearchExceptionElement() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["elementLiveSearch"] = $(".EditRevisionMainWrapper .CatalogExceptionsWrapper .search .SearchInput").val();
    data["selectCatalogSection"] = $(".EditRevisionMainWrapper .CatalogExceptionsWrapper .search .SectionsTreeSelectBox").val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowLiveSearchException.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            AdminPromotionsExceptionsElementsSearchResult().html(rezult);
            $(".EditRevisionMainWrapper .CatalogExceptionsWrapper .search-result .SearchException .ElementBlockContent .AddButton")
                .click(AdminPromotionsActionAddException);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionUiShowLiveSearchSection()");
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
            $(".CatalogSectionsWrapper .ListOfElements .DellButton").click(AdminPromotionsActionDeleteCatalogElement);
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
            $("."+wrapperClass+">.content").html(rezult);
            $("."+wrapperClass+" .ListOfElements .DellButton").click(AdminPromotionsActionDeleteCatalogElement);
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsUiShowRevisionImages() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowRevisionImages.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            $("#AplsAdminWrapper .RevisionImagesWrapper").html(result);
            $("#AplsAdminWrapper .RevisionImagesWrapper .PromotionImage").click(AdminPromotionsUiEditRevisionImages);
            $("#AplsAdminWrapper .RevisionImagesWrapper .DeleteButton").click(AdminPromotionsActionDeleteImage);
            BX.bind(BX("PromotionImageEditSearchString"), "keyup", BX.delegate(AdminPromotionsUiEditRevisionImagesSearching, $("#PromotionImageEditSearchString")));
        },
        onfailure: function () {
            alert("Ошибка: AdminPromotionsUiShowRevisionImages()");
        },
    });
}

function AdminPromotionsUiEditRevisionImages() {
    var typeId = $(this).attr("typeId");
    var imageId = $(this).find(".Image").attr("imageId");
    $("#AplsAdminWrapper .RevisionImagesWrapper .EditPointer").hide();
    $(this).parent().find(".EditPointer").show();
    $("#AplsAdminWrapper .RevisionImagesWrapper .PromotionImageEditWrapper").show();
    $("#AplsAdminWrapper .RevisionImagesWrapper #PromotionImageEditSearchString").val("");
    $("#AplsAdminWrapper .RevisionImagesWrapper #PromotionImageEditSearchString").attr("typeId",typeId);
    if(typeof(imageId) != "undefined") {
        $("#AplsAdminWrapper .RevisionImagesWrapper #PromotionImageEditSearchString").attr("imageId",imageId);
    } else {
        $("#AplsAdminWrapper .RevisionImagesWrapper #PromotionImageEditSearchString").removeAttr("imageId");
    }
    AdminPromotionsUiEditRevisionImagesSearch(typeId,imageId,"");
}

function AdminPromotionsUiEditRevisionImagesSearch(typeId,imageId,searchString) {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["typeId"] = typeId;
    data["imageId"] = imageId;
    data["searchString"] = searchString;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/EditRevisionImages.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            $("#AplsAdminWrapper .PromotionImageEditWrapper .PromotionImageEditSearchResult").html(result);
            $("#AplsAdminWrapper .PromotionImageEditWrapper .ImageBlock").click(AdminPromotionsActionSelectImage);
        },
        onfailure: function () {
            alert("Ошибка: AdminPromotionsUiEditRevisionImages()");
        },
    });
}

function AdminPromotionsUiEditRevisionImagesSearching() {
    var searchString = $(this).val();
    var typeId = $(this).attr('typeId');
    var imageId = $(this).attr('imageId');
    AdminPromotionsUiEditRevisionImagesSearch(typeId,imageId,searchString);
}

function AdminPromotionsUiShowNewImage(typeId, imageId) {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["typeId"] = typeId;
    data["imageId"] = imageId;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowImage.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            $("#AplsAdminWrapper .RevisionImagesWrapper .type-"+typeId).html(result);
        },
        onfailure: function () {
            alert("Ошибка: AdminPromotionsUiEditRevisionImages()");
        },
    });
}

function AdminPromotionsUiShowRevisionSections() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowRevisionSections.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            $("#AplsAdminWrapper .RevisionSectionsWrapper").html(result);
            aplsSortListAddSelectableAndSortable("AdminPromotionsActionSaveRevisionSections");
        },
        onfailure: function () {
            alert("Ошибка: AdminPromotionsUiShowRevisionSections()");
        },
    });
}

function AdminPromotionsUiShowRevisionLocations() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowRevisionLocations.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            $("#AplsAdminWrapper .RevisionLocationsWrapper").html(result);
            aplsSortListAddSelectableAndSortable("AdminPromotionsActionSaveRevisionLocations");
        },
        onfailure: function () {
            alert("Ошибка: AdminPromotionsUiShowRevisionLocations()");
        },
    });
}

function AdminPromotionsUIDownloadCatalogXmlFile() {
    var file_data = $('#CatalogUploadFile').prop('files')[0];
    var revisionId = $('.EditRevisionMainWrapper').attr('revisionid');
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('revision', revisionId);
    if (file_data.type !== 'text/xml') {
        alert('Загружен неверный тип файла, выберете файл с расширением .xml');
        // очистить форму
    } else {
        $.ajax({
            url: AdminPromotionsTemplateFolder() + "/ajax/ui/DownloadCatalogXml.php",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (result) {
                $(".CatalogSectionsWrapper .content").html(result);
                AdminPromotionsUiShowCatalogSections();
            },
            failure: function () {
                alert("Произошла ошибка выполнения скрипта AdminPromotionsUIDownloadFile");
            },
        });
    }
}

function AdminPromotionsUIDownloadProductXmlFile() {
    var file_data = $('#ProductUploadFile').prop('files')[0];
    var revisionId = $('.EditRevisionMainWrapper').attr('revisionid');
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('revision', revisionId);
    if (file_data.type !== 'text/xml') {
        alert('Загружен неверный тип файла, выберете файл с расширением .xml');
        // очистить форму
    } else {
        $.ajax({
            url: AdminPromotionsTemplateFolder() + "/ajax/ui/DownloadProductXml.php",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (result) {
                $(".CatalogProductsWrapper .content").html(result);
                AdminPromotionsUiShowCatalogProducts("product","CatalogProductsWrapper");
            },
            failure: function () {
                alert("Произошла ошибка выполнения скрипта AdminPromotionsUIDownloadFile");
            },
        });
    }
}

function AdminPromotionsUIDownloadExceptionXmlFile() {
    var file_data = $('#ExceptionUploadFile').prop('files')[0];
    var revisionId = $('.EditRevisionMainWrapper').attr('revisionid');
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('revision', revisionId);
    if (file_data.type !== 'text/xml') {
        alert('Загружен неверный тип файла, выберете файл с расширением .xml');
        // очистить форму
    } else {
        $.ajax({
            url: AdminPromotionsTemplateFolder() + "/ajax/ui/DownloadExceptionXml.php",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (result) {
                $(".CatalogExceptionsWrapper .content").html(result);
                AdminPromotionsUiShowCatalogProducts("exception","CatalogExceptionsWrapper");
            },
            failure: function () {
                alert("Произошла ошибка выполнения скрипта AdminPromotionsUIDownloadFile");
            },
        });
    }
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

function AdminPromotionsActionCreateCopyRevision() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(this).attr('revisionId');
    var confirmVal = confirm("Вы уверены что хотите создать дубликат данной ревизии?");
    if (confirmVal == true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action/CreateCopyRevision.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (result) {
                if(result !== "") {
                    AdminPromotionsUiEditRevisionMain(result);
                } else {
                    alert("К сожалению не удалось создать копию ревизии");
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
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["field"] = $(this).attr('field');
    data["value"] = $(this).val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/SaveText.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            if(result !== "yes") {
                alert("Похоже что-то пошло не так. Неудалось сохранить данные.");
            }
        },
        onfailure: function () {
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

function AdminPromotionsActionDeleteCatalogElement() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["tableId"] = $(this).parent().attr('tableId');
    data["type"] = $(this).parent().attr('type');
    alert(data["tableId"]);
    var confirmVal = confirm("Вы уверены что хотите удалить эту запись?");
    if (confirmVal === true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action/DeleteCatalogElement.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (result) {
                if(result === "yes") {
                    if(data["type"] === 'product') {
                        AdminPromotionsUiShowCatalogProducts("product","CatalogProductsWrapper");
                    } else if (data["type"] === 'exception') {
                        AdminPromotionsUiShowCatalogProducts("exception","CatalogExceptionsWrapper");
                    } else if (data["type"] === 'section') {
                        AdminPromotionsUiShowCatalogSections()
                    }
                } else {
                    alert("Что-то пошло не так, удаление не удалось");
                }
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

function AdminPromotionsActionAddSection() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["xml_id"] = $(this).attr("id");
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/AddSection.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            if(rezult === "yes") {
                AdminPromotionsUiShowCatalogSections();
            } else {
                alert("Ошибка при создании записи");
            }
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsActionAddSection()");
        },
    });
}

function AdminPromotionsActionAddProduct() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["xml_id"] = $(this).attr("id");
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/AddProduct.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            if(rezult === "yes") {
                AdminPromotionsUiShowCatalogProducts("product","CatalogProductsWrapper");
            } else {
                alert("Ошибка при создании записи");
            }
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsActionAddProduct()");
        },
    });
}

function AdminPromotionsActionAddException() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["xml_id"] = $(this).attr("id");
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/AddException.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            if(rezult === "yes") {
                AdminPromotionsUiShowCatalogProducts("exception","CatalogExceptionsWrapper");
            } else {
                alert("Ошибка при создании записи");
            }
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsActionAddException()");
        },
    });
}

function AdminPromotionsActionSelectImage() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["typeId"] = $(this).attr("typeId");
    data["imageId"] = $(this).attr("imageId");
    $("#AplsAdminWrapper .PromotionImageEditWrapper .ImageBlock").removeClass("ThisImage");
    $(this).addClass("ThisImage");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/SelectImage.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            AdminPromotionsUiShowNewImage(data["typeId"], data["imageId"])
            $("#PromotionImageEditSearchString").attr("imageId",data["imageId"]);
        },
        onfailure: function () {
            alert("Ошибка: AdminPromotionsUiEditRevisionImages()");
        },
    });
}

function AdminPromotionsActionDeleteImage() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["typeId"] = $(this).attr("typeId");
    if($(".PromotionImageEditWrapper .ShowImageWrapper").attr("typeId") === data["typeId"]) {
        $("#AplsAdminWrapper .PromotionImageEditWrapper .ImageBlock").removeClass("ThisImage");
        $("#PromotionImageEditSearchString").removeAttr("imageId");
    }
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/DeleteImage.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            AdminPromotionsUiShowNewImage(data["typeId"], "")
        },
        onfailure: function () {
            alert("Ошибка: AdminPromotionsUiEditRevisionImages()");
        },
    });
}

function AdminPromotionsActionSaveRevisionSections() {
    var sections = [];
    var data = [];
    $("#AplsAdminWrapper .RevisionSectionsWrapper .PromotionSectionsSelectedList .apls-sort-list-element").each(function (i,elem) {
        sections[i] = $(this).attr("sectionId");
    });
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["sections"] = sections;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/SaveRevisionSections.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            // alert(result);
        },
        onfailure: function (result) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsActionSaveRevisionLocations() {
    var location = [];
    var data = [];
    $("#AplsAdminWrapper .RevisionLocationsWrapper .PromotionLocationsSelectedList .apls-sort-list-element").each(function (i,elem) {
        location[i] = $(this).attr("locationId");
    });
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["locations"] = location;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/SaveRevisionLocations.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
            // alert(result);
        },
        onfailure: function (result) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsActionSetTitle () {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["revisionId"] = $(".EditRevisionMainWrapper").attr("revisionId");
    data["title"] = $("#RevisionTitleField").val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/action/SaveTitle.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (result) {
        },
        onfailure: function () {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

function AdminPromotionsActionClearTitle() {
    $("#RevisionTitleField").val("");
    AdminPromotionsActionSetTitle();
}

function AdminPromotionsActionClearElementsWrapper(elementType) {
    var confirmVal = confirm("Вы уверены что хотите удалить все элементы из раздела "+elementType+"?");
    if (confirmVal == true) {
        var data = [];
        data['revisionId'] = $('.EditRevisionMainWrapper').attr('revisionid');
        data['elementType'] = elementType;
        BX.ajax({
            url: AdminPromotionsTemplateFolder() + "/ajax/action/DeleteAllElements.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (result) {
                if (elementType == 'Sections') {
                    AdminPromotionsUiShowCatalogSections();
                } else if (elementType == 'Products') {
                    AdminPromotionsUiShowCatalogProducts("product","CatalogProductsWrapper");
                } else if (elementType == 'Exceptions') {
                    AdminPromotionsUiShowCatalogProducts("exception","CatalogExceptionsWrapper");
                }
            },
        });
    }
}

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