$(document).ready(function () {
    $('#btn_buscarData').click(function () {
        searchModules();
    });
    $(".spanAccStatus").click(function () {
        if ($(this).prev().val() == "1") {
            $(this).find('i:first-child').attr("class", "glyphicon glyphicon-unchecked");
            $(this).prev().val("0");
        } else {
            $(this).find('i:first-child').attr("class", "glyphicon glyphicon-check");
            $(this).prev().val("1");
        }
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipocontribuyente/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipocontribuyente/update";
    var arrParams = new Object();
    arrParams.id = $('#frm_id').val();
    arrParams.nombre = $('#frm_nombre').val();
    arrParams.fecha = $('#frm_fecha').val();
    arrParams.porrf = $('#frm_porrf').val();
    arrParams.porri = $('#frm_porri').val();
     arrParams.graiva = $('#frm_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

/**
 * Function to save Item from model or record
 */
function save() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipocontribuyente/save";
    var arrParams = new Object();
    arrParams.id = $('#frm_codigo').val();
    arrParams.nombre = $('#frm_nombre').val();
    arrParams.fecha = $('#frm_fecha').val();
    arrParams.porrf = $('#frm_porrf').val();
    arrParams.porri = $('#frm_porri').val();
    arrParams.graiva = $('#frm_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipocontribuyente/index";
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
function deleteItem(id) {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipocontribuyente/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if (response.status == "OK") {
            searchModules();
        }
        setTimeout(function () {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}

/**
 * Function to download Excel from gridview
 */
function exportExcel() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipocontribuyente/expexcel?search=" + search;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipocontribuyente/exppdf?pdf=1&search=" + search;
}