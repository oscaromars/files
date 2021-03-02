/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*  Funcion Retorna "retornarIndLista"
 *  Recibe: Lista Json, Propieda o Campo,Valor Comparacion, Campo del Valor a Retornar
 **/

function retornarIndLista(array, property, value, ids) {
    var index = -1;
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            index = array[i][ids];
            return index;
        }
    }
    //Retorna  -1 si no esta en ls lista
    return index;
}

function buscarDataIndex(control, op) {
    control = (control == '') ? 'txt_PER_CEDULA' : control;
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_DOCUMENTO').PbGridView('applyFilterData', { "CONT_BUSCAR": controlBuscarIndex(control, op) });
        setTimeout(hideLoadingPopup, 2000);
    }
}

function controlBuscarIndex(control, op) {
    var buscarArray = new Array();
    var buscarIndex = new Object();
    if (sessionStorage.src_buscIndex) {
        var arrayList = JSON.parse(sessionStorage.src_buscIndex);
        buscarIndex.CEDULA = retornarIndLista(arrayList, 'RazonSocialComprador', $('#' + control).val(), 'IdentificacionComprador');
    } else {
        buscarIndex.CEDULA = '';
    }
    buscarIndex.OP = op;
    buscarIndex.TIPO_APR = $('#cmb_tipoApr option:selected').val();
    buscarIndex.RAZONSOCIAL = $('#' + control).val(),

        buscarIndex.F_INI = $('#dtp_fec_ini').val();
    buscarIndex.F_FIN = $('#dtp_fec_fin').val();
    buscarArray[0] = buscarIndex;
    //return buscarArray[0];
    return JSON.stringify(buscarArray);
}

function autocompletarBuscarPersona(requestq, responseq, control, op) {
    var link = $('#txth_base').val() + "/fe_edoc/nubefactura/index";
    var arrParams = new Object();
    arrParams.valor = $('#' + control).val();
    arrParams.op = op;
    requestHttpAjax(link, arrParams, function(response) {
        //showAlert(response.status, response.label, response.message);
        //if (response.status == 'OK') {
        var arrayList = new Array;
        var count = response.length;
        for (var i = 0; i < count; i++) {
            row = new Object();
            row.IdentificacionComprador = response[i]['IdentificacionComprador'];
            row.RazonSocialComprador = response[i]['RazonSocialComprador'];

            // Campos Importandes relacionados con el  CJuiAutoComplete
            row.id = response[i]['IdentificacionComprador'];
            row.label = response[i]['RazonSocialComprador'] + ' - ' + response[i]['IdentificacionComprador']; //+' - '+data[i]['SEGURO_SOCIAL'];//Lo sugerido
            //row.value=response[i]['IdentificacionComprador'];//lo que se almacena en en la caja de texto
            row.value = response[i]['RazonSocialComprador']; //lo que se almacena en en la caja de texto
            arrayList[i] = row;
        }
        sessionStorage.src_buscIndex = JSON.stringify(arrayList); //dss=>DataSessionStore
        responseq(arrayList);
        //}
    }, true);
}


function verificaAcciones() {
    var ids = String($('#TbG_DOCUMENTO').PbGridView('getSelectedRows'));
    var count = ids.split(",");
    if (count.length > 0 && ids != "") {
        $("#btn_enviar").removeClass("disabled");
        //verificaAutorizado('TbG_DOCUMENTO');
    } else {
        $("#btn_enviar").addClass("disabled");
    }
}

function verificaAutorizado(TbGtable) {
    $('#' + TbGtable + ' tr').each(function() {
        var estado = $(this).find("td").eq(3).html(); //Columna Estado
        //Verifica que este CHeck la Primera COlumna
        if ($(this).children(':first-child').children(':first-child').is(':checked')) {
            //alert(estado);
            if (estado == 'Autorizado') { //Si es Igual Autorizado no lo deja Check

            }
        }
    });
}

