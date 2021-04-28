$(document).ready(function() {
    $('#btn_guardareducativa').click(function () {
        cargarUsuario();
    });

    $('#btn_buscarData_estregsitro').click(function () {
        actualizarGridEstregistro();
    });
   
    $('#btn_guardarcurso').click(function() {
        cargarDocumento();
    });  

    $('#btn_buscarCurso').click(function () {
        actualizarGridCureducativa();
    });
    
    $('#btn_newcurso').click(function () {
        savecurso();
    });

    $('#btn_editcurso').click(function () {
        editcurso();
    });

    $('#btn_buscarUnidad').click(function () {
        actualizarGridUnidad();
    });

    $('#btn_newunidad').click(function () {
        saveunidad();
    });

    $('#btn_editunidad').click(function () {
        editunidad();
    });

    $('#btn_guardarunidad').click(function() {
        cargarUnidad();
    });    

    $('#cmb_unidad_dises').change(function () {
        var link = $('#txth_base').val() + "/academico/distributivo/listarestudiantespago";
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidades", "Todos");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.uaca_id = $('#cmb_unidad_dises').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getasignatura = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.asignatura, "cmb_asignaturaes", "Todos");
                        }
                    }, true);
                }
            }
        }, true);
    });
    
    $('#cmb_modalidades').change(function () {
        var link = $('#txth_base').val() + "/academico/distributivo/listarestudiantespago";
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidad_dises').val();
        arrParams.moda_id = $(this).val();
        arrParams.getasignatura = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignatura, "cmb_asignaturaes", "Todos");
            }
        }, true);
    });

    $('#cmb_periodounidad').change(function() {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/indexunidad";
        var arrParams = new Object();
        arrParams.codcurso = $(this).val();
        arrParams.getcurso = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.periodo, "cmb_curso", "Todos");
            }
        }, true);
    });

    $('#cmb_periodonewunidad').change(function() {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/newunidad";
        var arrParams = new Object();
        arrParams.codcursounidad = $(this).val();
        arrParams.getcursounidad = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.periodounidad, "cmb_cursounidad", "Seleccionar");
            }
        }, true);
    });

    $('#cmb_periodoeditunidad').change(function() {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/editunidad";
        var arrParams = new Object();
        arrParams.codcursounidades = $(this).val();
        arrParams.getcursounidades = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.periodounidades, "cmb_cursoeditunidad", "Seleccionar");
            }
        }, true);
    });

    $('#cmb_periodoesasi').change(function() {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/asignarestudiantecurso";
        var arrParams = new Object();
        arrParams.codcursoasi = $(this).val();
        arrParams.getcursoasi = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.periodoasi, "cmb_cursoasi", "Todos");
            }
        }, true);
    });
});

function cargarUsuario() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/cargarusuario";
    arrParams.procesar_file = true;
    arrParams.emp_id = $('#cmb_empresa option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_educativa2').val() + "." + $('#txth_doc_adj_educativa').val().split('.').pop();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/cargarusuario";
            }, 5000);
        }, true);
    }
}

