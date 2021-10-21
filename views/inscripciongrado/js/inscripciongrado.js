
$(document).ready(function () {
    $('#btn_buscarAspirante').click(function () {
        actualizarGridAspirante();
    });
    $('#btn_editaraspirantegrado').click(function () {
        editaspirantegrado();
    });
    $('#btn_actualizaraspirantegrado').click(function () {
        updateaspirantegrado();
    });
    $('#cmb_carrera').change(function () {
        var link = $('#txth_base').val() + "/inscripciongrado/index";
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidad').val();
        //arrParams.moda_id = $(this).val();
        arrParams.eaca_id = $(this).val();
        arrParams.empresa_id = 1;
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad", "Seleccionar");
            }
        }, true);
    });

    $('#cmb_carrera_asp').change(function () {
        var link = $('#txth_base').val() + "/inscripciongrado/aspirantegrado";
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidad_asp').val();
        //arrParams.moda_id = $(this).val();
        arrParams.carrera = $(this).val();
        arrParams.empresa_id = 1;
        arrParams.getmodalidades = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidades, "cmb_modalidad_asp", "Seleccionar");
            }
        }, true);
    });

    /* codigo de area en datos personales*/

    $('#cmb_pais').change(function () {
        var link = $('#txth_base').val() + "/inscripciongrado/index";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_provincia");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciudad");
                        }
                    }, true);
                }
            }
        }, true);
        // actualizar codigo pais
        $("#lbl_codeCountry").text($("#cmb_pais option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_pais option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_pais option:selected").attr("data-code"));
    });

    $('#cmb_provincia').change(function () {
        var link = $('#txth_base').val() + "/inscripciongrado/index";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciudad");
            }
        }, true);
    });

    $('#cmb_paisEdit').change(function () {
        var link = $('#txth_base').val() + "/inscripciongrado/edit";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_provinciaEdit");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_cantonEdit");
                        }
                    }, true);
                }
            }
        }, true);
        // actualizar codigo pais
        $("#lbl_codeCountry").text($("#cmb_paisEdit option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_paisEdit option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_paisEdit option:selected").attr("data-code"));
    });

    $('#cmb_provinciaEdit').change(function () {
        var link = $('#txth_base').val() + "/inscripciongrado/edit";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_cantonEdit");
            }
        }, true);
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
    // tabs create
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
    });

    /*GUARDAR INFORMACION*/

    $('#btn_save_1').click(function () {
        guardarInscripcionGrado();
    });

    //Control del div de discapacidad
    $('#signup-dis').change(function () {
        if ($('#signup-dis').val() == 1) {
            $('#discapacidad').css('display', 'block');
            $("#signup-dis_no").prop("checked", "");
        } else {
            $('#discapacidad').css('display', 'none');
        }
    });

    $('#signup-dis_no').change(function () {
        if ($('#signup-dis_no').val() == 2) {
            $('#discapacidad').css('display', 'none');
            $("#signup-dis").prop("checked", "");
        } else {
            $('#discapacidad').css('display', 'block');
        }
    });

    //Control del div de homologacion
    $('#signup-hom').change(function () {
        if ($('#signup-hom').val() == 1) {
            $('#homologacion').css('display', 'block');
            $("#signup-hom_no").prop("checked", "");
        } else {
            $('#homologacion').css('display', 'none');
        }
    });

    $('#signup-hom_no').change(function () {
        if ($('#signup-hom_no').val() == 2) {
            $('#homologacion').css('display', 'none');
            $("#signup-hom").prop("checked", "");
        } else {
            $('#homologacion').css('display', 'block');
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

function guardarInscripcionGrado() {
    //var ID = /*(accion == "Update") ? */$('#txth_igra_id').val()/* : 0*/;
    var link = $('#txth_base').val() + "/inscripciongrado/guardarinscripciongrado";
    var arrParams = new Object();
    //arrParams.DATA_1 = dataInscripcion(ID);
    arrParams.igra_id = $('#txth_igra_id').val();
    arrParams.unidad = $('#cmb_unidad').val();
    arrParams.carrera = $('#cmb_carrera').val();
    arrParams.modalidad = $('#cmb_modalidad').val();
    arrParams.periodo = $('#cmb_periodo').val();
    arrParams.tipo_dni = $('#cmb_tipo_dni option:selected').val();
    if (arrParams.unidad == 1) {
        //objDat.ming_id = null;
    } else if (arrParams.unidad_academica == 2) {
        arrParams.ming_id = $('#cmb_metodo_solicitud option:selected').val();
    }
    if (arrParams.tipo_dni == 'CED') {
        arrParams.cedula = $('#txt_cedula').val();
        arrParams.pasaporte = '';
    } else {
        arrParams.cedula = $('#txt_pasaporte').val();
        arrParams.pasaporte = $('#txt_pasaporte').val();
    }
    //alert('cedula ' + arrParams.cedula);
    //alert('pasaporte ' + arrParams.pasaporte);
    //arrParams.ACCION = accion;
    //datos personales
    //arrParams.pasaporte = $('#txt_pasaporte').val();
    arrParams.primer_nombre = $('#txt_primer_nombre').val();
    arrParams.segundo_nombre = $('#txt_segundo_nombre').val();
    arrParams.primer_apellido = $('#txt_primer_apellido').val();
    arrParams.segundo_apellido = $('#txt_segundo_apellido').val();
    arrParams.cuidad_nac = $('#cmb_ciu_nac').val();
    arrParams.fecha_nac = $('#txt_fecha_nac').val();
    arrParams.nacionalidad = $('#cmb_nacionalidad').val();
    arrParams.estado_civil = $('#cmb_estado_civil').val();

    //Datos Contacto
    arrParams.pais = $('#cmb_pais').val();
    arrParams.provincia = $('#cmb_provincia').val();
    arrParams.canton = $('#cmb_ciudad').val();
    arrParams.parroquia = $('#txt_parroquia').val();
    arrParams.dir_domicilio = $('#txt_domicilio').val();
    arrParams.celular = $('#txt_celular').val();
    arrParams.telefono = $('#txt_telefono').val();
    arrParams.correo = $('#txt_correo').val();

    //Datos en caso de emergencias
    arrParams.dir_trabajo = $('#txt_direccion_trabajo').val();
    arrParams.cont_emergencia = $('#txt_contacto_emergencia').val();
    arrParams.parentesco = $('#cmb_parentesco').val();
    arrParams.tel_emergencia = $('#txt_telefono_emergencia').val();
    arrParams.dir_personacontacto = $('#txt_direccion_persona_contacto').val();

    //TAB 2
    arrParams.igra_ruta_doc_titulo = ($('#txth_doc_titulo').val() != '') ? $('#txth_doc_titulo').val() : '';
    arrParams.igra_ruta_doc_dni = ($('#txth_doc_dni').val() != '') ? $('#txth_doc_dni').val() : '';
    arrParams.igra_ruta_doc_certvota = ($('#txth_doc_certvota').val() != '') ? $('#txth_doc_certvota').val() : '';
    arrParams.igra_ruta_doc_foto = ($('#txth_doc_foto').val() != '') ? $('#txth_doc_foto').val() : '';
    arrParams.igra_ruta_doc_comprobantepago = ($('#txth_doc_comprobantepago').val() != '') ? $('#txth_doc_comprobantepago').val() : '';
    arrParams.igra_ruta_doc_record = ($('#txth_doc_record').val() != '') ? $('#txth_doc_record').val() : '';
    arrParams.igra_ruta_doc_certificado = ($('#txth_doc_nosancion').val() != '') ? $('#txth_doc_nosancion').val() : '';
    arrParams.igra_ruta_doc_syllabus = ($('#txth_doc_syllabus').val() != '') ? $('#txth_doc_syllabus').val() : '';
    arrParams.igra_ruta_doc_homologacion = ($('#txth_doc_especievalorada').val() != '') ? $('#txth_doc_especievalorada').val() : '';
    arrParams.igra_mensaje1 = ($("#chk_mensaje1").prop("checked")) ? '1' : '0';
    arrParams.igra_mensaje2 = ($("#chk_mensaje2").prop("checked")) ? '1' : '0';

    if ($("#chk_mensaje1").prop("checked") && $("#chk_mensaje2").prop("checked"))
    {
        if ($('#txth_doc_titulo').val() == "") {
            var mensaje = {wtmessage: "Debe adjuntar título.", title: "Información"};
            showAlert("NO_OK", "error", mensaje);
        } else {
            if ($('#txth_doc_dni').val() == "") {
                var mensaje = {wtmessage: "Debe adjuntar documento de identidad.", title: "Información"};
                showAlert("NO_OK", "error", mensaje);
            } else {
                //if ($('#cmb_tipo_dni').val() == "CED") {
                    if ($('#txth_doc_certvota').val() == "") {
                        var mensaje = {wtmessage: "Debe adjuntar certificado de votación.", title: "Información"};
                        showAlert("NO_OK", "error", mensaje);
                    } else {
                        if ($('#txth_doc_foto').val() == "") {
                            var mensaje = {wtmessage: "Debe adjuntar foto.", title: "Información"};
                            showAlert("NO_OK", "error", mensaje);
                        } else{
                            if ($('#txth_doc_comprobantepago').val() == "") {
                                var mensaje = {wtmessage: "Debe adjuntar comprobante de pago.", title: "Información"};
                                showAlert("NO_OK", "error", mensaje);
                            } else{
                                if ($("#signup-hom").prop("checked") == true)
                                {
                                    if ($('#txth_doc_record').val() == "") {
                                        var mensaje = {wtmessage: "Debe adjuntar Record Académico.", title: "Información"};
                                        showAlert("NO_OK", "error", mensaje);
                                    } else{
                                        if ($('#txth_doc_nosancion').val() == "") {
                                            var mensaje = {wtmessage: "Debe adjuntar Certificado no ser sancionado.", title: "Información"};
                                            showAlert("NO_OK", "error", mensaje);
                                        }else{
                                            if ($('#txth_doc_syllabus').val() == "") {
                                                var mensaje = {wtmessage: "Debe adjuntar Syllabus de materias aprobadas.", title: "Información"};
                                                showAlert("NO_OK", "error", mensaje);
                                            }else{
                                                if ($('#txth_doc_especievalorada').val() == "") {
                                                    var mensaje = {wtmessage: "Debe adjuntar Especie valorada.", title: "Información"};
                                                    showAlert("NO_OK", "error", mensaje);
                                                }else{
                                                /*if (!validateForm()) {
                                                    requestHttpAjax(link, arrParams, function (response) {
                                                        showAlert(response.status, response.label, response.message);
                                                        if (response.status == "OK") {
                                                            setTimeout(function() {
                                                                    window.location.href = $('#txth_base').val() + "/inscripciongrado/index";
                                                                }, 3000);
                                                        }
                                                    }, true);
                                                }*/
                                                if (!validateForm()) {
                                                    requestHttpAjax(link, arrParams, function(response) {
                                                        //Ruta es la direccion que deseemos que el boton nos dirija al momento de dar click
                                                        var ruta = [$('#txth_base').val() + "/inscripciongrado/index"];
                                                        //acciones son las variables que debemos enviar para dibujar el o los botones en el modal
                                                        var acciones = [{ id      : 'reloadpage',     //id que tendra el boton
                                                                          class   : 'btn btn-primary',//La clase para poderle dar un estilo al boton
                                                                          value   : 'Aceptar', //Este es el texto que tendra el boton//objLang.Accept,
                                                                          callback: 'gotoPage', //funcion que debe ejecutar el boton
                                                                          paramCallback : ruta, //variable a ser llamada por la funcion anterior ej gotoPage(ruta)
                                                                       }];
                                                        /*var cancelar = [{ callback: 'reloadPage', //funcion que debe ejecutar el boton
                                                                       }];*/
                                                        //Agregamos a nuestra variables message nuestras acciones
                                                        response.message.acciones    = acciones;
                                                        //response.message.closeaction = cancelar;
                                                        //Dejamos que la funcion showAlert dibuje el modal
                                                        showAlert(response.status, response.label, response.message);
                                                    }, true);
                                                }
                                                    }
                                            }
                                            }
                                    }
                                }// cierra if si radio es si
                                else{
                                    /*if (!validateForm()) {
                                        requestHttpAjax(link, arrParams, function (response) {
                                            showAlert(response.status, response.label, response.message);
                                            if (response.status == "OK") {
                                                setTimeout(function() {
                                                        window.location.href = $('#txth_base').val() + "/inscripciongrado/index";
                                                    }, 3000);
                                            }
                                        }, true);
                                    }*/
                                    if (!validateForm()) {
                                        requestHttpAjax(link, arrParams, function(response) {
                                            //Ruta es la direccion que deseemos que el boton nos dirija al momento de dar click
                                            var ruta = [$('#txth_base').val() + "/inscripciongrado/index"];
                                            //acciones son las variables que debemos enviar para dibujar el o los botones en el modal
                                            var acciones = [{ id      : 'reloadpage',     //id que tendra el boton
                                                              class   : 'btn btn-primary',//La clase para poderle dar un estilo al boton
                                                              value   : 'Aceptar', //Este es el texto que tendra el boton//objLang.Accept,
                                                              callback: 'gotoPage', //funcion que debe ejecutar el boton
                                                              paramCallback : ruta, //variable a ser llamada por la funcion anterior ej gotoPage(ruta)
                                                           }];
                                            /*var cancelar = [{ callback: 'reloadPage', //funcion que debe ejecutar el boton
                                                           }];*/
                                            //Agregamos a nuestra variables message nuestras acciones
                                            response.message.acciones    = acciones;
                                            //response.message.closeaction = cancelar;
                                            //Dejamos que la funcion showAlert dibuje el modal
                                            showAlert(response.status, response.label, response.message);
                                        }, true);
                                    }
                                }
                            }
                        }
                    }
                //}
            }
        }
    } else {
        var mensaje = {wtmessage: "Debe Aceptar los términos de la Información.", title: "Exito"};
        showAlert("NO_OK", "success", mensaje);

    }
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

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function actualizarGridAspirante() {
    var search = $('#txt_buscarAspirante').val();
    var periodo = $('#cmb_periodo_asp option:selected').val();
    var unidad = $('#cmb_unidad_asp option:selected').val();
    var carreras = $('#cmb_carrera_asp option:selected').val();
    var modalidad = $('#cmb_modalidad_asp option:selected').val();

    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#grid_registro_aspirante').PbGridView('applyFilterData', {'search': search, 'periodo': periodo, 'unidad': unidad, 'carreras': carreras, 'modalidad': modalidad});
        setTimeout(hideLoadingPopup, 2000);
    }
}
function editaspirantegrado() {
    var link = $('#txth_base').val() + "/inscripciongrado/edit" + "?id=" + $("#frm_per_id").val();
    window.location = link;
}

function updateaspirantegrado() {
    var ID = $('#txth_igra_id').val();
    var link = $('#txth_base').val() + "/inscripciongrado/update";
    var arrParams = new Object();
    arrParams.per_id = $("#frm_per_id").val();
    arrParams.cedula = $('#txt_cedulaEdit').val();
    arrParams.pasaporte = $('#txt_pasaporteEdit').val();
    arrParams.primer_nombre = $('#txt_primer_nombreEdit').val();
    arrParams.segundo_nombre = $('#txt_segundo_nombreEdit').val();
    arrParams.primer_apellido = $('#txt_primer_apellidoEdit').val();
    arrParams.segundo_apellido = $('#txt_segundo_apellidoEdit').val();
    arrParams.cuidad_nac = $('#cmb_ciudadEdit').val();
    arrParams.fecha_nac = $('#txt_fecha_nacimientoEdit').val();
    arrParams.nacionalidad = $('#cmb_nacionalidadEdit').val();
    arrParams.estado_civil = $('#cmb_estadocivilEdit').val();

    //Datos Contacto
    arrParams.pais = $('#cmb_paisEdit').val();
    arrParams.provincia = $('#cmb_provinciaEdit').val();
    arrParams.canton = $('#cmb_cantonEdit').val();
    arrParams.parroquia = $('#txt_parroquiaEdit').val();
    arrParams.dir_domicilio = $('#txt_domicilioEdit').val();
    arrParams.celular = $('#txt_celEdit').val();
    arrParams.telefono = $('#txt_phoneEdit').val();
    arrParams.correo = $('#txt_correoEdit').val();

    //Datos en caso de emergencias
    arrParams.dir_trabajo = $('#txt_trabajo_direccionEdit').val();
    arrParams.cont_emergencia = $('#txt_contc_emergenciasEdit').val();
    arrParams.parentesco = $('#cmb_parentescoEdit').val();
    arrParams.tel_emergencia = $('#txt_cel_contactoEdit').val();
    arrParams.dir_personacontacto = $('#txt_direccion_contEdit').val();

    //TAB 2
    arrParams.igra_ruta_doc_titulo = $('#txth_doc_titulo').val();
    arrParams.igra_ruta_doc_dni = $('#txth_doc_dni').val();
    arrParams.igra_ruta_doc_certvota = $('#txth_doc_certvota').val();
    arrParams.igra_ruta_doc_foto = $('#txth_doc_foto').val();
    arrParams.igra_ruta_doc_comprobantepago = $('#txth_doc_comprobantepago').val();
    arrParams.igra_ruta_doc_record = $('#txth_doc_record').val();
    arrParams.igra_ruta_doc_certificado = $('#txth_doc_nosancion').val();
    arrParams.igra_ruta_doc_syllabus = $('#txth_doc_syllabus').val();
    arrParams.igra_ruta_doc_homologacion = $('#txth_doc_especievalorada').val();


    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            //var message = response.message;
            if (response.status == "OK") {
                setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/inscripciongrado/aspirantegrado";
                    }, 5000);
            }
        }, true);
    }
}

function exportExcelaspirantegrado() {
    var search = $('#txt_buscarAspirante').val();
    var periodo = $('#cmb_periodo_asp option:selected').val();
    var unidad = $('#cmb_unidad_asp option:selected').val();
    var carreras = $('#cmb_carrera_asp option:selected').val();
    var modalidad = $('#cmb_modalidad_asp option:selected').val();
    window.location.href = $('#txth_base').val() + "/inscripciongrado/expexcelaspirantegrado?search=" + search + "&periodo=" + periodo + "&unidad=" + unidad + "&carreras=" + carreras + "&modalidad=" + modalidad;
}