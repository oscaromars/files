$(document).ready(function() {
    $('#btn_buscarData').click(function() {
        searchModules('boxgrid', 'grid_list');
    });
    $('#cmb_agrupacion').change(function() {
        if ($(this).val() == 0) {
            $('#dvTagr').hide();
            $('#dvTagr').attr('data-agrp', 0);
        } else {
            $('#dvTagr').show();
            $('#dvTagr').attr('data-agrp', 1);
        }
    });
    $('#cmb_compartamiento').change(function() {
        if ($(this).val() == 1) {
            $('#cmb_tmeta').val(2);
        } else {
            $('#cmb_tmeta').val(1);
        }
    });
});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_buscarData").val();
    arrParams.objetivo = $("#cmb_obj").val();
    arrParams.plan = $("#cmb_plan").val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit() {
    var link = $('#txth_base').val() + "/gpr/indicador/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/gpr/indicador/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.baseline = $('#frm_baseline').val();
    arrParams.operativo = $("#cmb_objoperativo").val();
    arrParams.medida = $("#cmb_medida").val();
    arrParams.fuente = $("#frm_fuente").val();
    arrParams.calculo = $("#frm_calculo").val();
    arrParams.jerarquia = $("#cmb_jerarquia").val();
    arrParams.unidad = $("#cmb_unidad").val();
    arrParams.tconfig = $("#cmb_tconfiguracion").val();
    arrParams.patron = $("#cmb_patron").val();
    arrParams.comportamiento = $('#cmb_compartamiento').val();
    arrParams.tmeta = $('#cmb_tmeta').val();
    arrParams.frecuencia = $('#cmb_frecuencia').val();
    arrParams.fini = $('#frm_fini').val();
    arrParams.fraccional = $('#cmb_fraccional').val();
    arrParams.agrupacion = $('#cmb_agrupacion').val();
    arrParams.tagrupacion = $('#cmb_tagrupacion').val();

    if ($('#cmb_medida').val() == 0) {
        var msg = objLang.Please_select_an_Unit_of_Measure_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_jerarquia').val() == 0) {
        var msg = objLang.Please_select_a_Hierarchy_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_unidad').val() == 0) {
        var msg = objLang.Please_select_an_Unity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_patron').val() == 0) {
        var msg = objLang.Please_select_an_Indicator_Pattern_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_tconfiguracion').val() == 0) {
        var msg = objLang.Please_select_a_Configuration_Type_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_objoperativo').val() == 0) {
        var msg = objLang.Please_select_an_Operative_Objective_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_compartamiento').val() == 0) {
        var msg = objLang.Please_select_an_Indicator_Behavior_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_tmeta').val() == 0) {
        var msg = objLang.Please_select_a_Goal_Type_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_frecuencia').val() == 0) {
        var msg = objLang.Please_select_an_Indicator_Frecuency_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_medida').val() == 2) {
        if ($('#cmb_fraccional').val() == 0) {
            var msg = objLang.Please_if_Unit_of_Measure_is_percentage_you_have_to_select_an_Indicator_Fractional_as_Yes_;
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
    var link = $('#txth_base').val() + "/gpr/indicador/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.baseline = $('#frm_baseline').val();
    arrParams.operativo = $("#cmb_objoperativo").val();
    arrParams.medida = $("#cmb_medida").val();
    arrParams.fuente = $("#frm_fuente").val();
    arrParams.calculo = $("#frm_calculo").val();
    arrParams.jerarquia = $("#cmb_jerarquia").val();
    arrParams.unidad = $("#cmb_unidad").val();
    arrParams.tconfig = $("#cmb_tconfiguracion").val();
    arrParams.patron = $("#cmb_patron").val();
    arrParams.comportamiento = $('#cmb_compartamiento').val();
    arrParams.tmeta = $('#cmb_tmeta').val();
    arrParams.frecuencia = $('#cmb_frecuencia').val();
    arrParams.fini = $('#frm_fini').val();
    arrParams.fraccional = $('#cmb_fraccional').val();
    arrParams.agrupacion = $('#cmb_agrupacion').val();
    arrParams.tagrupacion = $('#cmb_tagrupacion').val();

    if ($('#cmb_medida').val() == 0) {
        var msg = objLang.Please_select_an_Unit_of_Measure_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_jerarquia').val() == 0) {
        var msg = objLang.Please_select_a_Hierarchy_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_unidad').val() == 0) {
        var msg = objLang.Please_select_an_Unity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_patron').val() == 0) {
        var msg = objLang.Please_select_an_Indicator_Pattern_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_tconfiguracion').val() == 0) {
        var msg = objLang.Please_select_a_Configuration_Type_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_objoperativo').val() == 0) {
        var msg = objLang.Please_select_an_Operative_Objective_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_compartamiento').val() == 0) {
        var msg = objLang.Please_select_an_Indicator_Behavior_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_tmeta').val() == 0) {
        var msg = objLang.Please_select_a_Goal_Type_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_frecuencia').val() == 0) {
        var msg = objLang.Please_select_an_Indicator_Frecuency_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_medida').val() == 2) {
        if ($('#cmb_fraccional').val() == 0) {
            var msg = objLang.Please_if_Unit_of_Measure_is_percentage_you_have_to_select_an_Indicator_Fractional_as_Yes_;
            shortModal(msg, objLang.Error, "error");
            return;
        }
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/gpr/indicador/index";
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/gpr/indicador/delete";
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