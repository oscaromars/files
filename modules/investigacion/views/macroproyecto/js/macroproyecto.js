$(document).ready(function () {

    
    $('#btn_buscarDataLinea').click(function () {
        actualizarGrid();
    });

    $('#btn_cargarDocumento').click(function () {
        cargarDocumento();
    });

    $('#btn_buscarRegConf').click(function () {
        actualizarGridRegistroConf();
    });

    $('#btn_buscarPlanestudiante').click(function () {
        actualizarGridPlanest();
    });
    $('#btn_buscarResumenestudiante').click(function () {
        actualizarGridResumen();
    });
    
    $('#btn_buscarPlanest').click(function () {
        actualizarGridPlanestudiante();
    });

    $('#btn_saveplanificacion').click(function () {
        guardaplanificacion();
    });

    $('#btn_modificarplanificacion').click(function () {
        modificarplanificacion();
    });
    $('#btn_modificarplanificacionaut').click(function () {
        modificarplanificacionaut();
    });
    $('#btn_limpiarbuscador').click(function () {
        limpiarBuscador();
    });
    $('#PbPlanificaestudiante').change(function(){
        setTimeout(hideLoadingPopup(), 2000);
    });
    $('#cmb_modalidadesth').change( function(){
        $('#cmb_horaest').prop("disabled",false); 
        $("#cmb_horaest")[0].selectedIndex=0;
        if($('#cmb_modalidadesth option:selected').val() == 0){
            $('#cmb_horaest').prop("disabled",true); 
            var html_texto = `<option value="0" selected="">Seleccionar</option>`;
        }else if($('#cmb_modalidadesth option:selected').val() == 2 || $('#cmb_modalidadesth option:selected').val() == 3){
            var html_texto = `<option value="0" selected="">Seleccionar</option>
            <option value="1">Hora 1</option>
            <option value="2">Hora 2</option>
            <option value="3">Hora 3</option>
            <option value="4">Hora 4</option>
            <option value="5">Hora 5</option>
            <option value="6">Hora 6</option>`;
            
        }else if($('#cmb_modalidadesth option:selected').val() == 3 || $('#cmb_modalidadesth option:selected').val() == 4){
            var html_texto = `<option value="0" selected="">Seleccionar</option>
            <option value="1">Hora 1</option>
            <option value="2">Hora 2</option>
            <option value="3">Hora 3</option>`;
        }
        $("#cmb_horaest").html(html_texto);
    });

    /************ planificacion x estudiante **********************************/
    $('#cmb_unidades').change(function () {
        var link = $('#txth_base').val() + "/academico/planificacion/planificacionestudiante";
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        arrParams.empresa_id = 1;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidades", "Todas");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidades').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.empresa_id = 1;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "cmb_carreras", "Todas");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidades').change(function () {
        var link = $('#txth_base').val() + "/academico/planificacion/planificacionestudiante";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidades').val();
        arrParams.moda_id = $(this).val();
        arrParams.empresa_id = 1;
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "cmb_carreras", "Todas");
            }
        }, true);
    });
    /*************************************************************************/

    $('#cmb_unidad').change(function () {
        var link = $('#txth_base').val() + "/academico/marcacion/listarhorario";
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad", "Todos");
            }
        }, true);
    });

    $('#cmb_modalidad').change(function () {
        if ($(this).val() == 4 || $(this).val() == 1) {
            $('#divFechasDistancia').css('display', 'block');
        } else {
            $('#divFechasDistancia').css('display', 'none');
        }
    });
    /************ crear nueva planificacion **********************************/
    $('#cmb_carreraest').change(function () {
        var link = $('#txth_base').val() + "/academico/planificacion/new";
        var arrParams = new Object();
        arrParams.eaca_id = $(this).val();
        arrParams.getmodalidad = true;
        arrParams.empresa_id = 1;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidadest", "Seleccionar");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.uaca_id = $('#cmb_unidadest').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.eaca_id = $('#cmb_carreraest').val();
                    arrParams.empresa_id = 1;
                    arrParams.getmalla = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.mallaca, "cmb_malladoest", "Seleccionar");
                            if (data.mallaca.length > 0) {
                                arrParams.maca_id = data.mallaca[0].id;
                                arrParams.empresa_id = 1;
                                arrParams.getmateria = true;
                                requestHttpAjax(link, arrParams, function (response) {
                                    if (response.status == "OK") {
                                        data = response.message;
                                        setComboDataselect(data.asignatura, "cmb_asignaest", "Seleccionar");

                                    }
                                }, true);
                            }
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidadest').change(function () {
        var link = $('#txth_base').val() + "/academico/planificacion/new";
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidadest').val();
        arrParams.moda_id = $(this).val();
        arrParams.eaca_id = $('#cmb_carreraest').val();
        arrParams.empresa_id = 1;
        arrParams.getmalla = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.mallaca, "cmb_malladoest", "Seleccionar");
            }
        }, true);
    });



    
    /*************************************************************************/
    $('#btn_buscarHorario').click(function () {
        actualizarGridHorario();
    });

    $('#btn_buscarNoMarcacion').click(function () {
        cargarNoMarcadas();
    });

    

    $('#cmb_modalidad').change(function () {
        var arrParams2 = new Object();
        arrParams2.PBgetFilter = true;
        arrParams2.pla_periodo_academico = $("#cmb_per_academico").val();
        arrParams2.mod_id = $("#cmb_modalidad").val();
        /* console.log(arrParams2); */
        $("#grid_planificaciones_list").PbGridView("applyFilterData", arrParams2);
    });

    $('#btn_AgregarItemat').click(function () {
        //alert('HOLA');
        agregarItems('new')

    });    
});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
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

