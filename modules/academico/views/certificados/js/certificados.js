/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $('#btn_buscarCertificado').click(function() {
        actualizarGridCertificadosGeneradas();
    });

    $('#btn_subircertificado').click(function() {
        subircertificado();
    });
    $('#btn_buscarCertGenerado').click(function() {
        actualizarGridCertGenerado();
    });
    $('#btn_grabar').click(function() {
        autorizarCertificado();
    });
    $('#btn_buscarCertPendiente').click(function() {        
        actualizarGridCertPendiente();
    });
    

    $('#cmb_unidad_cer').change(function() {
        var link = $('#txth_base').val() + "/academico/certificados/index";
        var arrParams = new Object();
        arrParams.unidad = $('#cmb_unidad_cer').val();
        //arrParams.moda_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad_cer", "Todos");
            }
        }, true);
    });

    $('#cmb_unidad_cergen').change(function() {
        var link = $('#txth_base').val() + "/academico/certificados/listadogenerados";
        var arrParams = new Object();
        arrParams.unidad = $('#cmb_unidad_cergen').val();
        //arrParams.moda_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad_cergen", "Todos");
            }
        }, true);
    });
    
    $('#cmb_estado_autoriza').change(function () {
        if ($('#cmb_estado_autoriza').val() == 4)
        {
            $('#Divobservacion').show();
        } else
        {
            $('#Divobservacion').hide();
        }
    });
    
    $('#cmb_unidad_certpend').change(function() {
        var link = $('#txth_base').val() + "/academico/certificados/listadopendientes";
        var arrParams = new Object();
        arrParams.unidad = $('#cmb_unidad_certpend').val();  
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad_certpend", "Todos");
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

function actualizarGridCertificadosGeneradas() {
    var search = $('#txt_buscarDataCertificado').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_cer').val();
    var modalidad = $('#cmb_modalidad_cer').val();
    var estdocerti = $('#cmb_estadocertificado_cer').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Certificados').PbGridView('applyFilterData', { 'f_ini': f_ini, 'f_fin': f_fin, 'unidad': unidad, 'modalidad': modalidad, 'search': search, 'estdocerti': estdocerti });
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var search = $('#txt_buscarDataCertificado').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_cer').val();
    var modalidad = $('#cmb_modalidad_cer').val();
    var estdocerti = $('#cmb_estadocertificado_cer').val();

    window.location.href = $('#txth_base').val() + "/academico/certificados/expexcelcertificado?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad + "&estdocerti=" + estdocerti;
}

function exportPdf() {
    var search = $('#txt_buscarDataCertificado').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_cer').val();
    var modalidad = $('#cmb_modalidad_cer').val();
    var estdocerti = $('#cmb_estadocertificado_cer').val();

    window.location.href = $('#txth_base').val() + "/academico/certificados/exppdfcertificado?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad + "&estdocerti=" + estdocerti;
}

function subircertificado() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/certificados/savecertificado";
    arrParams.cgen_id = $('#txth_cgenid').val();
    arrParams.documento = $('#txth_doc_certificado').val();
    arrParams.observacion = $('#txt_observa').val();
    arrParams.codigocerti = $('txt_codigocerti').val();
    arrParams.per_id = $('#txth_perid').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                parent.window.location.href = $('#txth_base').val() + "/academico/certificados/index";
            }, 2000);

        }, true);
    }
}

function actualizarGridCertGenerado() {
    var search = $('#txt_buscarDataCertificado').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_cergen').val();
    var modalidad = $('#cmb_modalidad_cergen').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_CertGenerado').PbGridView('applyFilterData', { 'f_ini': f_ini, 'f_fin': f_fin, 'unidad': unidad, 'modalidad': modalidad, 'search': search });
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelcert() {
    var search = $('#txt_buscarDataCertificado').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_cergen').val();
    var modalidad = $('#cmb_modalidad_cergen').val();

    window.location.href = $('#txth_base').val() + "/academico/certificados/expexcelcertificadogen?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad;
}

function exportPdfcert() {
    var search = $('#txt_buscarDataCertificado').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_cergen').val();
    var modalidad = $('#cmb_modalidad_cergen').val();

    window.location.href = $('#txth_base').val() + "/academico/certificados/exppdfcertificadogen?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad;
}


function autorizarCertificado() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/certificados/saveautorizacion";
    arrParams.cgen_id = $('#txth_cgenid').val();
    arrParams.resultado = $('#cmb_estado_autoriza').val();
    arrParams.observacion = $('#cmb_observacion').val();    
    arrParams.detobserva = $('#txt_observacion').val();    

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                parent.window.location.href = $('#txth_base').val() + "/academico/certificados/listadopendientes";
            }, 2000);

        }, true);
    }
}

function actualizarGridCertPendiente() {
    var search = $('#txt_buscarDataCertificado').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_certpend').val();
    var modalidad = $('#cmb_modalidad_certpend').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_CertPendiente').PbGridView('applyFilterData', { 'f_ini': f_ini, 'f_fin': f_fin, 'unidad': unidad, 'modalidad': modalidad, 'search': search });
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelcertpend() {
    var search = $('#txt_buscarDataCertificado').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_certpend').val();
    var modalidad = $('#cmb_modalidad_certpend').val();

    window.location.href = $('#txth_base').val() + "/academico/certificados/expexcelcertificadopend?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad;
}

function exportPdfcertpend() {
    var search = $('#txt_buscarDataCertificado').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_certpend').val();
    var modalidad = $('#cmb_modalidad_certpend').val();

    window.location.href = $('#txth_base').val() + "/academico/certificados/exppdfcertificadopend?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad;
}