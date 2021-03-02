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
    arrParams.categoria = $("#cmb_cat").val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit() {
    var link = $('#txth_base').val() + "/gpr/entidad/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/gpr/entidad/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.categoria = $('#cmb_cat').val();
    arrParams.empresa = $('#cmb_emp').val();
    arrParams.estado = $('#frm_status').val();
    if ($('#cmb_cat').val() == 0) {
        var msg = objLang.Please_select_a_Category_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_emp').val() == 0) {
        var msg = objLang.Please_select_a_Company_Name_;
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
    var link = $('#txth_base').val() + "/gpr/entidad/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.categoria = $('#cmb_cat').val();
    arrParams.empresa = $('#cmb_emp').val();
    arrParams.estado = $('#frm_status').val();
    if ($('#cmb_cat').val() == 0) {
        var msg = objLang.Please_select_a_Category_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_emp').val() == 0) {
        var msg = objLang.Please_select_a_Company_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/gpr/entidad/index";
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/gpr/entidad/delete";
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