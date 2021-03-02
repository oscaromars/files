
$(document).ready(function () {
    /**
     * Function evento click en botón de Revisarpagocarga
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return 
     */
    // BORRAR LUEGO
    $('#btn_enviar').click(function () {
        var arrParams = new Object();
        var link = $('#txth_base').val() + "/financiero/pagos/savepago";
        arrParams.opag_id = $('#txth_ids').val();
        arrParams.estado_revision = $('#cmb_revision').val();
        arrParams.valor = $('#txth_val').val();
        arrParams.valorpagado = $('#txth_valp').val();
        arrParams.valortotal = $('#txth_valt').val();
        if ($('#cmb_revision').val() == "AP")
        {
            arrParams.observacion = "";
        } else
        {
            arrParams.observacion = $('#cmb_observacion').val();
        }
        arrParams.idd = $('#txth_idd').val();
        arrParams.int_id = $('#txth_int').val();
        arrParams.sins_id = $('#txth_sins').val();
        arrParams.per_id = $('#txth_perid').val();

        arrParams.controladm = '0';

        if ($('#cmb_revision').val() == "AP") {
            $('#cmb_observacion').removeClass("PBvalidation");
            arrParams.banderacrea = '1';
            if ((arrParams.valorpagado > 0) && (arrParams.valortotal > 0) && ((arrParams.valorpagado + arrParams.valor) > arrParams.valortotal)) {
                alert('Con este pago sobrepasa al valor total aprobado y pendiente.');
                return;
            }
        } else {
            arrParams.banderacrea = '0';
        }

        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                $('#cmb_revision').val(0);
                $('#cmb_observacion').val(0);
                $('#txth_ids').val("");
                showAlert(response.status, response.label, response.message);

                setTimeout(function () {
                    parent.window.location.href = $('#txth_base').val() + "/financiero/pagos/validarpagocarga?ido=" + arrParams.opag_id;
                }, 2000);

            }, true);
        }
    });

    function cerrarpopup() {
        $('.mfp-close').trigger('click');
    }
    // BORRAR LUEGO
    /**
     * Function evento click en botón de Registrarpagoadm
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return 
     */
    // BORRAR LUEGO
    $('#cmd_registrarPagoadm').click(function () {
        var arrParams = new Object();
        var link = $('#txth_base').val() + "/financiero/pagos/savepago";
        var valor_pendiente = $('#txth_saldo_pendiente').val();

        arrParams.opag_id = $('#txth_ids').val();
        arrParams.totpago = $('#txth_total').val();
        arrParams.valor = $('#txt_pago').val();
        arrParams.forma_pago = $('#cmb_forma_pago').val();
        arrParams.numero_transaccion = $('#txt_numero_transaccion').val();
        arrParams.fecha_transaccion = $('#txt_fecha_transaccion').val();
        arrParams.estado_revision = "AP";
        arrParams.documento = $('#txth_doc_titulo').val();
        arrParams.observacion = "";
        arrParams.int_id = $('#txth_int').val();
        arrParams.sins_id = $('#txth_sins').val();
        arrParams.per_id = $('#txth_perid').val();
        arrParams.banderacrea = '1';
        arrParams.controladm = '1';
        if (parseFloat(arrParams.valor) > parseFloat(arrParams.totpago))
        {
            alert("Esta tratando de ingresar un pago mayor al valor de su servicio. " + parseFloat(arrParams.totpago));
        } else if (parseFloat(arrParams.valor) < parseFloat(arrParams.totpago))
        {
            alert("Esta tratando de ingresar un pago mayor a su valor pendiente. " + parseFloat(valor_pendiente));
        } else {
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function (response) {
                    showAlert(response.status, response.label, response.message);
                    setTimeout(function () {
                        parent.window.location.href = $('#txth_base').val() + "/financiero/pagos/indexadm";
                    }, 2000);

                }, true);
            }
        }
    });


    $('#btn_validapago').click(function () {
        alert('Con este pago sobrepasa al valor total aprobado y pendiente.');
    });

    $('#cmb_revision').change(function () {
        if ($('#cmb_revision').val() == 'RE')
        {
            $('#Divobservalbl').show();
            $('#Divobservacmb').show();
        } else
        {
            $('#Divobservalbl').hide();
            $('#Divobservacmb').hide();
        }
    });
    $('#btn_buscarData').click(function () {
        actualizarGrid();
    });
    $('#btn_buscarDataPago').click(function () {
        actualizarGridPagoadm();
    });
    $('#btn_buscarDataReg').click(function () {
        actualizarGridPagoReg();
    });
    $('#btn_buscarPagoscargados').click(function () {
        actualizarGridPagosCargados();
    });
    $('#btn_buscarDataHist').click(function () {
        actualizarGridHistorial();
    });
    $('#btn_buscarDataPagoext').click(function () {
        actualizarGridPagoExterno();
    });
});