function fun_EnviarDocumento() {
    var ids = String($('#TbG_DOCUMENTO').PbGridView('getSelectedRows'));
    var count = ids.split(",");
    if (count.length > 0 && ids != "") {
        if (!confirm(mgEnvDocum)) return false;
        var link = $('#txth_base').val() + "/fe_edoc/nubefactura/enviardocumento";
        var encodedIds = base64_encode(ids); //Verificar cofificacion Base
        $("#TbG_DOCUMENTO").addClass("loading");
        var arrParams = new Object();
        arrParams.ids = encodedIds;
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == 'OK') {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
                //actualizarTbG_DOCUMENTO();
                buscarDataIndex('', '');
            } else {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
            }
            $("#TbG_DOCUMENTO").removeClass("loading");
        }, true);
    } else {
        //$("#messageInfo").html(selecDoc+buttonAlert); 
        shortModal(objLang.Select_an_item_to_process_the_request_, 'error', 'NO_OK');
        //alerMessage();
    }

    return true;
}

/*
 * ADMINISTRADOR DE TAREAS WEBSEA
 */
function fun_EnviarCorreccion() {
    var ids = String($('#TbG_DOCUMENTO').PbGridView('getSelectedRows'));
    var count = ids.split(",");
    if (count.length > 0 && ids != "") {
        if (!confirm(mgEnvDocum)) return false;
        var link = $('#txth_base').val() + "/fe_edoc/nubefactura/enviarcorreccion";
        var encodedIds = base64_encode(ids); //Verificar cofificacion Base
        $("#TbG_DOCUMENTO").addClass("loading");
        var arrParams = new Object();
        arrParams.ids = encodedIds;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
                showAlert(response.status, response.label, response.message);
                //actualizarTbG_DOCUMENTO();
                buscarDataIndex('', '');
            } else {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
                showAlert(response.status, response.label, response.message);
            }
        }, true);
        $("#TbG_DOCUMENTO").removeClass("loading");
    } else {
        $("#messageInfo").html(selecDocAnu + buttonAlert);
        alerMessage();
        shortModal(objLang.Select_an_item_to_process_the_request_, 'error', 'NO_OK');
    }
    return true;
}

function fun_ActulizaEstado() {
    var ids = String($('#TbG_DOCUMENTO').PbGridView('getSelectedRows'));
    //alert(ids);
    var count = ids.split(",");
    if (count.length > 0 && ids != "") {
        if (!confirm(mgEnvDocum)) return false;
        var link = $('#txth_base').val() + "/fe_edoc/nubefactura/actulizaestado";
        var encodedIds = base64_encode(ids); //Verificar cofificacion Base
        $("#TbG_DOCUMENTO").addClass("loading");
        var arrParams = new Object();
        arrParams.ids = encodedIds;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
                showAlert(response.status, response.label, response.message);
                //actualizarTbG_DOCUMENTO();
                buscarDataIndex('', '');
            } else {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
                showAlert(response.status, response.label, response.message);
            }
        }, true);
        $("#TbG_DOCUMENTO").removeClass("loading");
    } else {
        $("#messageInfo").html(selecDocAnu + buttonAlert);
        alerMessage();
        shortModal(objLang.Select_an_item_to_process_the_request_, 'error', 'NO_OK');
    }
    return true;
}

function fun_EnviarAnular() {
    var ids = String($('#TbG_DOCUMENTO').PbGridView('getSelectedRows'));
    var count = ids.split(",");
    if (count.length > 0 && ids != "") {
        if (!confirm(mgEnvDocumAnu)) return false;
        var link = $('#txth_base').val() + "/fe_edoc/nubefactura/enviaranular";
        var encodedIds = base64_encode(ids); //Verificar cofificacion Base
        $("#TbG_DOCUMENTO").addClass("loading");
        var arrParams = new Object();
        arrParams.ids = encodedIds;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
                showAlert(response.status, response.label, response.message);
                //actualizarTbG_DOCUMENTO();
                buscarDataIndex('', '');
            } else {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
                showAlert(response.status, response.label, response.message);
            }
        }, true);
        $("#TbG_DOCUMENTO").removeClass("loading");
    } else {
        $("#messageInfo").html(selecDocAnu + buttonAlert);
        alerMessage();
        shortModal(objLang.Select_an_item_to_process_the_request_, 'error', 'NO_OK');
    }
    return true;
}