function Marcacion(hape_id, horario, accion, dia, prof_id) {
    var link = $('#txth_base').val() + "/academico/marcacion/save";
    var arrParams = new Object();
    arrParams.hape_id = hape_id;
    arrParams.horario = horario;
    arrParams.accion = accion;
    arrParams.dia = dia;
    arrParams.profesor = prof_id;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/marcacion/marcacion";
                }, 5000);
            }
        }, true);
    }
}

function actualizarGridMarcacion() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var materia = $('#txt_buscarDataMateria').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();

    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#PbMarcacion').PbGridView('applyFilterData', { 'profesor': profesor, 'materia': materia, 'f_ini': f_ini, 'f_fin': f_fin, 'periodo': periodo });
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#boxgrid").val();
    //console.log (arrParams)
    console.log(arrParams.search)
    window.location.href = $('#txth_base').val() + "/documental/gestion/expexcel?search=" + arrParams.search;
}

function exportPdf() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var materia = $('#txt_buscarDataMateria').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/marcacion/exppdf?pdf=1&profesor=" + profesor + "&materia=" + materia + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo;
}

function cargarDocumento() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/planificacion/upload";
    arrParams.procesar_file = true;
    arrParams.archivo = $('#txth_pla_adj_documento2').val() + "." + $('#txth_pla_adj_documento').val().split('.').pop();
    arrParams.periodoAcademico = $('#frm_per_aca').val();
    arrParams.fechaInicio = $('#dtp_pla_fecha_ini').val();
    arrParams.fechaFin = $('#dtp_pla_fecha_fin').val();
    arrParams.modalidad = $('#cmb_moda').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            var message = response.message;
            if (response.status == "OK") {
                showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
                setTimeout(function () {
                    parent.window.location.href = $('#txth_base').val() + "/academico/planificacion/index";
                }, 2000);
            } else {
                showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
            }
        }, true);
    }
}

function descargarPlanificacion(pla_id) {
    /* console.log("Entra a descargar", pla_id); */
    window.location.href = $('#txth_base').val() + "/academico/planificacion/descargarplanificacion?pla_id=" + pla_id;
}

function searchModules(idbox, idgrid) {
    var arrParams2 = new Object();
    arrParams2.PBgetFilter = true;
    arrParams2.pla_periodo_academico = $("#cmb_per_academico").val();
    arrParams2.mod_id = $("#cmb_modalidad").val();
    /* console.log(arrParams2); */
    $("#grid_planificaciones_list").PbGridView("applyFilterData", arrParams2);
}

function actualizarGridHorario() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var unidad = $('#cmb_unidad option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();

    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#PbHorario').PbGridView('applyFilterData', { 'profesor': profesor, 'unidad': unidad, 'modalidad': modalidad, 'f_ini': f_ini, 'f_fin': f_fin, 'periodo': periodo });
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelhorario() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var unidad = $('#cmb_unidad option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/marcacion/expexcelhorario?profesor=" + profesor + "&unidad=" + unidad + '&modalidad=' + modalidad + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo;
}

function exportPdfhorario() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var unidad = $('#cmb_unidad option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/marcacion/exppdfhorario?pdf=1&profesor=" + profesor + "&unidad=" + unidad + '&modalidad=' + modalidad + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo;
}

function cargarNoMarcadas() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var materia = $('#txt_buscarDataMateria').val();
    var unidad = $('#cmb_unidad option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();
    var tipo = $('#cmb_tipo option:selected').val();

    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#PbNomarcacion').PbGridView('applyFilterData', { 'profesor': profesor, 'materia': materia, 'unidad': unidad, 'modalidad': modalidad, 'f_ini': f_ini, 'f_fin': f_fin, 'periodo': periodo, 'tipo': tipo });
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcelNoMarcadas() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var materia = $('#txt_buscarDataMateria').val();
    var unidad = $('#cmb_unidad option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();
    var tipo = $('#cmb_tipo option:selected').val();

    window.location.href = $('#txth_base').val() + "/academico/marcacion/expexcelnomarcadas?profesor=" + profesor + "&materia=" + materia + "&unidad=" + unidad + '&modalidad=' + modalidad + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo + "&tipo=" + tipo;
}

function exportPdfNoMarcadas() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var materia = $('#txt_buscarDataMateria').val();
    var unidad = $('#cmb_unidad option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();
    var tipo = $('#cmb_tipo option:selected').val();

    window.location.href = $('#txth_base').val() + "/academico/marcacion/exppdfnomarcadas?pdf=1&profesor=" + profesor + "&materia=" + materia + "&unidad=" + unidad + '&modalidad=' + modalidad + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo + "&tipo=" + tipo;
}

function actualizarGrid() {
    var linv_id = $('#cmb_linea option:selected').val();
        
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#grid_macro_list').PbGridView('applyFilterData', {'linv_id': linv_id});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function editReg() {
    var link = $('#txth_base').val() + "/investigacion/macroproyecto/edit/" + $("#frm_linv_id").val();
    window.location = link;
}

function updateReg() {
    var link = $('#txth_base').val() + "/investigacion/macroproyecto/edit/updatereg";
    var arrParams = new Object();
    arrParams.id = $('#frm_linv_id').val();
    arrParams.nombre_investigacion = $('#txt_nameline').val();
    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function () {
                   parent.window.location.href = $('#txth_base').val() + "/investigacion/macroproyecto/index";
                }, 3000);
            }
        }, true);
    }kbkb
}

