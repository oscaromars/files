$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
    $('#cmb_tipo').change(function() {
        if ($('#cmb_tipo').val() == "IN") {
            $('.boddst').show();
        } else {
            $('.boddst').hide();
        }
    });
    $('#btn_addItem').click(function() {
        addItemGrid();
    });
    $('#btn_updItem').click(function() {
        updateItemGrid();
    });
    $('#btn_canItem').click(function() {
        cancelItemGrid();
    });
    $('#btn_termItem').click(function() {
        termItemsValid();
    })
    $('#btn_salir').click(function() {
        cancelProcess();
    })
    $('#btn_anular').click(function() {
        anular();
    });
    $('#frm_itemcant').focusout(function() {
        let ref = parseFloat(removeMilesFormat($(this).val())).toFixed(2);
        $(this).val(ref);
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
 * Function to save Item from model or record
 */
function save() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/save";
    var arrParams = new Object();
    var arrData = JSON.parse(sessionStorage.grid_emerca);
    arrParams.tipo = $("#cmb_tipo").val();
    arrParams.fecha = $('#frm_fegreso').val();
    arrParams.bodorig = $('#autocomplete-bodorig').val();
    arrParams.bodosec = $('#frm_bodorigsec').val();
    arrParams.boddest = $('#autocomplete-boddest').val();
    arrParams.boddsec = $('#frm_boddestsec').val();
    arrParams.codcli = $('#autocomplete-cliente').val();
    arrParams.namcli = $('#frm_clientename').val();
    arrParams.items = arrData.data;
    arrParams.cbulto = $('#frm_bul').val();
    arrParams.recibido = $('#frm_recibido').val();
    arrParams.revisado = $('#frm_revisado').val();
    arrParams.kardex = $('#frm_kardex').val();
    arrParams.observacion = $('#txta_obse').val();
    arrParams.numart = $('#frm_tart').val();
    arrParams.canitem = $('#frm_citem').val();
    arrParams.total = $('#frm_ttotal').val();

    if (arrParams.tipo == 0) {
        var msg = objLang.Please_select_an_Egress_Types_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrData.data.length == 0) {
        var msg = objLang.There_are_no_items_added__Please_enter_one_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                var messagePB = new Object();
                messagePB.wtmessage = response.message.wtmessage;
                messagePB.title = response.message.title;
                var objNew = new Object();
                var objView = new Object();
                var objIndex = new Object();
                objNew.id = "btnid2new";
                objNew.class = "btn-primary clclass praclose";
                objNew.value = objLang.New;
                objNew.callback = 'redirectNew';

                objView.id = "btnid2view";
                objView.class = "btn-info clclass praclose";
                objView.value = objLang.View;
                objView.callback = 'redirectView';
                var parView = [arrParams.bodorig, arrParams.bodosec, "EG"];
                objView.paramCallback = parView;

                objIndex.id = "btnid2index";
                objIndex.class = "btn-warning clclass praclose";
                objIndex.value = objLang.Back;
                objIndex.callback = 'redirectIndex';

                messagePB.acciones = new Array();
                messagePB.closeaction = new Array();
                messagePB.closeaction[0] = objIndex;
                messagePB.acciones[0] = objView;
                messagePB.acciones[1] = objNew;
                showAlert('OK', 'info', messagePB);
                clearAll();
            } else {
                showAlert(response.status, response.label, response.message);
            }
        }, true);
    }
}

function redirectView(cod, egr, tip) {
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/view?cod=" + cod + "&egr=" + egr + "&tip=" + tip;
}

function redirectNew() {
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
}

function redirectIndex() {
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/index";
}

function clearAll() {
    $('#cmb_tipo').val(0);
    $('.boddst').hide();
    $('#autocomplete-bodorig').val('');
    $('#frm_bodorigsec').val('');
    $('#frm_bodorignom').val('');
    $('#frm_bodorigdir').val('');
    $('#autocomplete-boddest').val('');
    $('#frm_boddestsec').val('');
    $('#frm_boddestnom').val('');
    $('#frm_boddestdir').val('');
    $('#autocomplete-cliente').val('');
    $('#frm_clientename').val('');
    clearAddItemArea();
    disabledBulkdata();
    $('#frm_bul').val('');
    $('#frm_recibido').val('');
    $('#frm_revisado').val('');
    $('#frm_kardex').val('');
    $('#txta_obse').val('');
    $('#frm_tart').val('0');
    $('#frm_citem').val('0');
    $('#frm_ttotal').val('0.00');
    $('#lbl_ttotal').text('0.00');
    clearStorage();
}

