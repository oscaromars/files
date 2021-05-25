$(document).ready(function() {

});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function exportExcel() {
    var search = $("#boxgrid").val();
    window.location.href = $('#txth_base').val() + "/piensaecuador/registro/expexcel?search=" + search;
}

function exportPdf() {
    var search = $("#boxgrid").val();
    window.location.href = $('#txth_base').val() + "/piensaecuador/registro/exppdf?pdf=1&search=" + search;
}