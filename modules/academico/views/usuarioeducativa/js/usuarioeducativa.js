$(document).ready(function() {
    $('#btn_guardareducativa').click(function () {
        cargarUsuario();
    });
});

function cargarUsuario() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/cargarusuario";
    arrParams.procesar_file = true;
    arrParams.emp_id = $('#cmb_empresa option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_educativa2').val() + "." + $('#txth_doc_adj_educativa').val().split('.').pop();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/cargarusuario";
            }, 5000);
        }, true);
    }
}