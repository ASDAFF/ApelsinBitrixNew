
/*-------------------*/
/* HELPERS FUNCTIONS */
/*-------------------*/

/**
 * вернет путь к папке шаблона в виде строки
 * @returns путь кпапке шаблона
 */
function AdminPromotionsTemplateFolder() {
    return $(".PromotionWrapper").attr("templateFolder");
}

/**
 * вернет путь к папке компоненты в виде строки
 * @returns путь кпапке компоненты
 */
function AdminPromotionsComponentFolder() {
    return $(".PromotionWrapper").attr("componentFolder");
}

/**
 * Вернет jQuery объект основного Wrapper для размещения контента
 * @returns {jQuery|HTMLElement}
 */
function AdminPromotionsWrapper() {
    return $(".PromotionWrapper");
}

/*----------------------*/
/* AJAX UI - /ajax/ui/  */
/*----------------------*/

function AdminPromotionsUiShowPromotionsList() {
    var data = [];
    data["templateFolder"] = AdminPromotionsTemplateFolder();
    data["componentFolder"] = AdminPromotionsComponentFolder();
    BX.ajax({
        url: data["templateFolder"] + "/ajax/ui/ShowPromotionsList.php",
        data: data,
        method: 'POST',
        dataType: 'html',
        onsuccess: function (rezult) {
            AdminPromotionsWrapper().html(rezult);
        },
        onfailure: function (rezult) {
            alert("Ошибка: AdminPromotionsUiShowPromotionsList()");
        },
    });
}


/*------------------------------*/
/* AJAX ACTIONS - /ajax/action/ */
/*------------------------------*/


/*------------------------------*/
/* AJAX HELPERS - /ajax/helper/ */
/*------------------------------*/


/*----------------------*/
/* AFTER LOAD DOCUMENTS */
/*----------------------*/
$(document).ready(function () {
    AdminPromotionsUiShowPromotionsList()
});