function saveReg() {
    var link = $('#txth_base').val() + "/investigacion/macroproyecto/savereg";
    var arrParams = new Object();
    arrParams.linv_id = $('#cmb_linea option:selected').val();
    arrParams.descripcion = $('#txt_nameline').val();
    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    //location.reload();
                    parent.window.location.href = $('#txth_base').val() + "/investigacion/macroproyecto/index";
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/investigacion/macroproyecto/deletereg";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
            //actualizarGridRegistroConf();
            setTimeout(function () {
                location.reload();
                // parent.window.location.href = $('#txth_base').val() + "/investigacion/lineainvestigacion/index";
            }, 3000);
        }
        
    }, true);
}

function actualizarGridPlanest() {
    var link = $('#txth_base').val() + "/academico/planificacion/academicoestudiante";
    //alert(link);
    var arrParams = new Object();
    var modalidad = $('#cmb_modalidadesacad option:selected').val();
    arrParams.modalidad = modalidad;
    var periodo = $('#cmb_periodoacad option:selected').val();
    arrParams.periodo = periodo;
    arrParams.filtros = 1;
    //alert(modalidad+'-'+periodo+'-'+arrParams.filtros);
    //Buscar almenos una clase con el nombre para ejecutar
    //if (!$(".blockUI").length) {
        showLoadingPopup();
        //$('#PbPlanificaestudiante').PbGridView('applyFilterData', { 'modalidad': modalidad, 'periodo': periodo });
        setTimeout(function() {
            //windows.location.href = $('#txth_base').val() + "/academico/registro/index";    
            //hideLoadingPopup();
            parent.window.location.href = link+'?modalidad='+modalidad+'&periodo='+periodo+'&PBgetFilter='+arrParams.filtros;
            }, 1000);
    /* }
    try{
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                setTimeout(function() {
                    hideLoadingPopup();
                }, 3000);
            } else {
                showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
            }
        }, false);
    }catch(err){
        alert( "wtmessage <p>+"+$err+"</p>");    
        console.log("error: "+err)
    }*/
}
function actualizarGridResumen() {
    var link = $('#txth_base').val() + "/academico/planificacion/resumenplanificacion";
    //alert(link);
    showLoadingPopup();
    var arrParams = new Object();
    var modalidad = $('#cmb_modalidades option:selected').val();
    arrParams.modalidad = modalidad;
    var periodo = $('#cmb_periodo option:selected').val();
    arrParams.periodo = periodo;
    //arrParams.filtros = new Boolean(true);
    //alert(modalidad+'-'+periodo+'-'+arrParams.filtros);
    //Buscar almenos una clase con el nombre para ejecutar
    //if (!$(".blockUI").length) {
        
        //$('#PbPlanificaestudiante').PbGridView('applyFilterData', { 'modalidad': modalidad, 'periodo': periodo });
        setTimeout(function() {
            requestHttpAjax(link, arrParams, function(response) {
                var message = response.message;
                if (response.status == "OK") {
                    showAlert(response.status, response.type, { "wtmessage": 'Su informaci??n se consult?? con ??xito.', "title": response.label });
                   hideLoadingPopup();
                } else {
                    showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
                }
            },true);
        }, 4000);
    //}
}


