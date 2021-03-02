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
        if ($("#frm_status").val() == "1") {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_status").val("0");
        } else {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_status").val("1");
        }
    });
    $('#btn_buscarData').click(function() {
        searchModules('boxgrid', 'grid_list');
    });
});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_buscarData").val();
    arrParams.entidad = $("#cmb_ent").val();
    arrParams.cierre = $("#cmb_closed").val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit() {
    var link = $('#txth_base').val() + "/gpr/planificacionpedi/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/gpr/planificacionpedi/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.fini = $('#frm_fini').val();
    arrParams.fend = $('#frm_fend').val();
    arrParams.entidad = $('#cmb_ent').val();
    arrParams.cierre = $('#cmb_cierre').val();
    arrParams.estado = $('#frm_status').val();
    if ($('#cmb_ent').val() == 0) {
        var msg = objLang.Please_select_an_Entity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#frm_fini').val() >= $('#frm_fend').val()) {
        var msg = objLang.The_initial_date_of_registry_cannot_be_greater_than_end_date_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/gpr/planificacionpedi/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.fini = $('#frm_fini').val();
    arrParams.fend = $('#frm_fend').val();
    arrParams.entidad = $('#cmb_ent').val();
    arrParams.estado = $('#frm_status').val();
    if ($('#cmb_ent').val() == 0) {
        var msg = objLang.Please_select_an_Entity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#frm_fini').val() >= $('#frm_fend').val()) {
        var msg = objLang.The_initial_date_of_registry_cannot_be_greater_than_end_date_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/gpr/planificacionpedi/index";
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/gpr/planificacionpedi/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            searchModules('boxgrid', 'grid_list')
                //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}