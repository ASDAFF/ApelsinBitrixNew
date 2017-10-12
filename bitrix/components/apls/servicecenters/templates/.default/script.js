if(!window.BX.NoEnter) {
    BX.NoEnter = function () {
        if (event.keyCode == 13) {
            event.preventDefault();//отменить стандартные действия
        }
    }
}
if(!window.BX.BocFormSubmit) {
    BX.BocFormSubmit = function() {

        var target = BX.proxy_context,
            form = BX.findParent(target, {"tag" : "form"}),
            formInput,
            rezultDiv = form.getAttribute("rezultDiv"),
            data = [];


        formInput = BX.findChildren(form, {"tag" : "input"}, true);
        if(!!formInput && 0 < formInput.length) {
            for(i = 0; i < formInput.length; i++) {
                data[formInput[i].getAttribute("name")] = formInput[i].value;
            }
        }


        BX.ajax({
            url: form.getAttribute("scriptFile"),
            data: data,
            method: "POST",
            dataType: "json",
            onsuccess: function(data) {
                if(!!data.success) {
                    $("#"+rezultDiv).html(data.success.html)
                } else {
                    $("#"+rezultDiv).html(data.error.html)
                }
            }
        });
    }
}
function ServiceCentersToColumns(contentArchiveBlockId,columnsBlockId) {
    if($.isArray(columnsBlockId)) {
        jQuery.each($(contentArchiveBlockId).children(), function() {
            var columnName = columnsBlockId[0];
            var columnHeight = $(columnsBlockId[0]).height();

            jQuery.each(columnsBlockId, function() {
                var thisBlockID = this.toString();
                if($(thisBlockID).height() < columnHeight) {
                    columnHeight = $(thisBlockID).height();
                    columnName = thisBlockID;
                }
            });
            $(columnName).append(this);
        });
    } else {
        alert('columnsBlockId is not array');
    }
}