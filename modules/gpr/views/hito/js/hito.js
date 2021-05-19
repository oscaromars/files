$(document).ready(function() {

});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function newHito() {
    var link = $('#txth_base').val() + "/gpr/hito/new" + "?id=" + $("#frm_id").val();
    window.location = link;
}

function edit() {
    var link = $('#txth_base').val() + "/gpr/hito/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/gpr/hito/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.fechaInicio = $('#frm_festimada').val();
    arrParams.peso = $('#frm_peso').val();
    arrParams.presupuesto = $('#frm_presupuesto').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/gpr/hito/save";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.fechaInicio = $('#frm_festimada').val();
    arrParams.peso = $('#frm_peso').val();
    arrParams.presupuesto = $('#frm_presupuesto').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/gpr/hito/index/" + $("#frm_id").val();
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/gpr/hito/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            searchModules('boxgrid', 'grid_list_hito')
                //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}

function openHito() {
    var link = $('#txth_base').val() + "/gpr/hito/openhito";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
    }, true);
}

function saveProyeccion() {
    var link = $('#txth_base').val() + "/gpr/hito/saveproyeccion";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
    }, true);
}

function closeProyeccion() {
    var link = $('#txth_base').val() + "/gpr/hito/closeproyeccion";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
    }, true);
}