$(document).ready(function () {
    $('#btn_buscarMarcacion').click(function () {
        actualizarGridMarcacion();
    });
    
    $('#btn_cargarHorario').click(function () {
        cargarHorario();
    });
    
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
        if ($(this).val() == 4 || $(this).val() ==1) {
            $('#divFechasDistancia').css('display', 'block');
        } else {
            $('#divFechasDistancia').css('display', 'none');
        }            
    });
    
    $('#btn_buscarHorario').click(function () {
        actualizarGridHorario();
    });
    
    $('#btn_buscarNoMarcacion').click(function () {
        cargarNoMarcadas();
    });
    
});

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
    var estado = $('#cmb_estado option:selected').val();

    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#PbMarcacion').PbGridView('applyFilterData', {'profesor': profesor, 'materia': materia, 'f_ini': f_ini, 'f_fin': f_fin, 'periodo': periodo, 'estado': estado});
        setTimeout(hideLoadingPopup, 2000);
    }
}
function exportExcel() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var materia = $('#txt_buscarDataMateria').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();
    var estado = $('#cmb_estado option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/marcacion/expexcel?profesor=" + profesor + "&materia=" + materia + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo + "&estado=" + estado;
}
function exportPdf() {
    var profesor = $('#txt_buscarDataProfesor').val();
    var materia = $('#txt_buscarDataMateria').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();
    var estado = $('#cmb_estado option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/marcacion/exppdf?pdf=1&profesor=" + profesor + "&materia=" + materia + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo + "&estado=" + estado;
}

function cargarHorario() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/marcacion/cargarhorario";
    arrParams.procesar_file = true;    
    arrParams.periodo_id = $('#cmb_periodo option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_horario2').val() + "." + $('#txth_doc_adj_horario').val().split('.').pop();    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                window.location.href = $('#txth_base').val() + "/academico/marcacion/index";
            }, 3000);
        }, true);
    }
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
        $('#PbHorario').PbGridView('applyFilterData', {'profesor': profesor, 'unidad': unidad, 'modalidad': modalidad, 'f_ini': f_ini, 'f_fin': f_fin, 'periodo': periodo});
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
    window.location.href = $('#txth_base').val() + "/academico/marcacion/expexcelhorario?profesor=" + profesor + "&unidad="+ unidad + '&modalidad='+ modalidad + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo;
}

function exportPdfhorario() {
    var profesor = $('#txt_buscarDataProfesor').val();   
    var unidad = $('#cmb_unidad option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var periodo = $('#cmb_periodo option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/marcacion/exppdfhorario?pdf=1&profesor=" + profesor + "&unidad="+ unidad + '&modalidad='+ modalidad + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo;
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
        $('#PbNomarcacion').PbGridView('applyFilterData', {'profesor': profesor, 'materia': materia, 'unidad': unidad, 'modalidad': modalidad, 'f_ini': f_ini, 'f_fin': f_fin, 'periodo': periodo, 'tipo': tipo});
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

    window.location.href = $('#txth_base').val() + "/academico/marcacion/expexcelnomarcadas?profesor=" + profesor + "&materia="+ materia + "&unidad="+ unidad + '&modalidad='+ modalidad + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo + "&tipo=" + tipo;
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

    window.location.href = $('#txth_base').val() + "/academico/marcacion/exppdfnomarcadas?pdf=1&profesor=" + profesor + "&materia="+ materia + "&unidad="+ unidad + '&modalidad='+ modalidad + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&periodo=" + periodo + "&tipo=" + tipo;
}