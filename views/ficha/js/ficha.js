/*
 * It is released under the terms of the following BSD License.
 * Authors:
 * Diana Lopez <dlopez@uteg.edu.ec>
 */

$(document).ready(function () {
    /* codigo de area en datos personales*/

    /* Nacimiento */
    $('#cmb_pais_nac').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_nac");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_nac");
                        }
                    }, true);
                }

            }
        }, true);
        // actualizar codigo pais
        $("#lbl_codeCountry").text($("#cmb_pais_nac option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_pais_nac option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_pais_nac option:selected").attr("data-code"));
    });

    $('#cmb_prov_nac').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_nac");
            }
        }, true);
    });

    /* Domicilio */
    $('#cmb_pais_dom').change(function () {
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
    });

    /* Academico Estudio Nivel Medio */
    $('#cmb_pais_med').change(function () {
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
    });

    /* Academico Estudio Tercer Nivel */
    $('#cmb_pais_ter').change(function () {
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
    });

    /* Academico Estudio Cuarto Nivel */
    $('#cmb_pais_cuat').change(function () {
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
    });

    $('#cmb_prov_cuat').change(function () {
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

    /* DESPLAZAR TAB */
    // Tabs View
    $('#paso1nextView').click(function () {
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
    // Tabs de Actualizar
    $('#paso1nextUpdate').click(function () {
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
    });
    /*GUARDAR INFORMACION*/

    $('#btn_save_1').click(function () {
        var arrParams = new Object();
        var link = $('#txth_base').val() + "/ficha/guardarficha";
        arrParams.persona_id = $('#txth_ids').val();
        //FORM 1 datos personal
        arrParams.pnombre_persona = $('#txt_primer_nombre').val();
        arrParams.snombre_persona = $('#txt_segundo_nombre').val();
        arrParams.papellido_persona = $('#txt_primer_apellido').val();
        arrParams.sapellido_persona = $('#txt_segundo_apellido').val();
        arrParams.genero_persona = $('#cmb_genero').val();
        arrParams.etnia_persona = $('#cmb_raza_etnica').val();
        arrParams.etnia_otra = $('#txt_otra_etnia').val();
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
        }
        //alert(arrParams.nacecuador);
        //FORM 1 Informacion de Contacto
        arrParams.nombre_contacto = $('#txt_nombres_contacto').val();
        arrParams.apellido_contacto = $('#txt_apellidos_contacto').val();
        arrParams.telefono_contacto = $('#txt_telefono_con').val();
        arrParams.celular_contacto = $('#txt_celular_con').val();
        arrParams.direccion_contacto = $('#txt_address_con').val();
        arrParams.parentesco_contacto = $('#cmb_parentesco_con').val();

        //FORM 2 Datos Domicilio
        arrParams.paisd_domicilio = $('#cmb_pais_dom').val();
        arrParams.provinciad_domicilio = $('#cmb_prov_dom').val();
        arrParams.cantond_domicilio = $('#cmb_ciu_dom').val();
        arrParams.telefono_domicilio = $('#txt_telefono_dom').val();
        arrParams.sector_domicilio = $('#txt_sector_dom').val();
        arrParams.callep_domicilio = $('#txt_cprincipal_dom').val();
        arrParams.calls_domicilio = $('#txt_csecundaria_dom').val();
        arrParams.numero_domicilio = $('#txt_numeracion_dom').val();
        arrParams.referencia_domicilio = $('#txt_referencia_dom').val();

        //FORM 3 Datos Academicos - Estudios Nivel Medio
        arrParams.instituto_medio = $('#txt_inst_med').val();
        arrParams.tipinti_medio = $('#cmb_tip_instaca_med').val();
        arrParams.pais_medio = $('#cmb_pais_med').val();
        arrParams.prov_medio = $('#cmb_prov_med').val();
        arrParams.ciu_medio = $('#cmb_ciu_med').val();
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
        arrParams.grado_cuarto = $('#txt_grad_cuat').val();

        //FORM 4 Datos Familiares
        arrParams.inst_madre = $('#cmb_tip_instaca_medm').val();
        arrParams.inst_padre = $('#cmb_tip_instaca_medp').val();
        arrParams.miem_hogar = $('#txt_numeracion_mi').val();
        arrParams.miem_salario = $('#txt_numeracion_sal').val();

        //FORM 5 Datos Adicionales
        arrParams.discapacidadi = $('input[name=signup-dis]:checked').val();
        arrParams.tipoi = $('#cmb_tip_discap').val();
        arrParams.porcentajei = $('#txt_por_discapacidad').val();
        arrParams.archivoi = $('#txth_doc_adj_disi').val();
        arrParams.discapacidad = "1";
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
        }
        //alert(arrParams.persona_id);
        if (!validateForm()) {
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

    //Control del div enfermedad
    $('#signup-enf').change(function () {
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
    });

});