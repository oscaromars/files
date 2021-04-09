$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
    $('#cmb_pais').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/articulo/index";
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
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/articulo/index";
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
    $('.cper, #frm_descven').focusout(function() {
        let ref = parseFloat(removeMilesFormat($(this).val())).toFixed(2);
        $(this).val(ref);
    });
    $('.uval').focusout(function() {
        let value = ($(this).val() == "") ? "0" : ($(this).val());
        let ref = parseFloat(removeMilesFormat(value)).toFixed(0);
        $(this).val(ref);
    });
    $('.cper').keyup(function() {
        changePrecioByPor(this);
    });
    $('.cval').focusout(function() {
        let ref = parseFloat(removeMilesFormat($(this).val()));
        $(this).val(currencyFormat(ref, 4));
    });
    $('.cval').keyup(function() {
        changePorcentajeByPrec(this);
    });
    $('#frm_pref').focusout(function() {
        let ref = parseFloat(removeMilesFormat($(this).val()));
        $('#frm_pref').val(currencyFormat(ref, 4));
    });
    $('#frm_pref').keyup(function() {
        changePrecioByRef(this);
    });
});

/**
 * Function to apply filter action to gridview
 */
function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_search").val();
    arrParams.linea = $("#cmb_linea").val();
    arrParams.marca = $("#cmb_marca").val();
    arrParams.tipo = $("#cmb_tipo").val();
    arrParams.tpro = $("#cmb_tpro").val();
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}

/**
 * Function to go edit form
 */