function fun_EnviarCorreo() {
    var ids = String($('#TbG_DOCUMENTO').PbGridView('getSelectedRows'));
    var count = ids.split(",");
    if (count.length > 0 && ids != "") {
        if (!confirm(mgEnvDocum)) return false;
        var link = $('#txth_base').val() + "/fe_edoc/nubefactura/enviarcorreo";
        var encodedIds = base64_encode(ids); //Verificar cofificacion Base
        $("#TbG_DOCUMENTO").addClass("loading");
        var arrParams = new Object();
        arrParams.ids = encodedIds;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                showAlert(response.status, response.label, response.message);
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
                //actualizarTbG_DOCUMENTO();
                //buscarDataIndex('', '');
            } else {
                showAlert(response.status, response.label, response.message);
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
            }
        }, true);
        $("#TbG_DOCUMENTO").removeClass("loading");
    } else {
        //$("#messageInfo").html(selecDocMail + buttonAlert);
        //alerMessage();
        shortModal(objLang.Select_an_item_to_process_the_request_, 'error', 'NO_OK');
    }
    return true;
}

function fun_UpdateMail() {
    var link = "";
    var id = String($('#TbG_DOCUMENTO').PbGridView('getSelectedRows'));
    var count = id.split(",");
    if (count.length == 1 && id != "") {
        //id = base64_encode(ids);
        link = $('#txth_base').val() + "/fe_edoc/nubefactura/updatemail?";
        $('#btn_Update').attr("href", link + "id=" + id);
    } else {
        shortModal(objLang.Select_an_item_to_process_the_request_, 'error', 'NO_OK');
    }
}

function fun_CambiaMail() {
    var ids = $('#txth_usu_mail').val();
    //var IdsDoc = $('#txth_IdsDoc').val();
    var correo = $('#txt_correo').val();
    var dni = $('#txt_cedularuc').val();
    if ($('#txt_correo').val() != '' && ids != 0) {
        //pass = base64_encode(pass);
        var link = $('#txth_base').val() + "/fe_edoc/nubefactura/savemail";
        var arrParams = new Object();
        arrParams.DATA = correo;
        //arrParams.DNI = dni;
        arrParams.ID = ids;
        //arrParams.IDSDOC = IdsDoc;
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
            } else {
                $("#messageInfo").html(response.message + buttonAlert);
                alerMessage();
            }
        }, true);
    } else {
        shortModal(objLang.Email_is_incorrect_, 'error', 'NO_OK');
        //alert('Los Datos de correo no son correctos.');
    }
}

/********************************************** */

function exportPdf() {

}

/********************************************
 * DATOS
 * VER ERRORES DE DOCUMENTOS
 * Retorno de Valores a  los campos
 ********************************************/
function verCorrecciones(ids) {
    var link = $('#txth_base').val() + "/mceformulariotemp/viewmessage";
    var arrParams = new Object();
    arrParams.ids = ids;
    requestHttpAjax(link, arrParams, function(response) {
        var data = response.message;
        if (response.status == "OK") {
            divComentario(data.dataComentario);
        }
        //showAlert(response.status, response.type, {"wtmessage": data.info, "title": response.label});
    }, true);
}

function divComentario(data) {
    //$("#countMensaje").html(data.length);
    var option_arr = '';
    option_arr += '<div style="overflow-y: scroll;height:200px;">';
    for (var i = 0; i < data.length; i++) {
        option_arr += '<div class="post clearfix">';
        option_arr += '<div class="user-block">';
        option_arr += '<span>';
        option_arr += '<a href="#">' + (data[i]["Nombres"]).toUpperCase() + '</a>';
        //option_arr += '<a onclick="deleteComentario(\'' + data[i]['Ids'] + '\')" class="pull-right btn-box-tool" href="#"><i class="fa fa-times"></i></a>';
        option_arr += '</span><br>';
        option_arr += '<span>' + (data[i]["fecha"]).toUpperCase() + '</span>';
        option_arr += '</div>';
        option_arr += '<p>' + (data[i]["Mensaje"]).toUpperCase() + '</p>';
        option_arr += '</div>';
    }
    option_arr += '</div>';
    showAlert("OK", "info", { "wtmessage": option_arr, "title": "Correcciones" });
}