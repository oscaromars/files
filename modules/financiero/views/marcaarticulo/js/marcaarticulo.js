$(document).ready(function() {
    $("#frm_mart_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconmart").attr("class", $(this).val());
        else {
            $("#iconmart").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanmartStatus").click(function() {
        if ($("#frm_mart_status").val() == "1") {
            $("#iconmartStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_mart_status").val("0");
        } else {
            $("#iconmartStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_mart_status").val("1");
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
    var link = $('#txth_base').val() + "/financiero/marcaarticulo/edit" + "?id=" + $("#frm_mart_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/financiero/marcaarticulo/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_mart_id").val();
    arrParams.nombre = $('#frm_mart').val();
    arrParams.cod = $('#frm_mart_cod').val();
    arrParams.fecha = $('#dtp_mart_fecha').val();
    arrParams.estado = $('#frm_mart_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/financiero/marcaarticulo/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_mart').val();
    arrParams.cod = $('#frm_mart_cod').val();
    arrParams.fecha = $('#dtp_mart_fecha').val();
    arrParams.estado = $('#frm_mart_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/financiero/marcaarticulo/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_marcaarticulos_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}