function divComentario(data) {
    //$("#countMensaje").html(data.length);
    var option_arr = '';
    option_arr += '<div style="overflow-y: scroll;height:200px;">';
    option_arr += '<div class="post clearfix">';
    option_arr += '<div class="user-block">';
    option_arr += '<span>';
    //option_arr += '<a href="#">'+(data[i]["Nombres"]).toUpperCase()+'</a>';
    //option_arr += '<a onclick="deleteComentario(\'' + data[i]['Ids'] + '\')" class="pull-right btn-box-tool" href="#"><i class="fa fa-times"></i></a>';
    option_arr += '</span><br>';
    //option_arr += '<span>'+(data[i]["fecha"]).toUpperCase()+'</span>';
    option_arr += '</div>';
    option_arr += '<p>' + (data).toUpperCase() + '</p>';
    option_arr += '</div>';
    option_arr += '</div>';
    showAlert("OK", "info", {"wtmessage": option_arr, "title": "Observaciones"});
}
function saveBills() {
    //Verificamos que el Documento Ingresado sea Ingual al valor Generado
    if ($('#txth_opag_total').val() == $('#txt_rpfa_valor_documento').val()) {
        cargarFactura();
    } else {
        showAlert("NO_OK", "error", {"wtmessage": "Valor de factura es incorrecto", "title": "Observaciones"});
    }
}
function actualizar_pago(doc_id) {
    alert(doc_id);
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagos/actualizarpago";
        arrParams.doc_idd = doc_id;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                actualizarGridPagoExterno();
            }, 3000);
        }, true);
    }
}
function exportExcel() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var f_estado = $('#cmb_estado').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/expexcel?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&f_estado=" + f_estado;
}

