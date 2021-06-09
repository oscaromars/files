$(document).ready(function() {

    if(!alertify.errorAlert){
      //define a new errorAlert base on alert
      alertify.dialog('errorAlert',function factory(){
        return{
                build:function(){
                    var errorHeader = '<span class="success-modalPB" '
                    +    'style="vertical-align:middle;color:#e10000;">'
                    + '</span> Application Error';
                    this.setHeader(errorHeader);
                }
            };
        },true,'alert');
    }

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

    $('#btn_buscarUsuario').click(function () {
        actualizarGridUsereducativa();
    });

    $('#btn_buscarDistbedu').click(function () {
        actualizarGridDisteducativa();
    });
    
    $('#btn_newcurso').click(function () {
        savecurso();
    });

    $('#btn_newusuario').click(function () {
        saveusuario();
    });

    $('#btn_editcurso').click(function () {
        editcurso();
    });

    $('#btn_editusuario').click(function () {
        editarusuario();
    });

    $('#btn_buscarUnidad').click(function () {
        actualizarGridUnidad();
    });

    $('#btn_newunidad').click(function () {
        saveunidad();
    });

    $('#btn_buscarData_dise').click(function () {
        actualizarGridAsignaDistributivo();
    });

    $('#btn_editunidad').click(function () {
        editunidad();
    });

    $('#btn_guardarunidad').click(function() {
        cargarUnidad();
    }); 
    
    $('#btn_buscarData_estasignar').click(function () {
        actualizarGridAsignaCurso();
    });

    $('#btnAsignacurso').click(function () {
        asignarCurso();
    });

    $('#btnHabilitacurso').click(function () {
        asignarBloqueo();
    });

    $('#btnAsignadist').click(function () {
        savedistributivo();
    });

    // Activar asignación de estudiantes
    $('#insert_btn').click(function() {
        insertarEstudiantesConfirm();
    });

    $('#btn_buscarData_educativa').click(function() {
        actualizarGridEducativa();
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

    $('#cmb_periododistb').change(function() {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/distributivoindex";
        var arrParams = new Object();
        arrParams.codcursos = $(this).val();
        arrParams.getcursos = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.periodos, "cmb_cursodistb", "Todos");
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

    $('#cmb_periodoes').change(function() {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/listarestudianteregistro";
        var arrParams = new Object();
        arrParams.codcursoreg = $(this).val();
        arrParams.getcursoreg = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.periodoreg, "cmb_cursoreg", "Todos");
                var arrParams = new Object();
                if (data.periodoreg.length > 0) {
                    arrParams.paca_id = $('#cmb_periodoes').val(); 
                    arrParams.aulareg = $('#cmb_cursoreg').val();                  
                    arrParams.getunidadreg = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.unidadreg, "cmb_uniddades", "Todos");
                        }
                    }, true);
                }
            }
        }, true);
    });

    $('#cmb_cursoreg').change(function() {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/listarestudianteregistro";
        var arrParams = new Object();
        arrParams.aulareg = $('#cmb_cursoreg').val(); ;
        arrParams.getunidadreg = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.unidadreg, "cmb_uniddades", "Todos");
            }
        }, true);
    });

    $('#cmb_modalidadesasi').change(function () {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/asignarestudiantecurso";
        var arrParams = new Object();
        arrParams.uaca_ids = $('#cmb_unidad_disesasi').val();
        arrParams.moda_ids = $(this).val();
        arrParams.getasignaturasi = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignaturasi, "cmb_asignaturaesasi", "Todos");
            }
        }, true);
    });

    $('#cmb_modalidad_dise').change(function () {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/asignardistributivo";
        var arrParams = new Object();
        arrParams.uaca_ides = $('#cmb_unidad_dise').val();
        arrParams.moda_ides = $(this).val();
        arrParams.getasignaturasig = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignaturasig, "cmb_materia_dise", "Todos");
            }
        }, true);
    });

    $('#cmb_materia_dise').change(function () {
        var link = $('#txth_base').val() + "/academico/usuarioeducativa/asignardistributivo";
        var arrParams = new Object();
        arrParams.uaca_isd = $('#cmb_unidad_dise').val();
        arrParams.mod_isd = $('#cmb_modalidad_dise').val();
        arrParams.getjornada = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.jornada, "cmb_jornada_dise", "Todos");
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
            //Ruta es la direccion que deseemos que el boton nos dirija al momento de dar click
            var ruta = [$('#txth_base').val() + "/academico/usuarioeducativa/usuarioindex"];
            //acciones son las variables que debemos enviar para dibujar el o los botones en el modal
            var acciones = [{ id      : 'reloadpage',     //id que tendra el boton
                              class   : 'btn btn-primary',//La clase para poderle dar un estilo al boton 
                              value   : 'Regresar', //Este es el texto que tendra el boton//objLang.Accept, 
                              callback: 'gotoPage', //funcion que debe ejecutar el boton
                              paramCallback : ruta, //variable a ser llamada por la funcion anterior ej gotoPage(ruta)
                           }]; 
            var cancelar = [{ callback: 'reloadPage', //funcion que debe ejecutar el boton
                              //paramCallback : ruta, //variable a ser llamada por la funcion anterior ej gotoPage(ruta)
                           }];
            //Agregamos a nuestra variables message nuestras acciones
            response.message.acciones    = acciones;
            response.message.closeaction = cancelar;
            //Dejamos que la funcion showAlert dibuje el modal
            showAlert(response.status, response.label, response.message);
            /*
            alertify.alert(response.message.wtmessage, function(){
                alertify.message('OK');
            }).set({title:response.message.title});
            */
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
                //Aqui va el cambio gap 
                /*
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                        window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/index";
                    }, 5000); 
                */

                //Ruta es la direccion que deseemos que el boton nos dirija al momento de dar click
                var ruta = [$('#txth_base').val() + "/academico/usuarioeducativa/index"];
                //acciones son las variables que debemos enviar para dibujar el o los botones en el modal
                var acciones = [{ id      : 'reloadpage',     //id que tendra el boton
                                  class   : 'btn btn-primary',//La clase para poderle dar un estilo al boton 
                                  value   : 'Regresar', //Este es el texto que tendra el boton//objLang.Accept, 
                                  callback: 'gotoPage', //funcion que debe ejecutar el boton
                                  paramCallback : ruta, //variable a ser llamada por la funcion anterior ej gotoPage(ruta)
                               }]; 
                var cancelar = [{ callback: 'reloadPage', //funcion que debe ejecutar el boton
                                  //paramCallback : ruta, //variable a ser llamada por la funcion anterior ej gotoPage(ruta)
                               }];
                //Agregamos a nuestra variables message nuestras acciones
                response.message.acciones    = acciones;
                response.message.closeaction = cancelar;
                //Dejamos que la funcion showAlert dibuje el modal
                showAlert(response.status, response.label, response.message); 
            }, true);
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Periodo Académico: El campo no debe estar vacío.', "title": 'Error'});
    }//else
}//function cargarDocumento

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
    //var asignatura = $('#cmb_asignaturaes option:selected').val();
    var estado = $('#cmb_estadoes option:selected').val();
    var curso = $('#cmb_cursoreg option:selected').val();
    var unidadedu = $('#cmb_uniddades option:selected').val();
    //var jornada = $('#cmb_jornadaes option:selected').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Tbg_Registro_educativa').PbGridView('applyFilterData', {'search': search, 'profesor': profesor, 'unidad': unidad, 'modalidad': modalidad, 'periodo': periodo, /*'asignatura': asignatura,*/ 'estado': estado, 'curso': curso, 'unidadedu': unidadedu /*, 'jornada': jornada*/});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelEduregistro() {
    var search = $('#txt_buscarDataest').val();
    var profesor = $('#txt_buscarprofesor').val();
    var unidad = $('#cmb_unidad_dises option:selected').val();
    var modalidad = $('#cmb_modalidades option:selected').val();
    var periodo = $('#cmb_periodoes option:selected').val();
    //var asignatura = $('#cmb_asignaturaes option:selected').val();
    var estado = $('#cmb_estadoes option:selected').val();
    var curso = $('#cmb_cursoreg option:selected').val();
    //var jornada = $('#cmb_jornadaes option:selected').val();
    var unidadedu = $('#cmb_uniddades option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/expexcelestregistro?search=" + search + "&profesor=" + profesor + "&unidad=" + unidad + "&modalidad=" + modalidad + "&periodo=" + periodo + /*"&asignatura=" + asignatura +*/ "&estado=" + estado + "&curso=" + curso , "&unidadedu="+ unidadedu /*+ "&jornada=" + jornada*/;
}

