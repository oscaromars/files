$(document).ready(function() {
    $('#cmb_tplan').change(function() {
        var link = $('#txth_base').val() + "/gpr/presupuesto/index";
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
        searchModules();
    });
});

function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.tplan = $("#cmb_tplan").val();
    arrParams.id = $("#cmb_filter").val();
    if ($("#cmb_tplan").val() == 0) return;
    if ($("#cmb_filter").val() == 0) return;
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}