function cargarDocumento() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/upload";
    arrParams.procesar_file = true;
    arrParams.archivo = $('#txth_doc_adj_educativacu2').val() + "." + $('#txth_doc_adj_educativacu').val().split('.').pop();
    arrParams.paca_id = $("#cmb_per_aca").val();    
    if ($('#cmb_per_aca option:selected').val() != 0) {     
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {      
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/index";
                }, 5000);  
        }, true);
    }
  } else {
    showAlert('NO_OK', 'error', {"wtmessage": 'Periodo Académico: El campo no debe estar vacío.', "title": 'Error'});
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

function actualizarGridEstregistro() {
    var search = $('#txt_buscarDataest').val();
    var profesor = $('#txt_buscarprofesor').val();
    var unidad = $('#cmb_unidad_dises option:selected').val();
    var modalidad = $('#cmb_modalidades option:selected').val();
    var periodo = $('#cmb_periodoes option:selected').val();
    var asignatura = $('#cmb_asignaturaes option:selected').val();
    var estado = $('#cmb_estadoes option:selected').val();
    //var jornada = $('#cmb_jornadaes option:selected').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Tbg_Registro_educativa').PbGridView('applyFilterData', {'search': search, 'profesor': profesor, 'unidad': unidad, 'modalidad': modalidad, 'periodo': periodo, 'asignatura': asignatura, 'estado': estado/*, 'jornada': jornada*/});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelEduregistro() {
    var search = $('#txt_buscarDataest').val();
    var profesor = $('#txt_buscarprofesor').val();
    var unidad = $('#cmb_unidad_dises option:selected').val();
    var modalidad = $('#cmb_modalidades option:selected').val();
    var periodo = $('#cmb_periodoes option:selected').val();
    var asignatura = $('#cmb_asignaturaes option:selected').val();
    var estado = $('#cmb_estadoes option:selected').val();
    //var jornada = $('#cmb_jornadaes option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/expexcelestregistro?search=" + search + "&profesor=" + profesor + "&unidad=" + unidad + "&modalidad=" + modalidad + "&periodo=" + periodo + "&asignatura=" + asignatura + "&estado=" + estado /*+ "&jornada=" + jornada*/;
}

function exportPdfEduregistro() {
    var search = $('#txt_buscarDataest').val();
    var profesor = $('#txt_buscarprofesor').val();
    var unidad = $('#cmb_unidad_dises option:selected').val();
    var modalidad = $('#cmb_modalidades option:selected').val();
    var periodo = $('#cmb_periodoes option:selected').val();
    var asignatura = $('#cmb_asignaturaes option:selected').val();
    var estado = $('#cmb_estadoes option:selected').val();
    //var jornada = $('#cmb_jornadaes option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/exppdfestregistro?pdf=1&search=" + search + "&profesor=" + profesor + "&unidad=" + unidad + "&modalidad=" + modalidad + "&periodo=" + periodo + "&asignatura=" + asignatura + "&estado=" + estado/*+ "&jornada=" + jornada*/;
}

function actualizarGridCureducativa() {
    var search = $('#txt_buscarDataCurso').val();
    var periodo =  $('#cmb_periodo option:selected').val();
    var asignatura = $('#cmb_asignatura option:selected').val();  
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Pbcurso').PbGridView('applyFilterData', {'search': search, 'periodo': periodo, 'asignatura': asignatura});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelcurso() {
    var search = $('#txt_buscarDataCurso').val();
    var periodo =  $('#cmb_periodo option:selected').val();
    var asignatura = $('#cmb_asignatura option:selected').val(); 
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/expexcelestcurso?search=" + search + "&periodo=" + periodo + "&asignatura=" + asignatura;
}

function exportPdfcurso() {
    var search = $('#txt_buscarDataCurso').val();
    var periodo =  $('#cmb_periodo option:selected').val();
    var asignatura = $('#cmb_asignatura option:selected').val(); 
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/exppdfestcurso?pdf=1&search=" + search + "&periodo=" + periodo + "&asignatura=" + asignatura;
}

function savecurso() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/savecurso";
    var arrParams = new Object();
    arrParams.periodo = $('#cmb_periodonew option:selected').val();
    //arrParams.materia = $('#cmb_asignaturanew option:selected').val();
    arrParams.codigoaula = $('#txt_codigonew').val();
    arrParams.nombreaula = $('#txt_aulanew').val();
    if ($('#cmb_periodonew option:selected').val() != 0) {           
     // if ($('#cmb_asignaturanew option:selected').val() != 0) { 
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                if (response.status == "OK") {
                    setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/index";
                    }, 3000);
                }
            }, true);
        }
    /*} else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Asignatura: El campo no debe estar vacío.', "title": 'Error'});
      } */
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Código Aula: El campo no debe estar vacío.', "title": 'Error'});
     }  
}

