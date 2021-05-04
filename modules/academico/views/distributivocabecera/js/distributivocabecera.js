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
    arrParams.profesor = $("#cmb_profesor").val();
    arrParams.asignacion = $("#cmb_tipo_asignacion").val(); 
    $("#Tbg_Distributivo_Aca").PbGridView("applyFilterData", arrParams);
}

function aprobarDistributivo(){
    var keys = $('#grid').yiiGridView('getSelectedRows');
    console.log('keys: ' + keys);
    //alert('prubea');
    
     var link = $('#txth_base').val() + "/academico/distributivocabecera/aprobar";
    var arrParams = new Object();
    arrParams.id = $('#grid').yiiGridView('getSelectedRows');
    arrParams.resultado = 2;
    arrParams.observacion = ''; //$('#txt_detalle').val();
    //alert('id:'+id);

    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
            setTimeout(function() {
                var link = $('#txth_base').val() + "/academico/distributivocabecera/aprobardistributivo";
                window.location = link;
            }, 1000);
        }
    }, true);
} 

function exportexcellistadodocente() {
    var search = $('#txt_buscarData').val();    
    var periodo = $('#cmb_periodo').val();    
    var estado = $("#cmb_estado").val();
    var asignacion = $("#cmb_tipo_asignacion").val(); 
    window.location.href = $('#txth_base').val() + "/academico/distributivocabecera/exportexcellistadodocente?" +
        "search=" + search +        
        "&periodo=" + periodo + 
        "&estado=" + estado + 
        "&asignacion=" + asignacion; 
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
    arrParams.resultado = '1';
    arrParams.observacion = $('#txt_detalle').val();
    //alert('id:'+id);
     console.log('resultado: ' + $('#cmb_estado').val());
     console.log('resultado1: ' + $('#w1').val());
     
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
          /*  setTimeout(function() {
                var link = $('#txth_base').val() + "/academico/distributivocabecera/index";
                window.location = link;
            }, 1000);*/
        }
    }, true);
     
}