function exportPdfEduregistro() {
    var search = $('#txt_buscarDataest').val();
    var profesor = $('#txt_buscarprofesor').val();
    var unidad = $('#cmb_unidad_dises option:selected').val();
    var modalidad = $('#cmb_modalidades option:selected').val();
    var periodo = $('#cmb_periodoes option:selected').val();
    //var asignatura = $('#cmb_asignaturaes option:selected').val();
    var estado = $('#cmb_estadoes option:selected').val();
    var curso = $('#cmb_cursoreg option:selected').val();
    //var jornada = $('#cmb_jornadaes option:selected').val();
    var unidadedu = $('#cmb_uniddades option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/exppdfestregistro?pdf=1&search=" + search + "&profesor=" + profesor + "&unidad=" + unidad + "&modalidad=" + modalidad + "&periodo=" + periodo + /*"&asignatura=" + asignatura +*/ "&estado=" + estado + "&curso=" + curso, "&unidadedu="+ unidadedu/*+ "&jornada=" + jornada*/;
}

function actualizarGridDisteducativa() {
    var search = $('#txt_buscarDatadisted').val();
    var periodo =  $('#cmb_periododistb option:selected').val();
    var curso = $('#cmb_cursodistb option:selected').val();  
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Pbudistriutivoedu').PbGridView('applyFilterData', {'search': search, 'periodo': periodo, 'curso': curso});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function actualizarGridUsereducativa() {
    var search = $('#txt_buscarDataUsuario').val();   
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Pbusuarioedu').PbGridView('applyFilterData', {'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
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

function editarusuario() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/editarusuario";
    var arrParams = new Object();
    arrParams.uedu_id = $('#txth_uedu_id').val();
    arrParams.usuario = $('#txt_usuarioview').val();
    arrParams.nombre = $('#txt_nombreview').val();
    arrParams.apellido = $('#txt_apellidoview').val();
    arrParams.cedula = $('#txt_cedulaview').val();
    arrParams.matricula = $('#txt_matriculaview').val();
    arrParams.correo = $('#txt_correoview').val();  
  
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                if (response.status == "OK") {
                    setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/usuarioindex";
                    }, 3000);
                }
            }, true);
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
    var fechain = $('#txt_fecha_inidex').val();
    var fechafin = $('#txt_fecha_finidex').val();  
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Pbunidad').PbGridView('applyFilterData', {'search': search, 'periodo': periodo, 'curso': curso, 'fechain': fechain, 'fechafin': fechafin});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelunidad() {
    var search = $('#txt_buscarDataunidad').val();
    var periodo =  $('#cmb_periodounidad option:selected').val();
    var curso = $('#cmb_curso option:selected').val(); 
    var fechain = $('#txt_fecha_inidex').val();
    var fechafin = $('#txt_fecha_finidex').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/expexcelunidad?search=" + search + "&periodo=" + periodo + "&curso=" + curso + "&fechain=" + fechain + "&fechafin=" + fechafin;
}

