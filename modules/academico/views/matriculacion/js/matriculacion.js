
$(document).ready(function() {
    $('#btn_grabar_asignacion').click(function() {
        var arrParams = new Object();
        var link = $('#txth_base').val() + "/academico/matriculacion/save";

        arrParams.sins_id = $('#txth_sins_id').val();
        arrParams.par_id = $('#cmb_paralelo').val();
        arrParams.per_id = $('#cmb_periodo').val();
        arrParams.adm_id = $('#txth_adm_id').val();

        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);

                setTimeout(function() {
                    parent.window.location.href = $('#txth_base').val() + "/academico/admitidos/index";
                }, 2000);

            }, true);
        }
    });

    $('#btn_buscarEstudiantes').click(function () {
        actualizarGridAbePeriodoAcademicoCreate();
    });

    $('#cmb_periodo').change(function() {
        var link = $('#txth_base').val() + "/academico/matriculacion/newmetodoingreso";
        var arrParams = new Object();
        arrParams.pmin_id = $(this).val();
        arrParams.getparalelos = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.paralelos, "cmb_paralelo");

            }
        }, true);
    });

    $('#cmb_per_academico').change(function() {
        var arrParams2 = new Object();
        arrParams2.PBgetFilter = true;
        arrParams2.search = $("#boxgrid").val();
        arrParams2.pla_periodo_academico = $("#cmb_per_academico").val();
        arrParams2.mod_id = $("#cmb_modalidad").val();
        arrParams2.aprobacion = $("#cmb_estado").val();
        /* console.log(arrParams2); */
        $("#grid_pagos_list").PbGridView("applyFilterData", arrParams2);
    });

    $('#cmb_modalidad, #cmb_estado').change(function() {
        var arrParams2 = new Object();
        arrParams2.PBgetFilter = true;
        arrParams2.search = $("#boxgrid").val();
        arrParams2.pla_periodo_academico = $("#cmb_per_academico").val();
        arrParams2.mod_id = $("#cmb_modalidad").val();
        arrParams2.aprobacion = $("#cmb_estado").val();
        /* console.log(arrParams2); */
        $("#grid_pagos_list").PbGridView("applyFilterData", arrParams2);
    });

    $('#cmb_carrera, #cmb_per_acad, #cmb_mod, #cmb_status').change(function() {
        searchModulesList('boxgrid', 'grid_listadoregistrados_list');
    });

    /*
    $('#grid_registro_list > table > tbody > tr > td > input').change(function() {
        //alert("hola mundo");
        var subtotal = 0;
        var total    = 0;
        var asoc     = $('#frm_asc_est').val();
        var mat      = $('#frm_mat_cos').val();
        var gastos   = $('#frm_gas_adm').val();
        $('#grid_registro_list > table > tbody > tr > td > input').each(function() {
            if ($(this).is(':checked')) {
                var credits = $(this).parent().prev().text();
                var cat     = $('#frm_cat_price').val();
                var costMat = cat * credits;
                subtotal += costMat;
            }
            $('#costMat').text('$' + (subtotal.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        });
        //total = subtotal + parseFloat(asoc) + parseFloat(mat) + parseFloat(gastos);
        total = subtotal + parseFloat(asoc) + parseFloat(gastos);
        $('#costTotal').text('$' + (total.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    });*/
    $('#grid_registro_list > table > tbody > tr > td > input').change(function() {
        //alert(contador);
        var subtotal = 0;
        var total    = 0;
        var contador=0;
        var contadorb1=0;
        var contadorb2=0;
        var gastos=0.00;
        var bloque=['B1','B2'];
        
        $('#grid_registro_list > table > tbody > tr > td > input').each(function() {
            if ($(this).is(':checked')) {  
                //console.log($(this).parent().prev().prev().prev().prev().prev().text());
                $txt_cost_materia = $(this).parent().prev().prev().text();
                subtotal += parseFloat($txt_cost_materia.replace("$",""));
                $txt_bloque = $(this).parent().prev().prev().prev().prev().prev().prev().text();
                $mitad=1;
                if ($txt_bloque==bloque[0]){
                    //$mitad=2;
                    contadorb1+=1;

                } else {
                    //$mitad = 1;
                    contadorb2+=1; // Así que se divide a la mitad
                } 
                /*console.log(contadorb1);
                console.log(contadorb2);
                console.log("==================");*/
            }else {
                contador -=1;
                gastos=(contador>0)?gastos:0;
                /*console.log(contadorb1);
                console.log(contadorb2);
                console.log("==================    p");*/

            }

            //$('#costMat').text('$' + (subtotal.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        });

        var costoadm  = parseFloat($('#costoadm').val());
        if(contadorb1>0&& contadorb2>0){
            gastos=costoadm;
            
        }else{
            gastos=costoadm/2;
        }

            if(contadorb1==0&&contadorb2==0){
                gastos=0;    
            }
        /*console.log(gastos);
        console.log(subtotal);
        console.log(total);*/

        //total = subtotal + parseFloat(asoc) + parseFloat(mat) + parseFloat(gastos);
        //total = subtotal + gastos;
        total = subtotal + parseFloat(gastos);
        $('#costo').text("$" +subtotal.toFixed(2));
        $('#costAdmin').text("$"+gastos.toFixed(2));
        $('#costTotal').text("$" +total.toFixed(2));
        //$('#costo').text('$' + (subtotal.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        //$('#costTotal').text('$' + (total.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    });

    $("#cmb_formapago").on('change', function(){   
        var opcion = $('#cmb_formapago').val();

        if(opcion==1){
            $('#txt_fechapago').removeClass('PBvalidation');
            $('#pago_documento').hide(); 
            $('.pago_documento').hide();           

            $('#pago_stripe').show();
        }else if(opcion==4 || opcion==5){
            $('#txt_fechapago').addClass('PBvalidation');
            $('#pago_documento').show();
            $('.pago_documento').show();

            $('#pago_stripe').hide();
        }else{
            $('#txt_fechapago').removeClass('PBvalidation');
            $('#pago_documento').hide(); 
            $('.pago_documento').hide();           

            $('#pago_stripe').hide();
        }
    });

    $('.pago_documento').hide(); 

    $('#btn_registro_detalle').click(function () {
        continuarRegistro();
    });

    $('.chequeado').prop("checked",true);
    

    showRegisterSubject();
});

function savemethod() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/matriculacion/save";
    arrParams.sins_id = $('#txth_sins_id').val();
    arrParams.par_id = $('#cmb_paralelo').val();
    arrParams.per_id = $('#cmb_periodo').val();
    arrParams.adm_id = $('#txth_adm_id').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);

            setTimeout(function() {
                parent.window.location.href = $('#txth_base').val() + "/academico/admitidos/index";
            }, 2000);

        }, true);
    }

}

