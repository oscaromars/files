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
    arrParams.type = $("#cmb_tipo").val();
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}

/**
 * Function to go edit form
 */
function edit() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/divisa/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/divisa/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_nombre').val();
    arrParams.cotizacion = $('#frm_cotizacion').val();
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/divisa/save";
    var arrParams = new Object();
    arrParams.id = $('#frm_codigo').val();
    arrParams.nombre = $('#frm_nombre').val();
    arrParams.cotizacion = $('#frm_cotizacion').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/divisa/index";
                }, 3000);
            }
        }, true);
    }
}

/**
 * Function to delete Item from model or record
 *
 * @param {int} id - Id of Element to Delete from model or record
 * @param {int} cod - Cod of Element to Delete from model or record
 * @return {void} No return any value.
 */
function deleteItem(id, cod) {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/divisa/delete";
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
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/divisa/expexcel?search=" + search;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/divisa/exppdf?pdf=1&search=" + search;
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putCuentaPrincipalData(data) {
    let id = data[0];
    let name = data[1];
    let tipo = data[2];
    if (tipo.trim().toUpperCase() != 'MOVIMIENTO') {
        var msg = objLang.Please_select_only_Movement_Accounts_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-cuentaprincipal').val('');
        $('#frm_cuentaprincipaldesc').val('');
        return;
    }
    $('#frm_cuentaprincipaldesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putCuentaProvisionalData(data) {
    let id = data[0];
    let name = data[1];
    let tipo = data[2];
    if (tipo.trim().toUpperCase() != 'MOVIMIENTO') {
        var msg = objLang.Please_select_only_Movement_Accounts_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-cuentaprovisional').val('');
        $('#frm_cuentaprovisionaldesc').val('');
        return;
    }
    $('#frm_cuentaprovisionaldesc').val(name);
}