function exportPdfunidad() {
    var search = $('#txt_buscarDataunidad').val();
    var periodo =  $('#cmb_periodounidad option:selected').val();
    var curso = $('#cmb_curso option:selected').val(); 
    var fechain = $('#txt_fecha_inidex').val();
    var fechafin = $('#txt_fecha_finidex').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/exppdfunidad?pdf=1&search=" + search + "&periodo=" + periodo + "&curso=" + curso + "&fechain=" + fechain + "&fechafin=" + fechafin;
}

function saveunidad() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/saveunidad";
    var arrParams = new Object();
    arrParams.curso = $('#cmb_cursounidad option:selected').val();
    arrParams.codigounidad = $('#txt_codigonewunidad').val();
    arrParams.nombreunidad = $('#txt_descripcionnewunidad').val();
    arrParams.fechainiciog = $('#txt_fecha_iniig').val();
    arrParams.fechafing = $('#txt_fecha_finig').val();
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
        showAlert('NO_OK', 'error', {"wtmessage": 'Aula: El campo no debe estar vacío.', "title": 'Error'});
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
    arrParams.fechainicioed = $('#txt_fecha_inied').val();
    arrParams.fechafined = $('#txt_fecha_fined').val();

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
        showAlert('NO_OK', 'error', {"wtmessage": 'Aula: El campo no debe estar vacío.', "title": 'Error'});
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
            /*
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/indexunidad";
                }, 5000);
            */

            var ruta = [$('#txth_base').val() + "/academico/usuarioeducativa/indexunidad"];
            //acciones son las variables que debemos enviar para dibujar el o los botones en el modal
            var acciones = [{ id      : 'reloadpage',     //id que tendra el boton
                              class   : 'btn btn-primary',//La clase para poderle dar un estilo al boton 
                              value   : 'Regresar', //Este es el texto que tendra el boton//objLang.Accept, 
                              callback: 'gotoPage', //funcion que debe ejecutar el boton
                              paramCallback : ruta, //variable a ser llamada por la funcion anterior ej gotoPage(ruta)
                           }]; 
            var cancelar = [{ callback: 'reloadPage', //funcion que debe ejecutar el boton
                              //paramCallback : ruta, //variable a ser llamada por la funcion anterior ej gotoPage(ruta)
                           }];
            //Agregamos a nuestra variables message nuestras acciones
            response.message.acciones    = acciones;
            response.message.closeaction = cancelar;
            //Dejamos que la funcion showAlert dibuje el modal
            showAlert(response.status, response.label, response.message); 
        }, true);
    }  

}

