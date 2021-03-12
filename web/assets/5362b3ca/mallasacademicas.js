/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $('#btn_buscarDataMalla').click(function() {
        actualizarGridMallas();
    });

    $('#btn_buscarDataDetmalla').click(function() {
        actualizarGridDetmallas();
    });

    
});

function actualizarGridMallas() {
    var search = $('#txt_buscarData').val();
      
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Tbg_Mallas').PbGridView('applyFilterData', {'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function actualizarGridDetmallas() {
    var search = $('#txt_buscarDataDetmalla').val();
      
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Tbg_DetalleMallas').PbGridView('applyFilterData', {'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {     
    var malla_id = $('#txth_malla_id').val();
    var search = $('#txt_buscarDataDetmalla').val();    
    window.location.href = $('#txth_base').val() + "/academico/mallasacademicas/expexcel?search=" + search + "&malla_id=" + malla_id;
}

function exportPdf() {
    var malla_id = $('#txth_malla_id').val();
    var search = $('#txt_buscarDataDetmalla').val();    
    window.location.href = $('#txth_base').val() + "/academico/mallasacademicas/exppdf?pdf=1&search=" + search + "&malla_id=" + malla_id;
}