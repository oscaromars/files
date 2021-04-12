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
    arrParams.search = $("#txt_search").val();
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}
/**
 * Function to go edit form
 */
function edit() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/bodega/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/bodega/update";
    var arrParams = new Object();
    arrParams.id = $('#frm_id').val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.direccion = $('#frm_direccion').val();
    arrParams.pais = $('#frm_pais').val();
    arrParams.ciudad = $('#frm_ciudad').val();
    arrParams.telefono = $('#frm_telefono').val();
    arrParams.correo = $('#frm_correo').val();
    arrParams.responsable = $('#frm_responsable').val();
    arrParams.punto = $('#cmb_punto').val();
    arrParams.num_ing = $('#frm_num_ing').val();
    arrParams.num_egr = $('#frm_num_egr').val();
    arrParams.fecha = $('#frm_fecha').val();
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/bodega/save";
    var arrParams = new Object();
    arrParams.id = $('#frm_id').val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.direccion = $('#frm_direccion').val();
    arrParams.pais = $('#frm_pais').val();
    arrParams.ciudad = $('#frm_ciudad').val();
    arrParams.telefono = $('#frm_telefono').val();
    arrParams.correo = $('#frm_correo').val();
    arrParams.responsable = $('#frm_responsable').val();
    arrParams.punto = $('#cmb_punto').val();
    arrParams.num_ing = $('#frm_num_ing').val();
    arrParams.num_egr = $('#frm_num_egr').val();
    arrParams.fecha = $('#frm_fecha').val();
    
    if (arrParams.punto == 0) {
        var msg = objLang.Please_select_an_Item_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/bodega/index";
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
function deleteItem(id) {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/bodega/delete";
    var arrParams = new Object();
    arrParams.id = id;
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
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/bodega/expexcel?search=" + search;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/bodega/exppdf?pdf=1&search=" + search;
}