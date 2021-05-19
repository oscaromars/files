$(document).ready(function () {
    $('#btn_buscarEvaluacion').click(function () {
        actualizarGridEvaluacion();
    });

});

function actualizarGridEvaluacion() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var tipo_evaluacion = $('#cmb_tipoevaluacion').val();    
    var semestre = $('#cmb_semestre option:selected').val();

    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#PbEvaluacionDocente').PbGridView('applyFilterData', {'profesor': profesor, 'tipo_evaluacion': tipo_evaluacion, 'semestre': semestre});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var tipo_evaluacion = $('#cmb_tipoevaluacion').val();   
    var semestre = $('#cmb_semestre option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/evaluaciondocente/expexcel?profesor=" + profesor + "&tipo_evaluacion=" + tipo_evaluacion + "&semestre=" + semestre;
}

function exportPdf() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var tipo_evaluacion = $('#cmb_tipoevaluacion').val();   
    var semestre = $('#cmb_semestre option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/evaluaciondocente/exppdf?pdf=1&profesor=" + profesor + "&tipo_evaluacion=" + tipo_evaluacion + "&semestre=" + semestre;
}