$(document).ready(function() {
    $("body").on("keyup", "#numMeta, #denMeta, #indMeta, .resultC", function(event) {
        if ($('#frm_fraccional').val() == 1) {
            if (($('#denMeta').val() != "" && $('#denMeta').val() != 0) || ($('#numMeta').val() != "" && $('#numMeta').val() != 0)) {
                let numerador = $('#numMeta').val();
                let denominador = $('#denMeta').val();
                let meta = $('#indMeta').val();
                let resultado = Math.round((numerador / denominador) * 100);
                let avance = Math.round((resultado / meta) * 100);
                $('#indResultado').val(resultado);
                $('#indAvance').val(avance);
            }
        } else {
            let meta = $('#indMeta').val();
            let resultado = $('#indResultado').val();
            let avance = Math.round((resultado / meta) * 100);
            $('#indAvance').val(avance);
        }
    });
});

function searchModules(id) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.id = id;
    $("#grid_list_resultado").PbGridView("applyFilterData", arrParams);
}

function editResultado(id, periodo, denominador) {
    denominador = denominador || false;
    let idGrid = "grid_list_resultado";
    let status = "OK";
    let label = "Success";
    let dataNumerador = "";
    let disabled = "";
    let caracterTipo = ".00";
    let classTipo = "resultC";
    let message = new Object();
    message.title = objLang.Edit_Goal;
    let acciones = new Array();
    let btnSave = new Object();
    let params = new Array();
    btnSave.id = "btnSaveMeta";
    btnSave.class = "clclass";
    btnSave.value = objLang.Save;
    btnSave.callback = "saveResultado";
    params.push(id);
    btnSave.paramCallback = params;
    acciones.push(btnSave);
    message.acciones = acciones;
    //message.htmloptions = new Object();
    //message.htmloptions.style = style;
    let metaPeriodo = ($('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(3)').text()).replace("%", "");
    let resultado = ($('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(4)').text()).replace("%", "");
    let avance = ($('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(5)').text()).replace("%", "");
    let estado = ($('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(6)').text()).replace("%", "");
    let tipoM = $('#frm_tipoM').val();
    let descripcion = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(8)').text();
    let comentario = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(9)').text();


    if (denominador) {
        numerador = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(4)').text();
        denominado = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(5)').text();
        resultado = ($('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(6)').text()).replace("%", "");
        avance = ($('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(7)').text()).replace("%", "");
        estado = ($('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(8)').text()).replace("%", "");
        descripcion = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(10)').text();
        comentario = $('#' + idGrid + '>table.table>tbody>tr[data-key="' + id + '"]>td:nth-child(11)').text();
        dataNumerador =
            '<div class="form-group">' +
            '<label for="numMeta" class="col-sm-4 control-label">' + objLang.Numerator + '</label>' +
            '<div class="col-sm-8">' +
            '<input type="text" class="form-control PBvalidation" value="' + numerador + '" data-type="all" id="numMeta" placeholder="' + objLang.Numerator + '">' +
            '</div>' +
            '</div>' +
            '<div class="form-group">' +
            '<label for="denMeta" class="col-sm-4 control-label">' + objLang.Denominator + '</label>' +
            '<div class="col-sm-8">' +
            '<input type="text" class="form-control PBvalidation" disabled="disabled" value="' + denominado + '" data-type="all" id="denMeta" placeholder="' + objLang.Denominator + '">' +
            '</div>' +
            '</div>';
        disabled = 'disabled="disabled"';
        caracterTipo = "%";
        classTipo = "";
    }

    message.wtmessage =
        '<div class="box-header with-border">' +
        '<h4 class="box-title">' + objLang.Period + ': ' + periodo + ' (' + tipoM + ')</h4>' +
        '</div>' +
        '<form class="form-horizontal" enctype="multipart/form-data" id="resultadoform" method="post">' +
        '<div class="box-body">' +
        '<div class="form-group">' +
        '<label for="indMeta" class="col-sm-4 control-label">' + objLang.Period_Goal + '</label>' +
        '<div class="col-sm-8">' +
        '<div class="input-group">' +
        '<input type="text" class="form-control PBvalidation" value="' + metaPeriodo + '" disabled="disabled" data-type="all" id="indMeta" placeholder="' + objLang.Period_Goal + '">' +
        '<span class="input-group-addon">' + caracterTipo + '</span>' +
        '</div>' +
        '</div>' +
        '</div>' +
        dataNumerador +
        '<div class="form-group">' +
        '<label for="indResultado" class="col-sm-4 control-label">' + objLang.Result + '</label>' +
        '<div class="col-sm-8">' +
        '<div class="input-group">' +
        '<input type="text" class="form-control PBvalidation ' + classTipo + '" ' + disabled + ' value="' + resultado + '" data-type="all" id="indResultado" placeholder="' + objLang.Result + '">' +
        '<span class="input-group-addon">' + caracterTipo + '</span>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="indAvance" class="col-sm-4 control-label">' + objLang.Advance_Period + '</label>' +
        '<div class="col-sm-8">' +
        '<div class="input-group">' +
        '<input type="text" class="form-control PBvalidation" disabled="disabled" value="' + avance + '" data-type="all" id="indAvance" placeholder="' + objLang.Advance_Period + '">' +
        '<span class="input-group-addon">%</span>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="fileanex" class="col-sm-4 control-label">' + objLang.Attach_File + '</label>' +
        '<div class="col-sm-8">' +
        '<div class="file-upload-wrapper form-control" data-text="' + objLang.LoadFile + '" data-textbtn="' + objLang.Upload + '">' +
        '<input type="file" class="PBvalidation file-upload-field" value="" data-type="all" name="fileanex" id="fileanex" placeholder="' + objLang.Attach_File + '">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="seganex" class="col-sm-4 control-label">' + objLang.Attached_File_Name_Description + '</label>' +
        '<div class="col-sm-8">' +
        '<textarea class="form-control PBvalidation" data-type="all" id="seganex" placeholder="' + objLang.Attached_File_Name_Description + '">' + descripcion + '</textarea>' +
        '</div>' +
        '</div>' +
        '<div class="form-group">' +
        '<label for="comentario" class="col-sm-4 control-label">' + objLang.Comments + '</label>' +
        '<div class="col-sm-8">' +
        '<textarea class="form-control PBvalidation" data-type="all" id="comentario" placeholder="' + objLang.Comments + '">' + comentario + '</textarea>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</form>';

    showAlert(status, label, message);
    $('#btnSaveMeta').attr("data-dismiss", 'none');
}

