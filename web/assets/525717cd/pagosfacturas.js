/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $('#btn_guardarpago').click(function () {
        guardarPagofactura();
    });
    $('#btn_grabar_rechazo').click(function () {
        rechazarPago();
    });
    $('#btn_buscarpago').click(function () {
        actualizarGridRevisionPago();
    });
    $('#btn_buscarpagoest').click(function () {
        actualizarGridPagoFactura();
    });    
    $('#cmb_estado').change(function () {
        if ($('#cmb_estado').val() == 3)
        {
            $('#Divobservacion').show();
        } else
        {
            $('#Divobservacion').hide();
        }
    });
    $('#btn_modificarpago').click(function () {
        modificarPagofactura();
    });
});
function guardarPagofactura() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagosfacturas/savepagopendiente";
    var selected = '';
    arrParams.estid = $('#txth_idest').val();
    arrParams.per_id = $('#txth_per').val();
    arrParams.referencia = $('#txt_referencia').val();
    arrParams.formapago = $('#cmb_formapago').val();
    arrParams.valor = $('#txt_valor').val();
    arrParams.fechapago = $('#txt_fechapago').val();
    arrParams.observacion = $('#txt_observa').val();
    arrParams.documento = $('#txth_doc_pago').val();
    $('#TbgPagopendiente input[type=checkbox]').each(function () {
        if (this.checked) {
            selected += $(this).val() + '*';
        }
    });
    arrParams.pagado = selected.slice(0, -1);
    if (arrParams.pagado != '') {

        if (arrParams.formapago == '0') {
            var mensaje = {wtmessage: "Método Pago : El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
        } else {
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function (response) {
                    showAlert(response.status, response.label, response.message);
                    setTimeout(function () {
                        parent.window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/viewsaldo";
                    }, 2000);
                }, true);
            }
        }
    } else {
        var mensaje = {wtmessage: "Datos Facturas Pendientes : Debe seleccionar las cuotas.", title: "Error"};
        showAlert("NO_OK", "error", mensaje);
    }
}

function rechazarPago() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagosfacturas/saverechazo";
    arrParams.dpfa_id = $('#txth_dpfa_id').val();
    arrParams.resultado = $('#cmb_estado').val();
    arrParams.observacion = $('#cmb_observacion').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                parent.window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/revisionpagos";
            }, 2000);
        }, true);
    }
}

function actualizarGridRevisionPago() {
    var search = $('#txt_buscarDataEstudiante').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_revpago').val();
    var modalidad = $('#cmb_modalidad_revpago').val();
    var estadopago = $('#cmb_estado_revpago').val();
    var estadofinanciero = $('#cmb_estado_financiero').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Revisionpago').PbGridView('applyFilterData', {'search': search, 'f_ini': f_ini, 'f_fin': f_fin, 'unidad': unidad, 'modalidad': modalidad, 'estadopago': estadopago, 'estadofinanciero': estadofinanciero});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelrevpago() {
    var search = $('#txt_buscarDataEstudiante').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_revpago').val();
    var modalidad = $('#cmb_modalidad_revpago').val();
    var estadopago = $('#cmb_estado_revpago').val();
    var estadofinanciero = $('#cmb_estado_financiero').val();

    window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/expexcelfacpendiente?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad + "&estadopago=" + estadopago + "&estadofinanciero=" + estadofinanciero;
}

function exportPdfrevpago() {
    var search = $('#txt_buscarDataEstudiante').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad_revpago').val();
    var modalidad = $('#cmb_modalidad_revpago').val();
    var estadopago = $('#cmb_estado_revpago').val();
    var estadofinanciero = $('#cmb_estado_financiero').val();

    window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/exppdffacpendiente?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad + "&estadopago=" + estadopago + "&estadofinanciero=" + estadofinanciero;
}
function modificarPagofactura() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagosfacturas/modificarpagopendiente";
    arrParams.estid = $('#txth_idest').val();
    arrParams.per_id = $('#txth_per').val();
    arrParams.pfes_id = $('#txth_pfes_id').val();
    arrParams.referencia = $('#txt_referencia').val();
    arrParams.formapago = $('#cmb_formapago').val();
    arrParams.valor = $('#txt_valor').val();
    arrParams.fechapago = $('#txt_fechapago').val();
    arrParams.observacion = $('#txt_observa').val();
    arrParams.documento = $('#txth_doc_pago').val();
    arrParams.pfesid = $('#txth_pfesid').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                parent.window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/detallepagosfactura?pfes_id="+arrParams.pfesid ;
            }, 2000);
        }, true);
    }
}

function actualizarGridPagoFactura() {    
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();    
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_PagosFacturas').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin});
        setTimeout(hideLoadingPopup, 2000);
    }
}