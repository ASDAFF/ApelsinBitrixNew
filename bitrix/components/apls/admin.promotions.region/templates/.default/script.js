/*---------*/
/* AJAX UI */
/*---------*/

/**
 * Загружаем через ajax интерфейс для добавления территорий
 */
function AdminPromotionsRegionUiCitiesAdd() {
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/citiesAdd.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $(".RegionCitiesAddPanel").html(rezult);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsRegionUiCitiesAdd()");
        },
    });
}

/**
 * Загружаем через ajax интерфейс для отображения территорий
 */
function AdminPromotionsRegionUiCitiesShow(regionId) {
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    data["regionId"] = regionId;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/citiesShow.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $(".RegionCities").html(rezult);
            AdminPromotionsRegionUiCitiesAdd();
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsRegionUiCitiesAdd()");
        },
    });
}

/**
 * При помощи Ajax загружает интерфейс со списком всех регионов
 */
function AdminPromotionsRegionUiShowRegionsList() {
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/regionsList.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $(".PromotionRegionWrapper").html(rezult);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsRegionUiShowRegionsList()");
        },
    });
}

/**
 * СОБЫТИЕ: При помощи Ajax загружает интерфейс изменения региона
 */
function AdminPromotionsRegionUiShowRegionsEditEvent() {
    AdminPromotionsRegionUiShowRegionsEdit($(this).attr('regionId'));
}

/**
 * При помощи Ajax загружает интерфейс изменения региона
 */
function AdminPromotionsRegionUiShowRegionsEdit(regionId) {
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    data["regionId"] = regionId;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/regionEdit.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $(".PromotionRegionWrapper").html(rezult);
            AdminPromotionsRegionUiCitiesShow(regionId);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsRegionUiShowRegionsEdit()");
        },
    });
}

/*--------------*/
/* AJAX ACTIONS */
/*--------------*/

function AdminPromotionsRegionAdd() {
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    data["regionName"] = $(".PromotionRegionsListButtonPanel .NewRegionName input").val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/actions/regionAdd.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            if(rezult != "") {
                AdminPromotionsRegionUiShowRegionsEdit(rezult);
            } else {
                alert("Не удалось создать регион");
            }
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

/**
 * Устанавалием регион по умолчанию
 */
function AdminPromotionsRegionSetDefault() {
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    data["regionId"] = $(this).attr('regionId');
    var confirmVal = confirm("Сделать этот регион, регионом по умолчанию?");
    if (confirmVal == true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/actions/regionSetDefault.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                AdminPromotionsRegionUiShowRegionsList();
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

/**
 * удаление региона
 */
function AdminPromotionsRegionDelete() {
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    data["regionId"] = $(this).attr('regionId');
    var confirmVal = confirm("Вы уверены что хотите удалить этот регион и все привязанные к нему территории?");
    if (confirmVal == true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/actions/regionDelete.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                AdminPromotionsRegionUiShowRegionsList();
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

/**
 * Пересохраняет имя региона
 */
function AdminPromotionsRegionChangeName() {
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    data["regionId"] = $(".RegionField.RegionId .RegionFieldValue input").val();
    data["regionName"] = $(".RegionField.RegionName .RegionFieldValue input").val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/actions/regionChangeName.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            alert(rezult);
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

/**
 * Добавляем новую территорию в регион
 */
function AdminPromotionsRegionCityAdd() {
    var regionId = $(".RegionField.RegionId .RegionFieldValue input").val();
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    data["regionId"] = regionId;
    data["city"] = $(".RegionCitiesAddPanelSearch input.bx-ui-sls-route").val();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/actions/citiesAdd.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            AdminPromotionsRegionUiCitiesShow(regionId)
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

/**
 * удаляет территорию для региона
 * @param cityId
 */
function AdminPromotionsRegionCityDelete(cityId) {
    var regionId = $(".RegionField.RegionId .RegionFieldValue input").val();
    var data = [];
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["componentFolder"] = $(".PromotionRegionWrapper").attr("componentFolder");
    data["regionId"] = regionId;
    data["cityId"] = cityId;
    var confirmVal = confirm("Вы уверены что хотите удалить эту территорию?");
    if (confirmVal == true) {
        BX.ajax({
            url: data["templateFolder"] + "/ajax/actions/citiesDelete.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                AdminPromotionsRegionUiCitiesShow(regionId)
            },
            onfailure: function (rezult) {
                alert("Произошла ошибка выполнения скрипта");
            },
        });
    }
}

/**
 * Сохраняет текущий порядок регионов
 * @constructor
 */
function AdminPromotionsRegionStopSort() {
    var regions = [];
    var data = [];
    $(".PromotionRegionsList .apls-sort-list-content .apls-sort-list-element").each(function (i,elem) {
        regions[i] = $(this).attr("regionid");
    });
    data["templateFolder"] = $(".PromotionRegionWrapper").attr("templateFolder");
    data["regions"] = regions;
    BX.ajax({
        url: data["templateFolder"] + "/ajax/actions/regionsSort.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            $('.PromotionRegionsList .buttonPanel .editButton').click(AdminPromotionsRegionUiShowRegionsEditEvent);
            $('.PromotionRegionsList .buttonPanel .setDefaultButton').click(AdminPromotionsRegionSetDefault);
            $('.PromotionRegionsList .buttonPanel .delButton').click(AdminPromotionsRegionDelete);
        },
        onfailure: function (rezult) {
            alert("Произошла ошибка выполнения скрипта");
        },
    });
}

/*----------------------*/
/* AFTER LOAD DOCUMENTS */
/*----------------------*/

$(document).ready(function () {
    AdminPromotionsRegionUiShowRegionsList();
});