function exportExcelplanifica() {
    var estudiante = $('#txt_buscarDataPlanifica').val();
    //var unidad = $('#cmb_unidades option:selected').val();
    var modalidad = $('#cmb_modalidades option:selected').val();
    var carrera = $('#cmb_carreras option:selected').text(); //$('#cmb_carreras option:selected').val();
    var periodo = $('#cmb_periodo option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/planificacion/expexcelplanifica?estudiante=" + estudiante + /*"&unidad=" + unidad +*/ '&modalidad=' + modalidad + "&carrera=" + carrera + "&periodo=" + periodo;
}

function exportPdfplanifica() {
    var estudiante = $('#txt_buscarDataPlanifica').val();
    // var unidad = $('#cmb_unidades option:selected').val();
    var modalidad = $('#cmb_modalidades option:selected').val();
    var carrera = $('#cmb_carreras option:selected').text(); //$('#cmb_carreras option:selected').val();
    var periodo = $('#cmb_periodo option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/planificacion/exppdfplanifica?pdf=1&estudiante=" + estudiante + /*"&unidad=" + unidad +*/ '&modalidad=' + modalidad + "&carrera=" + carrera + "&periodo=" + periodo;
}
function accion(plaid, perid) {
    var link = $('#txth_base').val() + "/academico/planificacion/deleteplanest";
    var arrParams = new Object();
    arrParams.pla_id = plaid;
    arrParams.per_id = perid;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/planificacion/planificacionestudiante";
                }, 3000);
            }
        }, true);
    }
}

function deleteplanestudiante(plaid, perid) {
    var mensj = "??Seguro desea eliminar la planificaci??n?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'accion';
    var params = new Array(plaid, perid);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}

function accionmat(plaid, perid, bloque, hora) {
    var link = $('#txth_base').val() + "/academico/planificacion/deletematest";
    var arrParams = new Object();
    arrParams.pla_id = plaid;
    arrParams.per_id = perid;
    arrParams.bloque = bloque;
    arrParams.hora = hora;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/planificacion/edit?pla_id=" + arrParams.pla_id + "&per_id=" + arrParams.per_id;
                }, 1000);
            }
        }, true);
    }
}

function accionmataut(plaid, perid, bloque, hora) {
    var link = $('#txth_base').val() + "/academico/planificacion/deletematest";
    var arrParams = new Object();
    arrParams.pla_id = plaid;
    arrParams.per_id = perid;
    arrParams.bloque = bloque;
    arrParams.hora = hora;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/planificacion/newplanificacion?pla_id=" + arrParams.pla_id + "&estudiante=" + arrParams.per_id;
                }, 1000);
            }
        }, true);
    }
}

function deletematestudiante(plaid, perid, bloque, hora) {
    var mensj = "??Seguro desea eliminar la materia?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'accionmat';
    var params = new Array(plaid, perid, bloque, hora);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}

function deletematestudianteaut(pla_id,perid, bloque, hora) {
    var mensj = "??Seguro desea eliminar la materia?";
   
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'accionmataut';
    //var pla_id = $("#txth_pla_id").val(); 
    //alert('Se va a eliminar :'.pla_id + ":");
    var params = new Array(pla_id, perid, bloque, hora);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}

function buscaDatoTabla(par_tabla, par_comboID, par_clase){ 
    var retorna = false;
    console.log('Tabla:'+par_tabla)
    console.log('Combo: '+par_comboID  )
    console.log('Clase:'+par_clase)
    $('#' + par_tabla + ' tr').each(function() {
        var bloqueID = $(this).find("." + par_clase).html(); 
        console.log('Bloque: '+bloqueID);
        console.log('Combo: '+par_comboID);
        if (bloqueID === par_comboID){ 
            retorna = true; 
        } 
    });
    return retorna;
}

