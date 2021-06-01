$(document).ready(function () {
    $('#btn_buscarData').click(function () {
        searchModules();
    });
    $('#cmb_pais').change(function () {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/establecimiento/index";
        var arrParams = new Object();
        arrParams.getPais = "true";
        arrParams.pai_id = $(this).val()
        if (arrParams.pai_id != 0) {
            requestHttpAjax(link, arrParams, function (response) {
                if (response.status == "OK") {
                    data = response.message;
                    setComboData(data.provincias, "cmb_provincia");
                    setComboData(data.ciudades, "cmb_ciudad");
                }
            }, true);
        }
    });
    $('#cmb_provincia').change(function () {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/establecimiento/index";
        var arrParams = new Object();
        arrParams.getProvincia = "true";
        arrParams.pro_id = $(this).val();
        if (arrParams.pro_id != 0) {
            requestHttpAjax(link, arrParams, function (response) {
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
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}

/**
 * Function to go edit form
 */
function edit() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/establecimiento/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/establecimiento/update";
    var arrParams = new Object();
    arrParams.id = $('#frm_id').val();
    arrParams.nombre = $('#frm_nombre').val();
    arrParams.pais = $('#cmb_pais').val();
    arrParams.provincia = $('#cmb_provincia').val();
    arrParams.ciudad = $('#cmb_ciudad').val();
    arrParams.direccion = $('#frm_direccion').val();
    arrParams.telefono1 = $('#frm_telefono1').val();
    arrParams.telefono2 = $('#frm_telefono2').val();
    arrParams.telefonofax = $('#frm_telefonofax').val();
    arrParams.correoct = $('#frm_correoct').val();    
    arrParams.fecha = $('#frm_fecha').val();
    
    if (arrParams.pais == 0) {
        var msg = objLang.Please_select_a_Country_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrParams.provincia == 0) {
        var msg = objLang.Please_select_a_State_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrParams.ciudad == 0) {
        var msg = objLang.Please_select_a_City_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/establecimiento/save";
    var arrParams = new Object();

    arrParams.codigo = $('#frm_codigo').val();
    arrParams.nombre = $('#frm_nombre').val();
    arrParams.pais = $('#cmb_pais').val();
    arrParams.provincia = $('#cmb_provincia').val();
    arrParams.ciudad = $('#cmb_ciudad').val();
    arrParams.direccion = $('#frm_direccion').val();
    arrParams.telefono1 = $('#frm_telefono1').val();
    arrParams.telefono2 = $('#frm_telefono2').val();
    arrParams.telefonofax = $('#frm_telefonofax').val();
    arrParams.correoct = $('#frm_correoct').val();    
    arrParams.fecha = $('#frm_fecha').val();
    
    if (arrParams.pais == 0) {
        var msg = objLang.Please_select_a_Country_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrParams.provincia == 0) {
        var msg = objLang.Please_select_a_State_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrParams.ciudad == 0) {
        var msg = objLang.Please_select_a_City_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/establecimiento/index";
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/establecimiento/delete";
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
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/establecimiento/expexcel?search=" + search;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/establecimiento/exppdf?pdf=1&search=" + search;
}