$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
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
        CalcularTotalCosto();
    });
    $('#frm_p_costo').focusout(function() {
        let ref = parseFloat(removeMilesFormat($(this).val())).toFixed(2);
        $(this).val(ref);
        CalcularTotalCosto();
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/save";
    var arrParams = new Object();
    var arrData = JSON.parse(sessionStorage.grid_imerca);
    arrParams.tipo = $("#cmb_tipo").val();
    arrParams.fecha = $('#frm_fingreso').val();
    arrParams.bodorig = $('#autocomplete-bodorig').val();
    arrParams.bodosec = $('#frm_bodorigsec').val();
    //arrParams.boddest = $('#autocomplete-boddest').val();
    //arrParams.boddsec = $('#frm_boddestsec').val();
    arrParams.codpro = $('#autocomplete-proveedor').val();
    arrParams.nompro = $('#frm_proveedorname').val();
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
                //CREAR LA POPUP DEL MENSAJE
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
                var parView = [arrParams.bodorig, arrParams.bodosec, "IN"];
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

function redirectView(cod, ing, tip) {
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/view?cod=" + cod + "&ing=" + ing + "&tip=" + tip;
}

function redirectNew() {
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/new";
}

function redirectIndex() {
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/index";
}

function clearAll() {
    $('#cmb_tipo').val(0);
    $('#autocomplete-bodorig').val('');
    $('#frm_bodorigsec').val('');
    $('#frm_bodorignom').val('');
    $('#frm_bodorigdir').val('');
    $('#autocomplete-proveedor').val('');
    $('#frm_proveedorname').val('');
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
    var arrData = JSON.parse(sessionStorage.grid_imerca);
    var trmsg = arrData.trmessage;
    arrData.btnactions = "";
    arrData.data = "";
    arrData.label = "";
    sessionStorage.grid_imerca = JSON.stringify(arrData);
    $('#grid_imerca > table.dataTable > tbody').html(trmsg);
}

/**
 * Function to download Excel from gridview
 */
function exportExcel() {
    var search = $('#txt_search').val();
    var type = $("#cmb_tipo").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/expexcel?search=" + search + "&type=" + type;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    var type = $("#cmb_tipo").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/exppdf?pdf=1&search=" + search + "&type=" + type;
}

function printEgress() {
    var cod = $("#frm_cod_bod").val();
    var ing = $("#frm_cod_sec").val();
    var tip = $("#frm_type_in").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/printentry?cod=" + cod + "&ing=" + ing + "&tip=" + tip;
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
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/new";
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
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/new";
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
function putProveedorData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_proveedorname').val(name);
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

    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/new";
    var arrParams = new Object();
    arrParams.getBodOrgExt = 1;
    arrParams.articulo = id;
    arrParams.bodega = $('#autocomplete-bodorig').val();
    if ($('#autocomplete-bodorig').val() == "") {
        var msg = objLang.Please_select_a_Cellar_Origin_;
        shortModal(msg, objLang.Error, "error");
        $('#autocomplete-articulo').val('');
        $('#frm_articulodesc').val('');
        $('#frm_p_lista').val('0.00');
        $('#frm_p_costo').val('0.00');
        $('#frm_t_costo').val('0.00');
        return;
    }
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {

            let p_lista = parseFloat(response.message.data.p_lista, 2);
            let p_costo = parseFloat(response.message.data.p_costo, 2);


            /*if (np_costo <= 0) {
                var msg = objLang.There_is_no_stock_available__Select_another_Article_;
                shortModal(msg, objLang.Error, "error");
            }*/
            $('#frm_articulodesc').val(name);
            $('#frm_p_lista').val(parseFloat(p_lista).toFixed(2));
            $('#frm_p_costo').val(parseFloat(p_costo).toFixed(2));
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
    let cod_art = $('#autocomplete-articulo').val();
    let des_com = $('#frm_articulodesc').val();
    let p_lista = parseFloat($('#frm_p_lista').val());
    let p_costo = parseFloat($('#frm_p_costo').val());
    let t_costo = parseFloat($('#frm_t_costo').val());
    let cant = parseFloat($('#frm_itemcant').val());
    let bodega = $('#autocomplete-bodorig').val();
    var arrData = JSON.parse(sessionStorage.grid_imerca);
    var newItem = ["", cod_art];
    if (existItemGridContent(arrData.data, newItem)) { //Item no se puede repetir
        var message = objLang.Item_already_exists_;
        var label = "Error";
        var status = "NO_OK";
        shortModal(message, label, status);
        return;
    }
    if (t_costo > 0) {
        if (validateAddItem(cant, p_costo)) {
            t_costo = currencyFormat(t_costo, 2);
            p_lista = currencyFormat(p_lista, 4);
            p_costo = currencyFormat(p_costo, 4);
            addItemStorage(cod_art, des_com, cant.toFixed(2), p_lista, p_costo, t_costo);
            clearAddItemArea();
        }


    }


}

/**
 * Function to do add Items to grid  
 *
 * @param  {float}   cant   Amount of items.
 * @param  {float}   np_lista  Items Reserved.
 * @param  {float}   exTot  Total Items.
 * @return {bool} No error is true else false.
 */
function validateAddItem(cant, p_costo) {
    if (cant == "" || parseFloat(cant) <= 0) {
        var msg = objLang.You_must_enter_an_amount_of_items_greater_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return false;
    }
    if (p_costo == "" || parseFloat(p_costo) <= 0) {
        var msg = objLang.You_must_enter_an_amount_of_items_greater_than_zero_;
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
    $('#frm_p_lista').val('0.0000');
    $('#frm_p_costo').val('0.0000');
    $('#frm_t_costo').val('0.00');
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
function addItemStorage(cod, name, cant, p_lista, p_costo, t_costo) {
    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    //Arregki de Calculos
    tb_item[0] = 0;
    tb_item[1] = cod;
    tb_item[2] = name;
    tb_item[3] = removeMilesFormat(cant);
    tb_item[4] = removeMilesFormat(p_lista);
    tb_item[5] = removeMilesFormat(p_costo);
    tb_item[6] = removeMilesFormat(t_costo);
    //Presentacion con Texto de $
    tb_item2[0] = 0;
    tb_item2[1] = cod;
    tb_item2[2] = name;
    tb_item2[3] = cant;
    //tb_item2[4] = '$' + p_lista;
    //tb_item2[5] = '$' + p_costo;
    //tb_item2[6] = '$' + t_costo;
    tb_item2[4] = removeMilesFormat(p_lista);
    tb_item2[5] = removeMilesFormat(p_costo);
    tb_item2[6] = removeMilesFormat(t_costo);

    //Accines en el Grid
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemStorage(this)", title: parent.objLang.Delete, class: "", tipo_accion: "delete" };
    tb_acc[1] = { id: "editN", href: "", onclick: "javascript:editItemStorage(this)", title: parent.objLang.Edit, class: "", tipo_accion: "edit" };
    var arrData = JSON.parse(sessionStorage.grid_imerca);
    if (!existItemGridContent(arrData.data, tb_item)) { //vefifica que el item no exista
        if (arrData.data) { //en el arry de calculos
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
        if (arrData.label) { //Arrray con lABEL
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
            item3[item3.length] = tb_acc;
            arrData.btnactions = item3;
            // colocar codigo aqui para agregar acciones
        } else {
            var item3 = new Array();
            item3[0] = tb_acc;
            arrData.btnactions = item3;
            // colocar codigo aqui para agregar acciones
        }
        sessionStorage.grid_imerca = JSON.stringify(arrData);
        addItemGridContent("grid_imerca");
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
    var arrData = JSON.parse(sessionStorage.grid_imerca);
    var cantTipos = arrData.data.length;
    var total = 0.00;
    var cantItems = 0.00;
    for (var i = 0; i < arrData.data.length; i++) {
        cantItems = cantItems + parseFloat(removeMilesFormat(arrData.data[i][3])); //es la posicin Items
        total = total + parseFloat(removeMilesFormat(arrData.data[i][6])); //suma los totales
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
function removeItemStorage(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    //var cod_art = $(ref).parent().parent().find("td:nth-child(2)").text();
    //var cant = parseFloat($(ref).parent().parent().find("td:nth-child(4)").text());   
    removeItemGridContent("grid_imerca", indice);
    calcularTotalStorage();
    cancelItemGrid();
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
function editItemGridStorage(indice, cod_art, des_com, cant, t_costo, p_costo, p_lista) {
    var elementId = 'grid_imerca';
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
            tb_item[1] = cod_art;
            tb_item[2] = des_com;
            tb_item[3] = removeMilesFormat(cant);
            tb_item[4] = removeMilesFormat(p_lista);
            tb_item[5] = removeMilesFormat(p_costo);
            tb_item[6] = removeMilesFormat(t_costo);
            tb_item2[0] = indice;
            tb_item2[1] = cod_art;
            tb_item2[2] = des_com;
            tb_item2[3] = cant;
            tb_item2[4] = removeMilesFormat(p_lista);
            tb_item2[5] = removeMilesFormat(p_costo);
            tb_item2[6] = removeMilesFormat(t_costo);
            //tb_item2[4] = '$' + pprov;
            //tb_item2[5] = '$' + prefe;
            //tb_item2[6] = '$' + tCost;
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
    var cod_art = $(ref).parent().parent().find("td:nth-child(2)").text();
    var des_com = $(ref).parent().parent().find("td:nth-child(3)").text();
    var cant = parseFloat($(ref).parent().parent().find("td:nth-child(4)").text());
    var p_lista = $(ref).parent().parent().find("td:nth-child(5)").text();
    var p_costo = $(ref).parent().parent().find("td:nth-child(6)").text();
    var t_costo = $(ref).parent().parent().find("td:nth-child(7)").text();
    $('#autocomplete-articulo').val(cod_art);
    $('#frm_articulodesc').val(des_com);
    $('#frm_itemcant').val(parseFloat(cant).toFixed(2));
    $('#frm_p_lista').val(parseFloat(p_lista).toFixed(4));
    $('#frm_p_costo').val(parseFloat(p_costo).toFixed(4));
    $('#frm_t_costo').val(parseFloat(t_costo).toFixed(2));

    disabledSearchItem();
    showUpdateBtn();


}

/**
 * Function to get item session storage by code
 *
 * @param   {string}    code    Code for item to search
 * @return {void} No return any value.
 */
function getItemStorageByCode(code) {
    var arrData = JSON.parse(sessionStorage.grid_imerca);
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
    let cod_art = $('#autocomplete-articulo').val();
    let des_com = $('#frm_articulodesc').val();
    let p_lista = parseFloat($('#frm_p_lista').val());
    let p_costo = parseFloat($('#frm_p_costo').val());
    let t_costo = parseFloat($('#frm_t_costo').val());
    let cant = parseFloat($('#frm_itemcant').val());
    //let bodega = $('#autocomplete-bodorig').val();
    var arrData = JSON.parse(sessionStorage.grid_imerca);
    var newItem = ["", cod_art];

    if (t_costo > 0) {
        if (validateAddItem(cant, p_costo)) {
            t_costo = currencyFormat(t_costo, 2);
            p_lista = currencyFormat(p_lista, 4);
            p_costo = currencyFormat(p_costo, 4);
            var ref = null;
            $('#grid_imerca > table.dataTable > tbody > tr').each(function() {
                let indice = $(this).find("td:nth-child(1)").text();
                let codeRef = $(this).find("td:nth-child(2)").text();
                let dataId = $(this).attr('data-key');
                let ref = $(this).find('a[id^=editN]');
                if (codeRef == cod_art) {
                    editItemGridStorage(dataId, cod_art, des_com, cant.toFixed(2), t_costo, p_costo, p_lista);
                    calcularTotalStorage();
                    clearAddItemArea();
                    enabledSearchItem();
                    hideUpdateBtn();
                    return false;
                }
            });

        }


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
    $('#frm_p_lista').val('0.0000');
    $('#frm_p_costo').val('0.0000');
    $('#frm_t_costo').val('0.00');
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
    var arrData = JSON.parse(sessionStorage.grid_imerca);
    if (arrData.data.length > 0) {
        clearAddItemArea();
        enabledSearchItem();
        hideUpdateBtn();
        calcularTotalStorage();
    } else {
        window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/new";
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
    var cod = $('#frm_cod_bod').val();
    var tip = $('#frm_type_in').val();
    var ing = $('#frm_cod_sec').val();
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/anular";
    var arrParams = new Object();
    arrParams.tip = tip;
    arrParams.cod = cod;
    arrParams.ing = ing;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/ingresomercaderia/view?cod=" + cod + "&ing=" + ing + "&tip=" + tip;
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
    var arrData = JSON.parse(sessionStorage.grid_imerca);
    if (arrData.data.length == 0) {
        var msg = objLang.There_are_no_items_added__Please_enter_one_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    var arrData = JSON.parse(sessionStorage.grid_imerca);
    var codes = new Array();
    var cants = new Array();
    for (var i = 0; i < arrData.data.length; i++) {
        var code = arrData.data[i][1];
        var cant = arrData.data[i][3];
        codes.push(code);
        cants.push(cant);
    }
    enabledBulkdata();

}

/**
 * Function to valid Items Grid and Calculate values
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function CalcularTotalCosto() {
    let p_lista = parseFloat($('#frm_p_lista').val());
    let p_costo = parseFloat($('#frm_p_costo').val());
    let cant = parseFloat($('#frm_itemcant').val());
    let t_costo = p_costo * cant;
    $('#frm_t_costo').val(currencyFormat(t_costo, 2));
}