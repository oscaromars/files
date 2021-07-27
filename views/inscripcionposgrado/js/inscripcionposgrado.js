
$(document).ready(function () {
    $('#cmb_programa').change(function () {
        var link = $('#txth_base').val() + "/inscripcionposgrado/index";
        var arrParams = new Object();
        arrParams.unidad = $('#cmb_unidadpos').val();
        arrParams.programa = $(this).val();
        //arrParams.eaca_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidadpos", "Seleccionar");
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

    $('#cmb_nacionalidad').change(function () {
        var link = $('#txth_base').val() + "/inscripcionposgrado/index";
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
        $("#lbl_codeCountry").text($("#cmb_nacionalidad option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_nacionalidad option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_nacionalidad option:selected").attr("data-code"));
    });

    $('#cmb_provincia').change(function () {
        var link = $('#txth_base').val() + "/inscripcionposgrado/index";
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

    $('#cmb_prov_emp').change(function () {
        var link = $('#txth_base').val() + "/inscripcionposgrado/index";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_emp");
            }
        }, true);
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
    });
    $('#paso3next').click(function () {
        $("a[data-href='#paso3']").attr('data-toggle', 'none');
        $("a[data-href='#paso3']").parent().attr('class', 'disabled');
        $("a[data-href='#paso3']").attr('data-href', $("a[href='#paso3']").attr('href'));
        $("a[data-href='#paso3']").removeAttr('href');
        $("a[data-href='#paso4']").attr('data-toggle', 'tab');
        $("a[data-href='#paso4']").attr('href', $("a[data-href='#paso4']").attr('data-href'));
        $("a[data-href='#paso4']").trigger("click");
    });
    $('#paso4back').click(function () {
        $("a[data-href='#paso4']").attr('data-toggle', 'none');
        $("a[data-href='#paso4']").parent().attr('class', 'disabled');
        $("a[data-href='#paso4']").attr('data-href', $("a[href='#paso4']").attr('href'));
        $("a[data-href='#paso4']").removeAttr('href');
        $("a[data-href='#paso3']").attr('data-toggle', 'tab');
        $("a[data-href='#paso3']").attr('href', $("a[data-href='#paso3']").attr('data-href'));
        $("a[data-href='#paso3']").trigger("click");
    });
    
    /*GUARDAR INFORMACION*/

    $('#btn_guardar').click(function () {
        guardarInscripcionPosgrado();
        /*var arrParams = new Object();
        var link = $('#txth_base').val() + "/inscripcionposgrado/guardarinscripcionposgrado";
        //arrParams.persona_id = $('#txth_ids').val();

        arrParams.unidad = $('#cmb_unidad').val();
        arrParams.programa = $('#cmb_carrera').val();
        arrParams.modalidad = $('#cmb_modalidad').val();
        arrParams.año = $('#txt_año').val();*/

        


        //alert(arrParams.persona_id);
        /*if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    if (arrParams.persona_id > '0')
                    {
                        window.location.href = $('#txth_base').val() + "/interesado/listarinteresados";
                    } else
                    {
                        window.location.href = $('#txth_base').val() + "/ficha/view";
                    }
                }, 3000);
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

    //Control del div de docencia
    $('#signup-doc').change(function () {
        if ($('#signup-doc').val() == 1) {
            $('#docencia').css('display', 'block');
            $("#signup-doc_no").prop("checked", "");
        } else {
            $('#docencia').css('display', 'none');
        }
    });

    $('#signup-doc_no').change(function () {
        if ($('#signup-doc_no').val() == 2) {
            $('#docencia').css('display', 'none');
            $("#signup-doc").prop("checked", "");
        } else {
            $('#docencia').css('display', 'block');
        }
    });
    //Control del div de investigacion
    $('#signup-inv').change(function () {
        if ($('#signup-inv').val() == 1) {
            $('#investigacion').css('display', 'block');
            $("#signup-inv_no").prop("checked", "");
        } else {
            $('#investigacion').css('display', 'none');
        }
    });

    $('#signup-inv_no').change(function () {
        if ($('#signup-inv_no').val() == 2) {
            $('#investigacion').css('display', 'none');
            $("#signup-inv").prop("checked", "");
        } else {
            $('#investigacion').css('display', 'block');
        }
    });

    //control del div de financiamiento

    $("#paso3next").click(function () {  
        //$('#tipoFinanciamiento').on('click', function () {
        var tipo_financiamiento = $("[name=signup]:checked").val();
        //alert($("[name=signup]:checked").val());
        //})
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

function guardarInscripcionPosgrado() {
    var ID = /*(accion == "UpdateDepTrans") ?*/ $('#txth_ipos_id').val()/* : 0*/;
    var link = $('#txth_base').val() + "/inscripcionposgrado/guardarinscripcionposgrado";
    var arrParams = new Object();
    //arrParams.DATA_1 = dataInscripcion(ID);
    arrParams.unidad = $('#cmb_unidadpos').val();
    arrParams.programa = $('#cmb_programa').val();
    arrParams.modalidad = $('#cmb_modalidadpos').val();
    arrParams.año = $('#txt_año').val();
    arrParams.tipo_dni = $('#cmb_tipo_dni option:selected').val();
    //arrParams.ACCION = accion;
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
    alert($('#cmb_unidadpos').val());
    alert($('#cmb_programa').val());
    alert($('#cmb_modalidadpos').val());
    alert($('#txt_año').val());
    
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
    //FORM 1 datos personal
    arrParams.cedula = $('#txt_cedula').val();
    arrParams.primer_nombre = $('#txt_primer_nombre').val();
    arrParams.segundo_nombre = $('#txt_segundo_nombre').val();
    arrParams.primer_apellido = $('#txt_primer_apellido').val();
    arrParams.segundo_apellido = $('#txt_segundo_apellido').val();
    arrParams.cuidad_nac = $('#cmb_ciu_nac').val();
    arrParams.fecha_nac = $('#txt_fecha_nac').val();
    arrParams.nacionalidad = $('#cmb_nacionalidad').val();
    arrParams.estado_civil = $('#cmb_estado_civil').val();
    arrParams.pais = $('#cmb_pais').val();
    arrParams.provincia = $('#cmb_provincia').val();
    arrParams.canton = $('#cmb_ciudad').val();

    //Form 1 Datos Contacto
    arrParams.dir_domicilio = $('#txt_domicilio').val();
    arrParams.celular = $('#txt_celular').val();
    arrParams.telefono = $('#txt_telefono').val();
    arrParams.correo = $('#txt_correo').val();

    //FORM 1 datos en caso de emergencias
    arrParams.cont_emergencia = $('#txt_contacto_emergencia').val();
    arrParams.parentesco = $('#cmb_parentesco').val();
    arrParams.tel_emergencia = $('#txt_telefono_emergencia').val();

    //Form2 Datos formacion profesional
    arrParams.titulo_tercer = $('#txt_titulo_3erNivel').val();
    arrParams.universidad_tercer = $('#txt_universidad1').val();
    arrParams.grado_tercer = $('#txt_año_grado1').val();

    arrParams.titulo_cuarto = $('#txt_titulo_4toNivel').val();
    arrParams.universidad_cuarto = $('#txt_universidad2').val();
    arrParams.grado_cuarto = $('#txt_año_grado2').val();

    //Form2 Datos laborales
    arrParams.empresa = $('#txt_empresa').val();
    arrParams.cargo = $('#txt_cargo').val();
    arrParams.telefono_emp = $('#txt_telefono_emp').val();
    arrParams.prov_emp = $('#cmb_prov_emp').val();
    arrParams.ciu_emp = $('#cmb_ciu_emp').val();
    arrParams.parroquia = $('#txt_parroquia').val();
    arrParams.direccion_emp = $('#txt_direc_emp').val();
    arrParams.añoingreso_emp = $('#txt_añoingreso_emp').val();
    arrParams.correo_emp = $('#txt_correo_emp').val();
    arrParams.cat_ocupacional = $('#txt_cat_ocupacional').val();

    //Form2 Datos idiomas
    arrParams.idioma1 = $('#cmb_idioma1').val();
    arrParams.nivel1 = $('#cmb_nivelidioma1').val();

    arrParams.idioma2 = $('#cmb_idioma2').val();
    arrParams.nivel2 = $('#cmb_nivelidioma2').val();

    //Form2 Datos adicionales
    arrParams.discapacidades = $('input[name=signup-dis]:checked').val();
    arrParams.tipo_discap = $('#cmb_tipo_discap').val();
    arrParams.porcentaje_discap = $('#txt_porc_discapacidad').val();
    arrParams.discapacidad = "1";
    if ($('input[name=signup-dis_no]:checked').val() == 2) {
        $('#txt_porc_discapacidad').removeClass("PBvalidation");
        arrParams.discapacidad = "0";
    }
    arrParams.docencia = $('input[name=signup-doc]:checked').val();
    arrParams.año_docencia = $('#txt_año_docencia').val();
    arrParams.area_docencia = $('#txt_area_docencia').val();
    arrParams.docencias = "1";
    if ($('input[name=signup-doc_no]:checked').val() == 2) {
        $('#txt_area_docencia').removeClass("PBvalidation");
        arrParams.docencias = "0";
    }
    arrParams.investigacion = $('input[name=signup-inv]:checked').val();
    arrParams.articulos = $('#txt_articulos').val();
    arrParams.area_investigacion = $('#txt_area_investigacion').val();
    arrParams.investiga = "1";
    if ($('input[name=signup-inv_no]:checked').val() == 2) {
        $('#txt_area_investigacion').removeClass("PBvalidation");
        arrParams.investiga = "0";
    }

    //Form2 Datos financiamiento
    arrParams.tipo_financiamiento = $("[name=signup]:checked").val();
    

     //TAB 2
    arrParams.ipos_ruta_doc_foto = ($('#txth_doc_foto').val() != '') ? $('#txth_doc_foto').val() : '';
    arrParams.ipos_ruta_doc_dni = ($('#txth_doc_dni').val() != '') ? $('#txth_doc_dni').val() : '';
    arrParams.ipos_ruta_doc_certvota = ($('#txth_doc_certvota').val() != '') ? $('#txth_doc_certvota').val() : '';
    arrParams.ipos_ruta_doc_titulo = ($('#txth_doc_titulo').val() != '') ? $('#txth_doc_titulo').val() : '';
    arrParams.ipos_ruta_doc_comprobante = ($('#txth_doc_comprobante').val() != '') ? $('#txth_doc_comprobante').val() : '';
    arrParams.ipos_ruta_doc_record1 = ($('#txth_doc_record1').val() != '') ? $('#txth_doc_record1').val() : '';
    arrParams.ipos_ruta_doc_senescyt = ($('#txth_doc_senecyt').val() != '') ? $('#txth_doc_senecyt').val() : '';
    arrParams.ipos_ruta_doc_hojavida = ($('#txth_doc_hojavida').val() != '') ? $('#txth_doc_hojavida').val() : '';
    arrParams.ipos_ruta_doc_cartarecomendacion = ($('#txth_doc_cartarecomendacion').val() != '') ? $('#txth_doc_cartarecomendacion').val() : '';
    arrParams.ipos_ruta_doc_certificadolaboral = ($('#txth_doc_certificadolaboral').val() != '') ? $('#txth_doc_certificadolaboral').val() : '';
    arrParams.ipos_ruta_doc_certificadoingles = ($('#txth_doc_certificadoingles').val() != '') ? $('#txth_doc_certificadoingles').val() : '';
    arrParams.ipos_ruta_doc_recordacademico = ($('#txth_doc_recordacad').val() != '') ? $('#txth_doc_recordacad').val() : '';
    arrParams.ipos_ruta_doc_certnosancion = ($('#txth_doc_nosancion').val() != '') ? $('#txth_doc_nosancion').val() : '';
    arrParams.ipos_ruta_doc_syllabus = ($('#txth_doc_syllabus').val() != '') ? $('#txth_doc_syllabus').val() : '';
    arrParams.ipos_ruta_doc_homologacion = ($('#txth_doc_especievalorada').val() != '') ? $('#txth_doc_especievalorada').val() : '';
    arrParams.ipos_mensaje1 = ($("#chk_mensaje1").prop("checked")) ? '1' : '0';
    arrParams.ipos_mensaje2 = ($("#chk_mensaje2").prop("checked")) ? '1' : '0';

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
                    //$('#lbl_ming_tx').text(response.data.data.metodo);*/
                //return 1;
                setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/inscripcionposgrado/index";
                    }, 3000);
            }
        }, true);
    }
}

