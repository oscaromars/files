$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
    $('#cmb_pais').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/localidad/index";
        var arrParams = new Object();
        arrParams.getPais = "true";
        arrParams.pai_id = $(this).val()
        if (arrParams.pai_id != 0) {
            requestHttpAjax(link, arrParams, function(response) {
                if (response.status == "OK") {
                    data = response.message;
                    setComboData(data.provincias, "cmb_provincia");
                    setComboData(data.ciudades, "cmb_ciudad");
                }
            }, true);
        }
    });
    $('#cmb_provincia').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/localidad/index";
        var arrParams = new Object();
        arrParams.getProvincia = "true";
        arrParams.pro_id = $(this).val();
        if (arrParams.pro_id != 0) {
            requestHttpAjax(link, arrParams, function(response) {
                if (response.status == "OK") {
                    data = response.message;
                    setComboData(data.ciudades, "cmb_ciudad");
                }
            }, true);
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
    arrParams.tipo = $("#cmb_tipo").val();
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}

/**
 * Function to go edit form
 */
function edit() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/localidad/edit" + "?id=" + $("#frm_id").val() + "&cod=" + $("#frm_id_cod").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/localidad/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.cod = $("#frm_cod").val();
    arrParams.nombre = $('#frm_name').val();
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/localidad/save";
    var arrParams = new Object();
    arrParams.pai_id = $('#cmb_pais').val();
    arrParams.pro_id = $("#cmb_provincia").val();
    arrParams.can_id = $('#cmb_ciudad').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/localidad/index";
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/localidad/delete";
    var arrParams = new Object();
    arrParams.id = id;
    arrParams.cod = cod;
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
    var tipo = $("#cmb_tipo").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/localidad/expexcel?search=" + search + "&tipo=" + tipo;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    var tipo = $("#cmb_tipo").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/localidad/exppdf?pdf=1&search=" + search + "&tipo=" + tipo;
}