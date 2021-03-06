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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tiporol/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tiporol/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.horas = $('#frm_hour').val();
    arrParams.porcentaje = $('#frm_percentage').val();
    if (parseInt(arrParams.horas) <= 0) {
        var msg = objLang.Please_Hours_must_be_greater_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (parseInt(arrParams.porcentaje) <= 0 || parseInt(arrParams.porcentaje) > 100) {
        var msg = objLang.Please_Percentage_must_be_greater_than_0_and_less_than_100_;
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tiporol/save";
    var arrParams = new Object();
    arrParams.id = $('#frm_id').val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.horas = $('#frm_hour').val();
    arrParams.porcentaje = $('#frm_percentage').val();
    if (parseInt(arrParams.horas) <= 0) {
        var msg = objLang.Please_Hours_must_be_greater_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (parseInt(arrParams.porcentaje) <= 0 || parseInt(arrParams.porcentaje) > 100) {
        var msg = objLang.Please_Percentage_must_be_greater_than_0_and_less_than_100_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tiporol/index";
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tiporol/delete";
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
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tiporol/expexcel?search=" + search;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tiporol/exppdf?pdf=1&search=" + search;
}