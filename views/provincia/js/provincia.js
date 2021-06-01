
$(document).ready(function () {
    $("#frm_provincia_image").keyup(function () {
        if ($(this).val() != "")
            $("#iconProvincia").attr("class", $(this).val());
        else {
            $("#iconProvincia").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanProvinciaStatus").click(function () {        
        if ($("#frm_provincia_status").val() == "1") {
            $("#iconProvinciaStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_provincia_status").val("0");
        } else {
            $("#iconProvinciaStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_provincia_status").val("1");
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
    var link = $('#txth_base').val() + "/provincia/edit" + "?id=" + $("#frm_provincia_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/provincia/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_provincia_id").val();
    arrParams.nombre = $('#frm_provincia').val();
    arrParams.desc = $('#frm_provincia_desc').val();
    arrParams.pai_id = $('#cmb_pais').val(); 
    arrParams.capital = $('#frm_provincia_capital').val(); 
    arrParams.estado = $('#frm_provincia_status').val();
    if (!validateForm()) {
        console.log(arrParams);
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/provincia/save";
    var arrParams = new Object();    
    arrParams.nombre = $('#frm_provincia').val();
    arrParams.desc = $('#frm_provincia_desc').val();
    arrParams.pai_id = $('#cmb_pais').val();
    arrParams.capital = $('#frm_provincia_capital').val();    
    arrParams.estado = $('#frm_provincia_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/provincia/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_provincia_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function(){ 
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}
