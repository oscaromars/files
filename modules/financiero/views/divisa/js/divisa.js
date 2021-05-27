$(document).ready(function() {
    $("#frm_div_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconDiv").attr("class", $(this).val());
        else {
            $("#iconDiv").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanDivStatus").click(function() {
        if ($("#frm_div_status").val() == "1") {
            $("#iconDivStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_div_status").val("0");
        } else {
            $("#iconDivStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_div_status").val("1");
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
    var link = $('#txth_base').val() + "/financiero/divisa/edit" + "?id=" + $("#frm_div_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/financiero/divisa/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_div_id").val();
    arrParams.nombre = $('#frm_div').val();
    arrParams.cod = $('#frm_div_cod').val();
    arrParams.cot = $('#frm_div_cot').val();
    arrParams.fecha = $('#dtp_div_fecha').val();
    arrParams.estado = $('#frm_div_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/financiero/divisa/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_div').val();
    arrParams.cod = $('#frm_div_cod').val();
    arrParams.cot = $('#frm_div_cot').val();
    arrParams.fecha = $('#dtp_div_fecha').val();
    arrParams.estado = $('#frm_div_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/financiero/divisa/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_divisas_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}