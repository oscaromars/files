/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    $('#btn_buscarData_dist').click(function() {
        searchModules();
    });
    
    $('#cmb_estado').change(function () {        
        estado = $('#cmb_estado').val();        
        if (estado == 3) {
            $('#observacion').css('display', 'block');                       
        } else {
            $('#observacion').css('display', 'none');                       
        }
    });
 });

function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_buscarData").val();    
    arrParams.periodo = $("#cmb_periodo").val();    
    arrParams.estado = $("#cmb_estado").val();
    arrParams.asignacion = $("#cmb_tipo_asignacion").val(); 
    $("#Tbg_Distributivo_Aca").PbGridView("applyFilterData", arrParams);
}

function exportExcel() {
    var search = $('#txt_buscarData').val();    
    var periodo = $('#cmb_periodo').val();    
    var estado = $("#cmb_estado").val();
    var asignacion = $("#cmb_tipo_asignacion").val(); 
    window.location.href = $('#txth_base').val() + "/academico/distributivocabecera/exportexcel?" +
        "search=" + search +        
        "&periodo=" + periodo + 
        "&estado=" + estado + 
        "&asignacion=" + asignacion;   
}

function exportPdf() {
    var search = $('#txt_buscarData').val();    
    var periodo = $('#cmb_periodo').val();    
    var estado = $("#cmb_estado").val();
    var asignacion = $("#cmb_tipo_asignacion").val(); 
    window.location.href = $('#txth_base').val() + "/academico/distributivocabecera/exportpdf?pdf=1" +
        "&search=" + search +        
        "&periodo=" + periodo +
        "&estado=" + estado +
        "&asignacion=" + asignacion;      
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/academico/distributivocabecera/deletecab";
    var arrParams = new Object();
    arrParams.id = id;
    //alert('id:'+id);
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);        
        if (response.status == "OK") {              
            setTimeout(function() {   
                searchModules();
            }, 1000);
        }
    }, true);
}

function saveReview() {
    var link = $('#txth_base').val() + "/academico/distributivocabecera/savereview";
    var arrParams = new Object();
    arrParams.id = $('#txth_ids').val();
    arrParams.resultado = $('#cmb_estado').val();
    arrParams.observacion = $('#txt_detalle').val();
    //alert('id:'+id);
    
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
            setTimeout(function() {
                var link = $('#txth_base').val() + "/academico/distributivocabecera/index";
                window.location = link;
            }, 1000);
        }
    }, true);
     
}

function saveReversar() {
    var link = $('#txth_base').val() + "/academico/distributivocabecera/savereversar";
    var arrParams = new Object();
    arrParams.id = $('#txth_ids').val();
    arrParams.resultado = $('#cmb_estado').val();
    arrParams.observacion = $('#txt_detalle').val();
    //alert('id:'+id);
    
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
            setTimeout(function() {
                var link = $('#txth_base').val() + "/academico/distributivocabecera/index";
                window.location = link;
            }, 1000);
        }
    }, true);
     
}
