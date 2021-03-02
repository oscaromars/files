$(document).ready(function() {
    $('#btn_buscarData_dist').click(function() {
        searchModules();
    });
    $('#btn_saveData_dist').click(function() {
        save();
    })
});

function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.id = $("#txth_ids").val();
    $("#Tbg_Distributivo_Aca").PbGridView("applyFilterData", arrParams);
}

function getListStudent(search, response) { // function utilizada para el SearchboxList en evento getSource
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/edit" + "?id=" + $("#txth_ids").val();
    var arrParams = new Object();
    arrParams.search = search;
    arrParams.unidad = $('#txth_uids').val();
    arrParams.PBgetAutoComplete = true;
    requestHttpAjax(link, arrParams, function(rsp) {
        response(rsp);
    }, false, false, "json", "POST", null, false);
}

function showDataStudent(id, value) { // function utilizada para el SearchboxList en evento select
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/edit" + "?id=" + $("#txth_ids").val();
    var arrParams = new Object();
    arrParams.est_id = id;
    arrParams.PBgetDataEstudiante = true;
    $("#txth_esid").val(id);
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            $('#txt_buscarData').val("");
            $('#txt_nombres').val(response.data.nombres);
            $('#txt_apellidos').val(response.data.apellidos);
            $('#txt_carrera').val(response.data.carrera);
            $('#txt_matricula').val(response.data.matricula);
        }
    }, true);
}

function edit() {
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/edit" + "?id=" + $("#txth_ids").val();
    window.location = link;
}

function save() {
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/save";
    var arrParams = new Object();
    arrParams.id = $("#txth_ids").val();
    arrParams.est_id = $("#txth_esid").val();
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            searchModules();
            clearDataSearch();
        } else {
            showAlert(response.status, response.label, response.message);
        }
    }, true);
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            searchModules();
            setTimeout(function() {
                showAlert(response.status, response.label, response.message);
            }, 1000);
        }
    }, true);
}

function clearDataSearch() {
    $('#txt_buscarData').val("");
    $('#txt_nombres').val("");
    $('#txt_apellidos').val("");
    $('#txt_carrera').val("");
    $('#txt_matricula').val("");
    $("#txth_esid").val('');
}

function exportExcel() {
    var search = $('#txt_buscarData').val();
    var id = $('#txth_ids').val();
    window.location.href = $('#txth_base').val() + "/academico/distributivoestudiante/exportexcel?" +
        "id=" + id +
        "&search=" + search;
}

function exportPdf() {
    var search = $('#txt_buscarData').val();
    var id = $('#txth_ids').val();
    window.location.href = $('#txth_base').val() + "/academico/distributivoestudiante/exportpdf?pdf=1" +
        "&id=" + id +
        "&search=" + search;
}