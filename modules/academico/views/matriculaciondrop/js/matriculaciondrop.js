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

    $('#btn_registro').click(function () {
        registerSubject();
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



//function searchModulesList(idbox, idgrid) {
//    var arrParams2 = new Object();
//    arrParams2.PBgetFilter = true;
//    arrParams2.search = $("#" + idbox).val();
//    arrParams2.periodo = ($("#cmb_per_acad").val() > 0) ? ($("#cmb_per_acad option:selected").text()) : null;
//    arrParams2.carrera = ($("#cmb_carrera").val() > 0) ? ($("#cmb_carrera option:selected").text()) : null;
//    arrParams2.mod_id = ($("#cmb_mod").val() > 0) ? ($("#cmb_mod").val()) : null;
//    arrParams2.estado = ($("#cmb_status").val() > -1) ? ($("#cmb_status").val()) : null;
//    $("#" + idgrid).PbGridView("applyFilterData", arrParams2);
//}








function registro() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/matriculacion/registro";
    var materias = new Array();
    var codes = new Array();
    var credits = new Array();
    var costs = new Array();
    var num_min = $('#frm_num_min').val();
    var num_max = $('#frm_num_max').val();
    var contador = 0;
    $('#grid_registro_list input[type=checkbox]').each(function() {
        if (this.checked) {
            materias[contador] = $(this).val();
            codes[contador] = $(this).attr('name');
            credits[contador] = $(this).parent().prev().prev().text();
            costs[contador] = $(this).parent().prev().text();
            contador += 1;
        }
    });
    var message = {
        "wtmessage": objLang.You_must_choose_at_least_a_number_or_subjects_,
        "title": objLang.Error
    }
    if (contador < num_min) {
        message.wtmessage = message.wtmessage + num_min;
	 showAlert('NO_OK', 'error', {"wtmessage": "Debe Elegir un minimo de 3 asignaturas para el enrolamiento", "title": 'Information'});            
       // showAlert("NO_OK", "Error", message);
    } else if (contador > num_max) {
        message.wtmessage = message.wtmessage + num_max;
        showAlert("NO_OK", "Error", message);
    } else {
        arrParams.pes_id = $('#frm_pes_id').val();
        arrParams.modalidad = $('#frm_modalidad').val();
        arrParams.carrera = $('#frm_carrera').val();
        arrParams.pdf = 1;
        arrParams.codes = codes;
        arrParams.credits = credits;
        arrParams.costs = costs;
        arrParams.materias = materias;
        /* console.log(arrParams); */

        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/index";
            }, 2000);

        }, true);
    }
}

function exportPDF() {
    var ron_id = $('#frm_ron_id').val();
    /* console.log(ron_id); */
    window.location.href = $('#txth_base').val() + "/academico/matriculacion/exportpdf?pdf=1&ron_id=" + ron_id;
}


function cargarDocumento() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/matriculacion/registropago";
    arrParams.procesar_file = true;
    arrParams.archivo = $('#txth_pago_documento2').val() + "." + $('#txth_pago_documento').val().split('.').pop();
    arrParams.pla_id = $('#frm_pla_id').val();
    arrParams.pes_id = $('#frm_pes_id').val();
    /* console.log(arrParams); */
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            /* console.log(response); */
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/matriculacion/index";
                }, 3000);
            }
        }, true);
    }
}

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
    //arrParams.file = $('#txth_up_hoja2').val() + "." + $('#txth_up_hoja').val().split('.').pop();
    arrParams.observacion = $('#txt_observacion').val();
    arrParams.estado = $('#cmb_aprobar').val();
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            //parent.searchModulesList("boxgrid", "grid_listadoregistrados_list");
            showAlert(response.status, response.label, response.message);
        }
    }, true);
}

