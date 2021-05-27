
$(document).ready(function () {
    $("#frm_grupo_image").keyup(function () {
        if ($(this).val() != "")
            $("#iconGrup").attr("class", $(this).val());
        else {
            $("#iconGrup").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanGrupStatus").click(function () {
        if ($("#frm_grupo_status").val() == "1") {
            $("#iconGrupStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_grupo_status").val("0");
        } else {
            $("#iconGrupStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_grupo_status").val("1");
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
    var link = $('#txth_base').val() + "/grupo/edit" + "?id=" + $("#frm_grupo_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/grupo/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_grupo_id").val();
    arrParams.nombre = $('#frm_grupo').val();
    arrParams.desc = $('#frm_grupo_desc').val();
    arrParams.seg = $("#cmb_grupo_seg").val();
    arrParams.estado = $('#frm_grupo_status').val();
    arrParams.roles = $('#cmb_roles').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/grupo/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_grupo').val();
    arrParams.desc = $('#frm_grupo_desc').val();
    arrParams.seg = $("#cmb_grupo_seg").val();
    arrParams.estado = $('#frm_grupo_status').val();
    arrParams.roles = $('#cmb_roles').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/grupo/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_grupos_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function(){ 
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}