function enviardata() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagos/savecarga";
    var idsol = $('#txth_idsol').val();
    var pg = $('#txth_pg').val();
    arrParams.idpago = $('#txth_ids').val();
    arrParams.totpago = $('#txth_tot').val();
    arrParams.pago = $('#txt_pago').val();
    arrParams.documento = $('#txth_doc_titulo').val();
    arrParams.metodopago = $('#cmb_forma_pago').val();
    arrParams.numtransaccion = $('#txt_numtransaccion').val();
    arrParams.fechatransaccion = $('#txt_fecha_transaccion').val();
    arrParams.vista = $('#txth_vista').val();
    arrParams.empresa = $('#txth_empid').val();
    arrParams.observacion = $('#txt_observa').val();

    if (parseFloat(arrParams.pago) > parseFloat(arrParams.totpago))
    {
        alert("Está tratando de ingresar un pago mayor al valor de su servicio. $" + parseFloat(arrParams.totpago));
    } else if (parseFloat(arrParams.pago) < parseFloat(arrParams.totpago))
    {
        alert("Está tratando de ingresar un pago menor al valor de su servicio. $" + parseFloat(arrParams.totpago));
    } else {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    if (arrParams.vista == 'adm') {
                        parent.window.location.href = $('#txth_base').val() + "/financiero/pagos/index";
                    } else {
                        parent.window.location.href = $('#txth_base').val() + "/financiero/pagos/listarpagosolicitud?id_sol=" + idsol;
                    }
                }, 4000);
            }, true);
        }
    }
}
function actualizarGrid() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_SOLICITUD').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}
function actualizarGridPagoadm() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var f_estado = $('#cmb_estado').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Solicitudes').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'f_estado': f_estado, 'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}
function actualizarGridPagoReg() {
    var search = $('#txt_buscarDataReg').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();

    //Buscar al menos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Solicitudes').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}
function actualizarGridPagosCargados() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var f_estado = $('#cmb_estado').val();
    //Buscar al menos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Solicitudes').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'f_estado': f_estado, 'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}
function actualizarGridHistorial() {
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_HISTORIAL_TRANSACCIONES').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function actualizarGridPagoExterno() {
    var search = $('#txt_buscarDataPagosExternos').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    //var f_estado = $('#cmb_estado').val();
    //Buscar al menos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_VERFICAR_PAGOS_EXTERNOS').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin/*, 'f_estado': f_estado*/, 'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}
//Guarda carga de archivos
function SaveCarga() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagos/savecarga";
    var idpago = $('#txth_ids').val();
    var pg = $('#txth_pg').val();
    arrParams.idpago = $('#txth_ids').val();
    arrParams.totpago = $('#txth_tot').val();
    arrParams.pago = $('#txt_pago').val();
    arrParams.documento = $('#txth_doc_titulo').val();
    arrParams.metodopago = $('#cmb_forma_pago').val();
    arrParams.numtransaccion = $('#txt_numtransaccion').val();
    arrParams.fechatransaccion = $('#txt_fecha_transaccion').val();
    arrParams.vista = $('#txth_vista').val();

    if (parseFloat(arrParams.pago) > parseFloat(arrParams.totpago))
    {
        alert("Está tratando de ingresar un pago mayor al valor de su servicio. $" + parseFloat(arrParams.totpago));
    } else if (parseFloat(arrParams.pago) < parseFloat(arrParams.totpago))
    {
        alert("Está tratando de ingresar un pago menor al valor de su servicio. $" + parseFloat(arrParams.totpago));
    } else {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    if (arrParams.vista == 'adm') {
                        parent.window.location.href = $('#txth_base').val() + "/financiero/pagos/index";
                    } else {
                        parent.window.location.href = $('#txth_base').val() + "/financiero/pagos/listarpagosolicitud";
                    }
                }, 4000);
            }, true);
        }
    }
}
// VALIDA PAGOS
function Savepagos() {

    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagos/savepago";
    arrParams.opag_id = $('#txth_ids').val();
    arrParams.estado_revision = $('#cmb_revision').val();
    arrParams.valor = $('#txth_val').val();
    arrParams.valorpagado = $('#txth_valp').val();
    arrParams.valortotal = $('#txth_valt').val();
    if ($('#cmb_revision').val() == "AP")
    {
        arrParams.observacion = "";
    } else
    {
        arrParams.observacion = $('#cmb_observacion').val();
    }
    arrParams.idd = $('#txth_idd').val();
    arrParams.int_id = $('#txth_int').val();
    arrParams.sins_id = $('#txth_sins').val();
    arrParams.per_id = $('#txth_perid').val();

    arrParams.controladm = '0';

    if ($('#cmb_revision').val() == "AP") {
        $('#cmb_observacion').removeClass("PBvalidation");
        arrParams.banderacrea = '1';
        if ((arrParams.valorpagado > 0) && (arrParams.valortotal > 0) && ((arrParams.valorpagado + arrParams.valor) > arrParams.valortotal)) {
            alert('Con este pago sobrepasa al valor total aprobado y pendiente.');
            return;
        }
    } else {
        arrParams.banderacrea = '0';
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            $('#cmb_revision').val(0);
            $('#cmb_observacion').val(0);
            $('#txth_ids').val("");
            showAlert(response.status, response.label, response.message);

            setTimeout(function () {
                parent.window.location.href = $('#txth_base').val() + "/financiero/pagos/validarpagocarga?ido=" + arrParams.opag_id;
            }, 2000);

        }, true);
    }
}
// VALIDA PAGOS ADM
function Savepagosadm() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagos/savepago";
    var valor_pendiente = $('#txth_saldo_pendiente').val();

    arrParams.opag_id = $('#txth_ids').val();
    arrParams.totpago = $('#txth_total').val();
    arrParams.valor = $('#txt_pago').val();
    arrParams.forma_pago = $('#cmb_forma_pago').val();
    arrParams.numero_transaccion = $('#txt_numero_transaccion').val();
    arrParams.fecha_transaccion = $('#txt_fecha_transaccion').val();
    arrParams.estado_revision = "AP";
    arrParams.documento = $('#txth_doc_titulo').val();
    arrParams.observacion = "";
    arrParams.int_id = $('#txth_int').val();
    arrParams.sins_id = $('#txth_sins').val();
    arrParams.per_id = $('#txth_perid').val();
    arrParams.banderacrea = '1';
    arrParams.controladm = '1';

    if (parseFloat(arrParams.valor) > parseFloat(arrParams.totpago))
    {
        alert("Esta tratando de ingresar un pago mayor al valor de su servicio. " + parseFloat(arrParams.totpago));
    } else if (parseFloat(arrParams.valor) > parseFloat(valor_pendiente))
    {
        alert("Esta tratando de ingresar un pago mayor a su valor pendiente. " + parseFloat(valor_pendiente));
    } else {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    parent.window.location.href = $('#txth_base').val() + "/financiero/pagos/indexadm";
                }, 2000);

            }, true);
        }
    }
}


