$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules();
    });
    $('#cmb_objop').change(function() {
        var link = $('#txth_base').val() + "/gpr/proyecto/index";
        var arrParams = new Object();
        arrParams.objetivo = $(this).val();
        arrParams.getobjetivos = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.unidad, "cmb_ugpr");
            }
        }, true);
    });
});

function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_buscarData").val();
    arrParams.tpro = $("#cmb_tipo").val();
    arrParams.objetivo = $("#cmb_obj").val();
    arrParams.unidad = $("#cmb_unidad").val();
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}

function edit() {
    var link = $('#txth_base').val() + "/gpr/proyecto/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/gpr/proyecto/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.tipo = $('#cmb_tipo').val();
    arrParams.objetivo = $('#cmb_objop').val();
    arrParams.unidad = $('#cmb_ugpr').val();
    arrParams.presupuesto = $('#frm_presupuesto').val();
    arrParams.fini = $('#frm_fini').val();
    arrParams.fend = $('#frm_fend').val();
    arrParams.restricciones = $('#frm_restricciones').val();
    arrParams.razon = $('#frm_razon').val();
    if ($('#cmb_tipo').val() == 0) {
        var msg = objLang.Please_select_a_Project_Type_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_objop').val() == 0) {
        var msg = objLang.Please_select_an_Operative_Objective_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_ugpr').val() == 0) {
        var msg = objLang.Please_select_an_Unity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrParams.fini >= arrParams.fend) {
        var msg = objLang.Initial_Date_must_be_less_than_to_End_Date_;
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
    var link = $('#txth_base').val() + "/gpr/proyecto/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.tipo = $('#cmb_tipo').val();
    arrParams.objetivo = $('#cmb_objop').val();
    arrParams.unidad = $('#cmb_ugpr').val();
    arrParams.presupuesto = $('#frm_presupuesto').val();
    arrParams.fini = $('#frm_fini').val();
    arrParams.fend = $('#frm_fend').val();
    arrParams.restricciones = $('#frm_restricciones').val();
    if ($('#cmb_tipo').val() == 0) {
        var msg = objLang.Please_select_a_Project_Type_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_objop').val() == 0) {
        var msg = objLang.Please_select_an_Operative_Objective_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_ugpr').val() == 0) {
        var msg = objLang.Please_select_an_Unity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrParams.fini >= arrParams.fend) {
        var msg = objLang.Initial_Date_must_be_less_than_to_End_Date_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/gpr/proyecto/index";
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/gpr/proyecto/delete";
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

function openProject() {
    var link = $('#txth_base').val() + "/gpr/proyecto/openproject";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
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