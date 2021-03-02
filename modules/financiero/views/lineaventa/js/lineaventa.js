$(document).ready(function() {
    $("#frm_lven_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconlven").attr("class", $(this).val());
        else {
            $("#iconlven").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanlvenStatus").click(function() {
        if ($("#frm_lven_status").val() == "1") {
            $("#iconlvenStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_lven_status").val("0");
        } else {
            $("#iconlvenStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_lven_status").val("1");
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
    var link = $('#txth_base').val() + "/financiero/lineaventa/edit" + "?id=" + $("#frm_lven_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/financiero/lineaventa/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_lven_id").val();
    arrParams.nombre = $('#frm_lven').val();
    arrParams.cod = $('#frm_lven_cod').val();
    arrParams.fecha = $('#dtp_lven_fecha').val();
    arrParams.estado = $('#frm_lven_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/financiero/lineaventa/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_lven').val();
    arrParams.cod = $('#frm_lven_cod').val();
    arrParams.fecha = $('#dtp_lven_fecha').val();
    arrParams.estado = $('#frm_lven_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/financiero/lineaventa/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_lineaventas_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}