$(document).ready(function() {
    //*********** FUNCIONES QUE SE DEBEN REMOVER CUANDO ESTEN HABILITADOS LOS MENUS **********
    $('#cmb_state_opportunity').change(function() {
        if ($('#cmb_state_opportunity').val() == 5 || $('#cmb_state_opportunity').val() == 4 || $('#cmb_state_opportunity').val() == 3) {
            $("#txt_fecha_proxima").prop("disabled", true);
            $("#txt_hora_proxima").prop("disabled", true);
            $('#txt_fecha_proxima').removeClass("PBvalidation");
            $('#txt_hora_proxima').removeClass("PBvalidation");
            $('#txt_fecha_proxima').val('');
            $('#txt_hora_proxima').val('');

        } else {
            $("#txt_fecha_proxima").prop("disabled", false);
            $("#txt_hora_proxima").prop("disabled", false);
            $('#txt_fecha_proxima').addClass("PBvalidation");
            $('#txt_hora_proxima').addClass("PBvalidation");
            $('#txt_fecha_proxima').val('');
            $('#txt_hora_proxima').val('');
        }
        if ($('#cmb_state_opportunity').val() == 5) {
            $('#divoportunidad_perdida').css('display', 'block');
        } else {
            $('#divoportunidad_perdida').css('display', 'none');
        }
    });
    $('#cmb_lost_opportunity').change(function() {
        if ($('#cmb_lost_opportunity').val() == 13) {
            $('#div_otro_estudio').css('display', 'block');
        } else {
            $('#div_otro_estudio').css('display', 'none');
        }
    });
});

function newItem() {
    var opid = $('#txth_opid').val();
    var pgid = $('#txth_pgid').val();
    window.location.href = $('#txth_base').val() + "/admision/actividades/newactividad?opid=" + opid + "&pgid=" + pgid;
}

function save() {
    var link = $('#txth_base').val() + "/admision/actividades/save";
    var arrParams = new Object();
    //Datos Gestión 
    arrParams.oportunidad = $('#txth_opo_id').val();
    arrParams.estado_oportunidad = $('#cmb_state_opportunity').val();
    arrParams.fecatencion = $('#txt_fecha_atencion').val();
    arrParams.horatencion = $('#txt_hora_atencion').val();
    arrParams.observacion = $('#cmb_observacion').val();
    arrParams.descripcion = $('#txt_descripcion').val();
    if (arrParams.estado_oportunidad == 5) {
        arrParams.oportunidad_perdida = $('#cmb_lost_opportunity').val();
        if (arrParams.oportunidad_perdida == 13) {
            arrParams.otro_estudio = $('#cmb_otras_maestrias').val();
        }
    }
    //Datos Próxima Atención
    if (arrParams.estado_oportunidad == 5 || arrParams.estado_oportunidad == 4 || arrParams.estado_oportunidad == 3) {
        arrParams.fecproxima = '';
        arrParams.horproxima = '';
    } else {
        arrParams.fecproxima = $('#txt_fecha_proxima').val();
        arrParams.horproxima = $('#txt_hora_proxima').val();
    }
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
                var opor_id = $('#txth_opo_id').val();
                var pges_id = $('#txth_pgid').val();
                window.location.href = $('#txth_base').val() + "/admision/actividades/listaractividadxoportunidad?opor_id=" + opor_id + "&pges_id=" + pges_id;
            }, 3000);
        }, true);
    }
}

function update() {
    var link = $('#txth_base').val() + "/admision/actividades/update";
    var arrParams = new Object();
    //Datos Gestión 
    arrParams.bact_id = $('#txth_acid').val();
    arrParams.fecatencion = $('#txt_fecha_atencion').val();
    arrParams.horatencion = $('#txt_hora_atencion').val();
    arrParams.observacion = $('#cmb_observacion').val();
    arrParams.descripcion = $('#txt_descripcion').val();
    //Datos Próxima Atención
    arrParams.fecproxima = $('#txt_fecha_proxima').val();
    arrParams.horproxima = $('#txt_hora_proxima').val();
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
                var opor_id = $('#txth_opo_id').val();
                var pges_id = $('#txth_pgid').val();
                window.location.href = $('#txth_base').val() + "/admision/actividades/listaractividadxoportunidad?opor_id=" + opor_id + "&pges_id=" + pges_id;
            }, 3000);
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

function edit() {
    var opid = $('#txth_opo_id').val();
    var pgid = $('#txth_pgid').val();
    var acid = $('#txth_acid').val();
    window.location.href = $('#txth_base').val() + "/admision/actividades/edit?opid=" + opid + "&pgid=" + pgid + "&acid=" + acid;
}