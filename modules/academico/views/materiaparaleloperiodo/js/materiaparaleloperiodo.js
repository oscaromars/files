/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $('#btn_enviar').click(function () {
        updatehorario();
    });
    $('#btn_buscarDataAsignarMateriaParalelo').click(function(){
        BuscarGrid();
    });
    $('#btn_buscarDataNewAsignarMateriaParalelo').click(function(){
        BuscarGridNew();
    });
});

function save() {
    var link = $('#txth_base').val() + "/academico/materiaparaleloperiodo/save";
    /*var cmb_mod_id = $('#materiaparaleloperiodosearch-mod_id').val();
    var cmb_pac_id = $('#materiaparaleloperiodosearch-paca_id').val();*/
    var cmb_mod_id = $('#cmb_modalidad_new').val();//14 febrero 2022
    var cmb_pac_id = $('#cmb_periodo_new').val();//14 febrero 2022
    var arrParams = new Object();
    var items = [];
    $('tbody tr').each(function () {
        var itemOrden = {};
        var tds = $(this).find("td");
        itemOrden.asig_id = tds.filter(":eq(0)").text();
//        itemOrden.nombre_materia = tds.filter(":eq(1)").text();
        itemOrden.numero_paralelo = tds.filter(":eq(2)").find("select").val();
        itemOrden.mod_id = cmb_mod_id;
        itemOrden.paca_id = cmb_pac_id;
        if (itemOrden.numero_paralelo != "0" || itemOrden.numero_paralelo != 0) {
            items.push(itemOrden);
        }

    });
    arrParams.data = items;
    requestHttpAjax(link, arrParams, function (response) {
        showAlert(response.status, response.label, response.message);
          if (response.status == "OK") {
                         setTimeout(function () {
                        window.location.href = $('#txth_base').val() + "/academico/materiaparaleloperiodo/index";
                    }, 300);

          }
    }, true);
}

function update() {
    var link = $('#txth_base').val() + "/academico/materiaparaleloperiodo/actualizar";

    var arrParams = new Object();
     arrParams.num_paralelos = $("#cmb_num_paralelo :selected").text();
     arrParams.asig_id = $("#asi_id").val()
     arrParams.mod_id = $("#mod_id").val()
     arrParams.paca_id = $("#paca_id").val()
     arrParams.mpp_num_paralelo = $("#mpp_num_paralelo").val();

   requestHttpAjax(link, arrParams, function (response) {
        showAlert(response.status, response.label, response.message);
          if (response.status == "OK") {
                         setTimeout(function () {
                        window.location.href = $('#txth_base').val() + "/academico/materiaparaleloperiodo/index";
                    }, 300);

          }
    }, true);

}

function updatehorario() {
    var link = $('#txth_base').val() + "/academico/materiaparaleloperiodo/updatehorario";

    var arrParams = new Object();
     arrParams.mpp_id = $("#txth_ids").val();
     arrParams.daho_id = $("#cmb_horarios :selected").val();
     arrParams.mod_id = $("#txth_mod").val();
     arrParams.asi_id = $("#txth_asi").val()
     arrParams.paca_id = $("#txth_paca").val();
     arrParams.mpp_num_paralelo = $("#mpp_num_paralelo").val();
     if (arrParams.daho_id == '0') {
        var mensaje = {wtmessage: "Horario : El campo no debe estar vacío.", title: "Error"};
        showAlert("NO_OK", "error", mensaje);

    } else {
     requestHttpAjax(link, arrParams, function (response) {
        showAlert(response.status, response.label, response.message);
          if (response.status == "OK") {
                        setTimeout(function () {
                        parent.window.location.href = $('#txth_base').val() + "/academico/materiaparaleloperiodo/updateschedule?mod_id="+ arrParams.mod_id + "&asi_id=" + arrParams.asi_id + "&paca_id=" + arrParams.paca_id;
                    }, 2000);

          }
    }, true);
  }
}

function BuscarGrid() {
    var periodo     = $('#cmb_periodo option:selected').val();
    var unidad      = $('#cmb_unidad option:selected').val();
    var modalidad   = $('#cmb_modalidad option:selected').val();    

    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#tbl_materias').PbGridView('applyFilterData', {'periodo': periodo, 'unidad':unidad, 'modalidad': modalidad});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function BuscarGridNew() {
    var periodo     = $('#cmb_periodo_new option:selected').val();
    var unidad      = $('#cmb_unidad_new option:selected').val();
    var modalidad   = $('#cmb_modalidad_new option:selected').val();    

    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#tbl_materias_new').PbGridView('applyFilterData', {'periodo': periodo, 'unidad':unidad, 'modalidad': modalidad});
        setTimeout(hideLoadingPopup, 2000);
    }
}