// Scripts
$(document).on('ready', function() {
    P.on('response', function(data) {
        var resp = JSON.stringify(data, null, 2);
        $(".btnPago").hide();
        setResponseData(data);
    });
    setResponseData($("#vpos_execute_data").val(), $("#vpos_execute").val());
});

function playOnPay(processUrl) {
    if (!$(".btnBuy").hasClass("disabled"))
        P.init(processUrl);
}
/*
 * P.on('response', function(data) { 
	window.href.location = ‘http://localhost/asgard/pagosfrecuentes/resumen?doc_id=7’
});
 */
function returnFn() {
    //parent.reloadFn();
}

function reloadFn() {
    parent.reloadPage();
    parent.closeIframePopup();
}

function showResumen(lblAccept, label, status) {
    if (status == "OK")
        $('#legTransOk').show();
    else
        $('#legTransOk').hide();
    $('#status_id').text(lblAccept);
    $('#status_rsp').text(label);
    $('.vpos-resumen').addClass('rsp_show');
    $('.vpos-resumen').removeClass('rsp_hide');
    $('.form-vpos').addClass('rsp_hide');
}

function setResponseData(data, execute) {
    execute = execute || "3";
    var resp = data;
    if (execute == "1") {
        data = JSON.parse(data);
        resp = data;
    } else if (execute == "3") {

    }
    if (execute == "1" || execute == "2" || execute == "3") {
        var arrParams = new Object();
        var link = window.location.href;
        arrParams.resp = resp;
        arrParams.requestID = (data["requestId"]) ? data["requestId"] : data["requestID"];
        arrParams.referenceID = (data["reference"]) ? data["reference"] : data["payment"]["0"]["reference"];
        if (link.indexOf('?') > -1 && link.indexOf('referenceID') == -1) {
            link += '&referenceID=' + arrParams.referenceID;
        } else if (link.indexOf('referenceID') == -1) {
            link += '?referenceID=' + arrParams.referenceID;
        }

        requestHttpAjax(link, arrParams, function(response) {
            var wtmessage = data["status"]["message"];
            var label = (data["status"]["status"] == "APPROVED") ? objLang.Success : objLang.Error;
            var status = (data["status"]["status"] == "APPROVED") ? "OK" : "NO_OK";
            var callback = "returnFn";
            var lblAccept = (data["status"]["status"] == "APPROVED") ? objLang.Accept : objLang.Reload;
            if (response.status != "OK") {
                wtmessage = response.message.wtmessage;
                label = objLang.Error;
                status = "NO_OK";
                lblAccept = objLang.Accept;
            }
            var label_message = (data["status"]["status"] == "APPROVED") ?
                VPOS_VAR.APPROVED : ((data["status"]["status"] == "REJECTED") ? VPOS_VAR.REJECTED : VPOS_VAR.PENDING);
            var msg_alert = (data["status"]["status"] == "APPROVED") ?
                VPOS_VAR.MSJ_OK : ((data["status"]["status"] == "REJECTED") ? VPOS_VAR.MSJ_REJ : VPOS_VAR.MSJ_PEN);
            showResumen(label_message, wtmessage, status);
            shortModal(msg_alert, label, status, callback, lblAccept);
        }, true);
    }
}

function checkTerms(ref) {
    if ($(ref).is(':checked')) {
        $(".btnBuy").removeClass("disabled");
    } else {
        $(".btnBuy").addClass("disabled");
    }
}