function clearStorage() {
    var arrData = JSON.parse(sessionStorage.grid_emerca);
    var trmsg = arrData.trmessage;
    arrData.btnactions = "";
    arrData.data = "";
    arrData.label = "";
    sessionStorage.grid_emerca = JSON.stringify(arrData);
    $('#grid_emerca > table.dataTable > tbody').html(trmsg);
}

/**
 * Function to download Excel from gridview
 */
function exportExcel() {
    var search = $('#txt_search').val();
    var type = $("#cmb_tipo").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/expexcel?search=" + search + "&type=" + type;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    var type = $("#cmb_tipo").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/exppdf?pdf=1&search=" + search + "&type=" + type;
}

function printEgress() {
    var cod = $("#frm_cod_bod").val();
    var egr = $("#frm_cod_sec").val();
    var tip = $("#frm_type_eg").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/printegress?cod=" + cod + "&egr=" + egr + "&tip=" + tip;
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putBodegaOrgData(data) {
    let id = data[0];
    let name = data[1];
    let address = data[2];
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
    var arrParams = new Object();
    arrParams.getSecBodOrg = 1;
    arrParams.code = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            $('#frm_bodorigsec').val(response.message.secuence);
            $('#frm_bodorignom').val(name);
            $('#frm_bodorigdir').val(address);
        } else {
            setTimeout(function() {
                showAlert(response.status, response.label, response.message);
            }, 1000);
        }
    }, true);

}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putBodegaDstData(data) {
    let id = data[0];
    let name = data[1];
    let address = data[2];
    let codeBdgOrg = $('#autocomplete-bodorig').val();
    if (codeBdgOrg == id) {
        var msg = objLang.Cellar_Destiny_must_be_different_to_Cellar_Origin_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-boddest').val('');
        $('#frm_boddestnom').val('');
        $('#frm_boddestdir').val('');
        return;
    } else {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
        var arrParams = new Object();
        arrParams.getSecBodDst = 1;
        arrParams.code = id;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                $('#frm_boddestsec').val(response.message.secuence);
                $('#frm_boddestnom').val(name);
                $('#frm_boddestdir').val(address);
            } else {
                setTimeout(function() {
                    showAlert(response.status, response.label, response.message);
                }, 1000);
            }
        }, true);
    }

}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putClienteData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_clientename').val(name);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putArticuloData(data) {
    let id = data[0];
    let name = data[1]; // data[2] -> EXT_TOT ||  data[3] -> EXT_COM

    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
    var arrParams = new Object();
    arrParams.getBodOrgExt = 1;
    arrParams.articulo = id;
    arrParams.bodega = $('#autocomplete-bodorig').val();
    if ($('#autocomplete-bodorig').val() == "") {
        var msg = objLang.Please_select_a_Cellar_Origin_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-articulo').val('');
        $('#frm_articulodesc').val('');
        $('#frm_itemcompr').val('0.00');
        $('#frm_itemdispo').val('0.00');
        return;
    }
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            let exTot = parseFloat(response.message.data.exTot, 2);
            let exCom = parseFloat(response.message.data.exCom, 2);
            let exReal = exTot - exCom;
            if (exTot == 0) {
                var msg = objLang.There_is_no_stock_available__Select_another_Article_;
                shortModal(msg, objLang.Error, "error");
                $('#autocomplete-articulo').val('');
                name = '';
                exCom = "0.00";
                exTot = "0.00";
                exReal = "0.00";
            }
            if (exReal <= 0) {
                var msg = objLang.There_is_no_stock_available__Select_another_Article_;
                shortModal(msg, objLang.Error, "error");
            }
            $('#frm_articulodesc').val(name);
            $('#frm_itemcompr').val(parseFloat(exCom).toFixed(2));
            $('#frm_itemdispo').val(parseFloat(exReal).toFixed(2));
        } else {
            setTimeout(function() {
                showAlert(response.status, response.label, response.message);
            }, 1000);
        }
    }, true);
}

