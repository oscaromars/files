$(document).ready(function () {
    /**
     * Function evento click en botón de cargar cronograma
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return 
     */
    $('#btn_cargarCronograma').click(function () {
        saveCronograma();
    });

    $('#w0-tab0').click(function() {
        alert('entre');
        var href = $(ref).attr('data-href');
        var status = "OK";
        var label = objLang.Success;
        var message = new Object();
        message.title = "Ver Documento";
        message.wtmessage = "<embed src='" + href + "' width='570' height='375' type='application/pdf'>";
        message.acciones = null;
        message.closeaction = null;
        showAlert(status, label, message);
    });
});

function saveCronograma() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/cronograma/cargarcronograma";
   
    arrParams.uaca_id = $('#cmb_unidad').val();
    arrParams.paca_id = $('#cmb_periodo').val();
    arrParams.descripcion = $('#txt_descripcion').val();  
    arrParams.documento = $('#txth_doc_cronograma').val();
    
    if (arrParams.documento === "" || arrParams.paca_id === '0') {
        var mensaje = {wtmessage: "Alerta : Llene los campos obligatorios.", title: "Error"};
        showAlert("NO_OK", "error", mensaje);

    }  else {     
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    parent.window.location.href = $('#txth_base').val() + "/academico/cronograma/new";
                }, 2000);

            }, true);
        }
    } 
}

function viewPdf(ref) {   
    var href = $(ref).attr('data-href');
    var status = "OK";
    var label = objLang.Success;
    var message = new Object();
    message.title = "Ver Documento";
    message.wtmessage = "<embed src='" + href + "' width='570' height='375' type='application/pdf'>";
    message.acciones = null;
    message.closeaction = null;
    showAlert(status, label, message);
}