function registerSubject() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/matriculacion/registro";
    var materias = new Array();
    var codes = new Array();
    var credits = new Array();
    var costs = new Array();
    var contador = 0;
    $('#grid_registro_list input.byregister[type=checkbox]').each(function() {
        if (this.checked) {
            materias[contador] = $(this).val();
            codes[contador] = $(this).attr('name');
            credits[contador] = $(this).parent().prev().prev().prev().text();
            costs[contador] = $(this).parent().prev().prev().text();
            contador += 1;
        }
    });
    var message = {
        "wtmessage": objLang.You_must_choose_at_least_one,
        "title": objLang.Error
    }
    if (contador == 0) {
        message.wtmessage = message.wtmessage;
        showAlert("NO_OK", "Error", message);
        return;
    }
    arrParams.pes_id = $('#frm_pes_id').val();
    arrParams.ron_id = $('#frm_ron_id').val();
    arrParams.registerSubject = 1;
    arrParams.modalidad = $('#frm_modalidad').val();
    arrParams.carrera = $('#frm_carrera').val();
    arrParams.pdf = 1;
    arrParams.codes = codes;
    arrParams.credits = credits;
    arrParams.costs = costs;
    arrParams.materias = materias;

    /* console.log(arrParams); */

    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        setTimeout(function() {
            parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/index";
        }, 2000);

    }, true);
}

function cancelSubject() {
    var ron_id   = $('#frm_ron_id').val();
    var link     = $('#txth_base').val() + "/academico/matriculacion/anularregistro?ron_id=" + ron_id;
    var codes    = "";
    var contador = 0;

    $('#grid_registro_list input.byremove[type=checkbox]').each(function() {
        if (this.checked) {
            codes += $(this).attr('name') + ";";
            contador += 1;
        }
    });
    //alert(contador);
    var message = {
        "wtmessage": objLang.You_must_choose_at_least_one_Subject_to_Cancel_Registration,
        "title": objLang.Error
    }
    if (contador < 0 && contador >= 4) {
        message.wtmessage = message.wtmessage;
        showAlert("NO_OK", "Error", message);
        return;
    }
    if (validCancel() == false) {
        message.wtmessage = objLang.The_number_of_subject_that_you_can_cancel_is_ + $('#frm_min_cancel').val();
        showAlert("NO_OK", "Error", message);
        return;
    }
    link += "&pdf=1&cancelSubject=1&codes=" + codes;

    window.location.href = link;
}//function cancelSubject

function validCancel() {
    var minCancel = $('#frm_min_cancel').val();
    var contador = 0;
    var items = 0;
    $('#grid_registro_list input.byremove[type=checkbox]').each(function() {
        if (this.checked) {
            contador += 1;
        }
        if ($(this).is(':disabled')) {
            items -= 1;
        }
        items += 1;
    });
    if (contador == items) return true;
    if ((items - contador) >= minCancel) return true;
    return false;
}

function searchModulesLista(idbox, idgrid) {
    var arrParams2 = new Object();
    arrParams2.PBgetFilter = true;
    arrParams2.search = $("#" + idbox).val();
    arrParams2.periodo = ($("#cmb_per_acad").val() > 0) ? ($("#cmb_per_acad option:selected").text()) : null;
    arrParams2.carrera = ($("#cmb_carrera").val() > 0) ? ($("#cmb_carrera option:selected").text()) : null;
    arrParams2.mod_id = ($("#cmb_mod").val() > 0) ? ($("#cmb_mod").val()) : null;
    arrParams2.estado = ($("#cmb_status").val() > -1) ? ($("#cmb_status").val()) : null;
    $("#" + idgrid).PbGridView("applyFilterData", arrParams2);
}




//// NEW FUNCTIONS


//$('#cmb_carrera, #cmb_per_acad, #cmb_mod, #cmb_status, #txt_fecha_inilist').change(function() {
//    searchModulesLista('boxgrid', 'grid_listadoregistrados_list');
// });
 