/**
 * Function to do add Items to grid  
 *
 * @return {void} No return any value.
 */
function addItemGrid() {
    let id = $('#autocomplete-articulo').val();
    let exCom = parseFloat($('#frm_itemcompr').val());
    let exReal = parseFloat($('#frm_itemdispo').val());
    let cant = parseFloat($('#frm_itemcant').val());
    let bodega = $('#autocomplete-bodorig').val();
    var arrData = JSON.parse(sessionStorage.grid_emerca);
    var newItem = ["", id];
    if (existItemGridContent(arrData.data, newItem)) {
        var message = objLang.Item_already_exists_;
        var label = "Error";
        var status = "NO_OK";
        shortModal(message, label, status);
        return;
    }
    if (exReal > 0) {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
        var arrParams = new Object();
        arrParams.getReserveItem = 1;
        arrParams.action = "new";
        arrParams.code = id;
        arrParams.bod = bodega;
        arrParams.can = cant;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                var data = response.message.item;
                var cod = data.cod;
                var name = data.name;
                var pprov = parseFloat(data.pprovider);
                var prefe = parseFloat(data.preference);
                var exRes = parseFloat(data.exireserved);
                var exTot = parseFloat(data.exitotal);
                var tCost = prefe * cant;
                if (validateAddItem(cant, exRes, exTot)) {
                    tCost = currencyFormat(tCost, 4);
                    prefe = currencyFormat(prefe, 4);
                    pprov = currencyFormat(pprov, 4);
                    addItemStorage(cod, name, cant.toFixed(2), exRes, exTot, tCost, prefe, pprov);
                    clearAddItemArea();
                }

            } else {
                setTimeout(function() {
                    showAlert(response.status, response.label, response.message);
                }, 1000);
            }
        }, true);
    }
}

/**
 * Function to do add Items to grid  
 *
 * @param  {float}   cant   Amount of items.
 * @param  {float}   exCom  Items Reserved.
 * @param  {float}   exTot  Total Items.
 * @return {bool} No error is true else false.
 */
function validateAddItem(cant, exCom, exTot) {
    if (cant == "" || parseFloat(cant) <= 0) {
        var msg = objLang.You_must_enter_an_amount_of_items_greater_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return false;
    }
    let exReal = exTot - exCom;
    let texReal = exReal - cant;
    if (exTot == 0 || exReal <= 0) {
        var msg = objLang.There_is_no_stock_available__Select_another_Article_;
        shortModal(msg, objLang.Error, "error");
        return false;
    }
    if (texReal < 0) {
        var msg = objLang.There_is_no_stock_available__Select_another_Article_;
        shortModal(msg, objLang.Error, "error");
        return false;
    }
    return true;
}

/**
 * Function to do Clear Inputs in Add Item event
 *
 * @return {void} No return any value.
 */
function clearAddItemArea() {
    $('#autocomplete-articulo').val('');
    $('#frm_articulodesc').val('');
    $('#frm_itemcant').val('0.00');
    $('#frm_itemcompr').val('0.00');
    $('#frm_itemdispo').val('0.00');
}

/**
 * Function to do add Items to grid  
 * @param   {string}    cod     Code Item
 * @param   {string}    name    Name Item
 * @param   {float}     exRes   Items Reserved
 * @param   {float}     exTot   Total Items
 * @param   {float}     tCost   Total Cost
 * @param   {float}     prefe   Reference Price
 * @param   {float}     pprov   Provider Price
 * @return {void} No return any value.
 */
