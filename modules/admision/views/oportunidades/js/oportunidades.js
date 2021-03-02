$(document).ready(function() {
    $('#btn_buscarGestion').click(function() {
        actualizarGridGestion();
    });
    $('#cmb_carrera2').change(function() {
        var ref = $(this).attr("data-ref");
        if ($(this).val() != 0) {
            var link = $('#txth_base').val() + "/admision/oportunidades/newoportunidadxcontacto";
            if (ref == "edit")
                link = $('#txth_base').val() + "/admision/oportunidades/edit";
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
        var link = $('#txth_base').val() + "/admision/oportunidades/newoportunidadxcontacto";
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

function actualizarGridGestion() {
    //var agente = $('#txt_buscarDataAgente').val();
    var interesado = $('#txt_buscarDataPersona').val();
    var empresa = $('#cmb_empresa option:selected').val();
    var estado = $('#cmb_estadop option:selected').val();
    var fecregistroini = $('#txt_fecha_registro_ini').val();
    var fecregistrofin = $('#txt_fecha_registro_fin').val();
    var fecproximaini = $('#txt_fecha_proxima_ini').val();
    var fecproximafin = $('#txt_fecha_proxima_fin').val();

    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        //$('#Pbgestion').PbGridView('applyFilterData', {'agente': agente, 'interesado': interesado, 'empresa': empresa, 'estado': estado});
        $('#Pbgestion').PbGridView('applyFilterData', { 'interesado': interesado, 'empresa': empresa, 'estado': estado, 'fecregini': fecregistroini, 'fecregfin': fecregistrofin, 'fecproxini': fecproximaini, 'fecproxfin': fecproximafin });
        setTimeout(hideLoadingPopup, 2000);
    }
}

function edit() {
    var codigo = $('#txth_opoid').val();
    var persona = $('#txth_pgid').val();
    window.location.href = $('#txth_base').val() + "/admision/oportunidades/edit?codigo=" + codigo + "&pgesid=" + persona;
}

function update() {
    var link = $('#txth_base').val() + "/admision/oportunidades/update";
    var arrParams = new Object();
    arrParams.tipoOport = null;
    arrParams.pgid = $('#txth_pgid').val();
    arrParams.opo_id = $('#txth_opoid').val();
    arrParams.uaca_id = $('#cmb_nivelestudio_act').val();
    arrParams.modalidad = $('#cmb_modalidad_act').val();
    arrParams.empresa = $('#cmb_empresa').val();
    if ($('#cmb_nivelestudio_act').val() > 1) {
        arrParams.tipoOport = $('#cmb_tipo_oportunidad').val();
    }
    arrParams.estado = $('#cmb_state_opportunity').val();
    arrParams.carreraestudio = $('#cmb_carrera_estudio').val();
    arrParams.canal = $('#cmb_ccanal').val();
    arrParams.carrera2 = $('#cmb_carrera2').val();
    arrParams.subcarrera = $('#cmb_subcarrera').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/admision/oportunidades/index";
            }, 3000);
        }, true);
    }
}

function save() {
    var sub_carrera = ($('#cmb_subcarrera').val() != 0 && $('#cmb_subcarrera').val() != '') ? $('#cmb_subcarrera').val() : 0;
    var link = $('#txth_base').val() + "/admision/oportunidades/save";
    var arrParams = new Object();
    arrParams.id_tipo_oportunidad = null;
    arrParams.id_pgest = $('#txth_pgid').val();
    arrParams.empresa = $('#cmb_empresa').val();
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
    arrParams.correo = ($('#txt_correo').val()).toLowerCase();
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
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                if (response.status == "OK") {
                    //parent.window.location.href = $('#txth_base').val() + "/admision/admisiones/listaroportxcontacto?pgid=".arrParams.id_pgest;
                    parent.window.location.href = $('#txth_base').val() + "/admision/contactos/index";
                }
            }, 3000);
        }, true);
    }
}

function exportExcel() {
    var search = $('#txt_buscarDataAgente').val();
    var contacto = $('#txt_buscarDataPersona').val();
    var empresa = $('#cmb_empresa').val();
    var f_estado = $('#cmb_estadop').val();
    var fecregistroini = $('#txt_fecha_registro_ini').val();
    var fecregistrofin = $('#txt_fecha_registro_fin').val();
    var fecproximaini = $('#txt_fecha_proxima_ini').val();
    var fecproximafin = $('#txt_fecha_proxima_fin').val();
    //window.location.href = $('#txth_base').val() + "/admision/oportunidades/expexcel?search=" + search + "&contacto=" + contacto + "&empresa=" + empresa + "&f_estado=" + f_estado;
    window.location.href = $('#txth_base').val() + "/admision/oportunidades/expexcel?contacto=" + contacto + "&empresa=" + empresa + "&f_estado=" + f_estado + "&fecregistroini=" + fecregistroini + "&fecregistrofin=" + fecregistrofin + "&fecproximaini=" + fecproximaini + "&fecproximafin=" + fecproximafin;
}

function exportPdf() {
    var search = $('#txt_buscarDataAgente').val();
    var contacto = $('#txt_buscarDataPersona').val();
    var empresa = $('#cmb_empresa').val();
    var f_estado = $('#cmb_estadop').val();
    var fecregistroini = $('#txt_fecha_registro_ini').val();
    var fecregistrofin = $('#txt_fecha_registro_fin').val();
    var fecproximaini = $('#txt_fecha_proxima_ini').val();
    var fecproximafin = $('#txt_fecha_proxima_fin').val();
    //window.location.href = $('#txth_base').val() + "/admision/oportunidades/exppdfoportunidades?pdf=1&search=" + search + "&contacto=" + contacto + "&empresa=" + empresa + "&f_estado=" + f_estado;
    window.location.href = $('#txth_base').val() + "/admision/oportunidades/exppdfoportunidades?pdf=1&contacto=" + contacto + "&empresa=" + empresa + "&f_estado=" + f_estado + "&fecregistroini=" + fecregistroini + "&fecregistrofin=" + fecregistrofin + "&fecproximaini=" + fecproximaini + "&fecproximafin=" + fecproximafin;
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