$(document).ready(function () {
    $('#cmb_unidad').change(function () {
        var link = $('#txth_base').val() + "/academico/adminmetodoingreso/newperiodo";
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();       
        arrParams.getmodalidad = true;        
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.modalidad, "cmb_modalidad");                          
            }
        }, true);
        //métodos.
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();     
        arrParams.metodo = $('#cmb_metodo_ingreso').val();
        arrParams.getmetodo = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.metodos, "cmb_metodo_ingreso");                
            }
        }, true);
    });
    
    $('#cmb_unidad_modifica').change(function () {
        var link = $('#txth_base').val() + "/academico/adminmetodoingreso/update";
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();       
        arrParams.getmodalidad = true;        
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.modalidad, "cmb_modalidad");                          
            }
        }, true);
        //métodos.
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();     
        arrParams.metodo = $('#cmb_metodo_ingreso').val();
        arrParams.getmetodo = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.metodos, "cmb_metodo_ingreso");                
            }
        }, true);
    });
    
   
    
    $('#btn_buscarDataPeriodo').click(function () {
        actualizarperiodcanGrid();
    });
});

function actualizarperiodcanGrid() {
    var search = $('#txt_buscarDatapc').val();
    var f_ini = $('#txt_fecha_inipc').val();
    var f_fin = $('#txt_fecha_finpc').val();
    var mes = $('#cmb_mes option:selected').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Pbgperiodo').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'mes': mes, 'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportPdf(){
    var ObjData = new Object();
    ObjData.search = $('#txt_buscarDatapc').val();
    ObjData.f_ini = $('#txt_fecha_inipc').val();
    ObjData.f_fin = $('#txt_fecha_finpc').val();
    ObjData.mes = $('#cmb_mes option:selected').val();
    var rptData = base64_encode(JSON.stringify(ObjData));
    window.location.href = $('#txth_base').val() + "/academico/adminmetodoingreso/exportpdf?pdf=true&rptData="+rptData;
}


function grabarPeriodo() {
        var arrParams = new Object();
        var link = $('#txth_base').val() + "/academico/adminmetodoingreso/grabarperiodoxmetodoing";
        arrParams.anio = $('#txt_anio').val();
        arrParams.mes = $('#cmb_mes').val();
        arrParams.uaca = $('#cmb_unidad').val();
        arrParams.ming = $('#cmb_metodo_ingreso').val();        
        arrParams.mod = $('#cmb_modalidad').val();        
        arrParams.fecdesde = $('#txt_fecha_desde').val();
        arrParams.fechasta = $('#txt_fecha_hasta').val();

        var mes = "";
        if ($('#cmb_mes').val().length == 1) {
            var mes = "0" + $('#cmb_mes').val();
        } else {
            mes = $('#cmb_mes').val();
        }      
        var combo_modalidad = document.getElementById("cmb_modalidad");
        var des_modalidad = combo_modalidad.options[combo_modalidad.selectedIndex].text;
        switch (arrParams.ming) {
            case '1': 
                arrParams.codigo = "CAN" + des_modalidad.substr(0,3).toUpperCase() + mes + ($('#txt_anio').val()).substr(2, 2);
                break;
            case '2': 
                arrParams.codigo = "EXA" + des_modalidad.substr(0,3).toUpperCase() + mes + ($('#txt_anio').val()).substr(2, 2);
                break;
            case '3': 
                arrParams.codigo = "HOM" + des_modalidad.substr(0,3).toUpperCase() + mes + ($('#txt_anio').val()).substr(2, 2);
                break;
            case '4': 
                arrParams.codigo = "TIN" + des_modalidad.substr(0,3).toUpperCase() + mes + ($('#txt_anio').val()).substr(2, 2);
                break;
            default:                
                break;
        }        
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    parent.window.location.href = $('#txth_base').val() + "/academico/adminmetodoingreso/index";
                }, 2000);
            }, true);
        }
    };

    function grabarParalelo() {
        var arrParams = new Object();
        var link = $('#txth_base').val() + "/academico/adminmetodoingreso/grabarparalelo";
        arrParams.nombre = $('#txt_nombre').val();
        arrParams.descripcion = $('#txt_descripcion').val();
        arrParams.cupo = $('#txt_cupo').val();
        arrParams.pmin_id = $('#txth_id').val();       

        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    parent.window.location.href = $('#txth_base').val() + "/academico/adminmetodoingreso/index";
                }, 2000);
            }, true);
        }
    }; 

    function modificarPeriodo() {
        var arrParams = new Object();
        var link = $('#txth_base').val() + "/academico/adminmetodoingreso/updateperiodoxmetodoing";
        arrParams.pmin_id = $('#txth_pmin_id').val();
        arrParams.anio = $('#txt_anio').val();
        arrParams.mes = $('#cmb_mes').val();
        arrParams.nint = $('#cmb_unidad_modifica').val();
        arrParams.ming = $('#cmb_metodo_ingreso').val();
        arrParams.mod = $('#cmb_modalidad').val();
        arrParams.fecdesde = $('#txt_fecha_desde').val();
        arrParams.fechasta = $('#txt_fecha_hasta').val();
        var mes = "";
        if ($('#cmb_mes').val().length == 1) {
            var mes = "0" + $('#cmb_mes').val();
        } else {
            mes = $('#cmb_mes').val();
        }      
        var combo_modalidad = document.getElementById("cmb_modalidad");
        var des_modalidad = combo_modalidad.options[combo_modalidad.selectedIndex].text;
        switch (arrParams.ming) {
            case '1': 
                arrParams.codigo = "CAN" + des_modalidad.substr(0,3).toUpperCase() + mes + ($('#txt_anio').val()).substr(2, 2);
                break;
            case '2': 
                arrParams.codigo = "EXA" + des_modalidad.substr(0,3).toUpperCase() + mes + ($('#txt_anio').val()).substr(2, 2);
                break;
            case '3': 
                arrParams.codigo = "HOM" + des_modalidad.substr(0,3).toUpperCase() + mes + ($('#txt_anio').val()).substr(2, 2);
                break;
            case '4': 
                arrParams.codigo = "TIN" + des_modalidad.substr(0,3).toUpperCase() + mes + ($('#txt_anio').val()).substr(2, 2);
                break;
            default:                
                break;
        }        
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    parent.window.location.href = $('#txth_base').val() + "/academico/adminmetodoingreso/index";
                }, 2000);
            }, true);
        }
    };


