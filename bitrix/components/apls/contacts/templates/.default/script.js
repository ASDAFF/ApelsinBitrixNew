$(document).ready(function(){

    APLS_contacts_show_region($(".APLS_contacts_regions_block").first());

    $(".APLS_contacts_shops_address").click(function(){
        $(".APLS_contacts_buildings").hide();
        if($(this).hasClass("ActiveBlock")) {
            $(this).removeClass("ActiveBlock");
            $(this).parent().removeClass("ActiveBlock");
            APLS_contacts_ajax_load_region_map($(this).parent().attr("regionID"));

        } else {
            $(".APLS_contacts_shops_address").removeClass("ActiveBlock");
            $(".APLS_contacts_shops_block").removeClass("ActiveBlock");
            $("#"+$(this).attr("blockID")).show();
            $(this).addClass("ActiveBlock");
            $(this).parent().addClass("ActiveBlock");
            APLS_contacts_ajax_load_shop_map($(this).attr("shopID"));
        }
    });

    $(".APLS_contacts_regions_block").click(function(){
        APLS_contacts_show_region(this);
            // $(this).css('background', '#ef7f1a');
            // $(this).css('color', '#FFFFFF');
    });
});

function APLS_contacts_show_region(obj) {
    var regionID = $(obj).attr("regionID");
    $(".APLS_contacts_regions_block").removeClass("ActiveBlockRegions");
    $(obj).addClass("ActiveBlockRegions");
    $(".APLS_contacts_buildings").hide();
    $(".APLS_contacts_shops_address").removeClass("ActiveBlock");
    $(".APLS_contacts_shops_address").parent().removeClass("ActiveBlock");
    $(".APLS_contacts_shops_block").hide();
    $("[regionID = " + regionID + "]").show();
    APLS_contacts_ajax_load_region_map(regionID);
}

function APLS_contacts_ajax_load_region_map(regionID) {
    var HLS = $("#APLS_contacts_wrapper").attr("HIGHLOAD_SHOPS_ID");
    var HLR = $("#APLS_contacts_wrapper").attr("HIGHLOAD_REGION_ID");
    var TYPE = $("#APLS_contacts_wrapper").attr("TYPE_ID");
    BX.ajax({
        url: $("#APLS_contacts_wrapper").attr("regionsScriptFile"),
        data: {regionID:regionID,HLS:HLS,HLR:HLR,TYPE:TYPE},
        method: 'POST',
        dataType: 'html',
        onsuccess: function(data){
            $('.APLS_map').html(data);
        },
    });
}

function APLS_contacts_ajax_load_shop_map(shopID) {
    var HLS = $("#APLS_contacts_wrapper").attr("HIGHLOAD_SHOPS_ID");
    var HLR = $("#APLS_contacts_wrapper").attr("HIGHLOAD_REGION_ID");
    var TYPE = $("#APLS_contacts_wrapper").attr("TYPE_ID");
    BX.ajax({
        url: $("#APLS_contacts_wrapper").attr("shopScriptFile"),
        data: {shopID:shopID,HLS:HLS,HLR:HLR,TYPE:TYPE},
        method: 'POST',
        dataType: 'html',
        onsuccess: function(data){
            $('.APLS_map').html(data);
        },
    });
}