function saveResultado(id) {
    var link = $('#txth_base').val() + "/gpr/resultado/save";
    var arrParams = new Object();
    var files = $('#fileanex')[0].files;
    var lbl = "";
    var fileExt = $('#fileanex').val().split('.').pop();
    var filesize = $('#fileanex')[0].size;
    var filename = $('#fileanex').val().replace(/.*(\/|\\)/, '');
    var limit = parseInt($('#frm_filesize').val());
    var exten = $('#frm_extension').val();
    arrParams.id = id;
    arrParams.meta = $('#indMeta').val();
    arrParams.resultado = $('#indResultado').val();
    arrParams.avance = $('#indAvance').val();
    arrParams.numerador = ($("#numMeta").val() != undefined) ? $("#numMeta").val() : null;
    arrParams.denominador = ($("#denMeta").val() != undefined) ? $("#denMeta").val() : null;
    arrParams.descripcion = $("#seganex").val();
    arrParams.comentario = $("#comentario").val();
    arrParams.anexo = files[0];


    if (files.length <= 0) {
        setAlertMessage('NOOK', objLang.Error, objLang.Please_attach_a_File_Name_);
        return;
    }
    if (fileExt.toLowerCase() != 'pdf') {
        lbl = objLang._file__extension_is_invalid__Only__extensions__are_allowed_;
        lbl = lbl.replace(/\{file\}/g, filename);
        lbl = lbl.replace(/\{extensions\}/g, exten);
        setAlertMessage('NOOK', objLang.Error, lbl);
        return;
    }
    if (filesize > limit) {
        lbl = objLang._file__is_too_large__maximum_file_size_is__sizeLimit__;
        lbl = lbl.replace(/\{file\}/g, filename);
        lbl = lbl.replace(/\{sizeLimit\}/g, limit / 1024 / 1024 + 'MB');
        setAlertMessage('NOOK', objLang.Error, lbl);
        return;
    }
    var msgVal = validateForm(null, true);
    if (msgVal != "") {
        setAlertMessage('NOOK', objLang.Error, msgVal);
    } else {
        setOnLoadingAlert();
        requestHttpAjax(link, arrParams, function(response) {
            setAlertMessage(response.status, response.label, response.message.wtmessage);
            if (response.status == "OK") {
                //closeAlert();
                let idInd = $('#frm_id').val();
                searchModules(idInd);
            }
            setOffLoadingAlert();
        }, false, true);
    }
}

function openPeriodoResultado(mseg_id) {
    var link = $('#txth_base').val() + "/gpr/resultado/openresultado";
    var arrParams = new Object();
    arrParams.id = mseg_id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            let id = $('#frm_id').val();
            searchModules(id)
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}

function downloadFile(id) {
    var link = $('#txth_base').val() + "/gpr/resultado/downloadanexo" + "?id=" + id;
    window.location = link;
}