function addItemStorage(cod, name, cant, exRes, exTot, tCost, prefe, pprov) {
    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();

    tb_item[0] = 0;
    tb_item[1] = cod;
    tb_item[2] = name;
    tb_item[3] = removeMilesFormat(cant);
    tb_item[4] = removeMilesFormat(pprov);
    tb_item[5] = removeMilesFormat(prefe);
    tb_item[6] = removeMilesFormat(tCost);
    tb_item2[0] = 0;
    tb_item2[1] = cod;
    tb_item2[2] = name;
    tb_item2[3] = cant;
    tb_item2[4] = '$' + pprov;
    tb_item2[5] = '$' + prefe;
    tb_item2[6] = '$' + tCost;
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemStorage(this)", title: parent.objLang.Delete, class: "", tipo_accion: "delete" };
    tb_acc[1] = { id: "editN", href: "", onclick: "javascript:editItemStorage(this)", title: parent.objLang.Edit, class: "", tipo_accion: "edit" };
    var arrData = JSON.parse(sessionStorage.grid_emerca);
    if (!existItemGridContent(arrData.data, tb_item)) {
        if (arrData.data) {
            var item = arrData.data;
            tb_item[0] = item.length;
            item.push(tb_item);
            arrData.data = item;
        } else {
            var item = new Array();
            tb_item[0] = 0;
            item[0] = tb_item;
            arrData.data = item;
        }
        if (arrData.label) {
            var item2 = arrData.label;
            tb_item2[0] = item2.length;
            item2.push(tb_item2);
            arrData.label = item2;
        } else {
            var item2 = new Array();
            tb_item2[0] = 0;
            item2[0] = tb_item2;
            arrData.label = item2;
        }
        if (arrData.btnactions) {
            var item3 = arrData.btnactions;
            //tb_acc[0].onclick = "javascript:removeItemStorage(this)";
            item3[item3.length] = tb_acc;
            arrData.btnactions = item3;
            // colocar codigo aqui para agregar acciones
        } else {
            var item3 = new Array();
            item3[0] = tb_acc;
            arrData.btnactions = item3;
            // colocar codigo aqui para agregar acciones
        }
        sessionStorage.grid_emerca = JSON.stringify(arrData);
        addItemGridContent("grid_emerca");
        calcularTotalStorage();
    } else {
        var message = objLang.Item_already_exists_;
        var label = "Error";
        var status = "NO_OK";
        shortModal(message, label, status);
    }
}

/**
 * Function to calculate Total Values from Session Storage 
 *
 * @return {void} No return any value.
 */
function calcularTotalStorage() {
    var arrData = JSON.parse(sessionStorage.grid_emerca);
    var cantTipos = arrData.data.length;
    var total = 0.00;
    var cantItems = 0.00;
    for (var i = 0; i < arrData.data.length; i++) {
        cantItems = cantItems + parseFloat(removeMilesFormat(arrData.data[i][3]));
        total = total + parseFloat(removeMilesFormat(arrData.data[i][6]));
    }
    $('#frm_tart').val(parseFloat(cantTipos).toFixed(2));
    $('#frm_citem').val(parseFloat(cantItems).toFixed(2));
    $('#frm_ttotal').val(total);
    $('#lbl_ttotal').text(currencyFormat(parseFloat(total), 2));
}

function existItemGridContent(data, item) {
    var status = false;
    for (var i = 0; i < data.length; i++) {
        if (data[i][1] == item[1]) {
            status = true;
        }
    }
    return status;
}

/**
 * Function to do add Items to grid  
 *
 * @param   {objetc}    ref     Reference Object Element
 * @param   {boolean}   rmReserve   True is action to remove Reserve Item.
 * @return {void} No return any value.
 */
function removeItemStorage(ref, rmReserve) {
    var indice = $(ref).parent().parent().attr("data-key");
    var id = $(ref).parent().parent().find("td:nth-child(2)").text();
    var cant = parseFloat($(ref).parent().parent().find("td:nth-child(4)").text());
    var bodega = $('#autocomplete-bodorig').val();
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
    var arrParams = new Object();
    var dataStr = getItemStorageByCode(id);
    arrParams.getReserveItem = 1;
    arrParams.action = "delete";
    arrParams.code = id;
    arrParams.bod = bodega;
    arrParams.can = cant;
    arrParams.oldCan = cant; // cantidad
    if (rmReserve == undefined) {
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                removeItemGridContent("grid_emerca", indice);
                calcularTotalStorage();
                cancelItemGrid();
            } else {
                setTimeout(function() {
                    showAlert(response.status, response.label, response.message);
                }, 1000);
            }
        }, true);
    } else {
        removeItemGridContent("grid_emerca", indice);
        calcularTotalStorage();
        cancelItemGrid();
    }
}

