$(document).ready(function () {
    $("#frm_omod_image").keyup(function () {
        if ($(this).val() != "")
            $("#iconOMod").attr("class", $(this).val());
        else {
            $("#iconOMod").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanOModStatus").click(function () {
        if ($("#frm_omod_status").val() == "1") {
            $("#iconOModStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_omod_status").val("0");
        } else {
            $("#iconOModStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_omod_status").val("1");
        }
    });
    $("#spanOModVisib").click(function () {
        if ($("#frm_omod_visibility").val() == "1") {
            $("#iconOModVisib").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_omod_visibility").val("0");
        } else {
            $("#iconOModVisib").attr("class", "glyphicon glyphicon-check");
            $("#frm_omod_visibility").val("1");
        }
    });
    $("#cmb_omod_type").change(function(){
        $('#frm_acc_lk').val("");
        $('#frm_acc_fn').val("");
        switch($(this).val()) {
            case "0": // P
                $('#cmb_omod_padre').parent().parent().removeClass("unhideSubModulo");
                $('#cmb_omod_padre').parent().parent().addClass("hideSubModulo");
                $('#cmb_acc_type').parent().parent().removeClass("unhideActions");
                $('#cmb_acc_type').parent().parent().addClass("hideActions");
                $('#cmb_acc_type_btn').val(0);
                $('#cmb_acc_type_btn').parent().parent().removeClass("unhideActions");
                $('#cmb_acc_type_btn').parent().parent().addClass("hideActions");
                $('#frm_acc_fn').parent().parent().removeClass("unhideJsFn");
                $('#frm_acc_fn').parent().parent().addClass("hideJsFn");
                $('#frm_acc_lk').parent().parent().removeClass("unhideLk");
                $('#frm_acc_lk').parent().parent().addClass("hideLk");
                $('#frm_acc_lk').removeClass("PBvalidation");
                $('#frm_acc_fn').removeClass("PBvalidation");
                $('#frm_omod_url').removeClass("PBvalidation");
                break;
            case "1": // S
                $('#cmb_omod_padre').parent().parent().removeClass("hideSubModulo");
                $('#cmb_omod_padre').parent().parent().addClass("unhideSubModulo");
                $('#cmb_acc_type').parent().parent().removeClass("unhideActions");
                $('#cmb_acc_type').parent().parent().addClass("hideActions");
                $('#cmb_acc_type_btn').val(0);
                $('#cmb_acc_type_btn').parent().parent().removeClass("unhideActions");
                $('#cmb_acc_type_btn').parent().parent().addClass("hideActions");
                $('#frm_acc_fn').parent().parent().removeClass("unhideJsFn");
                $('#frm_acc_fn').parent().parent().addClass("hideJsFn");
                $('#frm_acc_lk').parent().parent().removeClass("unhideLk");
                $('#frm_acc_lk').parent().parent().addClass("hideLk");
                $('#frm_acc_lk').removeClass("PBvalidation");
                $('#frm_acc_fn').removeClass("PBvalidation");
                $('#frm_omod_url').addClass("PBvalidation");
                break;
            case "2": // A
                $('#cmb_omod_padre').parent().parent().removeClass("hideSubModulo");
                $('#cmb_omod_padre').parent().parent().addClass("unhideSubModulo");
                $('#cmb_acc_type').parent().parent().removeClass("hideActions");
                $('#cmb_acc_type').parent().parent().addClass("unhideActions");
                $('#cmb_acc_type_btn').parent().parent().removeClass("hideActions");
                $('#cmb_acc_type_btn').parent().parent().addClass("unhideActions");
                $('#frm_acc_fn').parent().parent().removeClass("unhideJsFn");
                $('#frm_acc_fn').parent().parent().addClass("hideJsFn");
                $('#frm_acc_lk').parent().parent().removeClass("hideLk");
                $('#frm_acc_lk').parent().parent().addClass("unhideLk");
                $('#frm_acc_lk').addClass("PBvalidation");
                $('#frm_omod_url').addClass("PBvalidation");
                break;
            default:
                $('#cmb_omod_padre').parent().parent().removeClass("unhideSubModulo");
                $('#cmb_omod_padre').parent().parent().addClass("hideSubModulo");
                $('#cmb_acc_type').parent().parent().removeClass("unhideActions");
                $('#cmb_acc_type').parent().parent().addClass("hideActions");
                $('#cmb_acc_type_btn').val(0);
                $('#cmb_acc_type_btn').parent().parent().removeClass("unhideActions");
                $('#cmb_acc_type_btn').parent().parent().addClass("hideActions");
                $('#frm_acc_fn').parent().parent().removeClass("unhideJsFn");
                $('#frm_acc_fn').parent().parent().addClass("hideJsFn");
                $('#frm_acc_lk').parent().parent().removeClass("unhideLk");
                $('#frm_acc_lk').parent().parent().addClass("hideLk");
                $('#frm_acc_lk').removeClass("PBvalidation");
                $('#frm_acc_fn').removeClass("PBvalidation");
                $('#frm_omod_url').addClass("PBvalidation");
                break;
        }
    });
    $('#cmb_acc_type_btn').change(function(){
        if($(this).val() == 0){
            $('#frm_acc_lk').parent().parent().removeClass("hideLk");
            $('#frm_acc_lk').parent().parent().addClass("unhideLk");
            $('#frm_acc_fn').parent().parent().removeClass("unhideJsFn");
            $('#frm_acc_fn').parent().parent().addClass("hideJsFn");
            $('#frm_acc_fn').val("");
            $('#frm_acc_lk').addClass("PBvalidation");
            $('#frm_acc_fn').removeClass("PBvalidation");
        }else {
            $('#frm_acc_lk').parent().parent().removeClass("unhideLk");
            $('#frm_acc_lk').parent().parent().addClass("hideLk");
            $('#frm_acc_fn').parent().parent().removeClass("hideJsFn");
            $('#frm_acc_fn').parent().parent().addClass("unhideJsFn");
            $('#frm_acc_lk').val("");
            $('#frm_acc_fn').addClass("PBvalidation");
            $('#frm_acc_lk').removeClass("PBvalidation");
        }
    });
});
function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit(){
    var link = $('#txth_base').val() + "/objetomodulos/edit" + "?id=" + $("#frm_omod_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/objetomodulos/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_omod_id").val();
    arrParams.nombre = $('#frm_omodulo').val();
    arrParams.mod_id = $('#cmb_modulo').val();
    arrParams.omod_padre = $('#cmb_omod_padre').val();
    arrParams.tipo = $('#cmb_omod_type').val();
    arrParams.btn = $('#cmb_omod_type_btn').val();
    arrParams.acc = $('#frm_omod_acc').val();
    arrParams.fn = $('#frm_omod_fn').val();
    arrParams.icon = $('#frm_omod_image').val();
    arrParams.url = $('#frm_omod_url').val();
    arrParams.orden = $('#frm_omod_orden').val();
    arrParams.visible = $('#frm_omod_visibility').val();
    arrParams.lang = $('#frm_omod_lang').val();
    arrParams.estado = $('#frm_omod_status').val();
    arrParams.acc_type = $('#cmb_acc_type').val();
    arrParams.type_btn = $('#cmb_acc_type_btn').val();
    arrParams.acc_fn = $('#frm_acc_fn').val();
    arrParams.acc_lk = $('#frm_acc_lk').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/objetomodulos/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_omodulo').val();
    arrParams.mod_id = $('#cmb_modulo').val();
    arrParams.omod_padre = $('#cmb_omod_padre').val();
    arrParams.tipo = $('#cmb_omod_type').val();
    arrParams.btn = $('#cmb_omod_type_btn').val();
    arrParams.acc = $('#frm_omod_acc').val();
    arrParams.fn = $('#frm_omod_fn').val();
    arrParams.icon = $('#frm_omod_image').val();
    arrParams.url = $('#frm_omod_url').val();
    arrParams.orden = $('#frm_omod_orden').val();
    arrParams.visible = $('#frm_omod_visibility').val();
    arrParams.lang = $('#frm_omod_lang').val();
    arrParams.estado = $('#frm_omod_status').val();
    arrParams.acc_type = $('#cmb_acc_type').val();
    arrParams.type_btn = $('#cmb_acc_type_btn').val();
    arrParams.acc_fn = $('#frm_acc_fn').val();
    arrParams.acc_lk = $('#frm_acc_lk').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/objetomodulos/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_omodule_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function(){ 
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}
