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

function updatestudioacademico() {
    var link = $('#txth_base').val() + "/academico/estudioacademico/updatestudioacademico";
    var arrParams = new Object();
    arrParams.id = $("#frm_eaca_id").val();
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