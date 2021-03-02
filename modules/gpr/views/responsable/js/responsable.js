var objContact = new Array();
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
    arrParams.empresa = $("#cmb_empresa").val();
    arrParams.nivel = $("#cmb_nivel").val();
    arrParams.unidad = $("#cmb_uni").val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit() {
    var link = $('#txth_base').val() + "/gpr/responsable/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/gpr/responsable/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.empresa = $('#cmb_empresa').val();
    arrParams.usuario = $('#cmb_usuario').val();
    arrParams.nivel = $('#cmb_nivel').val();
    arrParams.unidad = $('#cmb_unidad').val();
    arrParams.estado = $('#frm_status').val();
    arrParams.auditor = $('#cmb_auditor').val();
    if ($('#cmb_empresa').val() == 0) {
        var msg = objLang.Please_select_a_Company_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_usuario').val() == 0) {
        var msg = objLang.Please_select_a_Person_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_nivel').val() == 0) {
        var msg = objLang.Please_select_a_Level_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_unidad').val() == 0) {
        var msg = objLang.Please_select_an_Unity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_auditor').val() == 1) {
        var msg = objLang.Auditor_requires_N1_privileges_;
        if ($('#cmb_nivel').val() != 1) {
            shortModal(msg, objLang.Error, "error");
            return;
        }
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/gpr/responsable/save";
    var arrParams = new Object();
    arrParams.empresa = $('#cmb_empresa').val();
    arrParams.usuario = $('#cmb_usuario').val();
    arrParams.nivel = $('#cmb_nivel').val();
    arrParams.unidad = $('#cmb_unidad').val();
    arrParams.estado = $('#frm_status').val();
    arrParams.auditor = $('#cmb_auditor').val();
    if ($('#cmb_empresa').val() == 0) {
        var msg = objLang.Please_select_a_Company_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_usuario').val() == 0) {
        var msg = objLang.Please_select_a_Person_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_nivel').val() == 0) {
        var msg = objLang.Please_select_a_Level_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_unidad').val() == 0) {
        var msg = objLang.Please_select_an_Unity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_auditor').val() == 1) {
        var msg = objLang.Auditor_requires_N1_privileges_;
        if ($('#cmb_nivel').val() != 1) {
            shortModal(msg, objLang.Error, "error");
            return;
        }
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/gpr/responsable/index";
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/gpr/responsable/delete";
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