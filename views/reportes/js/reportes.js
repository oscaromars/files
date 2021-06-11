

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
      

    
});

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

function buscarDatapromedios(){
    var estudiante = $('#cmb_estudiante').val();
    //alert($('#cmb_estudiante').val());

    //if(!$(".blockUI").length) {
        //showLoadingPopup();
        $('#Tbg_Registro_promedios').PbGridView('applyFilterData', {'estudiante': estudiante});
        //setTimeout(hideLoadingPopup, 2000);
    //}
}
function buscarDatamatriculados(){
    var carrera = $('#cmb_carrera').val();
        $('#Tbg_Registro_matriculados').PbGridView('applyFilterData', {'carrera': carrera});
}