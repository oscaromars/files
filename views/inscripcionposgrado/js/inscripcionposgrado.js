
$(document).ready(function () {
    $('#btn_buscarAsp_posgrado').click(function () {
        actualizarGridAspirantePosgrado();
    });

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
    
    $('#cmb_programa_pos').change(function () {
        var link = $('#txth_base').val() + "/inscripcionposgrado/index";
        var arrParams = new Object();
        arrParams.unidad = $('#cmb_unidad_pos').val();
        arrParams.programa = $(this).val();
        //arrParams.eaca_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad_pos", "Seleccionar");
            }
        }, true);
    });

    $('#cmb_idioma2').change(function () {
        var valor = $('#cmb_idioma2').val();
        if (valor == 3) {
            $('#cmb_nivelidioma2').removeClass("PBvalidation");
            $('#txt_nombreidioma').addClass("PBvalidation");
            $('#cmb_nivelotroidioma').addClass("PBvalidation");
            $('#Divotroidioma').show();
            $('#Divotronivelidioma').show();
            $('#Dividiomas').hide();
        } else if (valor == 2)
        {
            $('#txt_nombreidioma').removeClass("PBvalidation");
            $('#cmb_nivelotroidioma').removeClass("PBvalidation");
            $('#cmb_nivelidioma2').addClass("PBvalidation");
            $('#Divotroidioma').hide();
            $('#Divotronivelidioma').hide();
            $('#Dividiomas').show();
        }
    });

    $('#cmb_idioma2Edit').change(function () {
        var valor = $('#cmb_idioma2Edit').val();
        if (valor == 3) {
            $('#cmb_nivelidioma2Edit').removeClass("PBvalidation");
            $('#txt_nombreidiomaEdit').addClass("PBvalidation");
            $('#cmb_nivelotroidiomaEdit').addClass("PBvalidation");
            $('#Divotroidioma').show();
            $('#Divotronivelidioma').show();
            $('#Dividiomas').hide();
        } else if (valor == 2)
        {
            $('#txt_nombreidiomaEdit').removeClass("PBvalidation");
            $('#cmb_nivelotroidiomaEdit').removeClass("PBvalidation");
            $('#cmb_nivelidioma2Edit').addClass("PBvalidation");
            $('#Divotroidioma').hide();
            $('#Divotronivelidioma').hide();
            $('#Dividiomas').show();
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
    });

    //Control del div de discapacidad
    $('#signup-dis').change(function () {
        if ($('#signup-dis').val() == 1) {
            $('#discapacidad').css('display', 'block');
            $("#cmb_tipo_discap").addClass("PBvalidation");
            $("#txt_porc_discapacidad").addClass("PBvalidation");
            $("#signup-dis_no").prop("checked", "");
        } else {
            $('#discapacidad').css('display', 'none');
            $("#cmb_tipo_discap").removeClass("PBvalidation");
            $("#txt_porc_discapacidad").removeClass("PBvalidation");
        }
    });

    $('#signup-dis_no').change(function () {
        if ($('#signup-dis_no').val() == 2) {
            $('#discapacidad').css('display', 'none');
            $("#cmb_tipo_discap").removeClass("PBvalidation");
            $("#txt_porc_discapacidad").removeClass("PBvalidation");
            $("#signup-dis").prop("checked", "");
        } else {
            $('#discapacidad').css('display', 'block');
            $("#cmb_tipo_discap").addClass("PBvalidation");
            $("#txt_porc_discapacidad").addClass("PBvalidation");
        }
    });

    //Control del div de docencia
    $('#signup-doc').change(function () {
        if ($('#signup-doc').val() == 1) {
            $('#docencia').css('display', 'block');
            $("#txt_año_docencia").addClass("PBvalidation");
            $("#txt_area_docencia").addClass("PBvalidation");
            $("#signup-doc_no").prop("checked", "");
        } else {
            $('#docencia').css('display', 'none');
            $("#txt_año_docencia").removeClass("PBvalidation");
            $("#txt_area_docencia").removeClass("PBvalidation");
        }
    });

    $('#signup-doc_no').change(function () {
        if ($('#signup-doc_no').val() == 2) {
            $('#docencia').css('display', 'none');
            $("#txt_año_docencia").removeClass("PBvalidation");
            $("#txt_area_docencia").removeClass("PBvalidation");
            $("#signup-doc").prop("checked", "");
        } else {
            $('#docencia').css('display', 'block');
            $("#txt_año_docencia").addClass("PBvalidation");
            $("#txt_area_docencia").addClass("PBvalidation");
        }
    });
    //Control del div de investigacion
    $('#signup-inv').change(function () {
        if ($('#signup-inv').val() == 1) {
            $('#investigacion').css('display', 'block');
            $("#txt_articulos").addClass("PBvalidation");
            $("#txt_area_investigacion").addClass("PBvalidation");
            $("#signup-inv_no").prop("checked", "");
        } else {
            $('#investigacion').css('display', 'none');
            $("#txt_articulos").removeClass("PBvalidation");
            $("#txt_area_investigacion").removeClass("PBvalidation");
        }
    });

    $('#signup-inv_no').change(function () {
        if ($('#signup-inv_no').val() == 2) {
            $('#investigacion').css('display', 'none');
            $("#txt_articulos").removeClass("PBvalidation");
            $("#txt_area_investigacion").removeClass("PBvalidation");
            $("#signup-inv").prop("checked", "");
        } else {
            $('#investigacion').css('display', 'block');
            $("#txt_articulos").addClass("PBvalidation");
            $("#txt_area_investigacion").addClass("PBvalidation");
        }
    });

    //control del div de financiamiento

    $("#paso3next").click(function () {  
        var tipo_financiamiento = $("[name=signup]:checked").val();
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
    var ID = /*(accion == "UpdateDepTrans") ?*/$('#txth_ipos_id').val()/* : 0*/;
    var link = $('#txth_base').val() + "/inscripcionposgrado/guardarinscripcionposgrado";
    var arrParams = new Object();
    //arrParams.DATA_1 = dataInscripcion(ID);
    arrParams.ipos_id = $('#txth_ipos_id').val();
    arrParams.unidad = $('#cmb_unidadpos').val();
    arrParams.programa = $('#cmb_programa').val();
    arrParams.modalidad = $('#cmb_modalidadpos').val();
    arrParams.año = $('#txt_año').val();
    arrParams.tipo_dni = $('#cmb_tipo_dni option:selected').val();
    //arrParams.ACCION = accion;
    if (arrParams.unidad == 2) {
        //objDat.ming_id = null;
    } else if (arrParams.unidad_academica == 1) {
        arrParams.ming_id = $('#cmb_metodo_solicitud option:selected').val();
    }
    if (arrParams.tipo_dni == 'CED') {
        arrParams.cedula = $('#txt_cedula').val();
    } else {
        arrParams.cedula = $('#txt_pasaporte').val();
    }
    
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
    arrParams.pasaporte = $('#txt_pasaporte').val();
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

    arrParams.otroidioma = $('#txt_nombreidioma').val();
    arrParams.otronivel = $('#cmb_nivelotroidioma').val();

    arrParams.tipo_idioma = $('#cmb_idioma2 option:selected').val();
    if (arrParams.tipo_idioma == 3) {
        arrParams.otroidioma = $('#txt_nombreidioma').val();
        arrParams.otronivel = $('#cmb_nivelotroidioma').val();
    } else {
        arrParams.nivel2 = $('#cmb_nivelidioma2').val();
    }

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
                setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/inscripcionposgrado/aspiranteposgrado";
                    }, 3000);
            }
        }, true);
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

