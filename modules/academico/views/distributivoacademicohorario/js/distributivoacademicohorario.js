$(document).ready(function() {

});

function savedistributivohorario() {
    var link = $('#txth_base').val() + "/academico/distributivoacademicohorario/savedistributivohorario";
    var arrParams = new Object();
    arrParams.modalidad = $('#distributivoacademicohorario-mod_id').val();
    arrParams.estudio = $('#distributivoacademicohorario-eaca_id').val();
    arrParams.unidad = $('#distributivoacademicohorario-uaca_id').val();
    arrParams.descripcion = $('#distributivoacademicohorario-daho_descripcion').val();
    arrParams.jornada = $('#distributivoacademicohorario-daho_jornada').val();
    arrParams.estado = $('#distributivoacademicohorario-daho_estado').val();
    arrParams.horario = $('#distributivoacademicohorario-daho_horario').val();
    arrParams.totalhora = $('#distributivoacademicohorario-daho_total_horas').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/distributivoacademicohorario/index";
                    }, 3000);
            }
        }, true);
    }
}

function updatedistributivohorario() {
    var link = $('#txth_base').val() + "/academico/distributivoacademicohorario/updatedistributivohorario";
    var arrParams = new Object();
    arrParams.id = $("#frm_daho_id").val();
    arrParams.modalidad = $('#distributivoacademicohorario-mod_id').val();
    arrParams.estudio = $('#distributivoacademicohorario-eaca_id').val();
    arrParams.unidad = $('#distributivoacademicohorario-uaca_id').val();
    arrParams.descripcion = $('#distributivoacademicohorario-daho_descripcion').val();
    arrParams.jornada = $('#distributivoacademicohorario-daho_jornada').val();
    arrParams.estado = $('#distributivoacademicohorario-daho_estado').val();
    arrParams.horario = $('#distributivoacademicohorario-daho_horario').val();
    arrParams.totalhora = $('#distributivoacademicohorario-daho_total_horas').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/distributivoacademicohorario/index";
                    }, 3000);
            }
        }, true);
    }
}