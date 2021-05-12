$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
    
    $('#txt_p_costo').focusout(function() {
        let ref = parseFloat(removeMilesFormat($(this).val()));
        $(this).val(currencyFormat(ref, 4));
    });
    
    $('#btn_reporte').click(function() {
        imprimirCostos();
    });
    
    $('#btn_reporteBodega').click(function() {
        imprimirExisteBodega();
    });
    
     $('#btn_procesar').click(function() {
        putCierreMensual();
    });
});

/**
 * Function to apply filter action to gridview
 */
function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.codBod = $("#cmb_bodega").val();
    arrParams.search = $("#txt_search").val();
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}
/**
 * Function to go edit form
 */
function edit() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/edit" + "?id=" + $("#txth_cod_bod").val() + '&id2=' + $("#txth_cod_art").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/update";
    var arrParams = new Object();    
    arrParams.cod_bod = $('#txt_cod_bod').val();
    arrParams.cod_art = $('#autocomplete-articulo').val();
    arrParams.ubi_fis = $('#txt_ubi_fis').val();
    arrParams.p_costo = removeMilesFormat($('#txt_p_costo').val());   
    //arrParams.fecha = $('#frm_fecha').val();
    if (arrParams.punto == 0) {
        var msg = objLang.Please_select_an_Item_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

/**
 * Function to save Item from model or record
 */
function save() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/save";
    var arrParams = new Object();
    arrParams.cod_bod = $('#autocomplete-bodega').val();
    arrParams.cod_art = $('#autocomplete-articulo').val();
    arrParams.ubi_fis = $('#txt_ubi_fis').val();
    arrParams.p_costo = removeMilesFormat($('#txt_p_costo').val());    
    //arrParams.fecha = $('#frm_fecha').val();
    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/index";
                }, 3000);
            }
        }, true);
    }
}

/**
 * Function to delete Item from model or record
 * 
 * @param {int} id - Id of Element to Delete from model or record
 * @return {void} No return any value.
 */
function deleteItem(id,id2) {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/delete";
    var arrParams = new Object();
    arrParams.id = id;
    arrParams.id2 = id2;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            searchModules();
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}

/**
 * Function to download Excel from gridview
 */
function exportExcel() {
    var search = $('#txt_search').val();
    var bodega = $('#cmb_bodega').val();    
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/expexcel?search=" + search + '&codBod=' + bodega;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    var bodega = $('#cmb_bodega').val();
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/exppdf?pdf=1&search=" + search + '&codBod=' + bodega;
    let status = "OK";
    let label = "Success";
    let message = new Object();
    message.title = objLang.Existence_Cellar;
    let acciones = new Array();
    let btnPdf = new Object();
    let btnXls = new Object();
    //let params = new Array();
    btnPdf.id = "btnInvPdf";
    btnPdf.class = "clclass btn-primary";
    btnPdf.value = objLang.PDF;
    btnPdf.callback = "printIframe";

    btnXls.id = "btnInvXls";
    btnXls.class = "btn-success clclass";
    btnXls.value = objLang.EXCEL;
    btnXls.callback = 'exportExcel';

    message.acciones = acciones;
    message.htmloptions = new Object();
    message.acciones[0] = btnXls;
    message.acciones[1] = btnPdf;

    // title box
    message.wtmessage =
        '<div class="box-header with-border">' +
        '<h4 class="box-title">' + objLang.Inventory_Print + '</h4>' +
        '</div>';
    // begin form
    message.wtmessage +=
        '<form class="form-horizontal">' +
        '<div class="box-body">';
    // body form
    message.wtmessage += '<iframe id="ifrpt" name="ifrpt" src="' + link + '" title="' + objLang.Report_Inventory_Review_List + '" width="100%" height="500"></iframe>';
    // end form
    message.wtmessage +=
        '</div>' +
        '</form>';

    showAlert(status, label, message);
    $('#btnInvPdf').attr("data-dismiss", 'none');
    $('#btnInvXls').attr("data-dismiss", 'none');
 
}

function printIframe() {
    window.frames["ifrpt"].focus();
    window.frames["ifrpt"].print();
}



/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putBodega(data) {    
    let id = data[0];
    let name = data[1];
    $('#txt_nom_bod').val(name);
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
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putCierreMensual () {   
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/cierremensual";
    var arrParams = new Object();
    //arrParams.cod_bod = $('#autocomplete-bodega').val();
    arrParams.cod_art = $('#autocomplete-articulo').val();
    arrParams.est_act = ($('#chk_exiTot').is(":checked")) ? 1 : 0;
    arrParams.fecha = $('#frm_fecha').val();    
    //if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                /*setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/cierremensual";
                }, 3000);*/
            }
        }, true);
    //}
}


/**
 * Function to download Pdf from gridview
 */
function imprimirCostos() {
    var search = $('#txt_search').val();   
    var cod_bod = $('#cmb_bodega').val();
    var cod_art = $('#autocomplete-articulo').val();
    var cod_lin = $('#cmb_linea').val();
    var cod_tip = $('#cmb_tipo').val();
    var cod_mar = $('#cmb_marca').val();
    var tip_pro = $('#cmb_marca').val();
    
    if (cod_bod == 0) {
        var msg = objLang.Select_an_Cellar_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/exicostpdf?pdf=1&bodega=" + cod_bod 
                + '&articulo=' + cod_art
                + '&linea=' + cod_lin
                + '&tipo=' + cod_tip
                + '&marca=' + cod_mar
                + '&tipro=' + tip_pro;
    
 
}

/**
 * Function to download Pdf from gridview
 */
function imprimirExisteBodega() {
    var search = $('#txt_search').val();   
    var cod_bod = $('#cmb_bodega').val();
    var cod_art = $('#autocomplete-articulo').val();
    var cod_lin = $('#cmb_linea').val();
    var cod_tip = $('#cmb_tipo').val();
    var cod_mar = $('#cmb_marca').val();
    var tip_pro = $('#cmb_marca').val();
    
    /*if (cod_bod == 0) {
        var msg = objLang.Select_an_Cellar_;
        shortModal(msg, objLang.Error, "error");
        return;
    }*/
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/existenciabodega/repexistenciapdf?pdf=1&bodega=" + cod_bod 
                + '&articulo=' + cod_art
                + '&linea=' + cod_lin
                + '&tipo=' + cod_tip
                + '&marca=' + cod_mar
                + '&tipro=' + tip_pro;
    
 
}