/********************************* FUNCIONES DE MATRICULACION TEMPORAL *********************************/

function searchModules(idbox, idgrid) {
    var arrParams2 = new Object();
    arrParams2.PBgetFilter = true;
    arrParams2.search = $("#boxgrid").val();
    arrParams2.pla_periodo_academico = $("#cmb_per_academico").val();
    arrParams2.mod_id = $("#cmb_modalidad").val();
    arrParams2.aprobacion = $("#cmb_estado").val();
    /* console.log(arrParams2); */
    $("#grid_pagos_list").PbGridView("applyFilterData", arrParams2);
}

function searchModulesList(idbox, idgrid) {
    var arrParams2 = new Object();
    arrParams2.PBgetFilter = true;
    arrParams2.search = $("#" + idbox).val();
    arrParams2.periodo = ($("#cmb_per_acad").val() > 0) ? ($("#cmb_per_acad option:selected").text()) : null;
    arrParams2.carrera = ($("#cmb_carrera").val() > 0) ? ($("#cmb_carrera option:selected").text()) : null;
    arrParams2.mod_id = ($("#cmb_mod").val() > 0) ? ($("#cmb_mod").val()) : null;
    arrParams2.estado = ($("#cmb_status").val() > -1) ? ($("#cmb_status").val()) : null;
    $("#" + idgrid).PbGridView("applyFilterData", arrParams2);
}

