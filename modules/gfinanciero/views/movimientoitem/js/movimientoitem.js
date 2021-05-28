$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
});

/**
 * Function to apply filter action to gridview
 */
function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    //arrParams.search = $("#txt_search").val();
    arrParams.CodArt = $("#autocomplete-articulo").val();
    arrParams.CodBod = $("#cmb_bodega").val();
    //arrParams.FecDes = $("#dtp_fec_ini").val();
    arrParams.FecHas = $("#dtp_fec_fin").val();
    if (arrParams.CodBod == 0) {
        var msg = objLang.Select_an_Cellar;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrParams.CodArt == "") {
        var msg = objLang.Please_select_an_Item_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    buscarInfo(arrParams);
}

/**
 * Function to Search Item from model or record
 * 
 * @param {int} id - Id of Element to Delete from model or record
 * @return {void} No return any value.
 */
 function buscarInfo(arrParams) {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/movimientoitem/index";
    var arrParamsCon = new Object();
    arrParamsCon.saldoBodega=true;
    arrParamsCon.CodArt = arrParams.CodArt;
    arrParamsCon.CodBod = arrParams.CodBod;
    arrParamsCon.FecHas = $("#dtp_fec_fin").val();
    requestHttpAjax(link, arrParamsCon, function(response) {
        if (response.status == "OK") {
            var data=response.message;
            $("#dtp_fec_ini").val(data['FEC_DES']);
            $("#frm_ingreso").val(data['TOT_ING']);
            $("#frm_egreso").val(data['TOT_EGR']);
            $("#frm_saldo").val(data['TOT_ING']-data['TOT_EGR']);
            $("#grid_list").PbGridView("applyFilterData", arrParams);
        }else{
            setTimeout(function() { 
                shortModal(objLang.No_data, objLang.Error, "error");             
            }, 1000);
        }
    }, true);
}


/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
 function putArticulo(data) {    
    let id = data[0];
    let name = data[1];
    $('#txt_des_com').val(name);
}

/**
 * Function to download Pdf from gridview
 */
function exportExcel() {
    var CodArt = $("#autocomplete-articulo").val();
    var CodBod = $("#cmb_bodega").val();
    var FecHas = $("#dtp_fec_fin").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/movimientoitem/expexcel?codbod=" + CodBod + "&codart=" + CodArt + "&fechas=" + FecHas;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var CodArt = $("#autocomplete-articulo").val();
    var CodBod = $("#cmb_bodega").val();
    var FecHas = $("#dtp_fec_fin").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/movimientoitem/exppdf?pdf=1&codbod=" + CodBod + "&codart=" + CodArt + "&fechas=" + FecHas;
}