/**
 * Function to do edit Item to grid  
 * @param   {string}    indice  Index for Storage Item
 * @param   {string}    cod     Code Item
 * @param   {string}    name    Name Item
 * @param   {float}     exRes   Items Reserved
 * @param   {float}     exTot   Total Items
 * @param   {float}     tCost   Total Cost
 * @param   {float}     prefe   Reference Price
 * @param   {float}     pprov   Provider Price
 * @return {void} No return any value.
 */
function editItemGridStorage(indice, cod, name, cant, exRes, exTot, tCost, prefe, pprov) {
    var elementId = 'grid_emerca';
    var storage = sessionStorage.getItem(elementId);
    var arr_data = JSON.parse(storage);
    var size_arr = arr_data.data.length;
    var newarr_data = new Array();
    var newarr_label = new Array();
    var newarr_btnactions = new Array();
    for (var i = 0; i < size_arr; i++) {
        if (arr_data.data[i][0] == indice) {
            var tb_item = new Array();
            var tb_item2 = new Array();
            tb_item[0] = indice;
            tb_item[1] = cod;
            tb_item[2] = name;
            tb_item[3] = removeMilesFormat(cant);
            tb_item[4] = removeMilesFormat(pprov);
            tb_item[5] = removeMilesFormat(prefe);
            tb_item[6] = removeMilesFormat(tCost);
            tb_item2[0] = indice;
            tb_item2[1] = cod;
            tb_item2[2] = name;
            tb_item2[3] = cant;
            tb_item2[4] = '$' + pprov;
            tb_item2[5] = '$' + prefe;
            tb_item2[6] = '$' + tCost;
            newarr_data[i] = tb_item;
            newarr_label[i] = tb_item2;
        } else {
            newarr_label[i] = arr_data.label[i];
            newarr_data[i] = arr_data.data[i];
        }
        newarr_btnactions[i] = arr_data.btnactions[i];
    }
    arr_data.data = newarr_data;
    arr_data.label = newarr_label;
    arr_data.btnactions = newarr_btnactions;
    sessionStorage[elementId] = JSON.stringify(arr_data);
    addItemGridContent(elementId);
}

/**
 * Function to Edit Item Grid after click in Edit Button on GridView
 *
 * @param   {object}    ref     Reference Object Button
 * @return {void} No return any value.
 */
function editItemStorage(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    var id = $(ref).parent().parent().find("td:nth-child(2)").text();
    var itemName = $(ref).parent().parent().find("td:nth-child(3)").text();
    var cant = parseFloat($(ref).parent().parent().find("td:nth-child(4)").text());
    var ppro = $(ref).parent().parent().find("td:nth-child(5)").text();
    var pref = $(ref).parent().parent().find("td:nth-child(6)").text();
    var tot = $(ref).parent().parent().find("td:nth-child(7)").text();
    var bodega = $('#autocomplete-bodorig').val();
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
    var arrParams = new Object();
    arrParams.getReserveItem = 1;
    arrParams.action = "edit";
    arrParams.code = id;
    arrParams.bod = bodega;
    arrParams.can = cant;
    arrParams.oldCan = cant;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            var data = response.message.item;
            var cod = data.cod;
            var name = data.name;
            var pprov = parseFloat(data.pprovider);
            var prefe = parseFloat(data.preference);
            var exRes = parseFloat(data.exireserved);
            var exTot = parseFloat(data.exitotal);
            var exDis = exTot - exRes;
            var tCost = prefe * cant;
            $('#autocomplete-articulo').val(cod);
            $('#frm_articulodesc').val(name);
            $('#frm_itemcant').val(parseFloat(cant).toFixed(2));
            $('#frm_itemcompr').val(parseFloat(exRes).toFixed(2));
            $('#frm_itemdispo').val(parseFloat(exDis).toFixed(2));
            disabledSearchItem();
            showUpdateBtn();
        } else {
            setTimeout(function() {
                showAlert(response.status, response.label, response.message);
            }, 1000);
        }
    }, true);
}

