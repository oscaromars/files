/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 var stripe;
 var cardElement;
$(document).ready(function () {
    $("#txt_valor").val(0);

    $('#btn_guardarpago').click(function () {
        guardarPagofactura();
    });
    $('#btn_grabar_rechazo').click(function () {
        rechazarPago();
    });
    $('#btn_buscarpago').click(function () {
        actualizarGridRevisionPago();
    });
    $('#btn_buscarpagofactura').click(function () {
        actualizarGridPagofactura();
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

    $('#btn_guardarcartera').click(function () {
        cargarCartera();
    });

    $('#btn_guardareverso').click(function () {
        ReversarEstado();
    });

    $('#cmb_unidad_facpago').change(function () {
        var link = $('#txth_base').val() + "/financiero/pagosfacturas/pagosfactura";
        var arrParams = new Object();
        //arrParams.unidada = $('#cmb_unidadbus').val();
        arrParams.unidada = $(this).val();        
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad_pagofac", "Todos");
            }
        }, true);
    });

    $("#cmb_formapago").on('change', function(){   
        var opcion = $('#cmb_formapago').val();

        if(opcion==1){
            $('#txt_fechapago').removeClass('PBvalidation');
            $('#pago_documento').hide();           
            //txth_doc_pago
            $('#pago_stripe').show();
        }else{
            $('#txt_fechapago').addClass('PBvalidation');
            $('#pago_documento').show();

            $('#pago_stripe').hide();
        }
    });

    $('#TbgPagopendiente input[type=checkbox]').click(function () {
        var valor = $("#txt_valor").val();
        var td    = $(this).parent().parent().find('td')[3];
        if (this.checked) 
            valor     = parseFloat(valor)  + parseFloat($(td).html());
        else
            valor     = parseFloat(valor)  - parseFloat($(td).html());
        
        if(valor < 0)
            valor = 0;

        $("#txt_valor").val(parseFloat(valor).toFixed(2));
        //console.log($(td).html());
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

function guardarPagofactura() {
    var arrParams = new Object();
    var link      = $('#txth_base').val() + "/financiero/pagosfacturas/savepagopendiente";
    var selected  = '';

    arrParams.nombres     = $('#txt_nombres').val();
    arrParams.estid       = $('#txth_idest').val();
    arrParams.per_id      = $('#txth_per').val();
    arrParams.referencia  = $('#txt_referencia').val();
    arrParams.formapago   = $('#cmb_formapago').val();
    arrParams.valor       = $('#txt_valor').val();
    arrParams.observacion = $('#txt_observa').val();
    
    //Pregunto por el metodo de pago
    if (arrParams.formapago == 0) {
        var mensaje = {wtmessage: "Método Pago : El campo no debe estar vacío.", title: "Error"};
        showAlert("NO_OK", "error", mensaje);
        return false;
    }//if

    //Pregunto por el valor
    if (arrParams.valor == '') {
        var mensaje = {wtmessage: "Valor : El campo no debe estar vacío.", title: "Error"};
        showAlert("NO_OK", "error", mensaje);
        return false;
    }//if

    //Este segmento es para saber si ha elegido cuotas
    $('#TbgPagopendiente input[type=checkbox]').each(function () {
        if (this.checked) 
            selected += $(this).val() + '*';
    });

    arrParams.pagado = selected.slice(0, -1);
    if (arrParams.pagado == '') {
        var mensaje = {wtmessage: "Datos Facturas Pendientes : Debe seleccionar las cuotas.", title: "Error"};
        showAlert("NO_OK", "error", mensaje);
        return false;
    }//if

    //Pregunto si es pago stripe
    if($('#cmb_formapago').val() != 1 ){
        //Si es por documentos cargo la fecha y el documento
        arrParams.fechapago = $('#txt_fechapago').val();
        arrParams.documento = $('#txth_doc_pago').val();
               
        if (arrParams.documento.length == 0) {
            var mensaje = {wtmessage: "Adjuntar Documento  : El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
            return false;
        }

         if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);

                setTimeout(function () {
                    parent.window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/viewsaldo";
                }, 2000);

            }, true);
        }//if
    }else{
        //Si es por STRIPE realizo las verificaciones del caso
        try{

            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    console.log(result);

                    var mensaje = {wtmessage: '<p>'+result.error.message+'</p>', title: "Error"};
                    showAlert("NO_OK", "error", mensaje);
                    return false;
                } else {
                    arrParams.token = result.token.id;
                    if (!validateForm()) {
                        requestHttpAjax(link, arrParams, function (response) {
                            showAlert(response.status, response.label, response.message);
                            /*
                            setTimeout(function () {
                                parent.window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/viewsaldo";
                            }, 2000);
                            */
                        }, true);
                    }//if
                }//else
            });//stripe
        }catch(err){
            var mensaje = {wtmessage: err+" ///catch", title: "Error"};
            showAlert("NO_OK", "error1", mensaje);
            return false;
        }
    }//else
}//function guardarPagofactura

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

function actualizarGridPagofactura() {
    var search = $('#txt_buscarDataEstudiantepag').val();
    var f_ini = $('#txt_fecha_inipag').val();
    var f_fin = $('#txt_fecha_finpag').val();
    var unidad = $('#cmb_unidad_facpago').val();
    var modalidad = $('#cmb_modalidad_pagofac').val();
    var estadopago = $('#cmb_estado_pagofac').val();   
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Pagofactura').PbGridView('applyFilterData', {'search': search, 'f_ini': f_ini, 'f_fin': f_fin, 'unidad': unidad, 'modalidad': modalidad, 'estadopago': estadopago});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelpagofactura() {
    var search = $('#txt_buscarDataEstudiantepag').val();
    var f_ini = $('#txt_fecha_inipag').val();
    var f_fin = $('#txt_fecha_finpag').val();
    var unidad = $('#cmb_unidad_facpago').val();
    var modalidad = $('#cmb_modalidad_pagofac').val();
    var estadopago = $('#cmb_estado_pagofac').val();   

    window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/expexcelpagfactura?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad + "&estadopago=" + estadopago;
}

function exportPdfpagofactura() {
    var search = $('#txt_buscarDataEstudiantepag').val();
    var f_ini = $('#txt_fecha_inipag').val();
    var f_fin = $('#txt_fecha_finpag').val();
    var unidad = $('#cmb_unidad_facpago').val();
    var modalidad = $('#cmb_modalidad_pagofac').val();
    var estadopago = $('#cmb_estado_pagofac').val();
    
    window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/exppdfpagfactura?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad + "&estadopago=" + estadopago;
}

function cargarCartera() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagosfacturas/cargarcartera";
    arrParams.procesar_file = true;
    arrParams.emp_id = $('#cmb_empresa option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_cartera2').val() + "." + $('#txth_doc_adj_cartera').val().split('.').pop();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/cargarcartera";
            }, 5000);
        }, true);
    }
}

function ReversarEstado() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagosfacturas/reversarestado";  
    arrParams.dpfa_id = $('#txth_dpfa_id').val();
    arrParams.resultado = $('#cmb_estadorev option:selected').val();
    arrParams.observacion = $('#txt_observacion').val();    
    if (arrParams.resultado == '0') {
        var mensaje = {wtmessage: "Resultado : Debe seleccionar el resultado.", title: "Error"};
        showAlert("NO_OK", "error", mensaje);
    }
   else {
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/financiero/pagosfacturas/revisionpagos";
            }, 5000);
        }, true);
    }
 }   
}