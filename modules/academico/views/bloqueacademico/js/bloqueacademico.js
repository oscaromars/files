$(document).ready(function() {

});

function savebloque() {
    var link = $('#txth_base').val() + "/academico/bloqueacademico/savebloque";
    var arrParams = new Object();
    arrParams.baca_nombre = $('#bloqueacademico-baca_nombre').val();
    arrParams.baca_descripcion = $('#bloqueacademico-baca_descripcion').val();
    arrParams.baca_anio = $('#bloqueacademico-baca_anio').val();
    
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

function updatebloque() {
    var link = $('#txth_base').val() + "/academico/bloqueacademico/updatebloque";
    var arrParams = new Object();
    arrParams.id = $("#frm_baca_id").val();
    arrParams.baca_nombre = $('#bloqueacademico-baca_nombre').val();
    arrParams.baca_descripcion = $('#bloqueacademico-baca_descripcion').val();
    arrParams.baca_anio = $('#bloqueacademico-baca_anio').val();

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

function eliminarbloque(id) {
    var link = $('#txth_base').val() + "/academico/bloqueacademico/eliminarbloque";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            window.location.href = $('#txth_base').val() + "/academico/bloqueacademico/index";
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}