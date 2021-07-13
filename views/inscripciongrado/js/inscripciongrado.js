
$(document).ready(function () {

    /* codigo de area en datos personales*/

    $('#cmb_pais_col').change(function () {
        var link = $('#txth_base').val() + "/fichasocioeconomica/index";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_col");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_col");
                        }
                    }, true);
                }

            }
        }, true);
        // actualizar codigo pais
        $("#lbl_codeCountry").text($("#cmb_pais_col option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_pais_col option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_pais_col option:selected").attr("data-code"));
    });

    $('#cmb_prov_col').change(function () {
        var link = $('#txth_base').val() + "/fichasocioeconomica/index";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_col");
            }
        }, true);
    });

    /* Domicilio */
    /*$('#cmb_pais_dom').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_dom");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_dom");
                        }
                    }, true);
                }
            }
        }, true);
        // actualizar codigo pais   
        $("#lbl_codeCountrydom").text($("#cmb_pais_dom option:selected").attr("data-code"));
    });

    $('#cmb_prov_dom').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_dom");
            }
        }, true);
    });*/

    /* Academico Estudio Nivel Medio */
    /*$('#cmb_pais_med').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_med");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_med");
                        }
                    }, true);
                }
            }
        }, true);
    });

    $('#cmb_prov_med').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_med");
            }
        }, true);
    });*/

    /* Academico Estudio Tercer Nivel */
    /*$('#cmb_pais_ter').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_ter");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_ter");
                        }
                    }, true);
                }
            }
        }, true);
    });

    $('#cmb_prov_ter').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_ter");
            }
        }, true);
    });*/

    /* Academico Estudio Cuarto Nivel */
    /*$('#cmb_pais_cuat').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_cuat");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_cuat");
                        }
                    }, true);
                }
            }
        }, true);
    });*/

    /*$('#cmb_prov_cuat').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_cuat");
            }
        }, true);
    });*/

    $('#cmb_raza_etnica').change(function () {
        var valor = $('#cmb_raza_etnica').val();
        if (valor == 6) {
            $("#txt_otra_etnia").removeAttr("disabled");
        } else {
            $("#txt_otra_etnia").attr('disabled', 'disabled');
            $("#txt_otra_etnia").val("");
        }
    });

    /* DESPLAZAR TAB */
    // Tabs View
    /*$('#paso1nextView').click(function () {
        $("a[href='#paso2']").trigger("click");
    });
    $('#paso2backView').click(function () {
        $("a[href='#paso1']").trigger("click");
    });
    $('#paso2nextView').click(function () {
        $("a[href='#paso3']").trigger("click");
    });
    $('#paso3backView').click(function () {
        $("a[href='#paso2']").trigger("click");
    });
    $('#paso3nextView').click(function () {
        $("a[href='#paso4']").trigger("click");
    });
    $('#paso4backView').click(function () {
        $("a[href='#paso3']").trigger("click");
    });
    $('#paso4nextView').click(function () {
        $("a[href='#paso5']").trigger("click");
    });
    $('#paso5backView').click(function () {
        $("a[href='#paso4']").trigger("click");
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
    $('#paso4next').click(function () {
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
    });
    // Tabs de Actualizar
    /*$('#paso1nextUpdate').click(function () {
        $("a[href='#paso2']").trigger("click");
    });
    $('#paso2backUpdate').click(function () {
        $("a[href='#paso1']").trigger("click");
    });
    $('#paso2nextUpdate').click(function () {
        $("a[href='#paso3']").trigger("click");
    });
    $('#paso3backUpdate').click(function () {
        $("a[href='#paso2']").trigger("click");
    });
    $('#paso3nextUpdate').click(function () {
        $("a[href='#paso4']").trigger("click");
    });
    $('#paso4backUpdate').click(function () {
        $("a[href='#paso3']").trigger("click");
    });
    $('#paso4nextUpdate').click(function () {
        $("a[href='#paso5']").trigger("click");
    });
    $('#paso5backUpdate').click(function () {
        $("a[href='#paso4']").trigger("click");
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

        
        /*arrParams.etnia_otra = $('#txt_otra_etnia').val();
        arrParams.ecivil_persona = $('#txt_estado_civil').val();
        arrParams.fnacimiento_persona = $('#txt_fecha_nacimiento').val();
        arrParams.pnacionalidad = $('#txt_nacionalidad').val();
        arrParams.pais_persona = $('#cmb_pais_nac').val();
        arrParams.provincia_persona = $('#cmb_prov_nac').val();
        arrParams.canton_persona = $('#cmb_ciu_nac').val();
        arrParams.correo_persona = $('#txt_ftem_correo').val();
        arrParams.celular_persona = $('#txt_celular').val();
        arrParams.tsangre_persona = $('#cmb_tipo_sangre').val();
        if ($('input[name=signup-ecu]:checked').val() == 1) {
            arrParams.nacecuador = 1;
        } else {
            arrParams.nacecuador = 0;
        }*/
        //alert(arrParams.nacecuador);
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
        /*arrParams.cantond_domicilio = $('#cmb_ciu_dom').val();
        arrParams.telefono_domicilio = $('#txt_telefono_dom').val();
        arrParams.sector_domicilio = $('#txt_sector_dom').val();
        arrParams.callep_domicilio = $('#txt_cprincipal_dom').val();
        arrParams.calls_domicilio = $('#txt_csecundaria_dom').val();
        arrParams.numero_domicilio = $('#txt_numeracion_dom').val();
        arrParams.referencia_domicilio = $('#txt_referencia_dom').val();*/

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

        /*arrParams.ciu_medio = $('#cmb_ciu_med').val();
        arrParams.tit_medio = $('#txt_titulo_med').val();
        arrParams.grado_medio = $('#txt_grad_med').val();
        //FORM 3 Datos Academicos - Estudios Tercer Nivel
        arrParams.instituto_tercer = $('#txt_inst_ter').val();
        arrParams.tipinti_tercer = $('#cmb_tip_instaca_ter').val();
        arrParams.pais_tercer = $('#cmb_pais_ter').val();
        arrParams.prov_tercer = $('#cmb_prov_ter').val();
        arrParams.ciu_tercer = $('#cmb_ciu_ter').val();
        arrParams.tit_tercer = $('#txt_titulo_ter').val();
        arrParams.grado_tercer = $('#txt_grad_ter').val();
        //FORM 3 Datos Academicos - Estudios Cuarto Nivel
        arrParams.instituto_cuarto = $('#txt_inst_cuat').val();
        arrParams.tipinti_cuarto = $('#cmb_tip_instaca_cuat').val();
        arrParams.pais_cuarto = $('#cmb_pais_cuat').val();
        arrParams.prov_cuarto = $('#cmb_prov_cuat').val();
        arrParams.ciu_cuarto = $('#cmb_ciu_cuat').val();
        arrParams.tit_cuarto = $('#txt_titulo_cuat').val();
        arrParams.grado_cuarto = $('#txt_grad_cuat').val();*/

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

        /*arrParams.discapacidad = "1";
        if ($('input[name=signup-dis_no]:checked').val() == 2) {
            $('#txt_por_discapacidad').removeClass("PBvalidation");
            arrParams.discapacidad = "0";
        }

        arrParams.enfermedc = $('input[name=signup-enf]:checked').val();
        arrParams.archivoc = $('#txth_doc_adj_enfc').val();
        arrParams.enfermedad = "1";
        if ($('input[name=signup-enf_no]:checked').val() == 2) {
            arrParams.enfermedad = "0";
        }

        arrParams.discapacidadsev = $('input[name=signup-discf]:checked').val();
        arrParams.tipof = $('#cmb_tip_discap_fam').val();
        arrParams.porcentajef = $('#txt_por_discap').val();
        arrParams.discapacidad_fam = "1";
        if ($('input[name=signup-discf_no]:checked').val() == 2) {
            $('#txt_por_discap').removeClass("PBvalidation");
            arrParams.discapacidad_fam = "0";
        }
        arrParams.parentescof = $('#cmb_tpare_dis_fam').val();
        arrParams.archivof = $('#txth_doc_adj_dis').val();

        arrParams.enfermedcf = $('input[name=signup-enfcf]:checked').val();
        arrParams.enfermedcp = $('#cmb_tpare_enf_fam').val();
        arrParams.archivocf = $('#txth_fadj_enf_fam').val();
        arrParams.enfermedad_fam = "1";
        if ($('input[name=signup-enfcf_no]:checked').val() == 2) {
            arrParams.enfermedad_fam = "0";
        }*/
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

    //Control del div enfermedad
    /*$('#signup-enf').change(function () {
        if ($('#signup-enf').val() == 1) {
            $('#enfermedad').css('display', 'block');
            $("#signup-enf_no").prop("checked", "");
        } else {
            $('#enfermedad').css('display', 'none');
        }
    });

    $('#signup-enf_no').change(function () {
        if ($('#signup-enf_no').val() == 2) {
            $('#enfermedad').css('display', 'none');
            $("#signup-enf").prop("checked", "");
        } else {
            $('#enfermedad').css('display', 'block');
        }
    });


    //Control del div con discapacidad familiar
    $('#signup-discf').change(function () {
        if ($('#signup-discf').val() == 1) {
            $('#discapacidad_fam').css('display', 'block');
            $("#signup-discf_no").prop("checked", "");
        } else {
            $('#discapacidad_fam').css('display', 'none');
        }
    });

    $('#signup-discf_no').change(function () {
        if ($('#signup-discf_no').val() == 2) {
            $('#discapacidad_fam').css('display', 'none');
            $("#signup-discf").prop("checked", "");
        } else {
            $('#discapacidad_fam').css('display', 'block');
        }
    });

    //Control de enfermedad familiar    
    $('#signup-enfcf').change(function () {
        if ($('#signup-enfcf').val() == 1) {
            $('#enfermedad_fam').css('display', 'block');
            $("#signup-enfcf_no").prop("checked", "");
        } else {
            $('#enfermedad_fam').css('display', 'none');
        }
    });

    $('#signup-enfcf_no').change(function () {
        if ($('#signup-enfcf_no').val() == 2) {
            $('#enfermedad_fam').css('display', 'none');
            $("#signup-enfcf").prop("checked", "");
        } else {
            $('#enfermedad_fam').css('display', 'block');
        }
    });*/

});