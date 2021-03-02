$(document).ready(function () {

    /***************filtra facultad segun nivel estudio interes vista listar evaluaciones**********************/
    $('#cmb_estadocontacto').change(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/listarclientes";
        var arrParams = new Object();
        arrParams.esta_id = $(this).val();
        arrParams.getfase = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.fase, "cmb_fasecontacto", "Todas");
            }
        }, true);
    });
    $('#cmb_state_opportunity').change(function () {        
        if ($('#cmb_state_opportunity').val() == 5 || $('#cmb_state_opportunity').val() == 4) {            
            $("#txt_fecha_proxima").prop("disabled", true);
            $("#txt_hora_proxima").prop("disabled", true);
            $('#txt_fecha_proxima').removeClass("PBvalidation");
            $('#txt_hora_proxima').removeClass("PBvalidation");
        }else{
            $("#txt_fecha_proxima").prop("disabled", false);
            $("#txt_hora_proxima").prop("disabled", false);
             $('#txt_fecha_proxima').addClass("PBvalidation");
             $('#txt_hora_proxima').addClass("PBvalidation");
        }
        if ($('#cmb_state_opportunity').val() == 5) {            
            $('#divoportunidad_perdida').css('display', 'block');
        } else {
            $('#divoportunidad_perdida').css('display', 'none');
        }
    });
    $('#btn_grabarOportunidad').click(function () {
        var sub_carrera=($('#cmb_subcarrera').val()!=0 && $('#cmb_subcarrera').val()!='')?$('#cmb_subcarrera').val():0;
        var link = $('#txth_base').val() + "/admision/admisiones/guardaroportunidad";
        var arrParams = new Object();
        arrParams.id_pgest = $('#txth_pgid').val();
        arrParams.empresa = $('#cmb_empresa').val();
        arrParams.id_unidad_academica = $('#cmb_nivelestudio').val();
        arrParams.id_modalidad = $('#cmb_modalidad').val();
        arrParams.id_tipo_oportunidad = $('#cmb_tipo_oportunidad').val();
        arrParams.id_estado_oportunidad = $('#cmb_state_opportunity').val();
        arrParams.id_estudio_academico = $('#cmb_carrera1').val();
        arrParams.canal_conocimiento = $('#cmb_knowledge_channel').val();
        arrParams.carrera2 = $('#cmb_carrera2').val();
        arrParams.sub_carrera = sub_carrera;
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    if (response.status == "OK") {
                        //parent.window.location.href = $('#txth_base').val() + "/admision/admisiones/listaroportxcontacto?pgid=".arrParams.id_pgest;
                        parent.window.location.href = $('#txth_base').val() + "/admision/admisiones/listarcontactos";
                    }
                }, 3000);
            }, true);
        }

    });
    $('#cmb_pais').change(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/crearcontacto";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu");
                        }
                    }, true);
                }

            }
        }, true);
        // actualizar codigo pais
        $("#lbl_codeCountry").text($("#cmb_pais option:selected").attr("data-code"));
    });

    $('#cmb_nivelestudio_act').change(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/actualizaroportunidad";
        var arrParams = new Object();
        arrParams.ninter_id = $(this).val();
        arrParams.getmodalidad = true;

        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                var arrParams = new Object();
                data = response.message;
                setComboData(data.modalidad, "cmb_modalidad_act");
                arrParams.unidada = $('#cmb_nivelestudio_act').val();
                arrParams.getoportunidad = true;
                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboData(data.oportunidad, "cmb_tipo_oportunidad");
                    }
                }, true);
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_nivelestudio_act').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.carrera, "cmb_carrera_estudio");
                        }
                    }, true);
                }
            }
        }, true);
    });

    $('#cmb_nivelestudio').change(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/crearoportunidad";
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.modalidad, "cmb_modalidad");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_nivelestudio').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getoportunidad = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.oportunidad, "cmb_tipo_oportunidad");
                        }
                    }, true);
                    var arrParams = new Object();
                    if (data.modalidad.length > 0) {
                        arrParams.unidada = $('#cmb_nivelestudio').val();
                        arrParams.moda_id = data.modalidad[0].id;
                        arrParams.getcarrera = true;
                        requestHttpAjax(link, arrParams, function (response) {
                            if (response.status == "OK") {
                                data = response.message;
                                setComboData(data.carrera, "cmb_carrera1");
                            }
                        }, true);
                    }
                }
            }
        }, true);
    });
    $('#cmb_modalidad').change(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/crearoportunidad";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_nivelestudio').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.carrera, "cmb_carrera1");
            }
        }, true);
    });

    $('#cmb_modalidad_act').change(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/actualizaroportunidad";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_nivelestudio_act').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.carrera, "cmb_carrera_estudio");
            }
        }, true);
    });


    $('#cmb_estado').change(function () {
        var arrParams = new Object();
        arrParams.estado_id = $(this).val();
        if (arrParams.estado_id > 2) {
            $("#txt_fecha_next").prop("disabled", true);
            $("#txt_hora_proxima").prop("disabled", true);
            $('#txt_fecha_next').removeClass("PBvalidation");
            $('#txt_hora_proxima').removeClass("PBvalidation");
            if (arrParams.estado_id == 4)
            {
                $('#oportunidad').css('display', 'block');
            } else
            {
                $('#oportunidad').css('display', 'none');
            }
            /*if (arrParams.estado_id != 7)
             {
             
             $("#cmb_tipocontacto").prop("disabled", true);
             $('#txt_fecha_next').removeClass("PBvalidation");
             $('#txt_hora_proxima').removeClass("PBvalidation");
             } else
             {
             $('#oportunidad').css('display', 'none');
             $("#txt_fecha_next").prop("disabled", false);
             $("#txt_hora_proxima").prop("disabled", false);
             $("#cmb_tipocontacto").prop("disabled", false);
             $('#txt_fecha_next').addClass("PBvalidation");
             $('#txt_hora_proxima').addClass("PBvalidation");
             }*/
        } else
        {
            $("#txt_fecha_next").prop("disabled", false);
            $("#txt_hora_proxima").prop("disabled", false);
            $('#txt_fecha_next').addClass("PBvalidation");
            $('#txt_hora_proxima').addClass("PBvalidation");
            if (arrParams.estado_id == 2 || arrParams.estado_id == 3)
            {
                $('#oportunidad').css('display', 'none');
            }
            /*$("#txt_fecha_next").prop("disabled", false);
             $("#txt_hora_proxima").prop("disabled", false);
             $("#cmb_tipocontacto").prop("disabled", false);
             $('#txt_fecha_next').addClass("PBvalidation");
             $('#txt_hora_proxima').addClass("PBvalidation");*/
        }
    });

    $('#cmb_prov').change(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/crearcontacto";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu");
            }
        }, true);
    });

    //Control del div de beneficiario
    $('#rdo_beneficio').change(function () {
        if ($('#rdo_beneficio').val() == 1) {
            $("#rdo_beneficio_no").prop("checked", "");
            $('#beneficio').css('display', 'none');
        } else {
            $('#beneficio').css('display', 'block');
        }
    });

    $('#rdo_beneficio_no').change(function () {
        if ($('#rdo_beneficio_no').val() == 2) {
            $("#rdo_beneficio").prop("checked", "");
            $('#beneficio').css('display', 'block');
        } else {
            $('#beneficio').css('display', 'none');
        }
    });

    $('#cmb_carrera2').change(function () {
        if ($(this).val() != 0) {
            var link = $('#txth_base').val() + "/admision/admisiones/crearoportunidad";
            var arrParams = new Object();
            arrParams.car_id = $(this).val();
            arrParams.getsubcarrera = true;
            requestHttpAjax(link, arrParams, function (response) {
                if (response.status == "OK") {
                    data = response.message;
                    setComboData(data.subcarrera, "cmb_subcarrera");
                }
            }, true);
        }else{
            $('#cmb_subcarrera').html("<option value='0'>Ninguno</option>");
        }
    });
    $('#btn_grabarContactTemporal').click(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/guardaractuacontactpend";
        var arrParams = new Object();
        arrParams.id_pertemp = $('#txth_idpt').val();
        arrParams.cont_name = $('#txt_name').val();
        arrParams.cont_lname = $('#txt_lname').val();
        arrParams.cont_email = $('#txt_email').val();
        arrParams.cont_phone = $('#txt_phone').val();
        arrParams.cont_smart_phone = $('#txt_sphone').val();
        arrParams.cont_status = $('#txt_status').val();
        arrParams.cont_observation = $('#txt_observation').val();
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                console.log(response);
                if (response.status == "OK") {
                    parent.actualizarGridContactoTemp();
                    parent.closeIframePopup();
                    //parent.window.location.href = $('#txth_base').val() + "/interesado/listarinteresados";
                }
            }, true);
        }
    });
    $('#btn_editcliente').click(function () {
        var codigo = $('#txth_pcon_id').val();
        var tper_id = $('#txth_tper_id').val();
        window.location.href = $('#txth_base').val() + "/admision/admisiones/actualizarcontacto?codigo=" + codigo + "&tper_id=" + tper_id;
    });

    $('#btn_updatecliente').click(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/guardaractuacontacto";
        var arrParams = new Object();
        camposnulos('#txt_celular');
        camposnulos('#txt_celular2');
        camposnulos('#txt_telefono_con');
        camposnulos('#txt_correo');
        camposnulos('#txt_telefono_empresa');
        camposnulos('#txt_direccion');
        camposnulos('#txt_cargo');
        arrParams.agenteauten = $('#txth_idag').val();
        arrParams.personauten = $('#txth_idpa').val();
        arrParams.txt_nombre1 = $('#txt_nombre1').val();
        arrParams.txt_nombre2 = $('#txt_nombre2').val();
        arrParams.txt_apellido1 = $('#txt_apellido1').val();
        arrParams.txt_apellido2 = $('#txt_apellido2').val();
        arrParams.pges_id = $('#txth_pges_id').val();
        arrParams.pais = $('#cmb_pais').val();
        arrParams.cmb_tipo_dni = $('#cmb_tipo_dni').val();
        arrParams.cedula = $('#txt_cedula').val();
        arrParams.provincia = $('#cmb_prov').val();
        arrParams.ciudad = $('#cmb_ciu').val();
        arrParams.celular = $('#txt_celular').val();
        arrParams.celular2 = $('#txt_celular2').val();
        arrParams.telefono = $('#txt_telefono_con').val();
        arrParams.correo = $('#txt_correo').val();
        arrParams.medio = $('#cmb_medio').val();
        arrParams.empresa = $('#txt_nombre_empresa').val();
        arrParams.telefono_empresa = $('#txt_telefono_empresa').val();
        arrParams.direccion = $('#txt_direccion').val();
        arrParams.cargo = $('#txt_cargo').val();
        arrParams.contacto_empresa = $('#txt_nombre_contacto').val();
        arrParams.numero_contacto = $('#txt_numero_contacto').val();
        arrParams.tipo_persona = $('#txth_tper_id').val();
        arrParams.perges_contacto = $('#txth_pgco_id').val();
        arrParams.txt_nombre1contacto = $('#txt_nombrebene1').val();
        arrParams.txt_nombre2contacto = $('#txt_nombrebene2').val();
        arrParams.txt_apellido1contacto = $('#txt_apellidobene1').val();
        arrParams.txt_apellido2contacto = $('#txt_apellidobene2').val();
        arrParams.txt_celularcontacto = $('#txt_celularbene').val();
        arrParams.txt_correocontacto = $('#txt_correobeni').val();
        arrParams.txt_telefonocontacto = $('#txt_telefono_conbeni').val();
        arrParams.txt_paiscontacto = $('#cmb_pais_contacto').val();

        if ($('input[name=signup-ecu]:checked').val() == 1) {
            arrParams.nacecuador = 1;
        } else {
            arrParams.nacecuador = 0;
        }
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/admision/admisiones/listarcontactos";
                }, 3000);
            }, true);
        }
    });
    $('#btn_grabarCliente').click(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/guardarcontacto";
        var arrParams = new Object();
        // funcion que permite verificar si viene vacío, remover la validación.
        camposnulos('#txt_celular');
        camposnulos('#txt_celular2');
        camposnulos('#txt_telefono_con');
        camposnulos('#txt_correo');
        camposnulos('#txt_telefono_empresa');
        camposnulos('#txt_direccion');
        camposnulos('#txt_cargo');
        arrParams.agenteauten = $('#txth_idagent').val();
        arrParams.personauten = $('#txth_idperage').val();
        // Datos Generales
        arrParams.txt_nombre1 = $('#txt_nombre1').val();
        arrParams.txt_nombre2 = $('#txt_nombre2').val();
        arrParams.txt_apellido1 = $('#txt_apellido1').val();
        arrParams.txt_apellido2 = $('#txt_apellido2').val();
        arrParams.pais = $('#cmb_pais').val();
        arrParams.provincia = $('#cmb_prov').val();
        arrParams.ciudad = $('#cmb_ciu').val();
        arrParams.celular = $('#txt_celular').val();
        arrParams.celular2 = $('#txt_celular2').val();
        arrParams.telefono = $('#txt_telefono_con').val();
        arrParams.correo = $('#txt_correo').val();
        arrParams.medio = $('#cmb_medio').val();
        arrParams.empresa = $('#txt_nombre_empresa').val();
        arrParams.telefono_empresa = $('#txt_telefono_empresa').val();
        arrParams.direccion = $('#txt_direccion').val();
        arrParams.cargo = $('#txt_cargo').val();
        arrParams.contacto_empresa = $('#txt_nombre_contacto').val();
        arrParams.numero_contacto = $('#txt_numero_contacto').val();
        arrParams.paisContacto = $('#cmb_pais_contacto').val();

        if ($('input[name=opt_tipo_persona_n]:checked').val() == 1) {
            arrParams.tipo_persona = 1;
            camposnulos('#txt_nombre_empresa');
            camposnulos('#txt_numero_contacto');
            camposnulos('#txt_nombre_contacto');
        } else {
            arrParams.tipo_persona = 2;
            camposnulos('#txt_nombre1');
            camposnulos('#txt_apellido1');
        }

        // Datos Beneficiario      
        camposnulos('#txt_celularbene');
        camposnulos('#txt_celularbeni2');
        camposnulos('#txt_telefono_conbeni');
        camposnulos('#txt_correobeni');

        if ($('input:radio[name=rdo_beneficio]:checked').val())
        {
            arrParams.beneficiario = 1;
            arrParams.contacto = $('input:radio[name=rdo_beneficio]:checked').val();
            arrParams.txt_nombrebeni1 = $('#txt_nombre1').val();
            arrParams.txt_nombrebeni2 = $('#txt_nombre2').val();
            arrParams.txt_apellidobeni1 = $('#txt_apellido1').val();
            arrParams.txt_apellidobeni2 = $('#txt_apellido2').val();
            arrParams.celularbeni = $('#txt_celular').val();
            arrParams.celular2beni = $('#txt_celular2').val();
            arrParams.telefonobeni = $('#txt_telefono_con').val();
            arrParams.correobeni = $('#txt_correo').val();
        } else
        {
            arrParams.beneficiario = 2;
            arrParams.contacto = $('input:radio[name=rdo_beneficio_no]:checked').val();
            arrParams.txt_nombrebeni1 = $('#txt_nombrebene1').val();
            arrParams.txt_nombrebeni2 = $('#txt_nombrebene2').val();
            arrParams.txt_apellidobeni1 = $('#txt_apellidobene1').val();
            arrParams.txt_apellidobeni2 = $('#txt_apellidobene2').val();
            arrParams.celularbeni = $('#txt_celularbene').val();
            arrParams.celular2beni = $('#txt_celularbeni2').val();
            arrParams.telefonobeni = $('#txt_telefono_conbeni').val();
            arrParams.correobeni = $('#txt_correobeni').val();
        }
        if (arrParams.beneficiario == 1)
        {
            $('#txt_nombrebene1').removeClass("PBvalidation");
            $('#txt_apellidobene1').removeClass("PBvalidation");
            $('#txt_celularbene').removeClass("PBvalidation");
            $('#txt_telefono_conbeni').removeClass("PBvalidation");
            $('#txt_correobeni').removeClass("PBvalidation");

            if (arrParams.celularbeni == '' && arrParams.telefonobeni == '' && arrParams.correobeni == '')
            {
                $('#txt_celular').addClass("PBvalidation");
                $('#txt_telefono_con').addClass("PBvalidation");
                $('#txt_correo').addClass("PBvalidation");
            }

        } else
        {
            $('#txt_nombrebene1').addClass("PBvalidation");
            $('#txt_apellidobene1').addClass("PBvalidation");

            if (arrParams.celularbeni == '' && arrParams.telefonobeni == '' && arrParams.correobeni == '')
            {
                $('#txt_celularbene').addClass("PBvalidation");
                $('#txt_telefono_conbeni').addClass("PBvalidation");
                $('#txt_correobeni').addClass("PBvalidation");
            } else
            {
                $('#txt_celularbene').removeClass("PBvalidation");
                $('#txt_telefono_conbeni').removeClass("PBvalidation");
                $('#txt_correobeni').removeClass("PBvalidation");
            }
        }
        if (arrParams.agenteauten == 1 || arrParams.agenteauten == 2 || arrParams.personauten == 1)
        {
            arrParams.agente = $('#cmb_agente').val();
        } else
        {
            arrParams.agente = $('#cmb_agenteau').val();
        }
        if ($('input[name=signup-ecu]:checked').val() == 1) {
            arrParams.nacecuador = 1;
        } else {
            arrParams.nacecuador = 0;
        }
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/admision/admisiones/listarcontactos";
                }, 3000);
            }, true);
        }
    });
    $('#btn_grabar').click(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/guardaroportunidad";
        var arrParams = new Object();
        arrParams.agenteauten = $('#txth_idag').val();
        arrParams.personauten = $('#txth_idpa').val();
        arrParams.nivel = $('#cmb_nivelestudio').val();
        arrParams.modalidad = $('#cmb_modalidad').val();
        arrParams.carrera1 = $('#cmb_carrera1').val();
        arrParams.carrera2 = $('#cmb_carrera2').val();
        arrParams.subcarrera = $('#cmb_subcarrera').val();
        //arrParams.programa1 = $('#cmb_programa1').val();
        //arrParams.programa2 = $('#cmb_programa2').val();
        arrParams.canal = $('#cmb_canal').val();
        arrParams.estado = $('#cmb_estado').val();
        arrParams.tipoOport = $('#cmb_tipo_oportunidad').val();
        arrParams.pcon_id = $('#txth_conid').val();
        arrParams.pben_id = $('#txth_benid').val();
        arrParams.pgid = $('#txth_pgid').val();
        //Datos Gestión          
        if (arrParams.agenteauten == 1 || arrParams.agenteauten == 2 || arrParams.personauten == 1)
        {
            arrParams.agente = $('#cmb_agente').val();
        } else
        {
            arrParams.agente = $('#cmb_agenteau').val();
        }

        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    //sessionStorage.clear();
                    window.location.href = $('#txth_base').val() + "/admision/admisiones/listarcontactos";
                }, 3000);
            }, true);
        }
    });

    $('#btn_Neopera').click(function () {
        var persona = $('#txth_ids').val();
        var pgid = $('#txth_pgid').val();
        window.location.href = $('#txth_base').val() + "/admision/admisiones/nuevagestion?id=" + persona + "&pgid=" + pgid;
    });
    $('#btn_crearoportunidad').click(function () {
        var pgid = $('#txth_pgid').val();
        window.location.href = $('#txth_base').val() + "/admision/admisiones/crearoportunidad?pgid=" + pgid;
    });
    $('#btn_crearactividad').click(function () {
        var opid = $('#txth_opid').val();
        var pgid = $('#txth_pgid').val();
        window.location.href = $('#txth_base').val() + "/admision/admisiones/crearactividad?opid=" + opid + "&pgid=" + pgid;
    });
    $('#btn_editaractividad').click(function () {
        var opid = $('#txth_opid').val();
        var pgid = $('#txth_pgid').val();
        var acid = $('#txth_acid').val();
        window.location.href = $('#txth_base').val() + "/admision/admisiones/actualizaractividad?opid=" + opid + "&pgid=" + pgid+ "&acid=" + acid;
    });

    $('#btn_grabaractividad').click(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/guardaractividad";
        var arrParams = new Object();
        //Datos Gestión 
        arrParams.oportunidad = $('#txth_opo_id').val();
        arrParams.estado_oportunidad = $('#cmb_state_opportunity').val();
        arrParams.fecatencion = $('#txt_fecha_atencion').val();
        arrParams.horatencion = $('#txt_hora_atencion').val();
        arrParams.observacion = $('#txt_observacion').val();        
        if(arrParams.estado_oportunidad==5){
            arrParams.oportunidad_perdida=$('#cmb_lost_opportunity').val();            
        }
        //Datos Próxima Atención
        arrParams.fecproxima = $('#txt_fecha_proxima').val();
        arrParams.horproxima = $('#txt_hora_proxima').val();
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    var opor_id = $('#txth_opo_id').val();
                    var pges_id = $('#txth_pgid').val();
                    window.location.href = $('#txth_base').val() + "/admision/admisiones/listaractixoport?opor_id=" + opor_id + "&pges_id=" + pges_id;
                }, 3000);
            }, true);
        }
    });
    $('#btn_actualizaractividad').click(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/guardaractactividad";
        var arrParams = new Object();
        //Datos Gestión 
        arrParams.bact_id = $('#txth_acid').val();
        arrParams.fecatencion = $('#txt_fecha_atencion').val();
        arrParams.horatencion = $('#txt_hora_atencion').val();
        arrParams.observacion = $('#txt_observacion').val();        
        //Datos Próxima Atención
        arrParams.fecproxima = $('#txt_fecha_proxima').val();
        arrParams.horproxima = $('#txt_hora_proxima').val();
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    var opor_id = $('#txth_opo_id').val();
                    var pges_id = $('#txth_pgid').val();
                    window.location.href = $('#txth_base').val() + "/admision/admisiones/listaractixoport?opor_id=" + opor_id + "&pges_id=" + pges_id;
                }, 3000);
            }, true);
        }
    });


    $('#btn_actualizarOportunidad').click(function () {
        var link = $('#txth_base').val() + "/admision/admisiones/guardaractoportunidad";
        var arrParams = new Object();       
        arrParams.pgid = $('#txth_pgid').val();
        arrParams.opo_id = $('#txth_opoid').val();        
        arrParams.uaca_id = $('#cmb_nivelestudio_act').val();
        arrParams.modalidad = $('#cmb_modalidad_act').val();
        arrParams.empresa = $('#cmb_empresa').val();
        arrParams.tipoOport = $('#cmb_tipo_oportunidad').val();   
        arrParams.estado = $('#cmb_state_opportunity').val();        
        arrParams.carreraestudio = $('#cmb_carrera_estudio').val();
        arrParams.canal = $('#cmb_ccanal').val();                              
        arrParams.carrera2 = $('#cmb_carrera2').val();
        arrParams.subcarrera = $('#cmb_subcarrera').val();                    
        
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/admision/admisiones/listaroportunidades";
                }, 3000);
            }, true);
        }
    });

    $('#btn_buscarGestion').click(function () {
        actualizarGridGestion();
    });

    $('#btn_buscarContacto').click(function () {
        actualizarGridContacto();
    });
    $('#btn_buscarContactot').click(function () {
        actualizarGridContactoTemp();
    });


    $('#btn_cargar').click(function () {
        cargarLeads('LEADS');
    });
    $('#btn_cargarLotes').click(function () {
        cargarLeads('LOTES');
    });

    $('#opt_tipo_persona_n').change(function () {
        if ($('#opt_tipo_persona_n').val() == 1) {
            $("#opt_tipo_persona_j").prop("checked", "");
            $('#divTipopersonaN').css('display', 'block');
            $('#divTipopersonaJ').css('display', 'none');
            $('#estudiante').css('display', 'block');
        } else {
            $('#divTipopersonaN').css('display', 'none');
            $('#divTipopersonaJ').css('display', 'block');
            $('#estudiante').css('display', 'none');
        }
    });

    $('#opt_tipo_persona_j').change(function () {
        if ($('#opt_tipo_persona_j').val() == 2) {
            $("#opt_tipo_persona_n").prop("checked", "");
            $('#divTipopersonaJ').css('display', 'block');
            $('#divTipopersonaN').css('display', 'none');
            $('#estudiante').css('display', 'none');
            $("#rdo_beneficio").prop("checked", "true");
            $("#rdo_beneficio_no").prop("checked", "");
            $('#beneficio').css('display', 'none');
        } else {
            $('#divTipopersonaJ').css('display', 'none');
            $('#divTipopersonaN').css('display', 'block');
            $('#estudiante').css('display', 'block');
        }
    });


    $('#btn_editaoportunidad').click(function () {
        var codigo = $('#txth_opoid').val();
        var persona = $('#txth_pgid').val();       
        
        window.location.href = $('#txth_base').val() + "/admision/admisiones/actualizaroportunidad?codigo=" + codigo + "&pgesid=" + persona;
       
         /*
            var link = $('#txth_base').val() + "/admision/admisiones/actualizaroportunidad?codigo=" + codigo + "&pgesid=" + persona;
            var arrParams = new Object();     
            arrParams.opo_id = $('#txth_opoid').val();   
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {  
                    if (response.status == "NOK") {
                        data = response.message;
                        window.location.href = $('#txth_base').val() + "/admision/admisiones/listaroportunidades";     
                    }
                }, 3000);
            }, true);*/      
    });

});
function cargarLeads(proceso) {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/admision/admisiones/cargarleads";
    arrParams.procesar_file = true;
    arrParams.tipo_proceso = proceso;
    arrParams.emp_id = $('#cmb_empresa option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_leads2').val() + "." + $('#txth_doc_adj_leads').val().split('.').pop();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                window.location.href = $('#txth_base').val() + "/admision/admisiones/listarcontactos";
            }, 3000);
        }, true);
    }
}
function grabarContactoGestion(ptem_id) {
    var link = $('#txth_base').val() + "/admision/admisiones/guardarcontacto";
    var arrParams = new Object();
    arrParams.id_pertemp = ptem_id;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                if (response.status == "OK") {
                    parent.window.location.href = $('#txth_base').val() + "/admision/admisiones/listarclientes";
                }
            }, 3000);
        }, true);
    }
}
function grabarInteresado(pgest_id) {
    var link = $('#txth_base').val() + "/admision/admisiones/guardarinteresado";
    var arrParams = new Object();
    arrParams.id_pgest = pgest_id;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                if (response.status == "OK") {
                    parent.window.location.href = $('#txth_base').val() + "/interesado/listarinteresados";
                }
            }, 3000);
        }, true);
    }
}
function actualizarGridGestion() {
    var agente = $('#txt_buscarDataAgente').val();
    var interesado = $('#txt_buscarDataPersona').val();
    // var f_atencion = $('#txt_fecha_atencion').val();
    var estado = $('#cmb_estadop option:selected').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        // $('#Pbgestion').PbGridView('applyFilterData', {'agente': agente, 'interesado': interesado, 'f_atencion': f_atencion, 'estado': estado});
        $('#Pbgestion').PbGridView('applyFilterData', {'agente': agente, 'interesado': interesado, 'estado': estado});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function actualizarGridContacto() {
    var search = $('#txt_buscarDataPersona').val();
    var estado = $('#cmb_estadocontacto option:selected').val();
    var fase = $('#cmb_fasecontacto option:selected').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Pbcontacto').PbGridView('applyFilterData', {'search': search, 'estado': estado, 'fase': fase});
        setTimeout(hideLoadingPopup, 2000);
    }
}
function actualizarGridContactoTemp() {
    var search = $('#txt_buscarDataPersonat').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Pbcontactot').PbGridView('applyFilterData', {'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}

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

function exportExcel() {
    var agente = $('#txt_buscarDataAgente').val();
    var interesado = $('#txt_buscarDataPersona').val();
    var f_atencion = $('#txt_fecha_atencion').val();
    var estado = $('#cmb_estado option:selected').val();
    window.location.href = $('#txth_base').val() + "/admision/admisiones/expexcel?agente=" + agente + "&interesado=" + interesado + "&f_atencion=" + f_atencion + "&estado=" + estado;
}

function exportPdf() {
    var agente = $('#txt_buscarDataAgente').val();
    var interesado = $('#txt_buscarDataPersona').val();
    var f_atencion = $('#txt_fecha_atencion').val();
    var estado = $('#cmb_estado option:selected').val();
    window.location.href = $('#txth_base').val() + "/admision/admisiones/exppdf?pdf=1&search=" + agente + "&interesado=" + interesado + "&f_atencion=" + f_atencion + "&estado=" + estado;
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
