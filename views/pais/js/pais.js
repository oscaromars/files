
$(document).ready(function () {
    $("#frm_pais_image").keyup(function () {
        if ($(this).val() != "")
            $("#iconPais").attr("class", $(this).val());
        else {
            $("#iconPais").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanPaisStatus").click(function () {        
        if ($("#frm_pais_status").val() == "1") {
            $("#iconPaisStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_pais_status").val("0");
        } else {
            $("#iconPaisStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_pais_status").val("1");
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
    var link = $('#txth_base').val() + "/pais/edit" + "?id=" + $("#frm_pais_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/pais/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_pais_id").val();
    arrParams.nombre = $('#frm_pais').val();
    arrParams.desc = $('#frm_pais_desc').val();
    arrParams.cont_id = $('#cmb_pais').val();    
    arrParams.cap = $('#frm_pais_cap').val();
    arrParams.nac = $('#frm_pais_nac').val();
    arrParams.iso2 = $('#frm_pais_iso2').val();
    arrParams.iso3 = $('#frm_pais_iso3').val();
    arrParams.cod = $('#frm_pais_cod').val();
    arrParams.estado = $('#frm_pais_status').val();
    if (!validateForm()) {
        console.log(arrParams);
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/pais/save";
    var arrParams = new Object();    
    arrParams.nombre = $('#frm_pais').val();
    arrParams.desc = $('#frm_pais_desc').val();
    arrParams.cont_id = $('#cmb_pais').val();    
    arrParams.cap = $('#frm_pais_cap').val();
    arrParams.nac = $('#frm_pais_nac').val();
    arrParams.iso2 = $('#frm_pais_iso2').val();
    arrParams.iso3 = $('#frm_pais_iso3').val();
    arrParams.cod = $('#frm_pais_cod').val();
    arrParams.estado = $('#frm_pais_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/pais/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_paises_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function(){ 
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}
