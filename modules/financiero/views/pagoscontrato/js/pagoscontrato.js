
$(document).ready(function () {
    $('#btn_buscarDatamat').click(function () {
        actualizarGrid();
    });

    $('#btn_guardarcontrato').click(function () {
        SaveContrato();
    });
    /***********************************************/
    /* Filtro para busqueda                        */
    /***********************************************/

    $('#cmb_unidadmat').change(function () {
        var link = $('#txth_base').val() + "/fianciero/pagoscontrato/index";
        document.getElementById("cmb_carreramat").options.item(0).selected = 'selected';
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidadmat", "Todos");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidadmat').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "cmb_carreramat", "Todos");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidadmat').change(function () {
        var link = $('#txth_base').val() + "/financiero/pagoscontrato/index";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidadmat').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "cmb_carreramat", "Todos");
            }
        }, true);
    });
});

function setComboDataselect(arr_data, element_id, texto) {
    var option_arr = "";
    option_arr += "<option value= '0'>" + texto + "</option>";
    for (var i = 0; i < arr_data.length; i++) {
        var id = arr_data[i].id;
        var value = arr_data[i].name;

        option_arr += "<option value='" + id + "'>" + value + "</option>";
    }
    $("#" + element_id).html(option_arr);
}
function actualizarGrid() {
    var search = $('#txt_buscarDatamatri').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidadmat option:selected').val();
    var modalidad = $('#cmb_modalidadmat option:selected').val();
    var carrera = $('#cmb_carreramat option:selected').val();
    var periodo = $('#txt_periodomat').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_PAGOS').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'search': search, 'unidad': unidad, 'modalidad': modalidad, 'carrera': carrera, 'periodo': periodo});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var search = $('#txt_buscarDatamatri').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidadmat option:selected').val();
    var modalidad = $('#cmb_modalidadmat option:selected').val();
    var carrera = $('#cmb_carreramat option:selected').val();
    var periodo = $('#txt_periodomat').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagoscontrato/expexcel?search=" + search + "&fecha_ini=" + f_ini + "&fecha_fin=" + f_fin + "&unidad=" + unidad + "&modalidad=" + modalidad + "&carrera=" + carrera + "&periodo=" + periodo;
}

function exportPdf() {
    var search = $('#txt_buscarDatamatri').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidadmat option:selected').val();
    var modalidad = $('#cmb_modalidadmat option:selected').val();
    var carrera = $('#cmb_carreramat option:selected').val();
    var periodo = $('#txt_periodomat').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagoscontrato/exppdf?pdf=1&search=" + search + "&fecha_ini=" + f_ini + "&fecha_fin=" + f_fin + "&unidad=" + unidad + "&modalidad=" + modalidad + "&carrera=" + carrera + "&periodo=" + periodo;
}

//Guarda Documento de contrato.
function SaveContrato() {
    var link = $('#txth_base').val() + "/financiero/pagoscontrato/savecontrato";
    var arrParams = new Object();
    arrParams.persona_id = $('#txth_per').val();
    arrParams.adm_id = $('#txth_admid').val();
    arrParams.arc_doc_contrato = $('#txth_doc_contrato').val();
    arrParams.convenio = $('#cmb_convenio option:selected').val();
    if (arrParams.arc_doc_contrato == null || arrParams.convenio == 0) {
        showAlert('NO_OK', 'error', {"wtmessage": "Los datos de convenio y documento son obligatorio.", "title": 'Error'});
    } else {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/financiero/pagoscontrato/index";
                }, 5000);
            }, true);
        }
    }

}
function downloadPdfs(ref) {
    var href = $(ref).attr('data-href');
    document.location.href = href;
}