
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
    
    /* codigo de area en datos personales*/
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

    $('#cmb_pais_emp').change(function () {
        var link = $('#txth_base').val() + "/inscripcionposgrado/index";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_emp");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_emp");
                        }
                    }, true);
                }

            }
        }, true);
        // actualizar codigo pais
        $("#lbl_codeCountry").text($("#cmb_pais_emp option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_pais_emp option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_pais_emp option:selected").attr("data-code"));
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

    $('#cmb_raza_etnica').change(function () {
        var valor = $('#cmb_raza_etnica').val();
        if (valor == 6) {
            $("#txt_otra_etnia").removeAttr("disabled");
        } else {
            $("#txt_otra_etnia").attr('disabled', 'disabled');
            $("#txt_otra_etnia").val("");
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
    /*$('#paso4next').click(function () {
        $("a[data-href='#paso4']").attr('data-toggle', 'none');
        $("a[data-href='#paso4']").parent().attr('class', 'disabled');
        $("a[data-href='#paso4']").attr('data-href', $("a[href='#paso4']").attr('href'));
        $("a[data-href='#paso4']").removeAttr('href');
        $("a[data-href='#paso5']").attr('data-toggle', 'tab');
        $("a[data-href='#paso5']").attr('href', $("a[data-href='#paso5']").attr('data-href'));
        $("a[data-href='#paso5']").trigger("click");
    });
    $('#paso5back').click(function () {
        $("a[data-href='#paso5']").attr('data-toggle', 'none');
        $("a[data-href='#paso5']").parent().attr('class', 'disabled');
        $("a[data-href='#paso5']").attr('data-href', $("a[href='#paso5']").attr('href'));
        $("a[data-href='#paso5']").removeAttr('href');
        $("a[data-href='#paso4']").attr('data-toggle', 'tab');
        $("a[data-href='#paso4']").attr('href', $("a[data-href='#paso4']").attr('data-href'));
        $("a[data-href='#paso4']").trigger("click");
    });
    $('#paso5next').click(function () {
        $("a[data-href='#paso5']").attr('data-toggle', 'none');
        $("a[data-href='#paso5']").parent().attr('class', 'disabled');
        $("a[data-href='#paso5']").attr('data-href', $("a[href='#paso5']").attr('href'));
        $("a[data-href='#paso5']").removeAttr('href');
        $("a[data-href='#paso6']").attr('data-toggle', 'tab');
        $("a[data-href='#paso6']").attr('href', $("a[data-href='#paso6']").attr('data-href'));
        $("a[data-href='#paso6']").trigger("click");
    });
    $('#paso6back').click(function () {
        $("a[data-href='#paso6']").attr('data-toggle', 'none');
        $("a[data-href='#paso6']").parent().attr('class', 'disabled');
        $("a[data-href='#paso6']").attr('data-href', $("a[href='#paso6']").attr('href'));
        $("a[data-href='#paso6']").removeAttr('href');
        $("a[data-href='#paso5']").attr('data-toggle', 'tab');
        $("a[data-href='#paso5']").attr('href', $("a[data-href='#paso5']").attr('data-href'));
        $("a[data-href='#paso5']").trigger("click");
    });*/
    
    /*GUARDAR INFORMACION*/

    $('#btn_save_1').click(function () {
        var arrParams = new Object();
        var link = $('#txth_base').val() + "/fichasocioeconomica/guardarfichasocioeconomica";
        //arrParams.persona_id = $('#txth_ids').val();
        //FORM 1 datos personal
        arrParams.primer_nombre = $('#txt_primer_nombre').val();
        arrParams.segundo_nombre = $('#txt_segundo_nombre').val();
        arrParams.primer_apellido = $('#txt_primer_apellido').val();
        arrParams.segundo_apellido = $('#txt_segundo_apellido').val();
        arrParams.genero_persona = $('#cmb_genero').val();
        arrParams.nacionalidad = $('#cmb_nacionalidad').val();
        arrParams.pais = $('#cmb_pais').val();
        arrParams.pais_reside = $('#cmb_pais_reside').val();
        arrParams.etnia_persona = $('#cmb_etnia').val();
        arrParams.actitudes = $('#text_actitudes').val();
        arrParams.formacion_madre = $('#text_formacion_madre').val();
        arrParams.formacion_padre = $('#text_formacion_padre').val();
        arrParams.miembros_hogar = $('#text_miembros_hogar').val();
        arrParams.discapacidad = $('input[name=signup-dis]:checked').val();
        arrParams.tipo_discapacidad = $('#cmb_tip_discap').val();
        arrParams.porcentaje_dicapacidad = $('#txt_porc_discapacidad').val();

        
        //FORM 2 Informacion Académica
        arrParams.nombre_colegio = $('#txt_colegio').val();
        arrParams.tipo_colegio = $('#txt_tipo_colegio').val();
        arrParams.pais_colegio = $('#cmb_pais_col').val();
        arrParams.prov_colegio = $('#cmb_prov_col').val();
        arrParams.ciu_colegio = $('#cmb_ciu_col').val();
        arrParams.especializacion = $('#txt_especializacion').val();
        arrParams.bachillerato = $('#txt_bachillerato').val();
        arrParams.homologacion = $('input[name=signup-hom]:checked').val();
        arrParams.universidad = $('#txt_universidad').val();
        arrParams.carrera = $('#txt_carrera').val();
        arrParams.graduado = $('#txt_graduado').val();
        arrParams.fecha_grado = $('#txt_fecha_grado').val();
        arrParams.año_estudio = $('#txt_año_est').val();txt_año_est
        arrParams.aprendizajes = $('#txt_neces_aprender').val();

        //FORM 3 Información financiamiento
        arrParams.tipo_financiamiento = $('#txt_tipo_financiamiento').val();
        arrParams.insttucion_credito = $('#txt_inst_credito').val();
        
        //FORM 4 Informacion idiomas
        arrParams.idioma1 = $('#cmb_idioma1').val();
        arrParams.nivel_escrito1 = $('#txt_nivel_escrito1').val();
        arrParams.nivel_leido1 = $('#txt_nivel_leido1').val();
        arrParams.nivel_hablado1 = $('#txt_nivel_hablado1').val();

        arrParams.idioma2 = $('#cmb_idioma2').val();
        arrParams.nivel_escrito2 = $('#txt_nivel_escrito2').val();
        arrParams.nivel_leido2 = $('#txt_nivel_leido2').val();
        arrParams.nivel_hablado2 = $('#txt_nivel_hablado2').val();

        arrParams.idioma3 = $('#cmb_idioma3').val();
        arrParams.nivel_escrito3 = $('#txt_nivel_escrito3').val();
        arrParams.nivel_leido3 = $('#txt_nivel_leido3').val();
        arrParams.nivel_hablado3 = $('#txt_nivel_hablado3').val();

        arrParams.idioma4 = $('#cmb_idioma4').val();
        arrParams.nivel_escrito4 = $('#txt_nivel_escrito4').val();
        arrParams.nivel_leido4 = $('#txt_nivel_leido4').val();
        arrParams.nivel_hablado4 = $('#txt_nivel_hablado4').val();

        //FORM 5 información laboral
        arrParams.empresa = $('#txt_empresa').val();
        arrParams.cargo = $('#txt_cargo').val();
        arrParams.cat_ocupacional = $('#txt_cat_ocupacional').val();
        arrParams.direc_empresa = $('#txt_direc_emp').val();
        arrParams.telefono_empresa = $('#txt_telefono_emp').val();
        arrParams.correo_empresa = $('#txt_correo_emp').val();
        arrParams.fecha_ingreso = $('#txt_fecha_ingreso').val();

        //FORM 6 Información becas
        arrParams.tipo_beca = $('txt_tipo_beca').val();
        arrParams.motivo_beca = $('#txt_mot_beca').val();
        arrParams.monto_recibido = $('#txt_monto_recibido').val();
        arrParams.porcentajes_beca = $('#txt_porcentajes').val();

        //alert(arrParams.persona_id);
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                /*setTimeout(function () {
                    if (arrParams.persona_id > '0')
                    {
                        window.location.href = $('#txth_base').val() + "/interesado/listarinteresados";
                    } else
                    {
                        window.location.href = $('#txth_base').val() + "/ficha/view";
                    }
                }, 3000);*/
            }, true);
        }
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