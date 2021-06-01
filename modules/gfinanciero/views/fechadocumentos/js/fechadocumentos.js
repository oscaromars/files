$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
});

/**
 * Function to apply filter action to gridview
 */
function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_search").val();
    arrParams.type_emi = $('#cmb_tipo_emision').val();
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}


/**
 * Function to update Item from model or record
 */
function update() {
    var ids = String($('#grid_list').PbGridView('getSelectedRows'));
    var count = ids.split(",");
    if (count.length > 0 && ids != "") {
        if (!confirm(objLang.Are_you_sure_you_want_to_update_the_documents_)) return false;
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/fechadocumentos/update";
        var encodedIds = base64_encode(ids); //Verificar cofificacion Base
        //$("#grid_list").addClass("loading");
        var arrParams = new Object();
        arrParams.ids = encodedIds;
        arrParams.fecha = $('#frm_fecha').val();
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    searchModules();
                    //window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/fechadocumentos/index";
                }, 3000);
            }
        }, true);
        //$("#grid_list").removeClass("loading");
    } else {
        var msg = objLang.objLang.Select_an_item_to_process_the_request_;
        shortModal(msg, objLang.Error, "error");
    }
    return true;

}