function registro() {
    var arrParams = new Object();
    var per_id = $('#frm_per_id').val();
    var link = $('#txth_base').val() + "/academico/matriculacion/registroadminindex?per_id="+per_id;
    var materias = new Array();
    var codes    = new Array();
    var bloque   = new Array();
    var paralelo = new Array();
    var hora     = new Array();
    var credits  = new Array();
    var costs    = new Array();
    var contador = 0;

    $('#grid_registro_list input[type=checkbox]').each(function() {
        if (this.checked ) {
            contador += 1;
        }
    });
    
    
     var message = {
        "wtmessage": objLang.You_must_choose_at_least_two,
        "title": objLang.Error
    }

    if (contador < 2) {
        message.wtmessage = message.wtmessage;
        showAlert("NO_OK", "Error", message);
        return;
    }

    var contador = 0;
    $('#grid_registro_list input[type=checkbox]').each(function() {
        //console.log("-----------------");
        //console.log(this);
        if (this.checked &&  $(this).attr('disabled') != "disabled" ) {
            materias[contador]  = $(this).val();
            codes[contador]     = $(this).attr('name');
            bloque[contador]    = $(this).parent().prev().prev().prev().prev().prev().prev().text();
            hora[contador]      = $(this).parent().prev().prev().prev().prev().prev().text();
            paralelo[contador]  = $(this).parent().prev().prev().prev().prev().text();
            credits[contador]   = $(this).parent().prev().prev().prev().text();
            costs[contador]     = $(this).parent().prev().prev().text();
            contador += 1;
        }
    }); 

    var message1 = {
        "wtmessage1": objLang.You_must_choose_the_maximum_of_six,
        "title": objLang.Error
    }  

    if(contador>6){
        message1.wtmessage1 = message1.wtmessage1;
        showAlert("NO_OK", "Error", message1);
        return;
    }

    arrParams.pes_id = $('#frm_pes_id').val();
    arrParams.ron_id = $('#frm_ron_id').val();
    arrParams.per_id = $('#frm_per_id').val();
    arrParams.registerSubject = 1;
    arrParams.modalidad = $('#frm_modalidad').val();
    arrParams.carrera = $('#frm_carrera').val();
    arrParams.pdf = 1;
    arrParams.codes = codes;
    arrParams.paralelo = paralelo;
    arrParams.bloque = bloque;
    arrParams.hora = hora;
    arrParams.credits = credits;
    arrParams.costs = costs;
    arrParams.materias = materias;

    console.log(arrParams);

    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        setTimeout(function() {
        //   parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/registro" + $('#frm_per_id').val();
        parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/registro" + "?uper_id=" + $("#frm_per_id").val();
               // parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/fundacion";
        }, 2000);

    }, true);
    
    
}


