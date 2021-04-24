$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });

    $("#frm_acc_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconAcc").attr("class", $(this).val());
        else {
            $("#iconAcc").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanAccStatus").click(function() {
        if ($("#frm_status").val() == "1") {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_status").val("0");
        } else {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_status").val("1");
        }
    });

    $('#cmb_tipo_establecimiento').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/" + "tipodocumento/index";
        var arrParams = new Object();
        arrParams.establecimiento = $(this).val();
        arrParams.getemision = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.arr_emision, "cmb_tipo_emision");

            }
        }, true);
    });
    $('#cmb_tipo_est').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/" + "tipodocumento/new";
        var arrParams = new Object();
        arrParams.establecimiento = $(this).val();
        arrParams.getemision = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.arr_emision, "cmb_tipo_emi");

            }
        }, true);
    });
    $('#cmb_tipo_trans').change(function() {
        var tipo = $(this).val();
        if (tipo == "O") {
            $('#frm_doc').removeAttr("disabled");
            var cod = $('#frm_codigo').val();
            $('#frm_doc').val(cod);
        } else {
            $('#frm_doc').attr("disabled", "disabled");
            $('#frm_doc').val(tipo);
        }
    });
    $('#cmb_tipo_edoc').change(function() {
        var tipo = $(this).val();
        if (tipo == 0) {
            $('.chkstatus').hide();
        } else {
            $('.chkstatus').show();
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
    arrParams.type_est = $("#cmb_tipo_establecimiento").val();
    arrParams.type_emi = $("#cmb_tipo_emision").val();
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}

/**
 * Function to go edit form
 */
function edit() {
    var codpto = $('#frm_codpto').val();
    var codcaj = $('#frm_codcaj').val();
    var tipnof = $('#frm_tipnof').val();
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipodocumento/edit" + "?codpto=" + codpto + "&codcaj=" + codcaj + "&tipnof=" + tipnof;
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipodocumento/update";
    var arrParams = new Object();
    
    arrParams.codpunto = $('#frm_codpto').val();
    arrParams.codcaja = $('#frm_codcaj').val();
    arrParams.tipnof = $('#frm_tipnof').val();

    arrParams.numdocumento = $('#frm_numdocumento').val();
    arrParams.nombredocumento = $('#frm_nombredocumento').val();
    arrParams.fechadocumento = $('#frm_fechadocumento').val();
    arrParams.ctaiva = $('#autocomplete-ctaiva').val();
    arrParams.iva = $('#frm_iva').val();
    arrParams.edoctipo = $('#cmb_tipo_edoc').val();
    arrParams.cantitems = $('#frm_cantitems').val();
    arrParams.secuencia = $("#cmb_tipo_sec").val();
    arrParams.tipdoc = $('#cmb_tipo_trans').val();
    arrParams.sedoc = $('#frm_status').val();
    arrParams.doc = $('#frm_doc').val();
   
   
    if (arrParams.codpunto == 0) {
        var msg = objLang.Please_select_an_Establishment_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if (arrParams.codcaja == 0) {
        var msg = objLang.Please_select_an_Issue_;
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipodocumento/save";
    var arrParams = new Object();
    arrParams.codpunto = $('#cmb_tipo_est').val();
    arrParams.codcaja = $('#cmb_tipo_emi').val();
    arrParams.tipnof = $('#frm_codigo').val();
    arrParams.numdocumento = $('#frm_numdocumento').val();
    arrParams.nombredocumento = $('#frm_nombredocumento').val();
    arrParams.fechadocumento = $('#frm_fechadocumento').val();
    arrParams.ctaiva = $('#autocomplete-ctaiva').val();
    arrParams.iva = $('#frm_iva').val();
    arrParams.edoctipo = $('#cmb_tipo_edoc').val();
    arrParams.cantitems = $('#frm_cantitems').val();
    arrParams.secuencia = $("#cmb_tipo_sec").val();
    arrParams.tipdoc = $('#cmb_tipo_trans').val();
    arrParams.sedoc = $('#frm_status').val();
    arrParams.doc = $('#frm_doc').val();

    if (arrParams.codpunto == 0) {
        var msg = objLang.Please_select_an_Establishment_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if (arrParams.codcaja == 0) {
        var msg = objLang.Please_select_an_Issue_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipodocumento/index";
                }, 3000);
            }
        }, true);
    }
}

/**
 * Function to delete Item from model or record
 *
 * @param {string} idpunto - IdPunto of Element to Delete from model or record
 * @param {string} idcaja - IdCaja of Element to Delete from model or record
 * @param {string} idtipnof - -IdTipNof of Element to Delete from model or record
 * @return {void} No return any value.
 */
function deleteItem(idpunto, idcaja, idtipnof) {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipodocumento/delete";
    var arrParams = new Object();
    arrParams.codpunto = idpunto;
    arrParams.codcaja = idcaja;
    arrParams.tipnof = idtipnof;
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
    var type_est = $("#cmb_tipo_establecimiento").val();
    var type_emi = $("#cmb_tipo_emision").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipodocumento/expexcel?search=" + search + "&type_est=" + type_est + "&type_emi=" + type_emi;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    var type_est = $("#cmb_tipo_establecimiento").val();
    var type_emi = $("#cmb_tipo_emision").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tipodocumento/exppdf?pdf=1&search=" + search + "&type_est=" + type_est + "&type_emi=" + type_emi;
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putCuentaIvaData(data) {
    let id = data[0];
    let name = data[1];
    let tipo = data[2];
    if (tipo.trim().toUpperCase() != 'MOVIMIENTO') {
        var msg = objLang.Please_select_only_Movement_Accounts_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-ctaiva').val('');
        $('#frm_ctaivadesc').val('');
        return;
    }
    $('#frm_ctaivadesc').val(name);
}