function actualizarGridAsignaCurso() {
    var profesor = $('#txt_buscarprofesorasi').val();
    var unidad =  $('#cmb_unidad_disesasi option:selected').val();
    var modalidad =  $('#cmb_modalidadesasi option:selected').val();
    var periodo =  $('#cmb_periodoesasi option:selected').val();
    //var asignatura = $('#cmb_asignatura option:selected').val();  
    var curso =  $('#cmb_cursoasi option:selected').val();
    var estado =  $('#cmb_estasi option:selected').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Tbg_AsignarCurso').PbGridView('applyFilterData', {'profesor': profesor, 'unidad': unidad, 'modalidad': modalidad, 'periodo': periodo/*, 'asignatura': asignatura*/ , 'curso': curso, 'estado': estado});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelEduasignar() {
    var profesor = $('#txt_buscarprofesorasi').val();
    var unidad =  $('#cmb_unidad_disesasi option:selected').val();
    var modalidad =  $('#cmb_modalidadesasi option:selected').val();
    var periodo =  $('#cmb_periodoesasi option:selected').val();
    var asignatura = $('#cmb_asignaturaesasi option:selected').val();  
    var curso =  $('#cmb_cursoasi option:selected').val(); 
    var estado =  $('#cmb_estasi option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/expexceleduasignar?profesor=" + profesor + "&unidad=" + unidad + "&modalidad=" + modalidad + "&periodo=" + periodo + "&curso=" + curso + "&asignatura=" + asignatura  + "&estado=" + estado;
}

function exportPdfEdurasignar() {
    var profesor = $('#txt_buscarprofesorasi').val();
    var unidad =  $('#cmb_unidad_disesasi option:selected').val();
    var modalidad =  $('#cmb_modalidadesasi option:selected').val();
    var periodo =  $('#cmb_periodoesasi option:selected').val();
    var asignatura = $('#cmb_asignaturaesasi option:selected').val();  
    var curso =  $('#cmb_cursoasi option:selected').val();
    var estado =  $('#cmb_estasi option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/exppdfeduasignar?pdf=1&profesor=" + profesor + "&unidad=" + unidad + "&modalidad=" + modalidad + "&periodo=" + periodo + "&curso=" + curso + "&asignatura=" + asignatura + "&estado=" + estado;
}

function asignarCurso() {        
       var link = $('#txth_base').val() + "/academico/usuarioeducativa/savestudiantescurso";
       var arrParams = new Object();
       arrParams.periodo = $('#cmb_periodoesasi').val();
       arrParams.curso = $('#cmb_cursoasi').val();
       var selected = '';
       var unselected = '';       
       $('#Tbg_AsignarCurso input[type=checkbox]').each(function () {
           if (this.checked) {
               selected += $(this).val() + ',';
           }else{
               unselected += $(this).val() + ',';
           }               
       });
           arrParams.asignado = selected.slice(0,-1);
           arrParams.noasignado = unselected.slice(0,-1);
       if ($('#cmb_cursoasi option:selected').val() != 0) {
          if (selected != '') {
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function (response) {
                    showAlert(response.status, response.label, response.message);
                    setTimeout(function () {
                        window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/asignarestudiantecurso";
                    }, 3000);
                }, true);
            }
         } else {
            showAlert('NO_OK', 'error', {"wtmessage": 'Selecciona: Debe seleccionar al menos un estudiante a asignar.', "title": 'Error'});
         } 
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Aula: El campo no debe estar vacío.', "title": 'Error'});
     }  
}

