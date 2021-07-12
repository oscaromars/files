
function habilitarSecciones() {
    var pais = $('#cmb_pais').val();
    var unidad = $('#cmb_unidad').val();
    //if (pais == 1) {
    if ((pais == 1) && (unidad == 1)) {
        $('#divCertvota').css('display', 'block');
    } else {
        $('#divCertvota').css('display', 'none');
    }
}
$(document).ready(function () {
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

        /*$('#txt_nombres_fac').removeClass("PBvalidation");
        $('#txt_dir_fac').removeClass("PBvalidation");
        $('#txt_apellidos_fac').removeClass("PBvalidation");
        $('#txt_dni_fac').removeClass("PBvalidation");
        $('#txt_pasaporte_fac').removeClass("PBvalidation");
        $('#txt_ruc_fac').removeClass("PBvalidation");
        $('#txt_correo_fac').removeClass("PBvalidation");*/
    });
    $('#paso2next').click(function () {
        $("a[data-href='#paso2']").attr('data-toggle', 'none');
        $("a[data-href='#paso2']").parent().attr('class', 'disabled');
        $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
        $("a[data-href='#paso2']").removeAttr('href');
        /*$("a[data-href='#paso3']").attr('data-toggle', 'tab');
        $("a[data-href='#paso3']").attr('href', $("a[data-href='#paso3']").attr('data-href'));
        $("a[data-href='#paso3']").trigger("click");*/
    });
    $('#cmb_pais').change(function() {
        var link = $('#txth_base').val() + "/inscripciongrado/index";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_provincia");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function(response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciudad");
                        }
                    }, true);
                }

            }
        }, true);
    });

    $('#cmb_provincia').change(function() {
        var link = $('#txth_base').val() + "/inscripciongrado/index";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciudad");
            }
        }, true);
    });
    $('#cmb_modalidad').change(function () {
        var link = $('#txth_base').val() + "/inscripciongrado/index";
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidad').val();
        arrParams.moda_id = $(this).val();
        arrParams.eaca_id = $('#cmb_carrera').val();
        arrParams.empresa_id = 1;
        arrParams.getmalla = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.mallaca, "cmb_malla", "Seleccionar");
            }
        }, true);
    });
    $('#sendInformacionAspirante').click(function () {
        habilitarSecciones();
        if ($('#txth_igra_id').val() == 0) {
            guardarInscripciongrado('Create', '1');
        } else {
            guardarInscripciongrado('Update', '1');
        }
    });
    $('#sendInformacionAspirante2').click(function () {
        var error = 0;
        var pais = $('#cmb_pais').val();
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
                    if ((pais == 1) && ($('#cmb_unidad').val() == 1)) {
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
                } /*else {
                 if ($('#txth_doc_hojavida').val() == "") {
                 error++;
                 var mensaje = {wtmessage: "Debe adjuntar hoja de vida.", title: "Información"};
                 showAlert("NO_OK", "error", mensaje);
                 }
                 }*/
            }
        }
        if ($('#cmb_convenio_empresa').val() > 0) {
            if ($('#txth_doc_aceptacion').val() == "") {
                error++;
                var mensaje = {wtmessage: "Debe adjuntar documento de aceptación.", title: "Información"};
                showAlert("NO_OK", "error", mensaje);
            }
        }
        /*Gviteri: 11/jun/2019 indicaron que no se solicite el documento (Diana López).
         * if ($('#cmb_unidad_solicitud').val() == 2) {
         if ($('#txth_doc_certificado').val() == "") {
         error++;
         var mensaje = {wtmessage: "Debe adjuntar certificado de materias.", title: "Información"};
         showAlert("NO_OK", "error", mensaje);
         }
         //alert($('#cmb_tipo_dni').val());
         
         }*/
        //alert(error);
        if (error == 0) {
            guardarInscripciongrado('Update', '2');
        }
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

