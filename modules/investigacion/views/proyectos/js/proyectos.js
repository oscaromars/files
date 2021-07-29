$(document).ready(function () {

     setTimeout(function(){
        $("div.alert").remove();
    }, 3000 ); 

    recargarGridItem();
    $('#btn_buscarMarcacion').click(function () {
        actualizarGridMarcacion();
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

    $('#cmb_malladoest').change(function () {
        var link = $('#txth_base').val() + "/academico/planificacion/new";
        var arrParams = new Object();
        arrParams.maca_id = $(this).val();
        arrParams.empresa_id = 1;
        arrParams.getmateria = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignatura, "cmb_asignaest", "Seleccionar");
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

    $('#cmb_per_academico').change(function () {
        var arrParams2 = new Object();
        arrParams2.PBgetFilter = true;
        arrParams2.pla_periodo_academico = $("#cmb_per_academico").val();
        arrParams2.mod_id = $("#cmb_modalidad").val();
        /* console.log(arrParams2); */
        $("#grid_planificaciones_list").PbGridView("applyFilterData", arrParams2);
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



function actualizarGridRegistroConf() {
    var arrParams2 = new Object();
    arrParams2.PBgetFilter = true;
    
    $("#grid_regconf_list").PbGridView("applyFilterData", arrParams2);
}

function editReg() {
    var link = $('#txth_base').val() + "/investigacion/proyectos/edit/" + $("#frm_linv_id").val();
    window.location = link;
}

function updateReg() {
    var link = $('#txth_base').val() + "/investigacion/proyectos/edit/updatereg";
    var arrParams = new Object();
    arrParams.id = $('#frm_proy_id').val();
    arrParams.nombre_investigacion = $('#txt_nameline').val();
    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function () {
                   parent.window.location.href = $('#txth_base').val() + "/investigacion/proyectos/index";
                }, 3000);
            }
        }, true);
    }kbkb
}

function saveReg() {
    var link = $('#txth_base').val() + "/investigacion/proyectos/savereg";
    var arrParams = new Object();
    arrParams.nombre_investigacion = $('#txt_nameline').val();
    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    //location.reload();
                    parent.window.location.href = $('#txth_base').val() + "/investigacion/proyectos/index";
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/investigacion/lineainvestigacion/deletereg";
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

