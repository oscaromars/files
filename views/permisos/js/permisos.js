
$(document).ready(function () {
    $("#cmb_grupo").change(function(){
        var link = $('#txth_base').val() + "/permisos/new";
        var arrParams = new Object();
        arrParams.gru_id = $("#cmb_grupo").val();
        requestHttpAjax(link, arrParams, function (response) {
            if(response.status == "OK")
                setComboData(response.message,"cmb_rol");
        }, true);
    });

    //$("#cmb_objmods").select2({});
    /*
    $("#cmb_objmods").selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });  
    */  

    //import SlimSelect from 'slim-select'

    new SlimSelect({
      select: '#cmb_objmods',
      closeOnSelect: false,
      searchFocus: false,
      //hideSelectedOption: true
    })
});
function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit(){
    var link = $('#txth_base').val() + "/permisos/edit" + "?id=" + $("#frm_grol_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/permisos/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_grol_id").val();
    arrParams.grupo = $('#cmb_grupo').val();
    arrParams.rol = $('#cmb_rol').val();
    arrParams.objmod = $('#cmb_objmods').val();
    //arrParams.estado = $('#frm_obmo_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/permisos/save";
    var arrParams = new Object();
    arrParams.grupo = $('#cmb_grupo').val();
    arrParams.rol = $('#cmb_rol').val();
    arrParams.objmod = $('#cmb_objmods').val();
    //arrParams.estado = $('#frm_obmo_status').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/permisos/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_omod_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function(){ 
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}
