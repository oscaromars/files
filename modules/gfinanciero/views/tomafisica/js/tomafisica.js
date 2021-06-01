$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
    $('#btn_setTemAct').click(function() {
        showConfirmSetTempStocks();
    });
    $('#btn_printInvVal').click(function() {
        printInvVal();
    });
    $('#btn_printInven').click(function() {
        printInventario();
    });
    $('#btn_printStock').click(function() {
        printStockFisico();
    });
});

/**
 * Function to apply filter action to gridview
 */
function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.cod = $("#autocomplete-bodega").val();
    arrParams.numEx = $("#cmb_stock").val();
    if (arrParams.cod == "") {
        var msg = objLang.Please_select_a_Cellar_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    $("#grid_list").PbGridView("applyFilterData", arrParams);
    getCantItems();
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putBodegaData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_bodegadesc').val(name);
}

/**
 * Function to get Amount of items
 *
 * @return {void} No return any value.
 */
function getCantItems() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/index";
    var arrParams = new Object();
    arrParams.getCant = true;
    arrParams.cod = $("#autocomplete-bodega").val();
    arrParams.numEx = $("#cmb_stock").val();
    if (arrParams.cod == "") {
        var msg = objLang.Please_select_a_Cellar_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            $('#citems').text(response.message.cant);
        } else {
            $('#citems').text('0');
        }
    });
    $('#citems').val();
}

/**
 * Function to do an action by response callback 
 *
 * @param {Object} ref - Reference Object event click
 * @return {void} No return any value.
 */
function editTomaFisica(code) {
    let idGrid = "grid_list";
    let cod = code;
    let bodega = $("#autocomplete-bodega").val();
    let desc = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + code + '"]>td:nth-child(3)').text();
    let tfisico = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + code + '"]>td:nth-child(4)').text();
    let stock = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + code + '"]>td:nth-child(5)').text();
    let estado = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + code + '"]>td:nth-child(6)').text();

    if (bodega == "") {
        var msg = objLang.Please_select_a_Cellar_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    let status = "OK";
    let label = "Success";
    let message = new Object();
    message.title = objLang.Edit_Physical_Count;
    let acciones = new Array();
    let btnSave = new Object();
    //let params = new Array();
    btnSave.id = "btnSaveToma";
    btnSave.class = "clclass";
    btnSave.value = objLang.Save;
    btnSave.callback = "saveItemTomaFisica";
    //params.push(id);
    //btnSave.paramCallback = params;
    acciones.push(btnSave);
    message.acciones = acciones;
    //message.htmloptions = new Object();
    //message.htmloptions.style = style;

    // title box
    message.wtmessage =
        '<div class="box-header with-border">' +
        '<h4 class="box-title">' + objLang.Cellar + ' ' + '(' + bodega + ')</h4>' +
        '</div>';
    // begin form
    message.wtmessage +=
        '<form class="form-horizontal">' +
        '<div class="box-body">';
    // body form
    message.wtmessage +=
        '<div class="form-group">' +
        '<label for="codArt" class="col-sm-4 control-label">' + objLang.Code + '</label>' +
        '<div class="col-sm-8">' +
        '<input type="text" class="form-control PBvalidation" value="' + cod + '" disabled="disabled" data-type="all" id="codArt" placeholder="' + objLang.Code + '">' +
        '</div>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="descArt" class="col-sm-4 control-label">' + objLang.Article_Name + '</label>' +
        '<div class="col-sm-8">' +
        '<textarea class="form-control PBvalidation"  data-type="all" rows="3" id="descArt" disabled="disabled" placeholder="' + objLang.Article_Name + '">' + desc + '</textarea>' +
        '</div>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="fisArt" class="col-sm-4 control-label">' + objLang.Physical_Count + '</label>' +
        '<div class="col-sm-8">' +
        '<input type="text" class="form-control PBvalidation" value="' + tfisico + '" data-type="all" id="fisArt" placeholder="' + objLang.Physical_Count + '">' +
        '</div>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="stockArt" class="col-sm-4 control-label">' + objLang.Stock + '</label>' +
        '<div class="col-sm-8">' +
        '<input type="text" class="form-control PBvalidation" value="' + stock + '" disabled="disabled" data-type="all" id="stockArt" placeholder="' + objLang.Stock + '">' +
        '</div>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="estArt" class="col-sm-4 control-label">' + objLang.Status + '</label>' +
        '<div class="col-sm-8">' +
        '<input type="text" class="form-control PBvalidation" disabled="disabled" value="' + estado + '" data-type="all" id="estArt" placeholder="' + objLang.Status + '">' +
        '</div>' +
        '</div>';
    // end form
    message.wtmessage +=
        '</div>' +
        '</form>';

    showAlert(status, label, message);
    $('#btnSaveToma').attr("data-dismiss", 'none');
}

