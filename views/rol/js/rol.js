
$(document).ready(function () {
    $("#frm_rol_image").keyup(function () {
        if ($(this).val() != "")
            $("#iconRol").attr("class", $(this).val());
        else {
            $("#iconRol").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanRolStatus").click(function () {
        if ($("#frm_rol_status").val() == "1") {
            $("#iconRolStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_rol_status").val("0");
        } else {
            $("#iconRolStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_rol_status").val("1");
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
    var link = $('#txth_base').val() + "/rol/edit" + "?id=" + $("#frm_rol_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/rol/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_rol_id").val();
    arrParams.nombre = $('#frm_rol').val();
    arrParams.desc = $('#frm_rol_desc').val();
    arrParams.estado = $('#frm_rol_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/rol/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_rol').val();
    arrParams.desc = $('#frm_rol_desc').val();
    arrParams.estado = $('#frm_rol_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/rol/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_roles_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function(){ 
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}