function editunidaded() {
    var ceuni_id = $('#txth_unidadid').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/editunidad?ceuni_id=" + ceuni_id;
}

function editcursoed() {
    var cedu_id = $('#txth_cursoid').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/edit?cedu_id=" + cedu_id;
}

function exportExcelusuarioedu() {
    var search = $('#txt_buscarDataUsuario').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/expexcelusuarioedu?search=" + search;
}

function exportPdfusuarioedu() {
    var search = $('#txt_buscarDataUsuario').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/exppdfusuarioedu?pdf=1&search=" + search;
}

function eliminarusuario(id) {   
    var mensj = "¿Seguro desea eliminar el usuario?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'accionus';
    var params = new Array(id, 0);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}

function accionus(id, tmp) {
    //alert ('id accion' + id);
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/deleteusuario";
    var arrParams = new Object();
    arrParams.uedu_id = id;    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/usuarioindex";
                }, 3000);
            }
        }, true);
    }
}

function editusuarioed() {
    var uedu_id = $('#txth_uedu_id').val();
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/editusuario?uedu_id=" + uedu_id;
}

function saveusuario() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/saveusuario";
    var arrParams = new Object();
    arrParams.usuario = $('#txt_usuarionew').val();
    arrParams.nombre = $('#txt_nombrenew').val();
    arrParams.apellido = $('#txt_apellidonew').val();
    arrParams.cedula = $('#txt_cedulanew').val();
    arrParams.matricula = $('#txt_matriculanew').val();
    arrParams.correo = $('#txt_correonew').val();

        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                if (response.status == "OK") {
                    setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/usuarioindex";
                    }, 3000);
                }
            }, true);
        }   
}

function asignarBloqueo() {        
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/savestudiantesbloqueo";
    var arrParams = new Object();    
    arrParams.curso = $('#cmb_cursoreg').val();
    var selecteds = '';
    var unselecteds = '';       
    $('#Tbg_Registro_educativa input[type=checkbox]').each(function () {
        if (this.checked) {
            selecteds += $(this).val() + ',';
        }else{
            unselecteds += $(this).val() + ',';
        }               
    });
        arrParams.nobloqueado = selecteds.slice(0,-1);
        arrParams.bloqueado = unselecteds.slice(0,-1);
    if ($('#cmb_cursoreg option:selected').val() != 0) {
       if (selecteds != '') {
         if (!validateForm()) {
             requestHttpAjax(link, arrParams, function (response) {
                 showAlert(response.status, response.label, response.message);
                 //alert("completado");
                 console.log(response);
                 /*
                 setTimeout(function () {
                     window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/listarestudianteregistro";
                 }, 3000);
                 */
             }, true);
         }
      } else {
         showAlert('NO_OK', 'error', {"wtmessage": 'Selecciona: Debe seleccionar al menos un estudiante para permitir evaluaciones.', "title": 'Error'});
      } 
 } else {
     showAlert('NO_OK', 'error', {"wtmessage": 'Aula: El campo no debe estar vacío.', "title": 'Error'});
  }  
}

function actualizarGridAsignaDistributivo() {
    var search = $('#txt_buscarData').val();
    var unidad =  $('#cmb_unidad_dise option:selected').val();
    var modalidad =  $('#cmb_modalidad_dise option:selected').val();
    var periodo =  $('#cmb_periodo_dise option:selected').val();
    var materia = $('#cmb_materia_dise option:selected').val();  
    var jornada = $('#cmb_jornada_dise option:selected').val();      
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Tbg_Asigdistributivo').PbGridView('applyFilterData', {'search': search, 'unidad': unidad, 'modalidad': modalidad, 'periodo': periodo, 'materia': materia, 'jornada': jornada});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelasigd() {
    var search = $('#txt_buscarData').val();
    var unidad =  $('#cmb_unidad_dise option:selected').val();
    var modalidad =  $('#cmb_modalidad_dise option:selected').val();
    var periodo =  $('#cmb_periodo_dise option:selected').val();
    var materia = $('#cmb_materia_dise option:selected').val();  
    var jornada = $('#cmb_jornada_dise option:selected').val();  
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/expexcelasigd?search=" + search + "&unidad=" + unidad + "&modalidad=" + modalidad + "&periodo=" + periodo + "&materia=" + materia + "&jornada=" + jornada;
}

