/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 *
 * @returns {voids}
 * Created: Kleber Loayza(kloayza@uteg.edu.ec)
 * date: Oct/23/18
 */
function habilitarSecciones() {
    //var pais = $('#cmb_pais_dom').val();
    var pais = 1;
    var unidad = $('#cmb_unidad_solicitud').val();
    if ((pais == 1) && (unidad == 1)) {
        $('#divCertvota').css('display', 'block');
    } else {
        $('#divCertvota').css('display', 'none');
    }
}
$(document).ready(function () {
    // para mostrar codigo de area
    $('#btn_pago_i').css('display', 'none');
    var unisol = $('#cmb_unidad_solicitud').val();
    if (unisol == 1) {
        $('#divmetodocan').css('display', 'none');
    } else if (unisol == 2) {
        $('#divmetodocan').css('display', 'block');
    }
    $('#cmb_convenio_empresa').change(function () {
        if ($('#cmb_convenio_empresa').val() != 0) {
            $('#divDocumAceptacion').css('display', 'block');
        } else {
            $('#divDocumAceptacion').css('display', 'none');
        }
        ;
    });

    $('#cmb_pais_dom').change(function () {
        var link = $('#txth_base').val() + "/inscribeducacioncontinua/index";
        var arrParams = new Object();
        arrParams.codarea = $(this).val();
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                $('#txt_codigoarea').val(data.area['name']);
            }
        }, true);
    });
    $('#sendInformacionAspirante').click(function () {
        habilitarSecciones();
        if ($('#txth_twin_id').val() == 0) {
            guardarInscripcion('Create', '1');
        } else {
            guardarInscripcion('Update', '1');
        }
    });

    $('#sendInformacionAspirante2').click(function () {
        var error = 0;
        //var pais = $('#cmb_pais_dom').val();
        var pais = 1;
        if ($("#chk_mensaje1").prop("checked") && $("#chk_mensaje2").prop("checked")) {
            error = 0;
        } else {
            var mensaje = {wtmessage: "Debe Aceptar los términos de la Información.", title: "Exito"};
            error++;
            showAlert("NO_OK", "success", mensaje);
        }
        if ($('#txth_doc_titulo').val() == "") {
            error++;
            var mensaje = {wtmessage: "Debe adjuntar título.", title: "Información"};
            showAlert("NO_OK", "error", mensaje);
        } else {
            if ($('#txth_doc_dni').val() == "") {
                error++;
                var mensaje = {wtmessage: "Debe adjuntar documento de identidad.", title: "Información"};
                showAlert("NO_OK", "error", mensaje);
            } else {
                if ($('#cmb_tipo_dni').val() == "CED") {
                    if ((pais == 1) && ($('#cmb_unidad_solicitud').val() == 1)) {
                        if ($('#txth_doc_certvota').val() == "") {
                            error++;
                            var mensaje = {wtmessage: "Debe adjuntar certificado de votación.", title: "Información"};
                            showAlert("NO_OK", "error", mensaje);
                        }
                    } else {
                        if ($('#txth_doc_foto').val() == "") {
                            error++;
                            var mensaje = {wtmessage: "Debe adjuntar foto.", title: "Información"};
                            showAlert("NO_OK", "error", mensaje);
                        }
                    }
                }
            }
        }
        if ($('#cmb_convenio_empresa').val() > 0) {
            if ($('#txth_doc_aceptacion').val() == "") {
                error++;
                var mensaje = {wtmessage: "Debe adjuntar documento de aceptación.", title: "Información"};
                showAlert("NO_OK", "error", mensaje);
            }
        }
        if (error == 0) {
            guardarInscripcion('Update', '2');
        }
    });

    $('#sendInscripcionsolicitud').click(function () {
        var link = $('#txth_base').val() + "/inscribeducacioncontinua/saveinscripciontemp";
        var arrParams = new Object();
        arrParams.codigo = $('#txth_twin_id').val();
        arrParams.ACCION = 'Fin';
        arrParams.nombres_fact = $('#txt_nombres_fac').val();
        arrParams.apellidos_fact = $('#txt_apellidos_fac').val();
        arrParams.direccion_fact = $('#txt_dir_fac').val();
        arrParams.telefono_fac = $('#txt_tel_fac').val();
        var tipo_dni_fact = "";
        if ($('#opt_tipo_DNI option:selected').val() == "1") {
            tipo_dni_fact = "CED";
        } else if ($('#opt_tipo_DNI option:selected').val() == "2") {
            tipo_dni_fact = "PASS";
        } else {
            tipo_dni_fact = "RUC";
        }
        arrParams.tipo_dni_fac = tipo_dni_fact;
        arrParams.dni = $('#txt_dni_fac').val();
        arrParams.empresa = $('#txt_empresa').val();
        arrParams.correo = $('#txt_correo_fac').val();
        arrParams.nivinstrucion = $('#cmb_instruccion option:selected').val();
        arrParams.redes = $('#cmb_redes_sociales option:selected').val();
        arrParams.encontramos = $('#txt_encontramos').val();
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                var message = response.message;
                  if (response.status == "OK") {
                    setTimeout(function () {
                        var uaca_id = parseInt(response.data.data.uaca_id);
                        var mod_id = parseInt(response.data.data.mod_id);
                        var ming = parseInt(response.data.data.twin_metodo_ingreso);
                        var sins_id = parseInt(response.data.dataext);
                        if ($('input[name=rdo_forma_pago_dinner]:checked').val() == 1) {
                            PagoDinners(sins_id);
                        } else {
                            switch (uaca_id) { /** OJO ESTO HAY UNOS LINK */
                                case 1:
                                    switch (mod_id) {
                                        case 1: //online
                                            window.location.href = "https://www.uteg.edu.ec/pagos-grado-online/";
                                            break;
                                        case 2:// presencial
                                            window.location.href = "https://www.uteg.edu.ec/pago-grado-presencial/";
                                            break;
                                        case 3:// semipresencial
                                            window.location.href = "https://www.uteg.edu.ec/pago-grado-semipresencial/";
                                            break;
                                        case 4: //distancia
                                            window.location.href = "https://www.uteg.edu.ec/pago-grado-distancia/";
                                            break;
                                    }
                                    break;
                                case 2:
                                    $('#tx_paypal').attr("href", "https://www.uteg.edu.ec/pago-posgrado/")
                                    $('#tx_paypal').val("https://www.uteg.edu.ec/pago-posgrado/");
                                    window.location.href = "https://www.uteg.edu.ec/pago-posgrado/";
                                    break;
                            }
                        }
                    }, 5000);
                }
            });
        }
    });


    $('#cmb_tipo_dni').change(function () {
        if ($('#cmb_tipo_dni').val() == 'PASS') {
            $('#txt_cedula').removeClass("PBvalidation");
            $('#txt_pasaporte').addClass("PBvalidation");
            $('#Divpasaporte').show();
            $('#Divcedula').hide();
        } else if ($('#cmb_tipo_dni').val() == 'CED')
        {
            $('#txt_pasaporte').removeClass("PBvalidation");
            $('#txt_cedula').addClass("PBvalidation");
            $('#Divpasaporte').hide();
            $('#Divcedula').show();
        }
    });

    $('#cmb_unidad_solicitud').change(function () {
        var unisol = $('#cmb_unidad_solicitud').val();
        if (unisol == 1) {
            $('#divmetodocan').css('display', 'none');
            $('#divRequisitosCANP').css('display', 'none');
            $('#divRequisitosCANSP').css('display', 'none');
            $('#divRequisitosCANAD').css('display', 'none');
            $('#divRequisitosCANO').css('display', 'none');
            $('#divRequisitosEXA').css('display', 'none');
            $('#divRequisitosPRP').css('display', 'none');
        } else if (unisol == 2) {
            $('#divmetodocan').css('display', 'block');
        }
        var link = $('#txth_base').val() + "/inscribeducacioncontinua/index";
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.modalidad, "cmb_modalidad_solicitud");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidad_solicitud').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.carrera, "cmb_carrera_solicitud");
                        }
                    }, true);
                }
            }
        }, true);

        //métodos.
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.metodo = $('#cmb_metodo_solicitud').val();
        arrParams.getmetodo = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.metodos, "cmb_metodo_solicitud");
                AparecerDocumento();
                Requisitos();
            }
        }, true);

    });

    $('#cmb_modalidad_solicitud').change(function () {
        var link = $('#txth_base').val() + "/inscribeducacioncontinua/index";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad_solicitud').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.carrera, "cmb_carrera_solicitud");
            }
        }, true);
        Requisitos();
    });

    $('#cmb_metodo_solicitud').change(function () {
        Requisitos();
        AparecerDocumento();
    });

    // tabs del index
    $('#paso1next').click(function () {
        $("a[data-href='#paso1']").attr('data-toggle', 'none');
        $("a[data-href='#paso1']").parent().attr('class', 'disabled');
        $("a[data-href='#paso1']").attr('data-href', $("a[href='#paso1']").attr('href'));
        $("a[data-href='#paso1']").removeAttr('href');
        $("a[data-href='#paso2']").attr('data-toggle', 'tab');
        $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
        $("a[data-href='#paso2']").trigger("click");
    });
    $('#paso2back').click(function () {
        $("a[data-href='#paso2']").attr('data-toggle', 'none');
        $("a[data-href='#paso2']").parent().attr('class', 'disabled');
        $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
        $("a[data-href='#paso2']").removeAttr('href');
        $("a[data-href='#paso1']").attr('data-toggle', 'tab');
        $("a[data-href='#paso1']").attr('href', $("a[data-href='#paso1']").attr('data-href'));
        $("a[data-href='#paso1']").trigger("click");

        $('#txt_nombres_fac').removeClass("PBvalidation");
        $('#txt_dir_fac').removeClass("PBvalidation");
        $('#txt_apellidos_fac').removeClass("PBvalidation");
        $('#txt_dni_fac').removeClass("PBvalidation");
        $('#txt_pasaporte_fac').removeClass("PBvalidation");
        $('#txt_ruc_fac').removeClass("PBvalidation");
        $('#txt_correo_fac').removeClass("PBvalidation");
    });
    $('#paso2next').click(function () {
        $("a[data-href='#paso2']").attr('data-toggle', 'none');
        $("a[data-href='#paso2']").parent().attr('class', 'disabled');
        $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
        $("a[data-href='#paso2']").removeAttr('href');
        $("a[data-href='#paso3']").attr('data-toggle', 'tab');
        $("a[data-href='#paso3']").attr('href', $("a[data-href='#paso3']").attr('data-href'));
        $("a[data-href='#paso3']").trigger("click");
    });
    $('#paso3back').click(function () {
        $("a[data-href='#paso3']").attr('data-toggle', 'none');
        $("a[data-href='#paso3']").parent().attr('class', 'disabled');
        $("a[data-href='#paso3']").attr('data-href', $("a[href='#paso3']").attr('href'));
        $("a[data-href='#paso3']").removeAttr('href');
        $("a[data-href='#paso2']").attr('data-toggle', 'tab');
        $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
        $("a[data-href='#paso2']").trigger("click");

        $('#txt_nombres_fac').removeClass("PBvalidation");
        $('#txt_dir_fac').removeClass("PBvalidation");
        $('#txt_apellidos_fac').removeClass("PBvalidation");
        $('#txt_dni_fac').removeClass("PBvalidation");
        $('#txt_pasaporte_fac').removeClass("PBvalidation");
        $('#txt_ruc_fac').removeClass("PBvalidation");
        $('#txt_correo_fac').removeClass("PBvalidation");
    });

    function AparecerDocumento() {
        if ($('#cmb_metodo_solicitud').val() == 4) {
            $('#divCertificado').css('display', 'block');
        } else {
            $('#divCertificado').css('display', 'none');
        }
    }

    function Requisitos() {
        if ($('#cmb_metodo_solicitud').val() != 0) {
            //Grado
            if ($('#cmb_unidad_solicitud').val() == 1) {
                $('#divRequisitosCANO').css('display', 'none');
                $('#divRequisitosCANP').css('display', 'none');
                $('#divRequisitosCANSP').css('display', 'none');
                $('#divRequisitosCANAD').css('display', 'none');
                $('#divRequisitosEXA').css('display', 'none');
                $('#divRequisitosPRP').css('display', 'none');
                //Método: CAN
//                if ($('#cmb_metodo_solicitud').val() == 1) {
//                //Online
//                if ($('#cmb_modalidad_solicitud').val() == 1) {
//                    $('#divRequisitosCANO').css('display', 'block');
//                    $('#divRequisitosCANP').css('display', 'none');
//                    $('#divRequisitosCANSP').css('display', 'none');
//                    $('#divRequisitosCANAD').css('display', 'none');
//                    $('#divRequisitosEXA').css('display', 'none');
//                    $('#divRequisitosPRP').css('display', 'none');
//                } else {  //Presencial
//                    if ($('#cmb_modalidad_solicitud').val() == 2) {
//                        $('#divRequisitosCANP').css('display', 'block');
//                        $('#divRequisitosCANO').css('display', 'none');
//                        $('#divRequisitosCANSP').css('display', 'none');
//                        $('#divRequisitosCANAD').css('display', 'none');
//                        $('#divRequisitosEXA').css('display', 'none');
//                        $('#divRequisitosPRP').css('display', 'none');
//                    } else {   //Semipresencial
//                        if ($('#cmb_modalidad_solicitud').val() == 3) {
//                            $('#divRequisitosCANSP').css('display', 'block');
//                            $('#divRequisitosCANO').css('display', 'none');
//                            $('#divRequisitosCANP').css('display', 'none');
//                            $('#divRequisitosCANAD').css('display', 'none');
//                            $('#divRequisitosEXA').css('display', 'none');
//                            $('#divRequisitosPRP').css('display', 'none');
//                        } else {  // distancia
//                            $('#divRequisitosCANAD').css('display', 'block');
//                            $('#divRequisitosCANO').css('display', 'none');
//                            $('#divRequisitosCANP').css('display', 'none');
//                            $('#divRequisitosCANSP').css('display', 'none');
//                            $('#divRequisitosEXA').css('display', 'none');
//                            $('#divRequisitosPRP').css('display', 'none');
//                        }
//                    }
//                }
//            } else {  //examen
//                //Online
//                $('#divRequisitosEXA').css('display', 'block');
//                $('#divRequisitosCANO').css('display', 'none');
//                $('#divRequisitosCANP').css('display', 'none');
//                $('#divRequisitosCANSP').css('display', 'none');
//                $('#divRequisitosCANAD').css('display', 'none');
//                $('#divRequisitosPRP').css('display', 'none');
//            }
            } else {  //Posgrado  Semipresencial
                if (($('#cmb_modalidad_solicitud').val() == 3) || ($('#cmb_modalidad_solicitud').val() == 2)) {
                    //Taller introductorio
                    if ($('#cmb_metodo_solicitud').val() == 4) {
                        //Taller introductorio
                        //$('#divRequisitosPRP').css('display', 'block');
                        $('#divRequisitosPRP').css('display', 'none');
                        $('#divRequisitosCANO').css('display', 'none');
                        $('#divRequisitosCANP').css('display', 'none');
                        $('#divRequisitosCANSP').css('display', 'none');
                        $('#divRequisitosCANAD').css('display', 'none');
                        $('#divRequisitosEXA').css('display', 'none');
                    }
                }
            }
        } else {
            $('#divRequisitosCANO').css('display', 'none');
            $('#divRequisitosCANP').css('display', 'none');
            $('#divRequisitosCANSP').css('display', 'none');
            $('#divRequisitosCANAD').css('display', 'none');
            $('#divRequisitosEXA').css('display', 'none');
            $('#divRequisitosPRP').css('display', 'none');
        }
    }

    //Control del div de beneficiario
    $('#rdo_forma_pago_dinner').change(function () {
        if ($('#rdo_forma_pago_dinner').val() == 1) {
            $("#rdo_forma_pago_otros").prop("checked", "");
        } else {
            $("#rdo_forma_pago_dinner").prop("checked", true);
        }
    });
    //Pago por stripe.-
    $('#rdo_forma_pago_otros').change(function () {
        if ($('#rdo_forma_pago_otros').val() == 2) {
            $("#rdo_forma_pago_dinner").prop("checked", "");
            $("#rdo_forma_pago_deposito").prop("checked", "");
            $("#rdo_forma_pago_transferencia").prop("checked", "");
            $('#DivSubirPago').css('display', 'none');
            $('#DivSubirPagoBtn').css('display', 'none');
            $('#DivBoton').css('display', 'block');
        } else {
            $("#rdo_forma_pago_otros").prop("checked", true);
            $('#DivBoton').css('display', 'none');
        }
    });

    $('#rdo_forma_pago_deposito').change(function () {
        if ($('#rdo_forma_pago_deposito').val() == 3) {
            $('#DivSubirPago').css('display', 'block');
            $('#DivSubirPagoBtn').css('display', 'block');
            $('#DivBoton').css('display', 'none');
            $("#rdo_forma_pago_dinner").prop("checked", "");
            $("#rdo_forma_pago_otros").prop("checked", "");
            $("#rdo_forma_pago_transferencia").prop("checked", "");
        } else {
            $('#DivSubirPago').css('display', 'none');
            $('#DivSubirPagoBtn').css('display', 'none');
            $("#rdo_forma_pago_deposito").prop("checked", true);
            $('#DivBoton').css('display', 'block');
        }
    });

    $('#rdo_forma_pago_transferencia').change(function () {
        if ($('#rdo_forma_pago_transferencia').val() == 4) {
            $('#DivSubirPago').css('display', 'block');
            $('#DivSubirPagoBtn').css('display', 'block');
            $('#DivBoton').css('display', 'none');
            $("#rdo_forma_pago_dinner").prop("checked", "");
            $("#rdo_forma_pago_otros").prop("checked", "");
            $("#rdo_forma_pago_deposito").prop("checked", "");
        } else {
            $('#DivSubirPago').css('display', 'none');
            $('#DivSubirPagoBtn').css('display', 'none');
            $("#rdo_forma_pago_transferencia").prop("checked", true);
            $('#DivBoton').css('display', 'block');
        }
    });

    $('input[name=opt_tipo_DNI]:radio').change(function () {
        if ($(this).val() == 1) {
            $('#DivcedulaFac').css('display', 'block');
            $('#DivpasaporteFac').css('display', 'none');
            $('#DivRucFac').css('display', 'none');
            $('#txt_dni_fac').addClass("PBvalidation");
            $('#txt_ruc_fac').removeClass("PBvalidation");
            $('#txt_pasaporte_fac').removeClass("PBvalidation");
        } else if ($(this).val() == 2) {
            $('#DivpasaporteFac').css('display', 'block');
            $('#DivcedulaFac').css('display', 'none');
            $('#DivRucFac').css('display', 'none');
            $('#txt_pasaporte_fac').addClass("PBvalidation");
            $('#txt_ruc_fac').removeClass("PBvalidation");
            $('#txt_dni_fac').removeClass("PBvalidation");
        } else {
            $('#DivRucFac').css('display', 'block');
            $('#DivpasaporteFac').css('display', 'none');
            $('#DivcedulaFac').css('display', 'none');
            $('#txt_ruc_fac').addClass("PBvalidation");
            $('#txt_dni_fac').removeClass("PBvalidation");
            $('#txt_pasaporte_fac').removeClass("PBvalidation");
        }
    });

    $('#sendInscripcionSubirPago').click(function () {
        guardarInscripcionTemp('UpdateDepTrans');
        var link = $('#txth_base').val() + "/inscribeducacioncontinua/saveinscripciontemp";
        var arrParams = new Object();
        arrParams.codigo = $('#txth_twin_id').val();
        arrParams.ACCION = 'Fin';
        arrParams.nombres_fact = $('#txt_nombres_fac').val();
        arrParams.apellidos_fact = $('#txt_apellidos_fac').val();
        arrParams.direccion_fact = $('#txt_dir_fac').val();
        arrParams.telefono_fac = $('#txt_tel_fac').val();
        var tipo_dni_fact = "";
        if ($('#opt_tipo_DNI option:selected').val() == "1") {
            tipo_dni_fact = "CED";
        } else if ($('#opt_tipo_DNI option:selected').val() == "2") {
            tipo_dni_fact = "PASS";
        } else {
            tipo_dni_fact = "RUC";
        }
        arrParams.tipo_dni_fac = tipo_dni_fact;
        arrParams.dni = $('#txt_dni_fac').val();
        arrParams.correo = $('#txt_correo_fac').val();
        //Datos del pago.
        $('#txt_numtransaccion').addClass("PBvalidation");
        $('#txt_fecha_transaccion').addClass("PBvalidation");

        arrParams.num_transaccion = $('#txt_numtransaccion').val();
        arrParams.observacion = $('#txt_observacion').val();
        arrParams.fecha_transaccion = $('#txt_fecha_transaccion').val();
        arrParams.doc_pago = $('#txth_doc_pago').val();
        if ($("input[name='rdo_forma_pago_otros']:checked").val() == "2") {//($('#rdo_forma_pago_otros option:selected').val() == "2") {
            arrParams.forma_pago = 2;
        } else if ($("input[name='rdo_forma_pago_deposito']:checked").val() == "3") { //rdo_forma_pago_deposito
            arrParams.forma_pago = 3;
        } else if  ($("input[name='rdo_forma_pago_transferencia']:checked").val() == "4") { //rdo_forma_pago_transferencia
            arrParams.forma_pago = 4;
        } else {
            arrParams.forma_pago = 1;
        }
        var error = 0;
        if ($('#txth_doc_pago').val() == "") {
            error++;
            var mensaje = {wtmessage: "Debe adjuntar documento de pago realizado.", title: "Información"};
            showAlert("NO_OK", "error", mensaje);
        } else {
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function (response) {
                    var message = response.message;
                    //console.log(response);
                    if (response.status == "OK") {
                        showAlert(response.status, response.label, response.message);
                        setTimeout(function () {
                            parent.window.location.href = $('#txth_base').val() +"/inscribeducacioncontinua/index";
                        }, 2000);
                    }
                });
            }
        }
    });
});

