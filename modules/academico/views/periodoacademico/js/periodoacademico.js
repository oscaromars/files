$(document).ready(function() {

});

function saveperiodoaca() {
    var link = $('#txth_base').val() + "/academico/periodoacademico/saveperiodo";
    var arrParams = new Object();
    arrParams.saca_id = $('#periodoacademico-saca_id').val();
    arrParams.baca_id = $('#periodoacademico-baca_id').val();
    arrParams.paca_activo = $('#periodoacademico-paca_activo').val();
    arrParams.paca_fecha_inicio = $('#periodoacademico-paca_fecha_inicio').val();
    arrParams.paca_fecha_fin = $('#periodoacademico-paca_fecha_fin').val();
    arrParams.paca_fecha_cierre_ini = $('#periodoacademico-paca_fecha_cierre_ini').val();
    arrParams.paca_fecha_cierre_fin = $('#periodoacademico-paca_fecha_cierre_fin').val();
    arrParams.paca_semanas_periodo = $('#periodoacademico-paca_semanas_periodo').val();
    arrParams.paca_semanas_inv_vinc_tuto = $('#periodoacademico-paca_semanas_inv_vinc_tuto').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/bloqueacademico/index";
                    }, 3000);
            }
        }, true);
    }
}

function updateperiodoaca() {
    var link = $('#txth_base').val() + "/academico/periodoacademico/updateperiodo";
    var arrParams = new Object();
    arrParams.id = $("#frm_paca_id").val();
    arrParams.saca_id = $('#periodoacademico-saca_id').val();
    arrParams.baca_id = $('#periodoacademico-baca_id').val();
    arrParams.paca_activo = $('#periodoacademico-paca_activo').val();
    arrParams.paca_fecha_inicio = $('#periodoacademico-paca_fecha_inicio').val();
    arrParams.paca_fecha_fin = $('#periodoacademico-paca_fecha_fin').val();
    arrParams.paca_fecha_cierre_ini = $('#periodoacademico-paca_fecha_cierre_ini').val();
    arrParams.paca_fecha_cierre_fin = $('#periodoacademico-paca_fecha_cierre_fin').val();
    arrParams.paca_semanas_periodo = $('#periodoacademico-paca_semanas_periodo').val();
    arrParams.paca_semanas_inv_vinc_tuto = $('#periodoacademico-paca_semanas_inv_vinc_tuto').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/bloqueacademico/index";
                    }, 3000);
            }
        }, true);
    }
}