/**
 * Function to get item session storage by code
 *
 * @param   {string}    code    Code for item to search
 * @return {void} No return any value.
 */
function getItemStorageByCode(code) {
    var arrData = JSON.parse(sessionStorage.grid_emerca);
    var data = arrData.data;
    for (var i = 0; i < data.length; i++) {
        if (data[i][1] == code) {
            return data[i];
        }
    }
    return null;
}

/**
 * Function to Update Item Grid after click in Update Button
 *
 * @return {void} No return any value.
 */
function updateItemGrid() {
    let id = $('#autocomplete-articulo').val();
    let exCom = parseFloat($('#frm_itemcompr').val());
    let exReal = parseFloat($('#frm_itemdispo').val());
    let cant = parseFloat($('#frm_itemcant').val());
    let bodega = $('#autocomplete-bodorig').val();
    if (exReal > 0 && exReal >= cant) {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
        var arrParams = new Object();
        var dataStr = getItemStorageByCode(id);
        arrParams.getReserveItem = 1;
        arrParams.action = "update";
        arrParams.code = id;
        arrParams.bod = bodega;
        arrParams.can = cant;
        arrParams.oldCan = dataStr[3]; // cantidad
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                var data = response.message.item;
                var cod = data.cod;
                var name = data.name;
                var pprov = parseFloat(data.pprovider);
                var prefe = parseFloat(data.preference);
                var exRes = (parseFloat(data.exireserved) - cant);
                var exTot = parseFloat(data.exitotal);
                var tCost = prefe * cant;
                if (validateAddItem(cant, exRes, exTot)) {
                    tCost = currencyFormat(tCost, 4);
                    prefe = currencyFormat(prefe, 4);
                    pprov = currencyFormat(pprov, 4);
                    var ref = null;
                    $('#grid_emerca > table.dataTable > tbody > tr').each(function() {
                        let indice = $(this).find("td:nth-child(1)").text();
                        let codeRef = $(this).find("td:nth-child(2)").text();
                        let dataId = $(this).attr('data-key');
                        let ref = $(this).find('a[id^=editN]');
                        if (codeRef == cod) {
                            editItemGridStorage(dataId, cod, name, cant.toFixed(2), exRes, exTot, tCost, prefe, pprov);
                            calcularTotalStorage();
                            clearAddItemArea();
                            enabledSearchItem();
                            hideUpdateBtn();
                            return false;
                        }
                    });
                }
            } else {
                setTimeout(function() {
                    showAlert(response.status, response.label, response.message);
                }, 1000);
            }
        }, true);
    } else {
        var msg = objLang.There_is_no_stock_available_by_the_number_items_to_add_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
}

/**
 * Function to disable Search Input
 *
 * @return {void} No return any value.
 */
function disabledSearchItem() {
    $('#autocomplete-articulo').attr('disabled', 'disabled');
}

/**
 * Function to enable Search Input
 *
 * @return {void} No return any value.
 */
function enabledSearchItem() {
    $('#autocomplete-articulo').removeAttr('disabled', 'disabled');
}

/**
 * Function to Show Update Button
 *
 * @return {void} No return any value.
 */
function showUpdateBtn() {
    $('#btn_updItem').show();
    $('#btn_addItem').hide();
    $('#btn_termItem').hide();
    showCancelBtn();
}

/**
 * Function to Show Update Button
 *
 * @return {void} No return any value.
 */
function showCancelBtn() {
    $('#btn_canItem').show();
}

/**
 * Function to Hide Update Button
 *
 * @return {void} No return any value.
 */
function hideUpdateBtn() {
    $('#btn_updItem').hide();
    $('#btn_addItem').show();
    $('#btn_termItem').show();
    hideCancelBtn();
}

/**
 * Function to Hide Update Button
 *
 * @return {void} No return any value.
 */
function hideCancelBtn() {
    $('#btn_canItem').hide();
}

function cancelItemGrid() {
    $('#autocomplete-articulo').val('');
    $('#frm_articulodesc').val('');
    $('#frm_itemcant').val('0.00');
    $('#frm_itemcompr').val('0.00');
    $('#frm_itemdispo').val('0.00');
    hideUpdateBtn();
    enabledSearchItem();
}

