$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
});

/**
 * Function to apply filter action to gridview
 */
function getData(id, value, item) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = item.name;
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}

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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/catalogo/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/catalogo/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.idtipcta = $('#cmb_tipcuenta option:selected').val();
    arrParams.idtipreg = $('#cmb_tipregistro option:selected').val();
    arrParams.fecha = $('#frm_fecha').val();
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/catalogo/save";
    var arrParams = new Object();
    arrParams.id = $('#frm_id').val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.idtipcta = $('#cmb_tipcuenta option:selected').val();
    arrParams.idtipreg = $('#cmb_tipregistro option:selected').val();
    arrParams.fecha = $('#frm_fecha').val();
    //validarDatos();//Validacione de Datos
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/catalogo/index";
                }, 3000);
            }
        }, true);
    }
}

function validarDatos() {
    var estado = true;
    var msg = '';
    if ($('#frm_id').val() == '') {//Verifico si tiene Datos
        msg += 'Ingresar Código de Cuenta <br>';
        estado = false;//Retorna Error
    }
    if ($('#frm_name').val() == '') {//Verifico si tiene Datos
        msg += 'Ingresar Nombre de Cuenta <br>';
        estado = false;//Retorna Error
    }
    if ($('#cmb_tipcuenta option:selected').val() > 0) {
        msg += 'Seleccionar Tipo de Cuenta <br>';
        estado = false;//Retorna Error
    }
    if ($('#cmb_tipregistro option:selected').val() > 0) {
        msg += 'Seleccionar Tipo de Registro <br>';
        estado = false;//Retorna Error
    }
//    if (arrParams.centro == 0) {
//        var msg = objLang.Please_select_a_Center_Name_;
//        shortModal(msg, objLang.Error, "error");
//        return;
//    }
    if (!estado) {//Muestra Mensaje en Caso de que Exista un error
        //shortModal(msg, objLang.Error, "error");
        showAlert("NO_OK", "error", msg);
        return;
    }
    
    
}

/**
 * Function to delete Item from model or record
 * 
 * @param {int} id - Id of Element to Delete from model or record
 * @return {void} No return any value.
 */
function deleteItem(id) {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/catalogo/delete";
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
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/catalogo/expexcel?search=" + search;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/catalogo/exppdf?pdf=1&search=" + search;
}