// function searchModulesLista(idbox, idgrid) {
//    var arrParams2 = new Object();
//    arrParams2.PBgetFilter = true;
//    arrParams2.search = $("#" + idbox).val();
//    arrParams2.periodo = ($("#cmb_per_acad").val() > 0) ? ($("#cmb_per_acad option:selected").text()) : null;
//    arrParams2.carrera = ($("#cmb_carrera").val() > 0) ? ($("#cmb_carrera option:selected").text()) : null;
//    arrParams2.mod_id = ($("#cmb_mod").val() > 0) ? ($("#cmb_mod").val()) : null;
//    arrParams2.estado = ($("#cmb_status").val() > -1) ? ($("#cmb_status").val()) : null;
//    arrParams2.fechaini = ($("#txt_fecha_inilist").val());
//   arrParams2.fechafin = ($("#txt_fecha_finlist").val());  
//    $("#" + idgrid).PbGridView("applyFilterData", arrParams2);
//}




$('#btn_buscarData').click(function () {
    actualizarGridlist();
});


function actualizarGridlist() {
    var search = $('#txt_buscarData').val();
    var periodo = ($("#cmb_per_acad").val() > 0) ? ($("#cmb_per_acad option:selected").text()) : null;
    var carrera = ($("#cmb_carrera").val() > 0) ? ($("#cmb_carrera option:selected").text()) : null;
    var modalidad = ($("#cmb_mod").val() > 0) ? ($("#cmb_mod").val()) : null;
    var estado = ($("#cmb_status").val() > -1) ? ($("#cmb_status").val()) : null;
    var fechaini = $('#txt_fecha_inilist').val();
    var fechafin = $('#txt_fecha_finlist').val();
    // var fechaini = $('#txt_fecha_inilist option:selected').val();
    //var fechafin = $('#txt_fecha_finlist option:selected').val();
         if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#grid_listadoregistrados_list').PbGridView('applyFilterData', {'search': search, 'carrera':carrera,'periodo':periodo,'modalidad':modalidad,'estado':estado,'fechaini':fechaini, 'fechafin':fechafin});
        setTimeout(hideLoadingPopup, 2000);
    }
}

 

 

function exportExcellista() {
    var search = $('#txt_buscarData').val();
    var periodo = ($("#cmb_per_acad").val() > 0) ? ($("#cmb_per_acad option:selected").text()) : '';
    var carrera = ($("#cmb_carrera").val() > 0) ? ($("#cmb_carrera option:selected").text()) : '';
    var modalidad = ($("#cmb_mod").val() > 0) ? ($("#cmb_mod").val()) : '';
    var estado = ($("#cmb_status").val() > -1) ? ($("#cmb_status").val()) : '';
    var fechaini = $('#txt_fecha_inilist').val();
    var fechafin = $('#txt_fecha_finlist').val();
    window.location.href = $('#txth_base').val() + "/academico/matriculacion/exportoexcel?search=" + search + "&carrera=" + carrera + "&periodo=" + periodo + "&modalidad=" + modalidad + "&estado=" + estado + "&fechaini=" + fechaini + "&fechafin=" + fechafin;
}

function exportExcellist() {
    var search = $('#txt_buscarData').val();
    var periodo = ($("#cmb_per_acad").val() > 0) ? ($("#cmb_per_acad option:selected").text()) : '';
    var carrera = ($("#cmb_carrera").val() > 0) ? ($("#cmb_carrera option:selected").text()) : '';
    var modalidad = ($("#cmb_mod").val() > 0) ? ($("#cmb_mod").val()) : '';
    var estado = ($("#cmb_status").val() > -1) ? ($("#cmb_status").val()) : '';
    var fechaini = $('#txt_fecha_inilist').val();
    var fechafin = $('#txt_fecha_finlist').val();    
    window.location.href = $('#txth_base').val() + "/academico/matriculacion/exportoexcel?search=" + search + "&carrera=" + carrera + "&periodo=" + periodo + "&modalidad=" + modalidad + "&estado=" + estado + "&fechaini=" + fechaini + "&fechafin=" + fechafin;
}
