$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
    $(".spanAccStatus").click(function() {
        if ($(this).prev().val() == "1") {
            $(this).find('i:first-child').attr("class", "glyphicon glyphicon-unchecked");
            $(this).prev().val("0");
        } else {
            $(this).find('i:first-child').attr("class", "glyphicon glyphicon-check");
            $(this).prev().val("1");
        }
    });
    $('#btn_save').click(function() {
        saveSetPunto();
    });
    $('#cmb_establecimiento').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/puntoemision/setpunto";
        var arrParams = new Object();
        arrParams.pEstablecimiento = $(this).val();
        arrParams.getEmision = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.pemision, "cmb_pemision");
            }
        }, true);
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
    var codpto = $('#frm_codpto').val();
    var codcaj = $('#frm_codcaj').val();
    var tipnof = $('#frm_tipnof').val();
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/puntoemision/edit" + "?codpto=" + codpto + "&codcaj=" + codcaj;
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/puntoemision/update";
    var arrParams = new Object();
    arrParams.codpunto = $('#frm_codpto').val();
    arrParams.codcaja = $('#frm_codcaj').val();

    arrParams.nomcaj = $('#frm_nombre').val();
    arrParams.ubicaj = $('#frm_ubicacion').val();
    arrParams.cajfec = $('#frm_fecha').val();
    arrParams.autcaj = $('#frm_status').val();
    arrParams.codres = $('#frm_responsable').val();

    if (arrParams.codpunto == 0) {
        var msg = objLang.Please_select_an_Establishment_;
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/puntoemision/save";
    var arrParams = new Object();
    arrParams.codcaja = $('#frm_codigo').val();
    arrParams.codpunto = $('#cmb_tipo_est').val();
    arrParams.nomcaj = $('#frm_nombre').val();
    arrParams.ubicaj = $('#frm_ubicacion').val();
    arrParams.cajfec = $('#frm_fecha').val();
    arrParams.autcaj = $('#frm_status').val();
    arrParams.codres = $('#frm_responsable').val();
    if (arrParams.codpunto == 0) {
        var msg = objLang.Please_select_an_Establishment_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/puntoemision/index";
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/puntoemision/delete";
    var arrParams = new Object();
    arrParams.codpunto = id;
    arrParams.codcaja = cod;
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
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/puntoemision/expexcel?search=" + search;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/puntoemision/exppdf?pdf=1&search=" + search;
}

/**
 * Function to save Point Establishment and Emission in server
 * 
 * @return {void} No return any value.
 */
function saveSetPunto() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/puntoemision/setpunto";
    var arrParams = new Object();
    arrParams.setPunto = true;
    arrParams.pEstablecimiento = $('#cmb_establecimiento').val();
    arrParams.pEmision = $('#cmb_pemision').val();
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
            setTimeout(function() {
                if (response.data.returnUrl != "")
                    window.location.href = response.data.returnUrl;
            }, 2000);
        }
    }, true);
}