$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        printReport();
    })
});

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putArticuloData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_articulodesc').val(name);
}

/**
 * Function to print Report
 *
 * @return {void} No return any value.
 */
function printReport() {
    //showConfirmExport();
    var articulo = $('#autocomplete-articulo').val();
    var linea = $('#cmb_linea').val();
    var marca = $('#cmb_marca').val();
    var tipo = $("#cmb_tipo").val();
    var precio = $("#cmb_precio").val();
    var stock = $("#cmb_stock").val();
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/listaprecio/printlista?pdf=1&articulo=" + articulo +
        "&linea=" + linea + "&marca=" + marca + "&tipo=" + tipo + "&precio=" + precio + "&stock=" + stock;
    let status = "OK";
    let label = "Success";
    let message = new Object();
    message.title = objLang.Export;
    let acciones = new Array();
    let btnPdf = new Object();
    let btnXls = new Object();
    //let params = new Array();
    btnPdf.id = "btnPdf";
    btnPdf.class = "clclass btn-primary";
    btnPdf.value = objLang.PDF;
    btnPdf.callback = "printIframe";

    btnXls.id = "btnXls";
    btnXls.class = "btn-success clclass";
    btnXls.value = objLang.EXCEL;
    btnXls.callback = 'exportxls';

    message.acciones = acciones;
    message.htmloptions = new Object();
    message.acciones[0] = btnXls;
    message.acciones[1] = btnPdf;

    // title box
    message.wtmessage =
        '<div class="box-header with-border">' +
        '<h4 class="box-title">' + objLang.Select_the_type_format_to_Export_ + '</h4>' +
        '</div>';
    // begin form
    message.wtmessage +=
        '<form class="form-horizontal">' +
        '<div class="box-body">';
    // body form
    message.wtmessage += '<iframe id="ifrpt" name="ifrpt" src="' + link + '" title="' + objLang.Report_Price_List + '" width="100%" height="500"></iframe>';
    // end form
    message.wtmessage +=
        '</div>' +
        '</form>';

    showAlert(status, label, message);
    $('#btnPdf').attr("data-dismiss", 'none');
    $('#btnXls').attr("data-dismiss", 'none');
}

/**
 * Function to show confirm type report pdf or xls
 *
 * @return {void} No return any value.
 */
/*
function showConfirmExport() {
    var messagePB = new Object();
    messagePB.wtmessage = objLang.Select_the_type_format_to_Export_;
    messagePB.title = objLang.Export;
    var objPdf = new Object();
    var objXls = new Object();
    objPdf.id = "btnid2pdf";
    objPdf.class = "btn-primary clclass praclose";
    objPdf.value = objLang.PDF;
    objPdf.callback = 'exportpdf';

    objXls.id = "btnid2xls";
    objXls.class = "btn-success clclass praclose";
    objXls.value = objLang.EXCEL;
    objXls.callback = 'exportxls';

    messagePB.acciones = new Array();
    messagePB.acciones[0] = objXls;
    messagePB.acciones[1] = objPdf;
    showAlert('OK', 'info', messagePB);
}*/

/**
 * Function to export in pdf
 *
 * @return {void} No return any value.
 */
function exportpdf() {
    var articulo = $('#autocomplete-articulo').val();
    var linea = $('#cmb_linea').val();
    var marca = $('#cmb_marca').val();
    var tipo = $("#cmb_tipo").val();
    var precio = $("#cmb_precio").val();
    var stock = $("#cmb_stock").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/listaprecio/exppdf?pdf=1&articulo=" + articulo +
        "&linea=" + linea + "&marca=" + marca + "&tipo=" + tipo + "&precio=" + precio + "&stock=" + stock;
}

/**
 * Function to export in xls
 *
 * @return {void} No return any value.
 */
function exportxls() {
    var articulo = $('#autocomplete-articulo').val();
    var linea = $('#cmb_linea').val();
    var marca = $('#cmb_marca').val();
    var tipo = $("#cmb_tipo").val();
    var precio = $("#cmb_precio").val();
    var stock = $("#cmb_stock").val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/listaprecio/expexcel?articulo=" + articulo +
        "&linea=" + linea + "&marca=" + marca + "&tipo=" + tipo + "&precio=" + precio + "&stock=" + stock;
}

function printIframe() {
    window.frames["ifrpt"].focus();
    window.frames["ifrpt"].print();
}