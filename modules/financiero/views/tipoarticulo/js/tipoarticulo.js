$(document).ready(function() {
    $("#frm_tart_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconTart").attr("class", $(this).val());
        else {
            $("#iconTart").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanTartStatus").click(function() {
        if ($("#frm_tart_status").val() == "1") {
            $("#iconTartStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_tart_status").val("0");
        } else {
            $("#iconTartStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_tart_status").val("1");
        }
    });
});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit() {
    var link = $('#txth_base').val() + "/financiero/tipoarticulo/edit" + "?id=" + $("#frm_tart_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/financiero/tipoarticulo/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_tart_id").val();
    arrParams.nombre = $('#frm_tart').val();
    arrParams.cod = $('#frm_tart_cod').val();
    arrParams.fecha = $('#dtp_tart_fecha').val();
    arrParams.estado = $('#frm_tart_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/financiero/tipoarticulo/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_tart').val();
    arrParams.cod = $('#frm_tart_cod').val();
    arrParams.fecha = $('#dtp_tart_fecha').val();
    arrParams.estado = $('#frm_tart_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/financiero/tipoarticulo/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_tart_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}