/* AGREGAR OPCIONES A GRID */
function agregarItems(opAccion) {
    var tGrid = 'PbPlanificaestudiantnew';
    //var nombre = $('#cmb_estandar_evi option:selected').text();
    //Verifica que tenga nombre producto y tenga foto
    //alert('add :1-' + $('#cmb_modalidadesth').val()+' :2-'+$('#cmb_bloqueest').val()+' :3-'+ $('#cmb_modalidadesth').val() +' :4-'+$('#cmb_horaest').val());
    if ($('#cmb_asignaest').val() != '0' /*&& $('#cmb_jornadaest').val() != '0'*/ && $('#cmb_bloqueest').val() != '0' && $('#cmb_modalidadesth').val() != '0' && $('#cmb_horaest').val() != '0') {
        /* var valor = $('#cmb_estandar_evi option:selected').text();*/
        if (opAccion != "edit") {
            //*********   AGREGAR ITEMS *********
            var arr_Grid = new Array();
            if (sessionStorage.dts_datosItemplan) {
                /*Agrego a la Sesion*/
                arr_Grid = JSON.parse(sessionStorage.dts_datosItemplan);
                var size = arr_Grid.length;
                if (size > 0) {
                    var str = $('#cmb_asignaest option:selected').text();
                    var n = str.indexOf(" - ");
                    var vasignatura = str.substring(0,n);// $('#cmb_asignaest option:selected').text();
                    var vbloque = $('#cmb_bloqueest option:selected').text();
                    var vhora = $('#cmb_horaest option:selected').text();
                    var vBloque =  buscaDatoTabla(tGrid, vbloque, 'bloque');
                    var vHora =  buscaDatoTabla(tGrid, vhora, 'hora');
                    var vExiste = (sessionStorage.dts_datosItemplan).indexOf(vasignatura);
                    var viguales = 0;
                    console.log('Bloque: '+vBloque);
                    console.log('Hora: '+ vHora);
                    //console.log(str + ' - - ' +vasignatura);
                    console.log('Existe: '+vExiste);
                    console.log('Grid: '+tGrid);
                    if (vBloque   && vHora ){
                        viguales = 1;
                    }
                    //alert ('sdsds' + viguales);
                    if (vExiste > 0){//(checkId(vasignatura, 'asignatura') /*||  (viguales === 1)*/) {
                     //if (vBloque && vhora) {                         
                        showAlert('NO_OK', 'error', { "wtmessage": "Ya ha ingresado esa asignatura", "title": 'Informaci??n' });
                        return;
                     //}
                    } else if(viguales === 1){
                        showAlert('NO_OK', 'error', { "wtmessage": "Ya ha ingresado una asignatura en este bloque y hora", "title": 'Informaci??n' });
                        return;
                    }   
                    else {
                        //Varios Items                    
                        arr_Grid[size] = objProducto(size);
                        sessionStorage.dts_datosItemplan = JSON.stringify(arr_Grid);//alert(JSON.stringify(arr_Grid));
                        addVariosItem(tGrid, arr_Grid, -1);
                    }         
                    limpiarDetalle();

                } else {
                    /*Agrego a la Sesion*/
                    //Primer Items                   
                    arr_Grid[0] = objProducto(0);
                    sessionStorage.dts_datosItemplan = JSON.stringify(arr_Grid);
                    addPrimerItem(tGrid, arr_Grid, 0);
                    limpiarDetalle();
                }
            } else {
                //No existe la Session
                //Primer Items
                arr_Grid[0] = objProducto(0);
                sessionStorage.dts_datosItemplan = JSON.stringify(arr_Grid);
                addPrimerItem(tGrid, arr_Grid, 0);
                limpiarDetalle();
            }
        } else {
            //data edicion
        }
    } else {
        showAlert('NO_OK', 'error', { "wtmessage": "Todos los datos del detalle planificaci??n son obligatorios", "title": 'Informaci??n' });
    }
}
function objProducto(indice) {
    var rowGrid = new Object();
    rowGrid.indice = indice;
    /*rowGrid.pla_id = $('#txth_pla_id').val();
     rowGrid.per_id = $('#txth_per_id').val();*/
    
    rowGrid.asignatura = $('#cmb_asignaest option:selected').text();
    rowGrid.jornada = $('#cmb_jornadaest option:selected').text();    
    rowGrid.bloque = $('#cmb_bloqueest option:selected').text();
    rowGrid.modalidad = $('#cmb_modalidadesth option:selected').text();
    rowGrid.hora = $('#cmb_horaest option:selected').text();
    rowGrid.accion = "new";
    return rowGrid;
}
function addPrimerItem(TbGtable, lista, i) {
    /*Remuevo la Primera fila*/
    $('#' + TbGtable + ' >table >tbody').html("");
    /*Agrego a la Tabla de Detalle*/
    $('#' + TbGtable + ' tr:last').after(retornaFila(i, lista, TbGtable, true));
}

function limpiarDetalle() {
    $('#cmb_asignaest').val("0");
    $('#cmb_jornadaest').val("0");
    $('#cmb_bloqueest').val("0");
    $('#cmb_modalidadesth').val("0");
    $('#cmb_horaest').val("0");
    //$('#txt_doc_archivo').fileinput('clear');

}

function addVariosItem(TbGtable, lista, i) {
    //i=(i==-1)?($('#'+TbGtable+' tr').length)-1:i;
    i = ($('#' + TbGtable + ' tr').length) - 1;
     if (i < 12) {
         $('#' + TbGtable + ' tr:last').after(retornaFila(i, lista, TbGtable, true));

    } else {
        showAlert('NO_OK', 'error', { "wtmessage": "Ya tiene ingresadas m??ximo de materias permitidas", "title": 'Informaci??n' });
    }
}