function registerSubject() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/matriculacion/registro";
    var materias = new Array();
    var codes    = new Array();
    var bloque   = new Array();
    var paralelo = new Array();
    var hora     = new Array();
    var credits  = new Array();
    var costs    = new Array();
    var contador = 0;

    $('#grid_registro_list input[type=checkbox]').each(function() {
        if (this.checked ) {
            contador += 1;
        }
    });
    var message = {
        "wtmessage": objLang.You_must_choose_at_least_two,
        "title": objLang.Error
    }

    if (contador < 2) {
        message.wtmessage = message.wtmessage;
        showAlert("NO_OK", "Error", message);
        return;
    }

    var contador = 0;
    $('#grid_registro_list input[type=checkbox]').each(function() {
        //console.log("-----------------");
        //console.log(this);
        if (this.checked &&  $(this).attr('disabled') != "disabled" ) {
            materias[contador]  = $(this).val();
            codes[contador]     = $(this).attr('name');
            bloque[contador]    = $(this).parent().prev().prev().prev().prev().prev().prev().text();
            hora[contador]      = $(this).parent().prev().prev().prev().prev().prev().text();
            paralelo[contador]  = $(this).parent().prev().prev().prev().prev().text();
            credits[contador]   = $(this).parent().prev().prev().prev().text();
            costs[contador]     = $(this).parent().prev().prev().text();
            contador += 1;
        }
    });   

    var message1 = {
        "wtmessage1": objLang.You_must_choose_the_maximum_of_six,
        "title": objLang.Error
    }  

    if(contador>6){
        message1.wtmessage1 = message1.wtmessage1;
        showAlert("NO_OK", "Error", message1);
        return;
    }

    arrParams.pes_id = $('#frm_pes_id').val();
    arrParams.ron_id = $('#frm_ron_id').val();
    arrParams.per_id = $('#frm_per_id').val();
    arrParams.registerSubject = 1;
    arrParams.modalidad = $('#frm_modalidad').val();
    arrParams.carrera = $('#frm_carrera').val();
    arrParams.pdf = 1;
    arrParams.codes = codes;
    arrParams.paralelo = paralelo;
    arrParams.bloque = bloque;
    arrParams.hora = hora;
    arrParams.credits = credits;
    arrParams.costs = costs;
    arrParams.materias = materias;

    console.log(arrParams);

    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        setTimeout(function() {
            location.reload();
            //parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/index";
        }, 2000);

    }, true);
}

function exportPDF() {
    var ron_id = $('#frm_ron_id').val();
    /* console.log(ron_id); */
    window.location.href = $('#txth_base').val() + "/academico/matriculacion/exportpdf?pdf=1&ron_id=" + ron_id;
}

function sendEmail() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/matriculacion/sendemail";
    arrParams.asunto = $('#txt_issue').val();
    arrParams.mensaje = $('#txt_message').val();
    arrParams.correo = $('#frm_per_correo').val();
    /* console.log(arrParams); */
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function cargarDocumento() {
    var arrParams = new Object();
    var link      = $('#txth_base').val() + "/academico/matriculacion/registropago";
    
    arrParams.periodo       = $('#txt_periodo_academico').val();
    arrParams.modalidad     = $('#txt_modalidad').val();
    arrParams.per_id        = $('#txth_per').val();
    arrParams.formapago     = $('#cmb_formapago').val();
    arrParams.valor         = $('#txt_valor').val();
    arrParams.observacion   = $('#txt_observa').val();
    arrParams.procesar_pago = true;
    arrParams.fechapago     = $('#txt_fechapago').val();
    //arrParams.documento     = $('#txth_pago_documento').val();
    arrParams.documento       = $('#txth_pago_documento2').val() + "." + $('#txth_pago_documento').val().split('.').pop();
    arrParams.referencia    = $('#txt_referencia').val();
    arrParams.banco         = $('#cmb_banco').val(); 

    if (arrParams.formapago != 1) {
        arrParams.procesar_file = true;

        if(arrParams.fechapago == ''){
            var mensaje = {wtmessage: "Fecha Pago : El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
            return false;
        }
        if (arrParams.documento.length == 0) {
            var mensaje = {wtmessage: "Adjuntar Documento  : El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
            return false;
        }
        if(arrParams.referencia == ''){
            var mensaje = {wtmessage: "Referencia : El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
            return false;
        }
        if(arrParams.banco == 0){
            var mensaje = {wtmessage: "Institucion Bancaria : El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
            return false;
        }
        if( !$('#checkAcepta').is(":checked") ){
            var mensaje = {wtmessage: "Debe aceptar las condiciones y terminos", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
            return false;
        }

    }//if
    
    arrParams.pla_id  = $('#frm_pla_id').val();
    arrParams.pes_id  = $('#frm_pes_id').val();
    arrParams.per_id  = $('#frm_per_id').val();

    if($('#cmb_formapago').val() != 1 ){
        arrParams.token = 0;

        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);

            if(response.status == 'OK'){
                setTimeout(function () {
                     var params = (arrParams.per_id == 0) ? "" : "?per_id=" + base64_encode(arrParams.per_id)
                     parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/registro" + params;
                }, 2000);
            }
        }, true);
    }else{
        //Si es por STRIPE realizo las verificaciones del caso
        try{

            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    console.log(result);

                    var mensaje = {wtmessage: '<p>'+result.error.message+'</p>', title: "Error"};
                    showAlert("NO_OK", "error", mensaje);
                    return false;
                } else {
                    arrParams.token = result.token.id;
                    requestHttpAjax(link, arrParams, function (response) {
                        showAlert(response.status, response.label, response.message);

                        if(response.status == 'OK'){
                            setTimeout(function () {
                                 var params = (arrParams.per_id == 0) ? "" : "?per_id=" + base64_encode(arrParams.per_id)
                                 parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/registro" + params;
                            }, 2000);
                        }
                    }, true);

                }//else
            });//stripe
        }catch(err){
            var mensaje = {wtmessage: err+" ///catch", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
            return false;
        }
    }//else

    
}//function cargarDocumento