function exportPdfasigd() {
    var search = $('#txt_buscarData').val();
    var unidad =  $('#cmb_unidad_dise option:selected').val();
    var modalidad =  $('#cmb_modalidad_dise option:selected').val();
    var periodo =  $('#cmb_periodo_dise option:selected').val();
    var materia = $('#cmb_materia_dise option:selected').val();  
    var jornada = $('#cmb_jornada_dise option:selected').val();  
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/exppdasigd?pdf=1&search=" + search + "&unidad=" + unidad + "&modalidad=" + modalidad + "&periodo=" + periodo + "&materia=" + materia + "&jornada=" + jornada;
}

function savedistributivo() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/savedistributivo";
    var arrParams = new Object();
    arrParams.nombre = null;
    arrParams.uaca_id = $('#cmb_unidad_dise').val(); 
    arrParams.mod_id = $('#cmb_modalidad_dise').val(); 
    arrParams.paca_id = $('#cmb_periodo_dise').val(); 
    arrParams.asig_id = $('#cmb_materia_dise').val(); 
    arrParams.jor_id = $('#cmb_jornada_dise').val();  
    
    var items = [];
    //var c_vacio = 0;
    $('tbody tr').each(function() {
        var itemOrden ={};
        var tds = $(this).find("td");
        itemOrden.profesor = tds.filter(":eq(0)").text();
        itemOrden.unidad = tds.filter(":eq(3)").text();
        itemOrden.modalidad = tds.filter(":eq(4)").text();
        itemOrden.periodo = tds.filter(":eq(5)").text();
        itemOrden.asignatura = tds.filter(":eq(6)").text();
        itemOrden.jornada = tds.filter(":eq(7)").text();       
        itemOrden.codigo_curso = tds.filter(":eq(8)").find("select").val();
        /*alert (itemOrden.profesor);
        alert (itemOrden.unidad);
        alert (itemOrden.modalidad);
        alert (itemOrden.periodo);
        alert (itemOrden.asignatura);
        alert (itemOrden.jornada);
        alert (itemOrden.codigo_curso);*/
        if ( itemOrden.codigo_curso != "0"){
            items.push(itemOrden);                    
        }  
         /*else {
            c_vacio = c_vacio+1;
        }*/ 
    });
    arrParams.nombre = items;
    //alert ('asaa' + arrParams.nombre);
    //console.log(arrParams.nombre);
     if ( $('#cmb_modalidad_dise option:selected').val() != 0 && $('#cmb_periodo_dise option:selected').val() != 0 && $('#cmb_materia_dise option:selected').val() != 0/* && arrParams.nombre.length > 0*/) {
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function(response) {
                    showAlert(response.status, response.label, response.message);
                    if (response.status == "OK") {
                        setTimeout(function() {
                            var link = $('#txth_base').val() + "/academico/usuarioeducativa/distributivoindex";
                            window.location = link;
                        }, 3000);
                    }
                }, true);
            }  
        
    }
    else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Debe seleccionar modalidad, periodo y asignatura.', "title": 'Información'});
    }
}

function exportExceldistedu() {
    var search = $('#txt_buscarDatadisted').val();
    var periodo =  $('#cmb_periododistb option:selected').val();
    var curso = $('#cmb_cursodistb option:selected').val(); 
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/expexceldistedu?search=" + search + "&periodo=" + periodo + "&curso=" + curso;
}

function exportPdfdistedu() {
    var search = $('#txt_buscarDatadisted').val();
    var periodo =  $('#cmb_periododistb option:selected').val();
    var curso = $('#cmb_cursodistb option:selected').val(); 
    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/exppdfdistedu?pdf=1&search=" + search + "&periodo=" + periodo + "&curso=" + curso;
}

function eliminardistributivo(id) {   
    var mensj = "¿Seguro desea eliminar el registro?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'accionreg';
    var params = new Array(id, 0);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}

function accionreg(id, tmp) {
    //alert ('id accion' + id);
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/deletedistributivo";
    var arrParams = new Object();
    arrParams.cedi_id = id;    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/distributivoindex";
                }, 3000);
            }
        }, true);
    }
}

// Este sólo abre el modal de asignación. Es para los botones de la barra superior
function insertarEstudiantes(){
    $('#confirmModal').modal('toggle');
}

