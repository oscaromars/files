$(document).ready(function() {
    $("#cmb_scon").change(function(){
        var link = $('#txth_base').val() + "/academico/asignatura/new";
        var arrParams = new Object();
        arrParams.scon_id = $("#cmb_scon").val();        
        requestHttpAjax(link, arrParams, function (response) {
            if(response.status == "OK")                
                setComboData(response.message,"cmb_acon");
        }, true);
    });

    $("#frm_asi_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconAsi").attr("class", $(this).val());
        else {
            $("#iconAsi").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });

    $("#spanAsiStatus").click(function() {
        if ($("#frm_asi_status").val() == "1") {
            $("#iconAsiStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_asi_status").val("0");
        } else {
            $("#iconAsiStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_asi_status").val("1");
        }
    });
});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit() {
    var link = $('#txth_base').val() + "/academico/asignatura/edit" + "?id=" + $("#frm_asi_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/academico/asignatura/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_asi_id").val();
    arrParams.nombre = $('#frm_asi').val();
    arrParams.descripcion = $('#frm_asi_desc').val();
    arrParams.scon_id = $('#cmb_scon').val();
    arrParams.acon_id = $('#cmb_acon').val();
    arrParams.estado = $('#frm_asi_status').val();
    if (!validateForm()) {        
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
               window.location.href = $('#txth_base').val() + "/academico/asignatura/index";
           }, 3000);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/academico/asignatura/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_asi').val();
    arrParams.descripcion = $('#frm_asi_desc').val();
    arrParams.scon_id = $('#cmb_scon').val();
    arrParams.acon_id = $('#cmb_acon').val();
    arrParams.uaca_id = $('#cmb_unidad').val();
    arrParams.estado = $('#frm_asi_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
               window.location.href = $('#txth_base').val() + "/academico/asignatura/index";
           }, 3000);
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/academico/asignatura/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_asignaturas_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}