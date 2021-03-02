
$(document).ready(function () {
    $('#btn_buscarInteresado').click(function () {
        actualizarGridInteresado();
    });
});
function actualizarGridInteresado(){
    var interesado = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var empresa = $('#cmb_empresa option:selected').val();
    // var unidad = $('#cmb_unidad option:selected').val();
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Interesado').PbGridView('applyFilterData', {'search': interesado, 'f_ini': f_ini, 'f_fin': f_fin, 'company': empresa/*, 'unidad': unidad*/});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var empresa = $('#cmb_empresa option:selected').val(); 
    // var unidad = $('#cmb_unidad option:selected').val();
    window.location.href = $('#txth_base').val() + "/admision/interesados/expexcel?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&empresa=" + empresa ;
   
}

function exportPdfAspirante(){
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var empresa = $('#cmb_empresa option:selected').val();  
    // var unidad = $('#cmb_unidad option:selected').val();
    window.location.href = $('#txth_base').val() + "/admision/interesados/exppdfaspirantes?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&empresa=" + empresa;   
}
