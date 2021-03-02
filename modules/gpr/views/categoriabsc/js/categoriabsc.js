$(document).ready(function() {
    $("#frm_acc_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconAcc").attr("class", $(this).val());
        else {
            $("#iconAcc").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanAccStatus").click(function() {
        if ($("#frm_bsc_status").val() == "1") {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_bsc_status").val("0");
        } else {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_bsc_status").val("1");
        }
    });
});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit() {
    var link = $('#txth_base').val() + "/gpr/categoriabsc/edit" + "?id=" + $("#frm_bsc_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/gpr/categoriabsc/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_bsc_id").val();
    arrParams.nombre = $('#frm_bsc_name').val();
    arrParams.desc = $('#frm_bsc_desc').val();
    arrParams.estado = $('#frm_bsc_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/gpr/categoriabsc/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_bsc_name').val();
    arrParams.desc = $('#frm_bsc_desc').val();
    arrParams.estado = $('#frm_bsc_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/gpr/categoriabsc/index";
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/gpr/categoriabsc/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            searchModules('boxgrid', 'grid_bsc_list')
                //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}