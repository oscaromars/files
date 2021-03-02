

$(document).ready(function () {
//    $('#btn_buscarActividad').click(function () {
//        buscarActividades();
//    }); 

    $('#btn_buscarData').click(function () {
        buscarInscriptos();
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