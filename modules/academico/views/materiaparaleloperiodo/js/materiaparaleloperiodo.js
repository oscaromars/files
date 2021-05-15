/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

});

function save() {
    var link = $('#txth_base').val() + "/academico/materiaparaleloperiodo/save";
    var cmb_mod_id = $('#materiaparaleloperiodosearch-mod_id').val();
    var cmb_pac_id = $('#materiaparaleloperiodosearch-paca_id').val();
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
     arrParams.mpp_num_paralelo = $("#mpp_num_paralelo").val()
   
   requestHttpAjax(link, arrParams, function (response) {
        showAlert(response.status, response.label, response.message);
          if (response.status == "OK") {
                         setTimeout(function () {
                        window.location.href = $('#txth_base').val() + "/academico/materiaparaleloperiodo/index";
                    }, 300);
                
          }
    }, true);
   
}