function dataInscripcion(ID) {
    var datArray = new Array();
    var objDat = new Object();
    objDat.ipos_id = ID;
    objDat.tipo_dni = $('#cmb_tipo_dni option:selected').val();
    if (objDat.tipo_dni == 'CED') {
        objDat.cedula = $('#txt_cedula').val();
    } else {
        objDat.cedula = $('#txt_pasaporte').val();
    }
    objDat.unidad = 2;
    objDat.carrera = $('#cmb_programa option:selected').val();
    objDat.modalidad = $('#cmb_modalidad option:selected').val();
    objDat.año = $('#txt_año').val();
    objDat.tipo_financiamiento = $("[name=signup]:checked").val();
    /*if (objDat.unidad == 1) {
        //objDat.ming_id = null;
    } else if (objDat.unidad_academica == 2) {
        objDat.ming_id = $('#cmb_metodo_solicitud option:selected').val();
    }*/

    //TAB 2
    objDat.ipos_ruta_doc_foto = ($('#txth_doc_foto').val() != '') ? $('#txth_doc_foto').val() : '';
    objDat.ipos_ruta_doc_dni = ($('#txth_doc_dni').val() != '') ? $('#txth_doc_dni').val() : '';
    objDat.ipos_ruta_doc_certvota = ($('#txth_doc_certvota').val() != '') ? $('#txth_doc_certvota').val() : '';
    objDat.ipos_ruta_doc_titulo = ($('#txth_doc_titulo').val() != '') ? $('#txth_doc_titulo').val() : '';
    objDat.ipos_ruta_doc_comprobante = ($('#txth_doc_comprobante').val() != '') ? $('#txth_doc_comprobante').val() : '';
    objDat.ipos_ruta_doc_record1 = ($('#txth_doc_record1').val() != '') ? $('#txth_doc_record1').val() : '';
    objDat.ipos_ruta_doc_senescyt = ($('#txth_doc_senecyt').val() != '') ? $('#txth_doc_senecyt').val() : '';
    objDat.ipos_ruta_doc_hojavida = ($('#txth_doc_hojavida').val() != '') ? $('#txth_doc_hojavida').val() : '';
    objDat.ipos_ruta_doc_cartarecomendacion = ($('#txth_doc_cartarecomendacion').val() != '') ? $('#txth_doc_cartarecomendacion').val() : '';
    objDat.ipos_ruta_doc_certificadolaboral = ($('#txth_doc_certificadolaboral').val() != '') ? $('#txth_doc_certificadolaboral').val() : '';
    objDat.ipos_ruta_doc_certificadoingles = ($('#txth_doc_certificadoingles').val() != '') ? $('#txth_doc_certificadoingles').val() : '';
    objDat.ipos_ruta_doc_recordacademico = ($('#txth_doc_recordacad').val() != '') ? $('#txth_doc_recordacad').val() : '';
    objDat.ipos_ruta_doc_certnosancion = ($('#txth_doc_nosancion').val() != '') ? $('#txth_doc_nosancion').val() : '';
    objDat.ipos_ruta_doc_syllabus = ($('#txth_doc_syllabus').val() != '') ? $('#txth_doc_syllabus').val() : '';
    objDat.ipos_ruta_doc_homologacion = ($('#txth_doc_especievalorada').val() != '') ? $('#txth_doc_especievalorada').val() : '';
    objDat.ipos_mensaje1 = ($("#chk_mensaje1").prop("checked")) ? '1' : '0';
    objDat.ipos_mensaje2 = ($("#chk_mensaje2").prop("checked")) ? '1' : '0';
  
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