//INSERTAR DATOS
function guardarInscripcion(accion, paso) {
    var ID = (accion == "Update") ? $('#txth_twin_id').val() : 0;
    var link = $('#txth_base').val() + "/inscribeducacioncontinua/saveinscripciontemp";
    var arrParams = new Object();
    arrParams.DATA_1 = dataInscripPart1(ID);
    arrParams.ACCION = accion;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            var message = response.message;
            //console.log(response);
            if (response.status == "OK") {
                if (accion == "Create") {
                    $('#txth_twin_id').val(response.data.ids)
                    paso1next();
                } else {
                    if (paso == "1") {
                        paso1next();
                    } else {
                        paso2next();
                    }
                    var uaca_id = response.data.data.uaca_id;
                    //Inicio ingreso informacion del tab 3\
                    $('#lbl_uaca_tx').text(response.data.data.unidad);
                    $('#lbl_moda_tx').text(response.data.data.modalidad);
                    $('#lbl_carrera_tx').text(response.data.data.carrera);
                    $('#lbl_ming_tx').text(response.data.data.metodo);
                    //Datos de facturación.
                    $('#txt_nombres_fac').val(response.data.data.twin_nombre);
                    $('#txt_apellidos_fac').val(response.data.data.twin_apellido);
                    $('#txt_dni_fac').val(response.data.data.twin_numero);

                    if (uaca_id == 1) {
                        $('#id_item_1').css('display', 'block');
                        $('#id_item_2').css('display', 'block');
                    } else if (uaca_id == 2) {
                        $('#id_item_1').css('display', 'none');
                        $('#id_item_2').css('display', 'none');
                        $('#id_mat_cur').css('display', 'none');
                    }
                    $('#id_item_1').css('display', 'none');
                    $('#id_item_2').css('display', 'none');
                    var leyenda = '';
                    var ming = response.data.data.twin_metodo_ingreso;
                    var mod_id = response.data.data.mod_id;
                    var id_carrera = response.data.data.id_carrera;
                    $('#lbl_fcur_lb').text("Fecha del curso:");
                    $('#lbl_item_1').text("Valor Matriculación: ");
                    var convenio = $('#cmb_convenio_empresa').val();
                    if (uaca_id == 2) {
                        if (id_carrera == 22) {
                            leyenda = 'El valor de la maestría: $15,500.00';
                        } else if ((id_carrera == 24) && (convenio == 1)) {
                            leyenda = 'El valor de la maestría: $4,500.00';
                        } else if ((id_carrera == 24) && (convenio != 1)) {
                            leyenda = 'El valor de la maestría: $5,000.00';
                        } else {
                            leyenda = 'El valor de la maestría: $11,300.00';
                        }
                        leyenda += '<br/><br/>El valor a cancelar por concepto de inscripción es: ';
                        $('#lbl_item_1').text("Valor Matriculación: ");
                        $('#val_item_1').text(response.data.data.precio);
                        $('#lbl_valor_pagar_tx').text(response.data.data.precio);
                        $('#lbl_fcur_tx').text("15 abril del 2019");
                    } else if (uaca_id == 1) {
                        leyenda = 'El valor a cancelar por concepto de matriculación en la modalidad ' + response.data.data.modalidad + ' es:';
                        if (mod_id == 1) {//online
                            $('#val_item_1').text('65');
                            $('#lbl_item_2').text("Plataforma: ");
                            $('#val_item_2').text('0');
                            //$('#val_item_2').text('50');
                            $('#lbl_valor_pagar_tx').text("$65");
                            $('#lbl_item_3').text("Pago Mínimo: ");
                            $('#val_item_3').text('$65');
                            // Habilitar los items.
                            $('#id_item_1').css('display', 'block');
                            $('#id_item_2').css('display', 'block');
//                                $('#lbl_valor_pagar_tx').text(response.data.data.precio);
//                                $('#lbl_fcur_lb').text("Fecha del curso:");
//                                $('#lbl_fcur_tx').text("22 de octubre al 14 de diciembre");
//                                $('#lbl_mcur_lb').text("Examenes a rendir");
//                                $('#lbl_fcur_lb').text("Fecha de las pruebas:");
//                                $('#lbl_valor_pagar_tx').text(response.data.data.precio);
                            //$('#lbl_fcur_tx').text("En quince (15) días a partir del registro (un coordinador te contactará para brindarte mayor información)");
                        } else if (mod_id == 2 || mod_id == 3 || mod_id == 4) {//presencial y semi presencial
                            //if (ming == 1) {// curso
                            var $val_item_1 = "";
                            if (mod_id == 2 || mod_id == 3) {
                                //$('#lbl_fcur_tx').text("22 de octubre al 30 de noviembre");
                                $('#val_item_1').text('200');
                                $val_item_1 = '$200';
                            } else if (mod_id == 4) {
                                $('#val_item_1').text('$115');
                                $val_item_1 = '$115';
                                //$('#lbl_fcur_tx').text("20 de octubre al 8 de diciembre");
                            }
//                                    $('#lbl_mcur_lb').text("Materias a cursar");
//                                    $('#lbl_item_1').text("Curso de nivelación: ");
                            $('#val_item_1').text(response.data.data.precio);
                            $('#lbl_item_2').text("Plataforma: ");
                            $('#val_item_2').text('$0');
                            $('#lbl_valor_pagar_tx').text($val_item_1);
                            $('#lbl_item_3').text("Pago Mínimo: ");
                            $('#val_item_3').text('$100');
                            //var totalvalor = parseInt(response.data.data.precio) - parseInt(response.data.data.ddit_valor);
                            //$('#lbl_valor_pagar_tx').text(totalvalor);
//                                    $('#lbl_fcur_lb').text("Fecha del curso:");
//                                    $('#id_item_1').css('display', 'block');
//                                    $('#id_item_2').css('display', 'block');
//                                } else if (ming == 2) { // examen
//                                    $('#lbl_fcur_tx').text("En quince (15) días a partir del registro (un coordinador te contactará para brindarte mayor información)");
//                                    $('#lbl_item_1').text("Exámen de Admisión: ");
//                                    $('#val_item_1').text(response.data.data.precio);
//                                    $('#lbl_item_2').text("Descuento especial: ");
//                                    $('#lbl_mcur_lb').text("Examenes a rendir");
//                                    $('#val_item_2').text(response.data.data.ddit_valor);
//                                    var totalvalor = parseInt(response.data.data.precio) - parseInt(response.data.data.ddit_valor);
//                                    $('#lbl_valor_pagar_tx').text(totalvalor);
//                                    $('#lbl_fcur_lb').text("Fecha de las pruebas:");
//                                    $('#id_item_1').css('display', 'block');
//                                    $('#id_item_2').css('display', 'block');
//                                }
                        }
                    }

                    $('#lbl_leyenda_pago_tx').html(leyenda);
                    //fin ingreso informacion del tab 3
                    $('#txth_twin_id').val(response.data.ids);//SE AGREGA AL FINAL
                    //paso2next();
                }
            }
        }, true);
    }

}

