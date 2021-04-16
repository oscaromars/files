$(document).ready(function() {
    $('#btn_guardareducativa').click(function () {
        cargarUsuario();
    });

    $('#btn_buscarData_estregsitro').click(function () {
        actualizarGridEstregistro();
    });

    $(document).ready(function() {  
        $('#btn_guardarcurso').click(function() {
            cargarDocumento();
        });
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
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {      
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/usuarioeducativa/upload";
                }, 5000);  
        }, true);
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