function insertarEstudiantesConfirm(){
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/insertarestudiantes";

    var arrParams = new Object();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/listarestudianteregistro";
                    }, 3000);
            }
        }, true);
    }
}

function actualizarGridEducativa(){
    var periodo    = $('#cmb_periodo_educ option:selected').val();
    var modalidad  = $('#cmb_modalidad_educ option:selected').val();
    var aula       = $('#cmb_aula_educ option:selected').val();
    var unidadeduc = $('#cmb_unidad_educ option:selected').val();

    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#Tbg_Asignar_Evaluacion').PbGridView('applyFilterData', {'periodo': periodo, 'modalidad': modalidad, 'aula': aula, 'unidadeduc': unidadeduc});
        setTimeout(hideLoadingPopup, 2000);
    }
}

$('#cmb_periodo_educ').change(function() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/asignarevaluacion";
    var arrParams = new Object();

    arrParams.codcursoreg = $(this).val();
    arrParams.getcursoreg = true;

    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            data = response.message;
            setComboDataselect(data.periodoreg, "cmb_aula_educ", "Todos");

            var arrParams = new Object();

            if (data.periodoreg.length > 0) {
                arrParams.paca_id = $('#cmb_periodo_educ').val(); 
                arrParams.aulareg = $('#cmb_aula_educ').val();                  
                arrParams.getunidadreg = true;

                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboDataselect(data.unidadreg, "cmb_unidad_educ", "Todos");
                    }
                }, true);
            }
        }
    }, true);
});

$('#cmb_aula_educ').change(function() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/asignarevaluacion";
    var arrParams = new Object();

    arrParams.aulareg = $('#cmb_aula_educ').val(); ;
    arrParams.getunidadreg = true;

    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            data = response.message;
            setComboDataselect(data.unidadreg, "cmb_unidad_educ", "Todos");
        }
    }, true);
});


var globalItems = '';

$('#cmb_unidad_educ').change(function() {
    var link = $('#txth_base').val() + "/academico/usuarioeducativa/asignarevaluacion";
    var arrParams = new Object();

    arrParams.aulareg = $('#cmb_aula_educ').val();
    arrParams.unidad  = $('#cmb_unidad_educ').val();
    arrParams.getitems = true;

    if($('#cmb_unidad_educ').val() == 0 || $('#cmb_unidad_educ').val() == ''){
        console.log("nada");
    }else{
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.items, "cmb_evaluacion_educ", "Todos");
            }
        }, true);
    }
});

/**
 * Asigna a los estudiantes con el check el examen elegido
 * @author Galo Aguirre <analistadesarrollo06@uteg.edu.ec>;
 * @param
 * @return
 */
function asignarItems() {        
    var link         = $('#txth_base').val() + "/academico/usuarioeducativa/asignaritems";
    var arrParams    = new Object();    
    arrParams.aula   = $('#cmb_aula_educ').val();
    arrParams.unidad = $('#cmb_unidad_educ').val();
    arrParams.item   = $('#cmb_evaluacion_educ option:selected').val();
    arrParams.desc   = $('#cmb_evaluacion_educ option:selected').text();

    //alert(arrParams.desc);return false;
    var selecteds    = '';
    var unselecteds  = '';       
    $('#Tbg_Asignar_Evaluacion input[type=checkbox]').each(function () {
        if (this.checked) {
            selecteds += $(this).val() + ',';
        }else{
            unselecteds += $(this).val() + ',';
        }               
    });
    arrParams.nobloqueado = selecteds.slice(0,-1);
    arrParams.bloqueado   = unselecteds.slice(0,-1);

    if ($('#cmb_evaluacion_educ option:selected').val() != 0) {
        if (selecteds != '') {
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function (response) {
                    showAlert(response.status, response.label, response.message);
                    //alert("completado");
                    console.log(response);
                   // actualizarGridEducativa();
                    
                    setTimeout(function () {
                        window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/asignarevaluacion";
                    }, 7000);
                    
                }, true);
            }//if
        }else 
            showAlert('NO_OK', 'error', {"wtmessage": 'Selecciona: Debe seleccionar al menos un estudiante para permitir evaluaciones.', "title": 'Error'});
    }else 
        showAlert('NO_OK', 'error', {"wtmessage": 'Debe seleccionar una evaluacion.', "title": 'Error'});
}//function asignarItems