function paso1next() {
    $("a[data-href='#paso1']").attr('data-toggle', 'none');
    $("a[data-href='#paso1']").parent().attr('class', 'disabled');
    $("a[data-href='#paso1']").attr('data-href', $("a[href='#paso1']").attr('href'));
    $("a[data-href='#paso1']").removeAttr('href');
    $("a[data-href='#paso2']").attr('data-toggle', 'tab');
    $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
    $("a[data-href='#paso2']").trigger("click");

    $('#txt_nombres_fac').removeClass("PBvalidation");
    $('#txt_dir_fac').removeClass("PBvalidation");
    $('#txt_apellidos_fac').removeClass("PBvalidation");
    $('#txt_dni_fac').removeClass("PBvalidation");
    $('#txt_pasaporte_fac').removeClass("PBvalidation");
    $('#txt_ruc_fac').removeClass("PBvalidation");
    $('#txt_correo_fac').removeClass("PBvalidation");

}

function paso2next() {
    $("a[data-href='#paso2']").attr('data-toggle', 'none');
    $("a[data-href='#paso2']").parent().attr('class', 'disabled');
    $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
    $("a[data-href='#paso2']").removeAttr('href');
    $("a[data-href='#paso3']").attr('data-toggle', 'tab');
    $("a[data-href='#paso3']").attr('href', $("a[data-href='#paso3']").attr('data-href'));
    $("a[data-href='#paso3']").trigger("click");
    //Adicionar validación de datos obligatorios en datos de factura.
    $('#txt_nombres_fac').addClass("PBvalidation");
    $('#txt_dir_fac').addClass("PBvalidation");
    $('#txt_apellidos_fac').addClass("PBvalidation");
    $('#txt_correo_fac').addClass("PBvalidation");
    if ($("input[name='opt_tipo_DNI']:checked").val() == "1") {
        $('#txt_dni_fac').addClass("PBvalidation");
        $('#txt_ruc_fac').removeClass("PBvalidation");
        $('#txt_pasaporte_fac').removeClass("PBvalidation");
    } else if ($("input[name='opt_tipo_DNI']:checked").val() == "2") {
        $('#txt_pasaporte_fac').addClass("PBvalidation");
        $('#txt_ruc_fac').removeClass("PBvalidation");
        $('#txt_dni_fac').removeClass("PBvalidation");
    } else {
        $('#txt_ruc_fac').addClass("PBvalidation");
        $('#txt_pasaporte_fac').removeClass("PBvalidation");
        $('#txt_dni_fac').removeClass("PBvalidation");
    }
}

