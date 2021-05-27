$(document).ready(function() {
    $('#cmb_tplan').change(function() {
        var link = $('#txth_base').val() + "/gpr/reporte/index";
        var arrParams = new Object();
        arrParams.tplan = $("#cmb_tplan").val();
        arrParams.getPlan = true;
        if ($("#cmb_tplan").val() == 0) return;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.planes, "cmb_filter");
            }
        }, true);
    });
    $('#btn_buscarData').click(function() {
        download();
    });
});

function download() {
    if ($('#cmb_tplan').val() != 0 && $('#cmb_filter').val() != 0 && $('#cmb_treporte').val() != 0) {
        var link = $('#txth_base').val() + "/gpr/reporte/download?pdf=1&tplan=" + $('#cmb_tplan').val() + "&filter=" + $('#cmb_filter').val() + "&tipo=" + $('#cmb_treporte').val();
        window.location = link;
    }
}