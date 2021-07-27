
$(document).ready(function () {

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
        var link = $('#txth_base').val() + "inscripciongrado/index";
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

    $('#cmb_tipo_dni').change(function () {
        if ($('#cmb_tipo_dni').val() != 0) {
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
    /*$('#btn_buscarCedula').click(function () {
        //buscarpersona('per_cedula');
        var link = $('#txth_base').val() + "/inscripciongrado/index";
        //var cedula = $('#txt_cedula').val();
        var arrParams = new Object();
        arrParams.per_cedula = $('#txt_cedula').val();
        alert($('#txt_cedula').val());
        if ($('#txt_cedula') != 0) {

            $('#Divdatospersona').show();
            
        } else if ($('#txt_cedula') == 0)
        {
            $('#Divdatospersona').hide();
        }
    });*/

    
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
    
    /*$('#paso1next').click(function () {
        //Esta funcion se ejecita en el paso para realizar el primer guardado
        //habilitarSecciones(); esta funcion era para certificado de votacion
        //Si $('#txth_twin_id').val() == 0 quiere decir que no esta cargada
        //el id de la tabla temporal
        if ($('#txth_igra_id').val() == 0) {
            //Si no existe el id en la tabla temporal lo crea
            guardarInscripciongrado();
        } else {
            //Si existe el id procede a actualizar
            guardarInscripciongrado1('Update', '1');
        }
    });*/
    
    /*GUARDAR INFORMACION*/

    $('#btn_save_1').click(function () {
        guardarInscripcionGrado();
        /*var link = $('#txth_base').val() + "/inscripciongrado/guardarinscripciongrado"; 
        var arrParams = new Object();
        arrParams.persona_id = $('#txth_ids').val();
        arrParams.codigo = $('#txth_igra_id').val();
        arrParams.ACCION = 'Fin';

        arrParams.unidad = $('#cmb_unidad').val();
        arrParams.carrera = $('#cmb_carrera').val();
        arrParams.modalidad = $('#cmb_modalidad').val();
        arrParams.periodo = $('#cmb_periodo').val();

        //datos personales
        arrParams.cedula = $('#txt_cedula').val();
        arrParams.pasaporte = $('#txt_pasaporte').val();
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
        arrParams.parroquia = $('#cmb_parroquia').val();
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

        //alert(arrParams.persona_id);
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
               
            }, true);
        }*/
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
    var ID = /*(accion == "Update") ? */$('#txth_igra_id').val()/* : 0*/;
    var link = $('#txth_base').val() + "/inscripciongrado/guardarinscripciongrado";
    var arrParams = new Object();
    //arrParams.DATA_1 = dataInscripcion(ID);
    arrParams.igra_id = $('#txth_igra_id').val();
    arrParams.unidad = $('#cmb_unidad').val();
    arrParams.carrera = $('#cmb_carrera').val();
    arrParams.modalidad = $('#cmb_modalidad').val();
    arrParams.periodo = $('#cmb_periodo').val();
    arrParams.tipo_dni = $('#cmb_tipo_dni option:selected').val();
    //alert($('#cmb_unidad').val());
    //alert($('#cmb_carrera').val());
    //alert($('#cmb_modalidad').val());
    //alert($('#cmb_periodo').val());
    if (arrParams.unidad == 1) {
        //objDat.ming_id = null;
    } else if (arrParams.unidad_academica == 2) {
        arrParams.ming_id = $('#cmb_metodo_solicitud option:selected').val();
    }
    if (arrParams.tipo_dni == 'CED') {
        arrParams.cedula = $('#txt_cedula').val();
    } else {
        arrParams.cedula = $('#txt_pasaporte').val();
    }
    //arrParams.ACCION = accion;
    var error = 0;
        //var pais = $('#cmb_pais_dom').val();
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
                if ($('#txth_doc_certvota').val() == "") {
                    error++;
                    var mensaje = {wtmessage: "Debe adjuntar certificado de votación.", title: "Información"};
                    showAlert("NO_OK", "error", mensaje);
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
    //datos personales
        arrParams.cedula = $('#txt_cedula').val();
        arrParams.pasaporte = $('#txt_pasaporte').val();
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
        arrParams.parroquia = $('#cmb_parroquia').val();
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

        /*if (error == 0) {
            guardarInscripcion('Update', '2');
        }*/

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

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) { 
            showAlert(response.status, response.label, response.message);
            //var message = response.message;                       
            if (response.status == "OK") {
                /*var unidad = response.data.data.unidad;
                    //Inicio ingreso informacion\
                    $('#cmb_unidad').text(response.data.data.unidad);
                    $('#cmb_carrera').text(response.data.data.carrera);
                    $('#cmb_modalidad').text(response.data.data.modalidad);
                    $('#cmb_periodo').text(response.data.data.periodo);
                    //$('#lbl_ming_tx').text(response.data.data.metodo);
                //return 1;*/
                setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/inscripciongrado/index";
                    }, 3000);
            }
        }, true);
    }
}

function dataInscripcion(ID) {
    var datArray = new Array();
    var objDat = new Object();
    objDat.igra_id = ID;

    /*objDat.tipo_dni = $('#cmb_tipo_dni option:selected').val();
    if (objDat.tipo_dni == 'CED') {
        objDat.cedula = $('#txt_cedula').val();
    } else {
        objDat.cedula = $('#txt_pasaporte').val();
    }
    objDat.unidad = $('#cmb_unidad option:selected').val();
    objDat.carrera = $('#cmb_carrera option:selected').val();
    objDat.modalidad = $('#cmb_modalidad option:selected').val();
    objDat.periodo = $('#cmb_periodo option:selected').val();
    if (objDat.unidad == 1) {
        //objDat.ming_id = null;
    } else if (objDat.unidad_academica == 2) {
        objDat.ming_id = $('#cmb_metodo_solicitud option:selected').val();
    }*/

    //TAB 2
    objDat.igra_ruta_doc_titulo = ($('#txth_doc_titulo').val() != '') ? $('#txth_doc_titulo').val() : '';
    objDat.igra_ruta_doc_dni = ($('#txth_doc_dni').val() != '') ? $('#txth_doc_dni').val() : '';
    objDat.igra_ruta_doc_certvota = ($('#txth_doc_certvota').val() != '') ? $('#txth_doc_certvota').val() : '';
    objDat.igra_ruta_doc_foto = ($('#txth_doc_foto').val() != '') ? $('#txth_doc_foto').val() : '';
    objDat.igra_ruta_doc_comprobantepago = ($('#txth_doc_comprobantepago').val() != '') ? $('#txth_doc_comprobantepago').val() : '';
    objDat.igra_ruta_doc_record = ($('#txth_doc_record').val() != '') ? $('#txth_doc_record').val() : '';
    objDat.igra_ruta_doc_certificado = ($('#txth_doc_nosancion').val() != '') ? $('#txth_doc_nosancion').val() : '';
    objDat.igra_ruta_doc_syllabus = ($('#txth_doc_syllabus').val() != '') ? $('#txth_doc_syllabus').val() : '';
    objDat.igra_ruta_doc_homologacion = ($('#txth_doc_especievalorada').val() != '') ? $('#txth_doc_especievalorada').val() : '';
    objDat.igra_mensaje1 = ($("#chk_mensaje1").prop("checked")) ? '1' : '0';
    objDat.igra_mensaje2 = ($("#chk_mensaje2").prop("checked")) ? '1' : '0';
    
  
    datArray[0] = objDat;
    sessionStorage.dataInscripciones = JSON.stringify(datArray);
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