/**
 * Function to save Item Value
 *
 * @return {void} No return any value.
 */
function saveItemTomaFisica() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/save";
    var arrParams = new Object();
    arrParams.saveItem = true;
    arrParams.code = $("#codArt").val();
    arrParams.bodega = $("#autocomplete-bodega").val();
    arrParams.desc = $('#descArt').val();
    arrParams.fisica = $('#fisArt').val();
    arrParams.stock = $('#stockArt').val();
    arrParams.estado = $('#estArt').val();

    setOnLoadingAlert();
    requestHttpAjax(link, arrParams, function(response) {
        let idGrid = "grid_list";
        let newVal = arrParams.fisica
        setAlertMessage(response.status, response.label, response.message.wtmessage);
        if (response.status == "OK") {
            $('#' + idGrid + '>table.table>tbody>tr[data-key="' + arrParams.code + '"]>td:nth-child(4)').text(parseFloat(newVal).toFixed(2));
            $('#' + idGrid + '>table.table>tbody>tr[data-key="' + arrParams.code + '"]>td:nth-child(6)').text('M');
        }
        setOffLoadingAlert();
    }, false);
}

/**
 * Function to set Temporal Stocks
 *
 * @return {void} No return any value.
 */
function setTempStockWithCurrentStock() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/save";
    var arrParams = new Object();
    var idGrid = "grid_list";
    arrParams.setTempAct = true;
    arrParams.bodega = $("#autocomplete-bodega").val();
    arrParams.numEx = $("#cmb_stock").val();
    if (arrParams.bodega == "") {
        var msg = objLang.Please_select_a_Cellar_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            $('#' + idGrid + '>table.table>tbody>tr').each(function() {
                var extT = $(this).find('td:nth-child(5)').text();
                $(this).find('td:nth-child(4)').text(extT);
                $(this).find('td:nth-child(6)').text('M');
            });
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}

/**
 * Function to show confirm set Temporal Stocks
 *
 * @return {void} No return any value.
 */
function showConfirmSetTempStocks() {
    var messagePB = new Object();
    messagePB.wtmessage = objLang.Are_you_sure_to_wish_update_Temporal_Stock_with_Current_Stock_for_all_Items_;
    messagePB.title = objLang.Update_Temporal_Stock;
    var objTmp = new Object();
    objTmp.id = "btnid2pdf";
    objTmp.class = "btn-primary clclass praclose";
    objTmp.value = objLang.Update;
    objTmp.callback = 'setTempStockWithCurrentStock';

    messagePB.acciones = new Array();
    messagePB.acciones[0] = objTmp;
    showAlert('OK', 'info', messagePB);
}

/**
 * Function to save inventory
 *
 * @return {void} No return any value.
 */
function save() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/save";
    var arrParams = new Object();
    var idGrid = "grid_list";
    arrParams.saveInventario = true;
    arrParams.bodega = $("#autocomplete-bodega").val();
    arrParams.numEx = $("#cmb_stock").val();
    if (arrParams.bodega == "") {
        var msg = objLang.Please_select_a_Cellar_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
            searchModules();
        }
    }, true);
}

function printInvVal() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/printvalorizados?pdf=1";
    let status = "OK";
    let label = "Success";
    let message = new Object();
    message.title = objLang.Report_Inventory_Valued_List;
    let acciones = new Array();
    let btnPdf = new Object();
    let btnXls = new Object();
    //let params = new Array();
    btnPdf.id = "btnInvalPdf";
    btnPdf.class = "clclass btn-primary";
    btnPdf.value = objLang.PDF;
    btnPdf.callback = "printIframe";

    btnXls.id = "btnInvalXls";
    btnXls.class = "btn-success clclass";
    btnXls.value = objLang.EXCEL;
    btnXls.callback = 'exportxlsinval';

    message.acciones = acciones;
    message.htmloptions = new Object();
    message.acciones[0] = btnXls;
    message.acciones[1] = btnPdf;

    // title box
    message.wtmessage =
        '<div class="box-header with-border">' +
        '<h4 class="box-title">' + objLang.Valued_Inventory_Print + '</h4>' +
        '</div>';
    // begin form
    message.wtmessage +=
        '<form class="form-horizontal">' +
        '<div class="box-body">';
    // body form
    message.wtmessage += '<iframe id="ifrpt" name="ifrpt" src="' + link + '" title="' + objLang.Report_Inventory_Valued_List + '" width="100%" height="500"></iframe>';
    // end form
    message.wtmessage +=
        '</div>' +
        '</form>';

    showAlert(status, label, message);
    $('#btnInvalPdf').attr("data-dismiss", 'none');
    $('#btnInvalXls').attr("data-dismiss", 'none');
}

