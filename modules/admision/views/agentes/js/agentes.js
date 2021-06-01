function guardarAgente() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/admision/agentes/savereasigna";
    arrParams.agente_nuevo = $('#cmb_agente').val();
    arrParams.opor_id = $('#txth_idop').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                parent.window.location.href = $('#txth_base').val() + "/admision/oportunidades/index";
            }, 2000);
        }, true);
    }
}