function dataInscripPart1(ID) {
    var datArray = new Array();
    var objDat = new Object();
    objDat.twin_id = ID;//Genero Automatico
    objDat.pges_pri_nombre = $('#txt_primer_nombre').val();
    objDat.pges_pri_apellido = $('#txt_primer_apellido').val();
    objDat.tipo_dni = $('#cmb_tipo_dni option:selected').val();
    if (objDat.tipo_dni == 'CED') {
        objDat.pges_cedula = $('#txt_cedula').val();
    } else {
        objDat.pges_cedula = $('#txt_pasaporte').val();
    }
    objDat.pges_empresa = $('#txt_empresa').val();
    objDat.pges_correo = $('#txt_correo').val();
    //objDat.pais = $('#cmb_pais_dom option:selected').val();
    objDat.pais = 1;
    objDat.pges_celular = $('#txt_celular').val();
    objDat.nivinstrucion = $('#cmb_instruccion option:selected').val();
    objDat.redes = $('#cmb_redes_sociales option:selected').val();
    objDat.unidad_academica = $('#cmb_unidad_solicitud option:selected').val();
    objDat.modalidad = $('#cmb_modalidad_solicitud option:selected').val();
    if (objDat.unidad_academica == 1) {
        objDat.ming_id = null;
    } else if (objDat.unidad_academica == 2) {
        objDat.ming_id = $('#cmb_metodo_solicitud option:selected').val();
    }
    //objDat.conoce = $('#cmb_conuteg option:selected').val();
    objDat.conoce = 0;
    objDat.encontramos = $('#txt_encontramos').val();
    objDat.carrera = $('#cmb_carrera_solicitud option:selected').val();
    //TABA 2
    objDat.ruta_doc_titulo = ($('#txth_doc_titulo').val() != '') ? $('#txth_doc_titulo').val() : '';
    objDat.ruta_doc_dni = ($('#txth_doc_dni').val() != '') ? $('#txth_doc_dni').val() : '';
    objDat.ruta_doc_certvota = ($('#txth_doc_certvota').val() != '') ? $('#txth_doc_certvota').val() : '';
    objDat.ruta_doc_foto = ($('#txth_doc_foto').val() != '') ? $('#txth_doc_foto').val() : '';
    objDat.ruta_doc_hojavida = ($('#txth_doc_hojavida').val() != '') ? $('#txth_doc_hojavida').val() : '';
    objDat.ruta_doc_certificado = ($('#txth_doc_certificado').val() != '') ? $('#txth_doc_certificado').val() : '';
    objDat.twin_mensaje1 = ($("#chk_mensaje1").prop("checked")) ? '1' : '0';
    objDat.twin_mensaje2 = ($("#chk_mensaje2").prop("checked")) ? '1' : '0';
    objDat.ruta_doc_aceptacion = ($('#txth_doc_aceptacion').val() != '') ? $('#txth_doc_aceptacion').val() : '';
    objDat.cemp_id = $('#cmb_convenio_empresa option:selected').val();
    //TAB 3
    objDat.ruta_doc_pago = ($('#txth_doc_pago').val() != '') ? $('#txth_doc_pago').val() : '';
    if ($("input[name='rdo_forma_pago_otros']:checked").val() == "2") {//($('#rdo_forma_pago_otros option:selected').val() == "2") {
        objDat.forma_pago = 2;
    } else if ($("input[name='rdo_forma_pago_deposito']:checked").val() == "3") { //rdo_forma_pago_deposito
        objDat.forma_pago = 3;
    } else if  ($("input[name='rdo_forma_pago_transferencia']:checked").val() == "4") { //rdo_forma_pago_transferencia
        objDat.forma_pago = 4;
    } else {
        objDat.forma_pago = 1;
    }
    datArray[0] = objDat;
    sessionStorage.dataInscrip_1 = JSON.stringify(datArray);
    return datArray;
}
function camposnulos(campo) {
    if ($(campo).val() == "")
    {
        $(campo).removeClass("PBvalidation");
    } else
    {
        $(campo).addClass("PBvalidation");
    }
}
function PagoDinners(solicitud) {
    var bohre = $('#txth_base').val() + "/inscribeducacioncontinua/savepagodinner?sins_id=" + solicitud + "&popup=1";
    $('#btn_pago_i').attr("href", bohre);
    $('#btn_pago_i').trigger("click");
}


function guardarInscripcionTemp(accion) {
    var ID = (accion == "UpdateDepTrans") ? $('#txth_twin_id').val() : 0;
    var link = $('#txth_base').val() + "/inscribeducacioncontinua/saveinscripciontemp";
    var arrParams = new Object();
    arrParams.DATA_1 = dataInscripPart1(ID);
    arrParams.ACCION = accion;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                return 1;
            }
        });
    }
}