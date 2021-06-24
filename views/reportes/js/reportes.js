

$(document).ready(function () {
//    $('#btn_buscarActividad').click(function () {
//        buscarActividades();
//    }); 

    $('#btn_buscarData').click(function () {
        buscarInscriptos();
    });

    $('#btn_buscarDatacartera').click(function () {
        buscarDatacartera();
    });  

    $('#btn_buscarDatapromedios').click(function () {
        buscarDatapromedios();
    });

    $('#btn_buscarDatamatriculados').click(function () {
        buscarDatamatriculados();
    });

    $('#modalidadestudiounidadsearch-uaca_id').change(function () {
        var link = $('#txth_base').val() + "/reportes/reportemallas";
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "modalidadestudiounidadsearch-mod_id", "Todos");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.uaca_id = $('#modalidadestudiounidadsearch-uaca_id').val();
                    arrParams.mod_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "modalidadestudiounidadsearch-eaca_id", "Todos");
                        }
                    }, true);
                }
            }
        }, true);
    });

    $('#modalidadestudiounidadsearch-mod_id').change(function () {
        var link = $('#txth_base').val() + "/reportes/reportemallas";
        var arrParams = new Object();
        arrParams.uaca_id = $('#modalidadestudiounidadsearch-uaca_id').val();
        arrParams.mod_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "modalidadestudiounidadsearch-eaca_id", "Todos");
            }
        }, true);
    });

        $('#modalidadestudiounidadsearch-eaca_id').change(function () {
        var link = $('#txth_base').val() + "/reportes/reportemallas";
        var arrParams = new Object();
        arrParams.uaca_id = $('#modalidadestudiounidadsearch-uaca_id').val();
        arrParams.mod_id = $('#modalidadestudiounidadsearch-mod_id').val();
        arrParams.eaca_id = $(this).val();
        arrParams.getmalla = true;
        arrParams.empresa_id = 1;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.mallaca, "cmb_malla", "Todos");
            }
        }, true);
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

function exportAgendamiento(){
    buscarActividades("1");//Reporte de Oportunidad x Actividad
}

function exportLostContact(){
    buscarActividades("2");//Reporte de Oportunidad x Proxima Oportunidad
}
function buscarActividades(op) {
    var searchdni = $('#txt_buscarDataPersona').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var empresa_id = $('#cmb_empresa').val();
    //var f_estado = '';//$('#cmb_estado').val();
    //Buscar al menos una clase con el nombre para ejecutar
    window.location.href = $('#txth_base').val() + "/reportes/expexcelreport?op="+op+"&f_ini="+f_ini+"&f_fin="+f_fin+"&searchdni="+searchdni+"&empresa_id="+empresa_id;
}
function pendingApplicants(){
    buscarActividades("3");//Reporte de Aspirantes pendientes
}
function payApplicants(){
    buscarActividades("4");//Reporte de Oportunidad x Proxima Oportunidad
}

function buscarInscriptos() {
    var anio = $('#cmb_anio option:selected').text(); 
    //var f_estado = '';//$('#cmb_estado').val();
    //Buscar al menos una clase con el nombre para ejecutar
    window.location.href = $('#txth_base').val() + "/reportes/expexcelinscriptos?anio="+anio;
}

function buscarDatacartera() {
    var search = $('#txt_buscarDatacartera').val();
    var f_inif = $('#txt_fecha_inifact').val();
    var f_finf = $('#txt_fecha_finfact').val();
    var f_iniv = $('#txt_fecha_inifactve').val();
    var f_finv = $('#txt_fecha_finfactve').val();   
    var estadopago = $('#cmb_estadocartera').val();    

    window.location.href = $('#txth_base').val() + "/reportes/expexcelreportcartera?search=" + search + "&f_inif=" + f_inif + "&f_finf=" + f_finf + '&f_iniv=' + f_iniv + "&f_finv=" + f_finv + "&estadopago=" + estadopago;
}

function exportExceldistributivo() {
    var periodo = $('#distributivoacademicosearch-paca_id').val();
    var tipo_asignacion =  $('#distributivoacademicosearch-tdis_id').val();
    var modalidad =  $('#distributivoacademicosearch-mod_id').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexceldistributivo?periodo=" + periodo + "&tipo_asignacion=" + tipo_asignacion + "&modalidad=" + modalidad;
}
function exportExcelmateriasnoasignadas() {
    var modalidad =  $('#distributivoacademicosearch-mod_id').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexcelmateriasnoasignadas?modalidad=" + modalidad;
}
function exportExcelmatrizdistributivo() {
    var periodo = $('#distributivoacademicosearch-paca_id').val();
    var dedicacion =  $('#distributivoacademicosearch-uaca_id').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexcelmatrizdistributivo?periodo=" + periodo + "&dedicacion=" + dedicacion;
}
function exportExcelmateriasparalelo() {
    var periodo = $('#distributivoacademicosearch-paca_id').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexcelmateriasparalelo?periodo=" + periodo;
}
function exportExcelmatriculados() {
    var periodo = $('#planificacionsearch-pla_id').val();
    var modalidad =  $('#planificacionsearch-mod_id').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexcelmatriculados?periodo=" + periodo + "&modalidad=" + modalidad;
}
function exportExcel() {
    var periodos = $('#distributivoacademicosearch-paca_id').val();
    var modalidades =  $('#distributivoacademicosearch-mod_id').val();
    var asignaturas =  $('#distributivoacademicosearch-asi_id').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexcel?periodos=" + periodos + "&modalidades=" + modalidades + "&asignaturas=" + asignaturas;
}
function exportExcelmallas() {
    var unidad = $('#modalidadestudiounidadsearch-uaca_id').val();
    var modalidad =  $('#modalidadestudiounidadsearch-mod_id').val();
    var carrera = $('#modalidadestudiounidadsearch-eaca_id').val();
    var malla =  $('#cmb_malla').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexcelmallas?unidad=" + unidad + "&modalidad=" + modalidad + "&carrera=" + carrera + "&malla=" + malla;
}
function exportExcelinscritosreporte() {
    var periodo = $('#distributivoacademicosearch-paca_id').val();
    var modalidad =  $('#distributivoacademicosearch-mod_id').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexcelinscritos?periodo=" + periodo + "&modalidad=" + modalidad;
}
function exportExcelpromedios() {
    var estudiante = $('#estudiantecarreraprogramasearch-est_id').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexcelpromedios?estudiante=" + estudiante;
}
function exportExceldistributivoposgrado() {
    var tipo_asignacion =  $('#distributivoacademicosearch-tdis_id').val();
    var modalidad =  $('#distributivoacademicosearch-mod_id').val();
    window.location.href = $('#txth_base').val() + "/reportes/expexceldistributivoposgrado?&tipo_asignacion=" + tipo_asignacion + "&modalidad=" + modalidad;
}






