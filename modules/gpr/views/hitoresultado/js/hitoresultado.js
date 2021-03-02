$(document).ready(function() {

});

function edit() {
    var link = $('#txth_base').val() + "/gpr/hitoresultado/edit" + "?id=" + $("#frm_hito_id").val();
    window.location = link;
}

function save() {
    var link = $('#txth_base').val() + "/gpr/hitoresultado/save";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.fecha = $('#frm_freal').val();
    arrParams.gasto = $('#frm_gasto').val();
    arrParams.avance = $('#frm_avance').val();
    arrParams.cumplido = $('#cmb_cumplido').val();
    if (arrParams.avance < 0) {
        var msg = objLang.Please_Advance_must_be_more_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function openResultado() {
    var link = $('#txth_base').val() + "/gpr/hitoresultado/openresultado";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
    }, true);
}