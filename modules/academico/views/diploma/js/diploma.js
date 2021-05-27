$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules('txt_buscarData', 'grid_diploma_list');
    });
});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    arrParams.carrera = $('#cmb_carrera option:selected').text();
    arrParams.modalidad = $('#cmb_modalidad option:selected').text();
    arrParams.programa = $('#cmb_programa option:selected').text();
    arrParams.fechainicio = $('#txt_fecha_ini').val();
    arrParams.fechafin = $('#txt_fecha_fin').val();
    if ($('#cmb_carrera').val() == 0) arrParams.carrera = "";
    if ($('#cmb_modalidad').val() == 0) arrParams.modalidad = "";
    if ($('#cmb_programa').val() == 0) arrParams.programa = "";
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function update() {
    var link = $('#txth_base').val() + "/grupo/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_grupo_id").val();
    arrParams.nombre = $('#frm_grupo').val();
    arrParams.desc = $('#frm_grupo_desc').val();
    arrParams.seg = $("#cmb_grupo_seg").val();
    arrParams.estado = $('#frm_grupo_status').val();
    arrParams.roles = $('#cmb_roles').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function downloadDiploma(id) {
    var link = $('#txth_base').val() + "/academico/diploma/diplomadownload" + "?id=" + id;
    window.location = link;
}