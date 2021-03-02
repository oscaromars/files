$(document).ready(function () {
    $("#frm_acc_image").keyup(function () {
        if ($(this).val() != "")
            $("#iconAcc").attr("class", $(this).val());
        else {
            $("#iconAcc").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanAccStatus").click(function () {
        if ($("#frm_acc_status").val() == "1") {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_acc_status").val("0");
        } else {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_acc_status").val("1");
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
    var link = $('#txth_base').val() + "/acciones/edit" + "?id=" + $("#frm_acc_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/acciones/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_acc_id").val();
    arrParams.nombre = $('#frm_accion').val();
    arrParams.desc = $('#frm_acc_desc').val();
    arrParams.tipo = $('#frm_acc_type').val();
    arrParams.icon = $('#frm_acc_image').val();
    arrParams.url = $('#frm_acc_url').val();
    arrParams.lang = $('#frm_acc_lang').val();
    arrParams.estado = $('#frm_acc_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/acciones/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_accion').val();
    arrParams.desc = $('#frm_acc_desc').val();
    arrParams.tipo = $('#frm_acc_type').val();
    arrParams.icon = $('#frm_acc_image').val();
    arrParams.url = $('#frm_acc_url').val();
    arrParams.lang = $('#frm_acc_lang').val();
    arrParams.estado = $('#frm_acc_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/acciones/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_acciones_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function(){ 
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}
