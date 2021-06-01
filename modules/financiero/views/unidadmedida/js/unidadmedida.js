$(document).ready(function() {
    $("#frm_umed_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconUmed").attr("class", $(this).val());
        else {
            $("#iconUmed").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanUmedStatus").click(function() {
        if ($("#frm_umed_status").val() == "1") {
            $("#iconUmedStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_umed_status").val("0");
        } else {
            $("#iconUmedStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_umed_status").val("1");
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
    var link = $('#txth_base').val() + "/financiero/unidadmedida/edit" + "?id=" + $("#frm_umed_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/financiero/unidadmedida/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_umed_id").val();
    arrParams.nombre = $('#frm_umed').val();
    arrParams.cod = $('#frm_umed_cod').val();
    arrParams.medida = $('#frm_umed_med').val();
    arrParams.estado = $('#frm_umed_status').val();
    arrParams.fecha = $('#frm_umed_fecha').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/financiero/unidadmedida/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_umed').val();
    arrParams.cod = $('#frm_umed_cod').val();
    arrParams.medida = $('#frm_umed_med').val();
    arrParams.estado = $('#frm_umed_status').val();
    arrParams.fecha = $('#frm_umed_fecha').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/financiero/unidadmedida/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_unidadmedidas_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}