function edit() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/articulo/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/articulo/update";
    var arrParams = new Object();
    //info
    arrParams.id = $('#frm_id').val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.alnombre = $("#frm_altname").val();
    arrParams.linea = $("#autocomplete-linea").val();
    arrParams.tipo = $('#autocomplete-tipo').val();
    arrParams.marca = $('#autocomplete-marca').val();
    arrParams.pais = $('#cmb_pais').val();
    arrParams.provincia = $('#cmb_provincia').val();
    arrParams.ciudad = $('#cmb_ciudad').val();
    arrParams.divisa = $('#autocomplete-divisa').val();
    arrParams.ubicacion = $('#frm_ubicacion').val();
    arrParams.proveedor = $('#autocomplete-proveedor').val();
    arrParams.inventario = $('#autocomplete-inventario').val();
    arrParams.venta = $('#autocomplete-venta').val();
    arrParams.cventa = $('#autocomplete-cventa').val();
    arrParams.iventa = $('#autocomplete-iventa').val();
    arrParams.medida = $('#autocomplete-medida').val();
    arrParams.expira = $('#frm_fexp').val();
    arrParams.tipopro = $('#cmb_tipo').val();
    arrParams.creditos = $('#frm_credit').val();
    arrParams.descontinuado = ($('#chk_descon').is(":checked")) ? 1 : 0;
    arrParams.iva = ($('#chk_iva').is(":checked")) ? 1 : 0;

    // Precios
    arrParams.precioref = removeMilesFormat($('#frm_pref').val());
    arrParams.fecharef = removeMilesFormat($('#frm_fref').val());
    arrParams.pv1po = removeMilesFormat($('#frm_pv1po').val());
    arrParams.pv1pr = removeMilesFormat($('#frm_pv1pr').val());
    arrParams.pv1un = removeMilesFormat($('#frm_pv1un').val());
    arrParams.pv2po = removeMilesFormat($('#frm_pv2po').val());
    arrParams.pv2pr = removeMilesFormat($('#frm_pv2pr').val());
    arrParams.pv2un = removeMilesFormat($('#frm_pv2un').val());
    arrParams.pv3po = removeMilesFormat($('#frm_pv3po').val());
    arrParams.pv3pr = removeMilesFormat($('#frm_pv3pr').val());
    arrParams.pv3un = removeMilesFormat($('#frm_pv3un').val());
    arrParams.pv4po = removeMilesFormat($('#frm_pv4po').val());
    arrParams.pv4pr = removeMilesFormat($('#frm_pv4pr').val());
    arrParams.pv4un = removeMilesFormat($('#frm_pv4un').val());
    arrParams.descventa = removeMilesFormat($('#frm_descven').val());

    // Existencias
    arrParams.exmin = $('#frm_min').val();
    arrParams.exmax = $('#frm_max').val();

    // Observaciones
    arrParams.observacion = $('#txta_observacion').val();

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
    if (arrParams.tipopro == 0) {
        var msg = objLang.Please_select_a_Product_Type_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (!validateForm()) {
        if (!validarPrecios()) return;
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

/**
 * Function to save Item from model or record
 */
function save() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/articulo/save";
    var arrParams = new Object();
    //info
    arrParams.id = $('#frm_id').val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.alnombre = $("#frm_altname").val();
    arrParams.linea = $("#autocomplete-linea").val();
    arrParams.tipo = $('#autocomplete-tipo').val();
    arrParams.marca = $('#autocomplete-marca').val();
    arrParams.pais = $('#cmb_pais').val();
    arrParams.provincia = $('#cmb_provincia').val();
    arrParams.ciudad = $('#cmb_ciudad').val();
    arrParams.divisa = $('#autocomplete-divisa').val();
    arrParams.ubicacion = $('#frm_ubicacion').val();
    arrParams.proveedor = $('#autocomplete-proveedor').val();
    arrParams.inventario = $('#autocomplete-inventario').val();
    arrParams.venta = $('#autocomplete-venta').val();
    arrParams.cventa = $('#autocomplete-cventa').val();
    arrParams.iventa = $('#autocomplete-iventa').val();
    arrParams.medida = $('#autocomplete-medida').val();
    arrParams.expira = $('#frm_fexp').val();
    arrParams.tipopro = $('#cmb_tipo').val();
    arrParams.creditos = $('#frm_credit').val();
    arrParams.descontinuado = ($('#chk_descon').is(":checked")) ? 1 : 0;
    arrParams.iva = ($('#chk_iva').is(":checked")) ? 1 : 0;

    // Precios
    arrParams.precioref = removeMilesFormat($('#frm_pref').val());
    arrParams.fecharef = removeMilesFormat($('#frm_fref').val());
    arrParams.pv1po = removeMilesFormat($('#frm_pv1po').val());
    arrParams.pv1pr = removeMilesFormat($('#frm_pv1pr').val());
    arrParams.pv1un = removeMilesFormat($('#frm_pv1un').val());
    arrParams.pv2po = removeMilesFormat($('#frm_pv2po').val());
    arrParams.pv2pr = removeMilesFormat($('#frm_pv2pr').val());
    arrParams.pv2un = removeMilesFormat($('#frm_pv2un').val());
    arrParams.pv3po = removeMilesFormat($('#frm_pv3po').val());
    arrParams.pv3pr = removeMilesFormat($('#frm_pv3pr').val());
    arrParams.pv3un = removeMilesFormat($('#frm_pv3un').val());
    arrParams.pv4po = removeMilesFormat($('#frm_pv4po').val());
    arrParams.pv4pr = removeMilesFormat($('#frm_pv4pr').val());
    arrParams.pv4un = removeMilesFormat($('#frm_pv4un').val());
    arrParams.descventa = removeMilesFormat($('#frm_descven').val());

    // Existencias
    arrParams.exmin = $('#frm_min').val();
    arrParams.exmax = $('#frm_max').val();

    // Observaciones
    arrParams.observacion = $('#txta_observacion').val();

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
    if (arrParams.tipopro == 0) {
        var msg = objLang.Please_select_a_Product_Type_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (!validateForm()) {
        if (!validarPrecios()) return;
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/articulo/index";
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/articulo/delete";
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
    var tipo = $("#cmb_tipo").val();
    var marca = $("#cmb_marca").val();
    var linea = $("#cmb_linea").val();
    var tpro = $("#cmb_tpro").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/articulo/expexcel?search=" + search + "&tipo=" + tipo + "&marca=" + marca + "&linea=" + linea + "&tpro=" + tpro;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    var tipo = $("#cmb_tipo").val();
    var marca = $("#cmb_marca").val();
    var linea = $("#cmb_linea").val();
    var tpro = $("#cmb_tpro").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/articulo/exppdf?pdf=1&search=" + search + "&tipo=" + tipo + "&marca=" + marca + "&linea=" + linea + "&tpro=" + tpro;
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putLineaData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_lineadesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putDivisaData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_divisadesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putProveedorData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_proveedordesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putInventarioData(data) {
    let id = data[0];
    let name = data[1];
    let tipo = data[2];
    if (tipo.trim().toUpperCase() != 'MOVIMIENTO') {
        var msg = objLang.Please_select_only_Movement_Accounts_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-inventario').val('');
        $('#frm_inventariodesc').val('');
        return;
    }
    $('#frm_inventariodesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putVentaData(data) {
    let id = data[0];
    let name = data[1];
    let tipo = data[2];
    if (tipo.trim().toUpperCase() != 'MOVIMIENTO') {
        var msg = objLang.Please_select_only_Movement_Accounts_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-venta').val('');
        $('#frm_ventadesc').val('');
        return;
    }
    $('#frm_ventadesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putCVentaData(data) {
    let id = data[0];
    let name = data[1];
    let tipo = data[2];
    if (tipo.trim().toUpperCase() != 'MOVIMIENTO') {
        var msg = objLang.Please_select_only_Movement_Accounts_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-cventa').val('');
        $('#frm_cventadesc').val('');
        return;
    }
    $('#frm_cventadesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putIVentaData(data) {
    let id = data[0];
    let name = data[1];
    let tipo = data[2];
    if (tipo.trim().toUpperCase() != 'MOVIMIENTO') {
        var msg = objLang.Please_select_only_Movement_Accounts_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-iventa').val('');
        $('#frm_iventadesc').val('');
        return;
    }
    $('#frm_iventadesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putMedidaData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_medidadesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putTipoData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_tipodesc').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putMarcaData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_marcadesc').val(name);
}

/**
 * Function to change prices in box and labels
 *
 * @param {JQuery|HTMLElement} elem - Element to execute action
 * @return {void} No return any value.
 */
function changePrecioByRef(elem) {
    let ref = parseFloat(removeMilesFormat($(elem).val()));
    let ppro = parseFloat(removeMilesFormat($('#frm_prom').val()));
    let pov1 = parseFloat($('#frm_pv1po').val());
    let pov2 = parseFloat($('#frm_pv2po').val());
    let pov3 = parseFloat($('#frm_pv3po').val());
    let pov4 = parseFloat($('#frm_pv4po').val());
    let ppv1 = 0.0000;
    let ppv2 = 0.0000;
    let ppv3 = 0.0000;
    let ppv4 = 0.0000;
    let error = false;

    if (ref != "" && ref > 0) {
        if (pov1 != "" && pov1 != 0 && pov1 > 0) {
            ppv1 = ref / ((100 - pov1) / 100);
            $('#frm_pv1pr').val(currencyFormat(ppv1, 4));
        } else {
            error = true;
        }
        if (pov2 != "" && pov2 != 0 && pov2 > 0) {
            ppv2 = ref / ((100 - pov2) / 100);
            $('#frm_pv2pr').val(currencyFormat(ppv2, 4));
        } else {
            error = true;
        }
        if (pov3 != "" && pov3 != 0 && pov3 > 0) {
            ppv3 = ref / ((100 - pov3) / 100);
            $('#frm_pv3pr').val(currencyFormat(ppv3, 4));
        } else {
            error = true;
        }
        if (pov4 != "" && pov4 != 0 && pov4 > 0) {
            ppv4 = ref / ((100 - pov4) / 100);
            $('#frm_pv4pr').val(currencyFormat(ppv4, 4));
        } else {
            error = true;
        }
    } else {
        $('#frm_pv1pr').val(currencyFormat(ppv1, 4));
        $('#frm_pv2pr').val(currencyFormat(ppv1, 4));
        $('#frm_pv3pr').val(currencyFormat(ppv1, 4));
        $('#frm_pv4pr').val(currencyFormat(ppv1, 4));
    }
    if (error) {
        var msg = objLang.Prices_and_percentages_must_be_greater_than_zero_;
        //shortModal(msg, objLang.Error, "error");
        return;
    }
}

/**
 * Function to change prices in box and labels by Percentage
 *
 * @param {JQuery|HTMLElement} elem - Element to execute action
 * @return {void} No return any value.
 */
function changePrecioByPor(elem) {
    let pref = parseFloat(removeMilesFormat($('#frm_pref').val()));
    let pov = parseFloat($(elem).val());
    let refId = $(elem).attr('data-refid');
    let error = false;
    if (pov == "" || pov <= 0 || pref == "" || pref <= 0) {
        error = true;
    } else {
        let ppv = pref / ((100 - pov) / 100);
        if (ppv < 0) error = true;
        $('#' + refId).val(currencyFormat(ppv, 4));
    }
    if (error) {
        var msg = objLang.Prices_and_percentages_must_be_greater_than_zero_;
        //shortModal(msg, objLang.Error, "error");
        return;
    }
}

/**
 * Function to change Porcentages in box and labels by Prices
 *
 * @param {JQuery|HTMLElement} elem - Element to execute action
 * @return {void} No return any value.
 */
function changePorcentajeByPrec(elem) {
    let pref = parseFloat(removeMilesFormat($('#frm_pref').val()));
    let ppv = parseFloat($(elem).val());
    let refId = $(elem).attr('data-refid');
    let error = false;
    if (ppv == "" || ppv <= 0 || pref == "" || pref <= 0) {
        error = true;
    } else {
        let pov = 100 - ((pref / ppv) * 100);
        if (pov < 0) error = true;
        $('#' + refId).val(parseFloat(pov).toFixed(2));
    }
    if (error) {
        var msg = objLang.Prices_and_percentages_must_be_greater_than_zero_;
        //shortModal(msg, objLang.Error, "error");
        return;
    }
}

/**
 * Function to validate prices in box and labels
 *
 * @return {bool} return TRUE if valid is OK or FALSE is not.
 */
function validarPrecios() {
    let pref = parseFloat(removeMilesFormat($('#frm_pref').val()));
    let ppro = parseFloat(removeMilesFormat($('#frm_prom').val()));
    let pov1 = parseFloat($('#frm_pv1po').val());
    let pov2 = parseFloat($('#frm_pv2po').val());
    let pov3 = parseFloat($('#frm_pv3po').val());
    let pov4 = parseFloat($('#frm_pv4po').val());
    let ppv1 = parseFloat(removeMilesFormat($('#frm_pv1pr').val()));
    let ppv2 = parseFloat(removeMilesFormat($('#frm_pv2pr').val()));
    let ppv3 = parseFloat(removeMilesFormat($('#frm_pv3pr').val()));
    let ppv4 = parseFloat(removeMilesFormat($('#frm_pv4pr').val()));
    let valid = true;

    if (ppro && ppro != undefined) {
        if (ppro > pref) {
            var msg = objLang.Reference_Price_is_not_correct__It_must_be_greater_than_Average_Price_;
            shortModal(msg, objLang.Error, "error");
            return false;
        }
    }
    if (pref <= 0) {
        var msg = objLang.Reference_Price_must_be_greater_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return false;
    }
    if (pov1 == "" || pov1 <= 0) {
        valid = false
    }
    if (pov2 == "" || pov2 <= 0) {
        valid = false
    }
    if (pov3 == "" || pov3 <= 0) {
        valid = false
    }
    if (pov4 == "" || pov4 <= 0) {
        valid = false
    }
    if (ppv1 == "" || ppv1 <= 0 || ppv1 < pref) {
        valid = false
    }
    if (ppv2 == "" || ppv2 <= 0 || ppv2 < pref) {
        valid = false
    }
    if (ppv3 == "" || ppv3 <= 0 || ppv3 < pref) {
        valid = false
    }
    if (ppv4 == "" || ppv4 <= 0 || ppv4 < pref) {
        valid = false
    }
    if (!valid) {
        var msg = objLang.Prices_and_percentages_must_be_greater_than_zero_;
        shortModal(msg, objLang.Error, "error");
    }
    return valid;
}