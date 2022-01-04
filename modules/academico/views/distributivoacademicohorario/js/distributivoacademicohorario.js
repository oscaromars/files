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
    alert ('sadd' + arrParams.modalidad);
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

/*function updatedistributivohorari() {
    var link = $('#txth_base').val() + "/academico/semestreacademico/updatesemestre" + "?id=" + $("#frm_saca_id").val();
    var arrParams = new Object();
    arrParams.id = $("#frm_saca_id").val();
    arrParams.nombre = $('#semestreacademico-saca_nombre').val();
    arrParams.descripcion = $('#semestreacademico-saca_descripcion').val();
    arrParams.ano = $('#semestreacademico-saca_anio').val();
    arrParams.estado = $('#semestreacademico-saca_estado').val();
    arrParams.intensivo = $('#frm_semestre_intensivo').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/semestreacademico/index";
                    }, 3000);
            }
        }, true);
    }
}*/