
$(document).ready(function () {
    $("#frm_canton_image").keyup(function () {
        if ($(this).val() != "")
            $("#iconCanton").attr("class", $(this).val());
        else {
            $("#iconCanton").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanCantonesStatus").click(function () {        
        if ($("#frm_canton_status").val() == "1") {
            $("#iconCantonStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_canton_status").val("0");
        } else {
            $("#iconCantonStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_canton_status").val("1");
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
    var link = $('#txth_base').val() + "/canton/edit" + "?id=" + $("#frm_canton_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/canton/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_canton_id").val();
    arrParams.nombre = $('#frm_canton').val();
    arrParams.desc = $('#frm_canton_desc').val();
    arrParams.pro_id = $('#cmb_provincia').val();    
    arrParams.estado = $('#frm_canton_status').val();
    if (!validateForm()) {
        console.log(arrParams);
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/canton/save";
    var arrParams = new Object();    
    arrParams.nombre = $('#frm_canton').val();
    arrParams.desc = $('#frm_canton_desc').val();
    arrParams.pro_id = $('#cmb_provincia').val();    
    arrParams.estado = $('#frm_canton_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/canton/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_cantones_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function(){ 
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}