function actualizarGridAspirantePosgrado(){
    var search = $('#txt_buscarAspirante').val();
    var año = $('#txt_año_pos option:selected').val();
    var unidad = $('#cmb_unidad_pos option:selected').val();
    var programa = $('#cmb_programa_pos option:selected').val();
    var modalidad = $('#cmb_modalidad_pos option:selected').val();
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Tbg_Registro_posgrado').PbGridView('applyFilterData', {'search': search, 'año': año, 'unidad': unidad, 'programa': programa, 'modalidad': modalidad});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function editaspirantegrado() {
    var link = $('#txth_base').val() + "/inscripcionposgrado/edit" + "?id=" + $("#frm_per_id").val();
    window.location = link;
}

function updateaspirantegrado() {
    var link = $('#txth_base').val() + "//inscripcionposgrado/update";
    var arrParams = new Object();
    arrParams.per_id = $("#frm_per_id").val();
    arrParams.pri_nombre = $('#txt_primer_nombre').val();
    arrParams.seg_nombre = $('#txt_segundo_nombre').val();
    arrParams.pri_apellido = $('#txt_primer_apellido').val();
    arrParams.seg_apellido = $('#txt_segundo_apellido').val();
    arrParams.cedula = $('#txt_cedula').val();
    arrParams.ruc = $('#txt_ruc').val();
    arrParams.pasaporte = $('#txt_pasaporte').val();
    arrParams.correo = $('#txt_correo').val();

    arrParams.pai_id = $('#cmb_pais').val();
    arrParams.pro_id = $('#cmb_provincia').val();
    arrParams.can_id = $('#cmb_canton').val();
    arrParams.sector = $('#txt_sector').val();
    arrParams.calle_pri = $('#txt_calle_pri').val();
    arrParams.calle_sec = $('#txt_calle_sec').val();
    arrParams.numeracion = $('#txt_numeracion').val();
    arrParams.referencia = $('#txt_referencia').val();
    arrParams.nacionalidad = $('#txt_nacionalidad').val();
    arrParams.celular = $('#txt_cel').val();
    arrParams.phone = $('#txt_phone').val();
    arrParams.dedicacion = $('#cmb_dedicacion').val();
    arrParams.pro_num_contrato = $('#txt_contrato').val();
    arrParams.fecha_nacimiento = $('#txt_fecha_nacimiento').val();

    arrParams.usuario = $('#txt_usuario').val();
    arrParams.clave = $('#frm_clave').val();
    arrParams.gru_id = $('#cmb_grupo').val();
    arrParams.rol_id = $('#cmb_rol').val();
    arrParams.emp_id = $('#cmb_empresa').val();
    arrParams.foto = $('#txth_doc_foto').val() + ".jpg";

    /** Session Storages **/
    arrParams.grid_instruccion_list = (JSON.parse(sessionStorage.grid_instruccion_list)).data;
    arrParams.grid_docencia_list = (JSON.parse(sessionStorage.grid_docencia_list)).data;
    arrParams.grid_experiencia_list = (JSON.parse(sessionStorage.grid_experiencia_list)).data;
    arrParams.grid_idioma_list = (JSON.parse(sessionStorage.grid_idioma_list)).data;
    arrParams.grid_investigacion_list = (JSON.parse(sessionStorage.grid_investigacion_list)).data;
    arrParams.grid_evento_list = (JSON.parse(sessionStorage.grid_evento_list)).data;
    arrParams.grid_conferencia_list = (JSON.parse(sessionStorage.grid_conferencia_list)).data;
    arrParams.grid_publicacion_list = (JSON.parse(sessionStorage.grid_publicacion_list)).data;
    arrParams.grid_coordinacion_list = (JSON.parse(sessionStorage.grid_coordinacion_list)).data;
    arrParams.grid_evaluacion_list = (JSON.parse(sessionStorage.grid_evaluacion_list)).data;
    arrParams.grid_referencia_list = (JSON.parse(sessionStorage.grid_referencia_list)).data;


    if (!validateForm()) {
        //console.log(arrParams);
        requestHttpAjax(link, arrParams, function(response) {
            var btnacciones = new Array();
            var accion1 = new Object();
            accion1.id = "btnid1";
            accion1.class = "clclass";
            accion1.value = "Aceptar";
            accion1.callback = "backtoList";
            btnacciones[0] = accion1;
            response.message.acciones = btnacciones;
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

/** IDIOMAS **/
function addIdioma() {
    var idioma = $("#cmb_idioma2Edit :selected").val();
    var nivelidioma = $("#cmb_nivelidioma2Edit :selected").text(); 

    if (idioma == 3) {
        var otroidioma = $('#txt_nombreidiomaEdit').val();
        var otronivel = $('#cmb_nivelotroidiomaEdit').val();
    } else {
        nivelidioma = $('#cmb_nivelidioma2Edit').val();
    }
    /*if (escrito == "" || oral == "" || certificado == "" || institucion == "") {
        fillDataAlert();
        return;
    }*/

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = idioma;
    tb_item[2] = nivelidioma;
    tb_item[3] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = otroidioma;
    tb_item2[2] = otronivel;
    tb_item2[3] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemIdioma(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_idiomas_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemIdioma(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_idiomas_list = JSON.stringify(arrData);
    addItemGridContent("grid_idiomas_list");

    $("#cmb_idioma2Edit").val('');
    $("#cmb_nivelidioma2Edit").val('');
    $("#txt_nombreidiomaEdit").val('');
    $("#cmb_nivelotroidiomaEdit").val('');
}

function removeItemIdioma(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_idiomas_list", indice);
    removeItemsBase(indice,4);
}