function retornaFila(c, Grid, TbGtable, op) {
    //var RutaImagenAccion='ruta IMG'//$('#txth_rutaImg').val();
    var pla_id = $('#txth_pla_id').val();
    var per_id = $('#txth_per_id').val();
    var strFila = "";
    strFila += '<td style="display:none; border:none;">' + Grid[c]['indice'] + '</td>';
    strFila += '<td style=" display:none; border:none;">' + pla_id + '</td>';
    strFila += '<td style=" display:none;border:none;">' + per_id + '</td>';
    strFila += '<td for="asignatura">' + Grid[c]['asignatura'] + '</td>';
    strFila += '<td>' + Grid[c]['jornada'] + '</td>';
    strFila += '<td class="bloque">' + Grid[c]['bloque'] + '</td>';
    strFila += '<td>' + Grid[c]['modalidad'] + '</td>';
    strFila += '<td class="hora">' + Grid[c]['hora'] + '</td>';
    strFila += '<td>';//??Est?? seguro de eliminar este elemento?   
    strFila += '<a onclick="eliminarItems(\'' + Grid[c]['indice'] + '\',\'' + TbGtable + '\')" ><span class="glyphicon glyphicon-remove"></span></a>';
    strFila += '</td>';
    //alert('cxc'+ Grid[c]['indice']); //Este necesito regresar
    if (op) {
        strFila = '<tr>' + strFila + '</tr>';
    }
    return strFila;
}

function checkId(id, cadena) {
    let ids = document.querySelectorAll('#PbPlanificaestudiantnew td[for="' + cadena + '"]');
    console.log('+++' + ids);
    console.log('sdd' + [].filter.call(ids, td => td.textContent === id).length === 1);
    return [].filter.call(ids, td => td.textContent === id).length === 1;    
}

function eliminarItems(val, TbGtable) {
    var ids = "";
    //var count=0;
    if (sessionStorage.dts_datosItemplan) {
        var Grid = JSON.parse(sessionStorage.dts_datosItemplan);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function () {
                ids = $(this).find("td").eq(0).html();
                if (ids == val) {
                    var array = findAndRemove(Grid, 'indice', ids);
                    sessionStorage.dts_datosItemplan = JSON.stringify(array);
                    //if (count==0){sessionStorage.removeItem('detalleGrid')} 
                    $(this).remove();
                }
            });
        }
    }
}

function findAndRemove(array, property, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            array.splice(i, 1);
        }
    }
    return array;
}

// Recarga la Grid de Productos si Existe
function recargarGridItem() {
    var tGrid = 'PbPlanificaestudiantnew';
    if (sessionStorage.dts_datosItemplan) {
        var arr_Grid = JSON.parse(sessionStorage.dts_datosItemplan);
        if (arr_Grid.length > 0) {
            $('#' + tGrid + ' > tbody').html("");
            for (var i = 0; i < arr_Grid.length; i++) {
                $('#' + tGrid + ' > tbody:last-child').append(retornaFila(i, arr_Grid, tGrid, true));
            }
        }
    }
}

function guardaplanificacion() {
    var arrParams = new Object();
    var accion = "Create";
    var link = $('#txth_base').val() + "/academico/planificacion/saveplanificacion";

    //arrParams.jornadaest = $('#cmb_jornadaest option:selected').text();
    arrParams.carreraest = $('#cmb_carreraest option:selected').text();
    arrParams.modalidadest = $('#cmb_modalidadest').val();
    arrParams.mallaest = $('#cmb_malladoest option:selected').text();
    arrParams.periodoest = $('#cmb_periodoest').val();
    arrParams.nombreest = $('#cmb_buscarest').val();   
    if (/*$('#cmb_jornadaest').val() != '0' &&*/ $('#cmb_carreraest').text() != 'Seleccionar' && $('#cmb_modalidadest').val() != '0' && $('#cmb_malladoest').val() != '0' && $('#cmb_periodoest').val() != '0' && $('#cmb_buscarest').val() > '0') {
        if (sessionStorage.dts_datosItemplan) {
             var arr_Grid = JSON.parse(sessionStorage.dts_datosItemplan);
            if (arr_Grid.length > 0) {
                arrParams.DATAS = sessionStorage.dts_datosItemplan
                arrParams.ACCION = accion;
                requestHttpAjax(link, arrParams, function (response) {
                    var message = response.message;
                    if (response.status == "OK") {
                        showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
                        limpiarDetalle();
                        sessionStorage.removeItem('dts_datosItemplan')
                        setTimeout(function () {
                            parent.window.location.href = $('#txth_base').val() + "/academico/planificacion/planificacionestudiante";
                        }, 2000);
                    } else {
                        showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
                    }
                }, true);
            } else {             
                showAlert('NO_OK', 'error', { "wtmessage": "No Existe datos ", "title": 'Informaci??n' });
            }
        } else {
            showAlert('NO_OK', 'error', { "wtmessage": "No ha ingresado detalle en planificaci??n", "title": 'Informaci??n' });
        }
    } else {
        showAlert('NO_OK', 'error', { "wtmessage": "Todos los datos de la cabecera planificaci??n son obligatorios", "title": 'Informaci??n' });
    }

}