function printInventario() {
    var bodega = $("#autocomplete-bodega").val();
    var numEx = $("#cmb_stock").val();
    if (bodega == "") {
        var msg = objLang.Please_select_a_Cellar_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/printinventario?pdf=1&bodega=" + bodega + "&numEx=" + numEx;
    let status = "OK";
    let label = "Success";
    let message = new Object();
    message.title = objLang.Report_Inventory_Review_List;
    let acciones = new Array();
    let btnPdf = new Object();
    let btnXls = new Object();
    //let params = new Array();
    btnPdf.id = "btnInvPdf";
    btnPdf.class = "clclass btn-primary";
    btnPdf.value = objLang.PDF;
    btnPdf.callback = "printIframe";

    btnXls.id = "btnInvXls";
    btnXls.class = "btn-success clclass";
    btnXls.value = objLang.EXCEL;
    btnXls.callback = 'exportxlsinventario';

    message.acciones = acciones;
    message.htmloptions = new Object();
    message.acciones[0] = btnXls;
    message.acciones[1] = btnPdf;

    // title box
    message.wtmessage =
        '<div class="box-header with-border">' +
        '<h4 class="box-title">' + objLang.Inventory_Print + '</h4>' +
        '</div>';
    // begin form
    message.wtmessage +=
        '<form class="form-horizontal">' +
        '<div class="box-body">';
    // body form
    message.wtmessage += '<iframe id="ifrpt" name="ifrpt" src="' + link + '" title="' + objLang.Report_Inventory_Review_List + '" width="100%" height="500"></iframe>';
    // end form
    message.wtmessage +=
        '</div>' +
        '</form>';

    showAlert(status, label, message);
    $('#btnInvPdf').attr("data-dismiss", 'none');
    $('#btnInvXls').attr("data-dismiss", 'none');
}

function printStockFisico() {
    var bodega = $("#autocomplete-bodega").val();
    var numEx = $("#cmb_stock").val();
    if (bodega == "") {
        var msg = objLang.Please_select_a_Cellar_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/printtomafisica?pdf=1&bodega=" + bodega + "&numEx=" + numEx;
    let status = "OK";
    let label = "Success";
    let message = new Object();
    message.title = objLang.Report_Physical_Count_List;
    let acciones = new Array();
    let btnPdf = new Object();
    let btnXls = new Object();
    //let params = new Array();
    btnPdf.id = "btnInvPdf";
    btnPdf.class = "clclass btn-primary";
    btnPdf.value = objLang.PDF;
    btnPdf.callback = "printIframe";

    btnXls.id = "btnInvXls";
    btnXls.class = "btn-success clclass";
    btnXls.value = objLang.EXCEL;
    btnXls.callback = 'exportxlsfisico';

    message.acciones = acciones;
    message.htmloptions = new Object();
    message.acciones[0] = btnXls;
    message.acciones[1] = btnPdf;

    // title box
    message.wtmessage =
        '<div class="box-header with-border">' +
        '<h4 class="box-title">' + objLang.Physical_Stock_Print + '</h4>' +
        '</div>';
    // begin form
    message.wtmessage +=
        '<form class="form-horizontal">' +
        '<div class="box-body">';
    // body form
    message.wtmessage += '<iframe id="ifrpt" name="ifrpt" src="' + link + '" title="' + objLang.Report_Physical_Count_List + '" width="100%" height="500"></iframe>';
    // end form
    message.wtmessage +=
        '</div>' +
        '</form>';

    showAlert(status, label, message);
    $('#btnInvPdf').attr("data-dismiss", 'none');
    $('#btnInvXls').attr("data-dismiss", 'none');
}

function exportxlsinval() {
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/expexcelvalorizados";
}

function exportxlsinventario() {
    var bodega = $("#autocomplete-bodega").val();
    var numEx = $("#cmb_stock").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/expexcelinventario?bodega=" + bodega + "&numEx=" + numEx;
}

function exportxlsfisico() {
    var bodega = $("#autocomplete-bodega").val();
    var numEx = $("#cmb_stock").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/tomafisica/expexceltomafisica?bodega=" + bodega + "&numEx=" + numEx;
}

function printIframe() {
    window.frames["ifrpt"].focus();
    window.frames["ifrpt"].print();
}