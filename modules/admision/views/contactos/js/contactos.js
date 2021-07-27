$(document).ready(function() {
    $('#cmb_pais').change(function() {
        var link = $('#txth_base').val() + "/admision/contactos/edit";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function(response) {
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

    $('#cmb_prov').change(function() {
        var link = $('#txth_base').val() + "/admision/contactos/edit";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu");
            }
        }, true);
    });

    //Control del div de beneficiario
    $('#rdo_beneficio').change(function() {
        if ($('#rdo_beneficio').val() == 1) {
            $("#rdo_beneficio_no").prop("checked", "");
            $('#beneficio').css('display', 'none');
        } else {
            $('#beneficio').css('display', 'block');
        }
    });

    $('#rdo_beneficio_no').change(function() {
        if ($('#rdo_beneficio_no').val() == 2) {
            $("#rdo_beneficio").prop("checked", "");
            $('#beneficio').css('display', 'block');
        } else {
            $('#beneficio').css('display', 'none');
        }
    });

    function camposnulos(campo) {
        if ($(campo).val() == "") {
            $(campo).removeClass("PBvalidation");
        } else {
            $(campo).addClass("PBvalidation");
        }
    }
    $('#btn_buscarContacto').click(function() {
        actualizarGridContacto();
    });

    $('#btn_cargarotroscanales').click(function() {
        cargarOtrosCanales("OTROS_CANALES");
    });

    $('#genAspirante').change(function() {
        if ($(this).prop('checked')) {
            $('#oportunidadDiv').show();
        } else {
            $('#oportunidadDiv').hide();
        }
    });


    $('#cmb_carrera2').change(function() {
        var ref = $(this).attr("data-ref");
        if ($(this).val() != 0) {
            var link = $('#txth_base').val() + "/admision/oportunidades/newoportunidadxcontacto";
            var arrParams = new Object();
            arrParams.car_id = $(this).val();
            arrParams.getsubcarrera = true;
            requestHttpAjax(link, arrParams, function(response) {
                if (response.status == "OK") {
                    data = response.message;
                    setComboData(data.subcarrera, "cmb_subcarrera");
                }
            }, true);
        } else {
            $('#cmb_subcarrera').html("<option value='0'>Ninguno</option>");
        }
    });
    $('#cmb_nivelestudio').change(function() {
        var link = $('#txth_base').val() + "/admision/oportunidades/newoportunidadxcontacto";
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        arrParams.empresa_id = $('#cmb_empresa').val();
        if ($('#cmb_nivelestudio').val() > 1) {
            $('#divtiopor').css('display', 'block');
        } else {
            $('#divtiopor').css('display', 'none');
        }
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.modalidad, "cmb_modalidad");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_nivelestudio').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.empresa_id = $('#cmb_empresa').val();
                    arrParams.getoportunidad = true;
                    requestHttpAjax(link, arrParams, function(response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.oportunidad, "cmb_tipo_oportunidad");
                        }
                    }, true);
                    var arrParams = new Object();
                    if (data.modalidad.length > 0) {
                        arrParams.unidada = $('#cmb_nivelestudio').val();
                        arrParams.moda_id = data.modalidad[0].id;
                        arrParams.empresa_id = $('#cmb_empresa').val();
                        arrParams.getcarrera = true;
                        requestHttpAjax(link, arrParams, function(response) {
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
    $('#cmb_nivelestudio_act').change(function() {
        var link = $('#txth_base').val() + "/admision/oportunidades/edit";
        var arrParams = new Object();
        arrParams.ninter_id = $(this).val();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.getmodalidad = true;
        if ($('#cmb_nivelestudio_act').val() > 1) {
            $('#divtiopor').css('display', 'block');
        } else {
            $('#divtiopor').css('display', 'none');
        }
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                var arrParams = new Object();
                data = response.message;
                setComboData(data.modalidad, "cmb_modalidad_act");
                arrParams.unidada = $('#cmb_nivelestudio_act').val();
                arrParams.empresa_id = $('#cmb_empresa').val();
                arrParams.getoportunidad = true;
                requestHttpAjax(link, arrParams, function(response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboData(data.oportunidad, "cmb_tipo_oportunidad");
                    }
                }, true);
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_nivelestudio_act').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.empresa_id = $('#cmb_empresa').val();
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function(response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.carrera, "cmb_carrera_estudio");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_empresa').change(function() { // cambio 2
        var link = $('#txth_base').val() + "/admision/contacto/new";
        var arrParams = new Object();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.getuacademias = true;
        if ($('#cmb_empresa').val() == 1) {
            $('#divtiopor').css('display', 'none');
        } else {
            $('#divtiopor').css('display', 'block');
        }
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.unidad_academica, "cmb_nivelestudio");
                var arrParams = new Object();
                if (data.unidad_academica.length > 0) {
                    var arrParams = new Object();
                    arrParams.nint_id = $('#cmb_nivelestudio').val();
                    arrParams.empresa_id = $('#cmb_empresa').val();
                    arrParams.getmodalidad = true;
                    requestHttpAjax(link, arrParams, function(response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.modalidad, "cmb_modalidad");
                            if (data.modalidad.length > 0) {
                                var arrParams = new Object();
                                arrParams.unidada = $('#cmb_nivelestudio').val();
                                arrParams.moda_id = $('#cmb_modalidad').val();
                                arrParams.empresa_id = $('#cmb_empresa').val();
                                arrParams.getcarrera = true;
                                requestHttpAjax(link, arrParams, function(response) {
                                    if (response.status == "OK") {
                                        data = response.message;
                                        setComboData(data.carrera, "cmb_carrera1");
                                    }
                                }, true);
                                var arrParams = new Object();
                                arrParams.unidada = $('#cmb_nivelestudio').val();
                                arrParams.empresa_id = $('#cmb_empresa').val();
                                arrParams.getoportunidad = true;
                                requestHttpAjax(link, arrParams, function(response) {
                                    if (response.status == "OK") {
                                        data = response.message;
                                        setComboData(data.oportunidad, "cmb_tipo_oportunidad");
                                    }
                                }, true);
                            }
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidad').change(function() {
        var link = $('#txth_base').val() + "/admision/oportunidades/newoportunidadxcontacto";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_nivelestudio').val();
        arrParams.moda_id = $(this).val();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.carrera, "cmb_carrera1");
            }
        }, true);
    });
    $('#cmb_modalidad_act').change(function() {
        var link = $('#txth_base').val() + "/admision/oportunidades/edit";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_nivelestudio_act').val();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.carrera, "cmb_carrera_estudio");
            }
        }, true);
    });
    $('#cmb_state_opportunity').change(function() {
        if ($('#cmb_state_opportunity').val() == 5 || $('#cmb_state_opportunity').val() == 4) {
            $("#txt_fecha_proxima").prop("disabled", true);
            $("#txt_hora_proxima").prop("disabled", true);
            $('#txt_fecha_proxima').removeClass("PBvalidation");
            $('#txt_hora_proxima').removeClass("PBvalidation");
        } else {
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

    $('#btn_cargarGestion').click(function() {
        cargarGestion();
    });

});

function actualizarGridContacto() {
    var search = $('#txt_buscarDataPersona').val();
    var estado = $('#cmb_estadocontacto option:selected').val();
    var fase = $('#cmb_fasecontacto option:selected').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var medio = $('#cmb_medio option:selected').val();
    var agente = $('#cmb_agente option:selected').val();
    var correo = $('#txt_correo').val();
    var telefono = $('#txt_telefono').val();
    var empresa = $('#cmb_empresa option:selected').val();
    var unidad = $('#cmb_unidad option:selected').val();
    var gestion = $('#cmb_estado_gestion option:selected').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Pbcontacto').PbGridView('applyFilterData', { 'search': search, 'estado': estado, 'fase': fase, 'f_ini': f_ini, 'f_fin': f_fin, 'medio': medio, 'agente': agente, 'correo': correo, 'telefono': telefono, 'empresa': empresa, 'unidad': unidad, 'gestion': gestion });
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var search = $('#txt_buscarDataPersona').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var medio = $('#cmb_medio option:selected').val();
    var agente = $('#cmb_agente option:selected').val();
    var correo = $('#txt_correo').val();
    var telefono = $('#txt_telefono').val();
    var empresa = $('#cmb_empresa option:selected').val();
    var unidad = $('#cmb_unidad option:selected').val();
    var gestion = $('#cmb_estado_gestion option:selected').val();
    window.location.href = $('#txth_base').val() + "/admision/contactos/expexcel?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&medio=" + medio + "&agente=" + agente + "&correo=" + correo + "&telefono=" + telefono + "&empresa=" + empresa + "&unidad=" + unidad + "&gestion=" + gestion;
}

function exportPdf() {
    var search = $('#txt_buscarDataPersona').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var medio = $('#cmb_medio option:selected').val();
    var agente = $('#cmb_agente option:selected').val();
    var correo = $('#txt_correo').val();
    var telefono = $('#txt_telefono').val();
    var empresa = $('#cmb_empresa option:selected').val();
    var unidad = $('#cmb_unidad option:selected').val();
    var gestion = $('#cmb_estado_gestion option:selected').val();
    window.location.href = $('#txth_base').val() + "/admision/contactos/exppdf?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&medio=" + medio + "&agente=" + agente + "&correo=" + correo + "&telefono=" + telefono + "&empresa=" + empresa + "&unidad=" + unidad + "&gestion=" + gestion;
}

function loadLeads() {
    window.location.href = $('#txth_base').val() + "/admision/contactos/cargarleads";
}

function loadFile() {
    cargarLeads('LEADS');
}

function loadCall() {
    cargarLeads('LOTES');
}

function edit() {
    var codigo = $('#txth_pcon_id').val();
    var tper_id = $('#txth_tper_id').val();
    window.location.href = $('#txth_base').val() + "/admision/contactos/edit?codigo=" + codigo + "&tper_id=" + tper_id;
}

function newOportunidadXContacto() {
    var pgid = $('#txth_pgid').val();
    window.location.href = $('#txth_base').val() + "/admision/oportunidades/newoportunidadxcontacto?pgid=" + pgid;
}

function save() {
    var link = $('#txth_base').val() + "/admision/contactos/save";
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

    if ($('input:radio[name=rdo_beneficio]:checked').val()) {
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
    } else {
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
    if (arrParams.beneficiario == 1) {
        $('#txt_nombrebene1').removeClass("PBvalidation");
        $('#txt_apellidobene1').removeClass("PBvalidation");
        $('#txt_celularbene').removeClass("PBvalidation");
        $('#txt_telefono_conbeni').removeClass("PBvalidation");
        $('#txt_correobeni').removeClass("PBvalidation");

        if (arrParams.celularbeni == '' && arrParams.telefonobeni == '' && arrParams.correobeni == '') {
            //$('#txt_celular').addClass("PBvalidation");
            $('#txt_telefono_con').addClass("PBvalidation");
            //$('#txt_correo').addClass("PBvalidation");
        }

    } else {
        $('#txt_nombrebene1').addClass("PBvalidation");
        $('#txt_apellidobene1').addClass("PBvalidation");

        if (arrParams.celularbeni == '' && arrParams.telefonobeni == '' && arrParams.correobeni == '') {
            $('#txt_celularbene').addClass("PBvalidation");
            $('#txt_telefono_conbeni').addClass("PBvalidation");
            $('#txt_correobeni').addClass("PBvalidation");
        } else {
            $('#txt_celularbene').removeClass("PBvalidation");
            $('#txt_telefono_conbeni').removeClass("PBvalidation");
            $('#txt_correobeni').removeClass("PBvalidation");
        }
    }
    if (arrParams.agenteauten == 1 || arrParams.agenteauten == 2 || arrParams.personauten == 1) {
        arrParams.agente = $('#cmb_agente').val();
    } else {
        arrParams.agente = $('#cmb_agenteau').val();
    }
    if ($('input[name=signup-ecu]:checked').val() == 1) {
        arrParams.nacecuador = 1;
    } else {
        arrParams.nacecuador = 0;
    }
    arrParams.genAspirante = $('#genAspirante').prop('checked');
    if ($('#genAspirante').prop('checked')) {
        $('#txt_cedula').addClass("PBvalidation");
        var sub_carrera = ($('#cmb_subcarrera').val() != 0 && $('#cmb_subcarrera').val() != '') ? $('#cmb_subcarrera').val() : 0;
        $('#txt_correo').addClass("PBvalidation");
        arrParams.id_tipo_oportunidad = 0;
        arrParams.id_pgest = $('#txth_pgid').val();
        arrParams.empresaid = $('#cmb_empresa').val();
        arrParams.id_unidad_academica = $('#cmb_nivelestudio').val();
        arrParams.id_modalidad = $('#cmb_modalidad').val();
        if ($('#cmb_nivelestudio').val() > 1) {
            arrParams.id_tipo_oportunidad = $('#cmb_tipo_oportunidad').val();
        }
        arrParams.id_estado_oportunidad = $('#cmb_state_opportunity').val();
        arrParams.id_estudio_academico = $('#cmb_carrera1').val();
        arrParams.canal_conocimiento = $('#cmb_knowledge_channel').val();
        arrParams.carrera2 = $('#cmb_carrera2').val();
        arrParams.sub_carrera = sub_carrera;
        arrParams.modulo_estudio = $('#cmb_modalidad_estudio').val();
        arrParams.cedula = $('#txt_cedula').val();
        arrParams.correobeni = (arrParams.correobeni).toLowerCase();
        if ($('#cmb_state_opportunity').val() == "3" && $('#txt_cedula').val() == "") {
            var msg = objLang.Please_enter_a_valid_dni_;
            shortModal(msg, objLang.Error, "error");
            return;
        }
        if ($('#cmb_state_opportunity').val() == "3" && $('#txt_correo').val() == "") {
            var msg = objLang.Please_enter_a_valid_Email_;
            shortModal(msg, objLang.Error, "error");
            return;
        }
        arrParams.seguimiento = $('#cmb_medio_contacto').val();
        let sizeMedio = ($('#cmb_medio_contacto').val() != undefined && $('#cmb_medio_contacto').val().length >= 1) ? true : false;
        if (!sizeMedio) {
            var msg = objLang.Enter_a_Type_Contact_;
            shortModal(msg, objLang.Error, "error");
            return;
        }
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/admision/contactos/index";
                }, 3000);
            }
        }, true);
    }
}