/**
 * Function to enable Bulk Area
 *
 * @return {void} No return any value.
 */
function enabledBulkdata() {
    $('#frm_bul').removeAttr('disabled');
    $('#frm_recibido').removeAttr('disabled');
    $('#frm_revisado').removeAttr('disabled');
    $('#frm_kardex').removeAttr('disabled');
    $('#txta_obse').removeAttr('disabled');
}

/**
 * Function to disable Bulk Area
 *
 * @return {void} No return any value.
 */
function disabledBulkdata() {
    $('#frm_bul').attr('disabled', 'disabled');
    $('#frm_recibido').attr('disabled', 'disabled');
    $('#frm_revisado').attr('disabled', 'disabled');
    $('#frm_kardex').attr('disabled', 'disabled');
    $('#txta_obse').attr('disabled', 'disabled');
}

/**
 * Function to cancel process and unreserve items
 *
 * @return {void} No return any value.
 */
function cancelProcess() {
    var arrData = JSON.parse(sessionStorage.grid_emerca);
    var bodega = $('#autocomplete-bodorig').val();
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
    var arrParams = new Object();
    arrParams.cancelProcess = 1;
    arrParams.bod = bodega;
    arrParams.items = arrData.data;
    if (arrData.data.length > 0) {
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                $('#grid_emerca > table.dataTable > tbody > tr').each(function() {
                    if ($(this).find('a[id^=editN]'))
                        removeItemGridContent("grid_emerca", 0);
                });
                clearAddItemArea();
                enabledSearchItem();
                hideUpdateBtn();
                calcularTotalStorage();
            } else {
                setTimeout(function() {
                    showAlert(response.status, response.label, response.message);
                }, 1000);
            }
        }, true);
    } else {
        window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
    }
}

/**
 * Function to invalidate Egress process
 *
 * @return {void} No return any value.
 */
function anular() {
    let title = objLang.Invalidate;
    let msg = objLang.Are_you_sure_to_cancel_this_registry_;
    confirmDelete('anularProcess', '', msg, title);
}

/**
 * Function to invalidate Egress process
 *
 * @return {void} No return any value.
 */
function anularProcess() {
    //var arrData = JSON.parse(sessionStorage.grid_emerca);
    var cod = $('#frm_cod_bod').val();
    var tip = $('#frm_type_eg').val();
    var egr = $('#frm_cod_sec').val();
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/anular";
    var arrParams = new Object();
    arrParams.tip = tip;
    arrParams.cod = cod;
    arrParams.egr = egr;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/view?cod=" + cod + "&egr=" + egr + "&tip=" + tip;
        } else {
            setTimeout(function() {
                showAlert(response.status, response.label, response.message);
            }, 1000);
        }
    }, true);
}

/**
 * Function to valid Items Grid and Calculate values
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function termItemsValid() {
    let items = parseFloat(removeMilesFormat($('#frm_tart').val()));
    let cantItems = parseFloat(removeMilesFormat($('#frm_citem').val()));
    let total = parseFloat(removeMilesFormat($('#frm_ttotal').val()));
    let bodega = $('#autocomplete-bodorig').val();
    if (items <= 0 || cantItems <= 0 || total <= 0) {
        var msg = objLang.There_are_no_items_added__Please_enter_one_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    // validar que hayan items en el session storage.
    var arrData = JSON.parse(sessionStorage.grid_emerca);
    if (arrData.data.length == 0) {
        var msg = objLang.There_are_no_items_added__Please_enter_one_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    var arrData = JSON.parse(sessionStorage.grid_emerca);
    var codes = new Array();
    var cants = new Array();
    for (var i = 0; i < arrData.data.length; i++) {
        var code = arrData.data[i][1];
        var cant = arrData.data[i][3];
        codes.push(code);
        cants.push(cant);
    }
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/egresomercaderia/new";
    var arrParams = new Object();
    arrParams.validItems = 1;
    arrParams.codes = codes;
    arrParams.cants = cants;
    arrParams.bod = bodega;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            enabledBulkdata();
        } else {
            showAlert(response.status, response.label, response.message);
        }
    }, true);
}