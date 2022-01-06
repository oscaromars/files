$(document).ready(function() {

});

function deleteItem(id) {
    var link = $('#txth_base').val() + "/academico/estudioacademico/deletestudio";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            //searchModules('boxgrid', 'grid_list')
            //window.location = window.location.href;
            window.location.href = $('#txth_base').val() + "/academico/estudioacademico/index";
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}

function savestudioacademico() {
    var link = $('#txth_base').val() + "/academico/estudioacademico/savestudioacademico";
    var arrParams = new Object();
    arrParams.teac_id = $('#estudioacademico-teac_id').val();
    arrParams.eaca_nombre = $('#estudioacademico-eaca_nombre').val();
    arrParams.eaca_descripcion = $('#estudioacademico-eaca_descripcion').val();
    arrParams.eaca_alias_resumen = $('#estudioacademico-eaca_alias_resumen').val();
    arrParams.eaca_alias = $('#estudioacademico-eaca_alias').val();
    arrParams.estado = $('#estudioacademico-eaca_estado').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/estudioacademico/index";
                    }, 3000);
            }
        }, true);
    }
}

/*function updatedistributivohorario() {
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
}*/