function modificarplanificacion() {
    var arrParams = new Object();
    var accion = "Update";
    var link = $('#txth_base').val() + "/academico/planificacion/saveplanificacion";

    arrParams.jornadaest = $('#cmb_jornadaest option:selected').text();
    arrParams.carreraest = $('#txt_carrera').val();
    arrParams.modalidadest = $('#cmb_modalidadest').val();
    //arrParams.mallaest = $('#cmb_malladoest').val();
    arrParams.periodoest = $('#cmb_periodoest').val();
    arrParams.nombreest = $('#cmb_buscarest').val();
    arrParams.pla_id = $('#txth_pla_id').val();
    arrParams.per_id = $('#txth_per_id').val();
    
    //if ($('#cmb_jornadaest').val() != '0' && $('#txt_carrera').text() != 'Seleccionar' && $('#cmb_modalidadest').val() != '0' && /*$('#cmb_malladoest').val() != '0' &&*/ $('#cmb_periodoest').val() != '0' && $('#cmb_buscarest').val() > '0') {
    if (sessionStorage.dts_datosItemplan) {
    var arr_Grid = JSON.parse(sessionStorage.dts_datosItemplan);
    if (arr_Grid.length > 0) {
        arrParams.DATAS = sessionStorage.dts_datosItemplan
        arrParams.ACCION = accion;
        requestHttpAjax(link, arrParams, function (response) {
            var message = response.message;
            if (response.status == "OK") {
                showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
                limpiarDetalle();
                sessionStorage.removeItem('dts_datosItemplan')
                setTimeout(function () {
                    parent.window.location.href = $('#txth_base').val() + "/academico/planificacion/planificacionestudiante";
                }, 2000);
            } else {
                showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
            }
        }, true);
    } else {
        showAlert('NO_OK', 'error', { "wtmessage": "No Existe datos ", "title": 'Informaci??n' });
    }
     } else {
        showAlert('NO_OK', 'error', {"wtmessage": "No ha ingresado nuevas asignaturas del estudiante", "title": 'Informaci??n'});
    }
    /*} else {
        showAlert('NO_OK', 'error', {"wtmessage": "Todos los datos de la cabecera planificaci??n son obligatorios", "title": 'Informaci??n'});
    }*/

}
function edit() {
    var link = $('#txth_base').val() + "/academico/planificacion/edit" + "?pla_id=" + $("#txth_pla_id").val() + "&per_id=" + $("#txth_per_id").val();
    window.location = link;
}

function fillDataAlert() {
    var type = "alert";
    var label = "error";
    var status = "NO_OK";
    var messagew = {};
    messagew = {
        "wtmessage": "Llene todos los campos obligatorios",//objLang.Must_be_Fill_all_information_in_fields_with_label___,
        "title": objLang.Error,
        "acciones": [{
            "id": "btnalert",
            "class": "btn-primary clclass praclose",
            "value": objLang.Accept
        }],
    };
    showResponse(type, status, label, messagew);
}

function exportPdfplanificacion() {
    var estudiante = $('#txt_buscarDataPlanifica').val();
    // var unidad = $('#cmb_unidades option:selected').val();
    var modalidad = $('#cmb_modalidadesacad option:selected').val();
    var carrera = $('#cmb_carreras option:selected').text(); //$('#cmb_carreras option:selected').val();
    var periodo = $('#cmb_periodoacad option:selected').val();
    //alert('Modalidad : '+modalidad+' periodo: '+periodo);
    window.location.href = $('#txth_base').val() + "/academico/planificacion/exppdfplanificacion?pdf=1&estudiante=" + estudiante + /*"&unidad=" + unidad +*/ '&modalidad=' + modalidad + "&carrera=" + carrera + "&periodo=" + periodo;
}