function estadoPago(id, state) {
    var link = $('#txth_base').val() + "/academico/matriculacion/updateestadopago";
    var arrParams = new Object();
    arrParams.id = id;
    arrParams.state = state;
    /* console.log(arrParams); */
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            arrParams2.pla_periodo_academico = $("#cmb_per_academico").val();
            arrParams2.mod_id = $("#cmb_modalidad").val();
            /* console.log(arrParams2); */
            $("#grid_pagos_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}

function descargarPago(rpm_id) {
    /* console.log("Entra a descargar", rpm_id); */
    window.location.href = $('#txth_base').val() + "/academico/matriculacion/descargarpago?rpm_id=" + rpm_id;
}

function generar() {
    var link = $('#txth_base').val() + "/academico/matriculacion/updatepagoregistro";
    var arrParams = new Object();
    arrParams.id_rpm = $('#frm_rpm_id').val();
    arrParams.id_ron = $('#frm_ron_id').val();
    arrParams.file = $('#txth_up_hoja2').val() + "." + $('#txth_up_hoja').val().split('.').pop();
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            //parent.searchModulesList("boxgrid", "grid_listadoregistrados_list");
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/academico/matriculacion/list";
            }, 3000);
        }
    }, true);
}


function continuarRegistro(){
    var link = $('#txth_base').val() + "/academico/matriculacion/registrodetalle";

    var arrParams = new Object();

    var selecteds = '';
    var unselecteds = '';

    $('#grid_registro_list input[type=checkbox]').each(function () {
        if (this.checked) {
            selecteds += $(this).val() + ',';
        }else{
            unselecteds += $(this).val() + ',';
        }               
    });

    arrParams.seleccionados = selecteds.slice(0,-1);
    arrParams.noseleccionados = unselecteds.slice(0,-1);
    arrParams.per_id = $('#frm_per_id').val();
    // console.log(arrParams);

    if (selecteds != '') {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                // showAlert(response.status, response.label, response.message);
                //alert("completado");
                // console.log(response);
                window.location.href = $('#txth_base').val() + "/academico/matriculacion/registrodetalle";
                
            }, true);
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Selecciona: Debe seleccionar al menos una materia.', "title": 'Error'});
    }
}

function showRegisterSubject(){
    $('#grid_registro_list input[type=checkbox]').each(function() {
        if (!this.checked) {
            $("#register_subject_btn").show();
        }
    });
}

function actualizarGridAbePeriodoAcademicoCreate() {
        var planificacion = $('#cmb_planificacion option:selected').val();
        var admitido = $('#txt_buscarDataCreate').val();
         if (!$(".blockUI").length) {
            showLoadingPopup();
            $('#Tbg_Abe_listado_create').PbGridView('applyFilterData', {'planificacion':planificacion,'admitido': admitido});
            setTimeout(hideLoadingPopup, 2000);
         }
}