function cargarFactura() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagos/savefactura";
    arrParams.procesar_file = true;
    arrParams.sins_id = $('#txt_rpfa_num_solicitud').val();//$('#txth_sins_id').val();
    arrParams.rpfa_num_solicitud = $('#txt_rpfa_num_solicitud').val();
    arrParams.rpfa_valor_documento = $('#txt_rpfa_valor_documento').val();
    arrParams.rpfa_numero_documento = $('#txt_rpfa_numero_documento').val();
    arrParams.rpfa_fecha_documento = $('#txt_rpfa_fecha_documento').val();
    arrParams.documento = "facturas/" + $('#txth_per').val() + "/" + $('#txth_doc_titulo').val();
    //arrParams.archivo = $('#txth_doc_adj_leads2').val() + "." + $('#txth_doc_adj_leads').val().split('.').pop();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                window.location.href = $('#txth_base').val() + "/financiero/pagos/index";
            }, 3000);
        }, true);
    }
}

function exportExcelPagos() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var f_estado = $('#cmb_estado').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/expexcelpagos?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&f_estado=" + f_estado;
}
function exportExcelColec() {
    var search = $('#txt_buscarDataReg').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/expexcelcolec?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin;
}
function generarSolicitud(doc_id) {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/financiero/pagos/generarsolicitud";
    arrParams.doc_id = doc_id;    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                //actualizarGridPagoExterno();
                window.location.href = $('#txth_base').val() + "/financiero/pagos/verificarpagoexterno";
            }, 3000);
        }, true);
    }
}
function exportPdf() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var f_estado = $('#cmb_estado').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/exppdfpagosestud?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&f_estado=" + f_estado;
}
function exportPdfpagos() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var f_estado = $('#cmb_estado').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/exppdfpagos?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&f_estado=" + f_estado;
}
function exportPdfColec() {
    var search = $('#txt_buscarDataReg').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/expexcelcolec?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin;
}
function exportExcelhis() {
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/expexcelhis?f_ini=" + f_ini + "&f_fin=" + f_fin;
}
function exportPdfhis() {
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/exppdfhis?pdf=1&f_ini=" + f_ini + "&f_fin=" + f_fin;
}
function exportExcelvpex() {
    var search = $('#txt_buscarDataPagosExternos').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/expexcelpagosext?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin;
}
function exportPdfvpex() {
    var search = $('#txt_buscarDataPagosExternos').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    window.location.href = $('#txth_base').val() + "/financiero/pagos/exppdfpagosext?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin;
}