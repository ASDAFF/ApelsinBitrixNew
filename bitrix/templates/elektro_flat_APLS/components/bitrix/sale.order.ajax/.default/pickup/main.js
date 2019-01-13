function pickUpHideAPLS() {
    $(".bx-soa-pickup-list-item").hide();
    $(".bx-soa-pickup-list-item.bx-selected").show();
    $.each( ORDER_AJAX_PICK_UP, function( key, value ) {
        $("#store-"+value).show();
    });
}