function exportExcelplanificacion() {
    var estudiante = $('#txt_buscarDataPlanifica').val();
    //var unidad = $('#cmb_unidades option:selected').val();
    var modalidad = $('#cmb_modalidadesacad option:selected').val();
    var carrera = $('#cmb_carreras option:selected').text(); //$('#cmb_carreras option:selected').val();
    var periodo = $('#cmb_periodoacad option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/planificacion/expexcelplanificacion?estudiante=" + estudiante + /*"&unidad=" + unidad +*/ '&modalidad=' + modalidad + "&carrera=" + carrera + "&periodo=" + periodo;
}


function actualizarGridPlanestudiante() {
    var estudiante = $('#cmb_buscarest option:selected').val();
    var unidad = $('#cmb_unidadest option:selected').val();
    var modalidad = $('#cmb_modalidadest option:selected').val();
    //var carrera = $('#cmb_carreraest option:selected').text(); //$('#cmb_carreras option:selected').val();//$('#cmb_carreras option:selected').val();
    var carrera = $('#txt_carrera').val();
    var periodo = $('#cmb_periodoest option:selected').val();
    //var malla = $('#cmb_mallaest option:selected').val();
    var malla = $('#txt_malla').val();
    var per_id = estudiante;
    var pla_id = $('#txth_pla_id').val();
    var saca_id = $('#cmb_periodoest option:selected').val();

    //Buscar almenos una clase con el nombre para ejecutar
    //alert('OK : '+$('#cmb_periodoest option:selected').val());
    if (!$(".blockUI").length) {
        showLoadingPopup();
        //$('#PbPlanificaestudiante').PbGridView('applyFilterData', { 'per_id': estudiante, 'unidad': unidad, 'modalidad': modalidad, 'carrera': carrera, 'periodo': periodo,'malla':malla });
        window.location.href = $('#txth_base').val() + "/academico/planificacion/newplanificacion?estudiante=" + estudiante + '&unidad=' + unidad + '&modalidad=' + modalidad +'&malla=' + malla + "&carrera=" + carrera + "&periodo=" + periodo + "&per_id=" + per_id + "&pla_id=" + pla_id;
        setTimeout(hideLoadingPopup, 2000);
    }
}



function generate() {
     var haspla = $('#frm_hasplanning').val(); 
    var periodo = $('#cmb_per_academico option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    
    

    if (haspla != undefined) {
 
  // showAlert('NO_OK', 'error','wtmessage'); 
  showAlert('NO_OK', 'error', {"wtmessage": 'Ya existe una planificacion activa para la modalidad elegida', "title": 'Informaci??n'});

  
} else {

 if (modalidad == 0) {

 showAlert('NO_OK', 'error', {"wtmessage": 'Seleccione una modalidad', "title": 'Informaci??n'});


    } else {

     showLoadingPopup();
    window.location.href = $('#txth_base').val() + "/academico/planificacion/generator?periodo=" + periodo + '&modalidad=' + modalidad + '&haspla=' + haspla;

}

}
}




function descargarPlanificacionestu(pla_id) {
    /* console.log("Entra a descargar", pla_id); */
    window.location.href = $('#txth_base').val() + "/academico/planificacion/descargarples?pla_id=" + pla_id;
}


function modificarplanificacionaut() {
    var arrParams = new Object();
    var accion = "Update";
    var link = $('#txth_base').val() + "/academico/planificacion/saveplanificacion";

    arrParams.jornadaest = $('#cmb_jornadaest').val();
    arrParams.carreraest = $('#txt_carrera').val();
    arrParams.modalidadest = $('#cmb_modalidadest').val();
    //arrParams.mallaest = $('#cmb_malladoest').val();
    arrParams.periodoest = $('#cmb_periodoest').val();
    arrParams.nombreest = $('#cmb_buscarest').val();
    arrParams.pla_id = $('#txth_pla_id').val();
    arrParams.per_id = $('#txth_per_id').val();
    //alert($('#txth_base').val()+':1-' + $('#cmb_jornadaest').val()+':2-'+$('#txt_carrera').val()+':3-'+$('#cmb_modalidadest').val()+':4-'+$('#cmb_periodoest').val()+':5-'+$('#cmb_buscarest').val()+':6-'+$('#txth_pla_id').val()+':7-'+$('#txth_per_id').val());
    //if ($('#cmb_jornadaest').val() != '0' && $('#txt_carrera').text() != 'Seleccionar' && $('#cmb_modalidadest').val() != '0' && /*$('#cmb_malladoest').val() != '0' &&*/ $('#cmb_periodoest').val() != '0' && $('#cmb_buscarest').val() > '0') {
    if (sessionStorage.dts_datosItemplan) {
    var arr_Grid = JSON.parse(sessionStorage.dts_datosItemplan);
    if (arr_Grid.length > 0) {
        arrParams.DATAS = sessionStorage.dts_datosItemplan
        arrParams.ACCION = accion;
        requestHttpAjax(link, arrParams, function (response) {
            var message = response.message;
            if (response.status == "OK") {
                showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
                limpiarDetalle();
                sessionStorage.removeItem('dts_datosItemplan')
                
                setTimeout(function () {
                    //window.location.href = $('#txth_base').val() + "/academico/planificacion/newplanificacion?estudiante=" .$('#cmb_buscarest').val()+"&pla_id=".$('#txth_pla_id').val();
                    window.location.href = $('#txth_base').val() + "/academico/planificacion/newplanificacion";
                }, 2000);
                
            } else {
                showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
            }
        }, true);
    } else {
        showAlert('NO_OK', 'error', { "wtmessage": "No Existe datos ", "title": 'Informaci??n' });
    }
     } else {
        showAlert('NO_OK', 'error', {"wtmessage": "No ha ingresado nuevas asignaturas del estudiante", "title": 'Informaci??n'});
    }
    /*} else {
        showAlert('NO_OK', 'error', {"wtmessage": "Todos los datos de la cabecera planificaci??n son obligatorios", "title": 'Informaci??n'});
    }*/

}

function limpiarBuscador(){
    //alert($('#txth_base').val());
    window.location.href = $('#txth_base').val() + "/academico/planificacion/newplanificacion";
}