function update() {
    var link = $('#txth_base').val() + "/admision/contactos/update";
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
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status) {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/admision/contactos/index";
                }, 3000);
            }
        }, true);
    }
}

function grabarInteresado(pgest_id) {
    var link = $('#txth_base').val() + "/admision/interesados/guardarinteresado";
    var arrParams = new Object();
    arrParams.id_pgest = pgest_id;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                if (response.status == "OK") {
                    parent.window.location.href = $('#txth_base').val() + "/admision/interesados/index";
                }
            }, 3000);
        }, true);
    }
}

function camposnulos(campo) {
    if ($(campo).val() == "") {
        $(campo).removeClass("PBvalidation");
    } else {
        $(campo).addClass("PBvalidation");
    }
}

function cargarLeads(proceso) {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/admision/contactos/cargarleads";
    arrParams.procesar_file = true;
    arrParams.tipo_proceso = proceso;
    arrParams.emp_id = $('#cmb_empresa option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_leads2').val() + "." + $('#txth_doc_adj_leads').val().split('.').pop();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/admision/contactos/index";
            }, 3000);
        }, true);
    }
}

function exportLostContact() {
    var op = 1;
    window.location.href = $('#txth_base').val() + "/admision/contactos/export?op=" + op;
}

function exportStatContact() {
    var op = 2;
    window.location.href = $('#txth_base').val() + "/admision/contactos/export?op=" + op;
}

function cargarOtrosCanales(proceso) {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/admision/contactos/cargarotroscanales";
    arrParams.procesar_file = true;
    arrParams.tipo_proceso = proceso;
    arrParams.emp_id = $('#cmb_empresa option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_leads2').val() + "." + $('#txth_doc_adj_leads').val().split('.').pop();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/admision/contactos/index";
            }, 3000);
        }, true);
    }
}

function cargarGestion() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/admision/oportunidades/cargargestion";
    arrParams.procesar_file = true;
    //arrParams.tipo_proceso = proceso;*/
    arrParams.emp_id = $('#cmb_empresa option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_leads2').val() + "." + $('#txth_doc_adj_leads').val().split('.').pop();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/admision/oportunidades/index";
            }, 3000);
        }, true);
    }
}