function editcurso() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/editcurso";
    var arrParams = new Object();
    arrParams.ceduid = $('#txth_cursoid').val();
    arrParams.periodo = $('#cmb_periodoedit option:selected').val();
    //arrParams.materia = $('#cmb_asignaturaedit option:selected').val();
    arrParams.codigoaula = $('#txt_codigoedit').val();
    arrParams.nombreaula = $('#txt_aulaedit').val();
    if ($('#cmb_periodoedit option:selected').val() != 0) {           
     // if ($('#cmb_asignaturaedit option:selected').val() != 0) { 
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                if (response.status == "OK") {
                    setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/index";
                    }, 3000);
                }
            }, true);
        }
    /*} else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Asignatura: El campo no debe estar vacío.', "title": 'Error'});
      } */
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Código Aula: El campo no debe estar vacío.', "title": 'Error'});
     }  
}

function eliminarcurso(id) {   
    //alert ('id eliminar' + id);
    var mensj = "¿Seguro desea eliminar el curso?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'accion';
    var params = new Array(id, 0);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}

function accion(id, tmp) {
    //alert ('id accion' + id);
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/deletecurso";
    var arrParams = new Object();
    arrParams.cur_id = id;    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/index";
                }, 3000);
            }
        }, true);
    }
}

function actualizarGridUnidad() {
    var search = $('#txt_buscarDataunidad').val();
    var periodo =  $('#cmb_periodounidad option:selected').val();
    var curso =  $('#cmb_curso option:selected').val();    
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Pbunidad').PbGridView('applyFilterData', {'search': search, 'periodo': periodo, 'curso': curso});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelunidad() {
    var search = $('#txt_buscarDataunidad').val();
    var periodo =  $('#cmb_periodounidad option:selected').val();
    var curso = $('#cmb_curso option:selected').val(); 
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/expexcelunidad?search=" + search + "&periodo=" + periodo + "&curso=" + curso;
}

function exportPdfunidad() {
    var search = $('#txt_buscarDataunidad').val();
    var periodo =  $('#cmb_periodounidad option:selected').val();
    var curso = $('#cmb_curso option:selected').val(); 
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/exppdfunidad?pdf=1&search=" + search + "&periodo=" + periodo + "&curso=" + curso;
}

function saveunidad() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/saveunidad";
    var arrParams = new Object();
    arrParams.curso = $('#cmb_cursounidad option:selected').val();
    arrParams.codigounidad = $('#txt_codigonewunidad').val();
    arrParams.nombreunidad = $('#txt_descripcionnewunidad').val();
    if ($('#cmb_cursounidad option:selected').val() != 0) {           
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                if (response.status == "OK") {
                    setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/indexunidad";
                    }, 3000);
                }
            }, true);
        }    
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Curso: El campo no debe estar vacío.', "title": 'Error'});
     }  
}

function editunidad() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/updateunidad";
    var arrParams = new Object();
    arrParams.ceuni_id = $('#txth_unidadid').val();
    arrParams.periodounidad = $('#cmb_periodoeditunidad option:selected').val();
    arrParams.cursodounidad = $('#cmb_cursoeditunidad option:selected').val();
    arrParams.codigounidad = $('#txt_codigoeditunidad').val();
    arrParams.nombreunidad = $('#txt_descripcioneditunidad').val();
    if ($('#cmb_cursoeditunidad option:selected').val() != 0) { 
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                if (response.status == "OK") {
                    setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/indexunidad";
                    }, 3000);
                }
            }, true);
        }    
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Curso: El campo no debe estar vacío.', "title": 'Error'});
     }  
}

function eliminarunidad(id) {   
    //alert ('id eliminar' + id);
    var mensj = "¿Seguro desea eliminar la unidad?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'accioneli';
    var params = new Array(id, 0);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}

function accioneli(id, tmp) {
    //alert ('id accion' + id);
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/deleteunidad";
    var arrParams = new Object();
    arrParams.ceuni_id = id;    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/indexunidad";
                }, 3000);
            }
        }, true);
    }
}

function cargarUnidad() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/uploadunidad";
    arrParams.procesar_file = true;
    arrParams.archivo = $('#txth_doc_adj_educativaun2').val() + "." + $('#txth_doc_adj_educativaun').val().split('.').pop();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {      
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/indexunidad";
                }, 5000);  
        }, true);
    }  

}
