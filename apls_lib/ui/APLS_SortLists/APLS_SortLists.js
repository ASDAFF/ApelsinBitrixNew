function aplsSortListAddSelectableAndSortable() {
    $('.apls-sort-list-content').selectable({
        cancel: '.sort-handle',
        items: ">div",
        selected: function( e, ui ) {
            if ($( ui.selected ).hasClass( "ui-selected-apls" )) {
                $( ui.selected ).removeClass( "ui-selected-apls" );
            } else {
                $( ui.selected ).addClass( "ui-selected-apls" );
            }
        },
        // unselected: function( e, ui ) {
        //     $( ui.unselected ).removeClass( "ui-selected-apls" );
        // }
    }).sortable({
        connectWith: ".apls-sort-list-content",
        handle: 'div, .sort-handle, .ui-selected-apls',
        cancel: ">div",
        helper: function (e, item) {
            if (!item.hasClass('ui-selected-apls')) {
                item.parent().children('.ui-selected-apls').removeClass('ui-selected-apls');
                item.addClass('ui-selected-apls');
            }
            var selected = item.parent().children('.ui-selected-apls').clone();
            ph = item.outerHeight() * selected.length;
            item.data('multidrag', selected).siblings('.ui-selected-apls').remove();
            return $('<div/>').append(selected);
        },
        start: function (e, ui) {
            ui.placeholder.css({'height': ph});
        },
        stop: function (e, ui) {
            var selected = ui.item.data('multidrag');
            ui.item.after(selected);
            ui.item.removeClass( "ui-selected-apls" );
            ui.item.remove();
            $(".apls-sort-list-content.ui-selectable .apls-sort-list-element").removeClass( "ui-selected-apls" );
        }
    });
    $(document).click(function(event) {
        if ($(event.target).closest(".apls-sort-list-content").length) return;
        $(".apls-sort-list-content.ui-selectable .apls-sort-list-element").removeClass( "ui-selected-apls" );
        event.stopPropagation();
    });
}

$(document).ready(function () {
    aplsSortListAddSelectableAndSortable();
});