//INSERTAR DATOS
function guardarInscripciongrado(accion, paso) {
    var ID = (accion == "Update") ? $('#txth_igra_id').val() : 0;
    var link = $('#txth_base').val() + "/inscripciongrado/saveinscripciongrado";
    var arrParams = new Object();
    arrParams.DATA_1 = dataInscripGrado1(ID);
    arrParams.ACCION = accion;
    if (!validateForm()) {       
        requestHttpAjax(link, arrParams, function (response) {
            var message = response.message;
            //console.log(response);
            if (response.status == "OK") {
                if (accion == "Create") {
                    $('#txth_igra_id').val(response.data.ids)
                    paso1next();
                } else {
                    if (paso == "1") {
                        paso1next();
                    } else {
                        paso2next();
                    }
                    /*var uaca_id = response.data.data.uaca_id;
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
                            $('#val_item_1').text('$65');
                            $('#lbl_item_2').text("Plataforma: ");
                            $('#val_item_2').text('$50');
                            $('#lbl_valor_pagar_tx').text("$115");
                            $('#lbl_item_3').text("Pago Mínimo: ");
                            $('#val_item_3').text('$115');
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
                                $('#val_item_1').text('$250');
                                $val_item_1 = '$250';
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
                    $('#txth_igra_id').val(response.data.ids);//SE AGREGA AL FINAL                            
                    //paso2next();*/
                }

                //var data =response.data;
                //AccionTipo=data.accion;
                //limpiarDatos();
                //var renderurl = $('#txth_base').val() + "/inscripciones/index";
                //window.location = renderurl;
            }
            //showAlert(response.status, response.label, response.message);
        }, true);
    }

}

/*function paso1next() {
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

}*/

/*function paso2next() {
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
}*/

function dataInscripGrado1(ID) {
    var datArray = new Array();
    var objDat = new Object();
    objDat.igra_id = ID;//Genero Automatico
    objDat.pges_pri_nombre = $('#txt_primer_nombre').val();
    objDat.pges_pri_apellido = $('#txt_primer_apellido').val();
    objDat.tipo_dni = $('#cmb_tipo_dni option:selected').val();
    if (objDat.tipo_dni == 'CED') {
        objDat.pges_cedula = $('#txt_cedula').val();
    } else {
        objDat.pges_cedula = $('#txt_pasaporte').val();
    }
    //objDat.pges_empresa = $('#txt_empresa').val();
    objDat.pges_correo = $('#txt_correo').val();
    objDat.pais = $('#cmb_pais option:selected').val();
    objDat.pges_celular = $('#txt_celular').val();
    objDat.unidad_academica = $('#cmb_unidad option:selected').val();
    objDat.modalidad = $('#cmb_modalidad option:selected').val();
    if (objDat.unidad_academica == 1) {
        objDat.ming_id = null;
    } /*else if (objDat.unidad_academica == 2) {
        objDat.ming_id = $('#cmb_metodo_solicitud option:selected').val();
    }*/
    //objDat.conoce = $('#cmb_conuteg option:selected').val();
    objDat.carrera = $('#cmb_carrera option:selected').val();
    //TABA 2
    objDat.ruta_doc_titulo = ($('#txth_doc_titulo').val() != '') ? $('#txth_doc_titulo').val() : '';
    objDat.ruta_doc_dni = ($('#txth_doc_dni').val() != '') ? $('#txth_doc_dni').val() : '';
    objDat.ruta_doc_certvota = ($('#txth_doc_certvota').val() != '') ? $('#txth_doc_certvota').val() : '';
    objDat.ruta_doc_foto = ($('#txth_doc_foto').val() != '') ? $('#txth_doc_foto').val() : '';
    objDat.ruta_doc_hojavida = ($('#txth_doc_hojavida').val() != '') ? $('#txth_doc_hojavida').val() : '';
    objDat.ruta_doc_certificado = ($('#txth_doc_certificado').val() != '') ? $('#txth_doc_certificado').val() : '';
    objDat.igra_mensaje1 = ($("#chk_mensaje1").prop("checked")) ? '1' : '0';
    objDat.igra_mensaje2 = ($("#chk_mensaje2").prop("checked")) ? '1' : '0';
    objDat.ruta_doc_aceptacion = ($('#txth_doc_aceptacion').val() != '') ? $('#txth_doc_aceptacion').val() : '';
    //objDat.cemp_id = $('#cmb_convenio_empresa option:selected').val();
    //TAB 3
    /*objDat.ruta_doc_pago = ($('#txth_doc_pago').val() != '') ? $('#txth_doc_pago').val() : '';    
    if ($("input[name='rdo_forma_pago_otros']:checked").val() == "2") {//($('#rdo_forma_pago_otros option:selected').val() == "2") {        
        objDat.forma_pago = 2;        
    } else if ($("input[name='rdo_forma_pago_deposito']:checked").val() == "3") { //rdo_forma_pago_deposito
        objDat.forma_pago = 3;
    } else if  ($("input[name='rdo_forma_pago_transferencia']:checked").val() == "4") { //rdo_forma_pago_transferencia
        objDat.forma_pago = 4;
    } else {
        objDat.forma_pago = 1;
    }  */
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