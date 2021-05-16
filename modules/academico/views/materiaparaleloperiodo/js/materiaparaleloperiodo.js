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
    var c_vacio = 0;
    $('tbody tr').each(function () {
        var itemOrden = {};
        var tds = $(this).find("td");
        itemOrden.asig_id = tds.filter(":eq(0)").text();
//        itemOrden.nombre_materia = tds.filter(":eq(1)").text();
        itemOrden.numero_paralelo = tds.filter(":eq(2)").find("select").val();
        itemOrden.mod_id = cmb_mod_id;
        itemOrden.paca_id = cmb_pac_id;
//        itemOrden.nombre_paralelo = tds.filter(":eq(3)").text();
//        itemOrden.numero_estudiante = tds.filter(":eq(4)").text();
//        itemOrden.codigo_horario = tds.filter(":eq(5)").find("select").val();
//        itemOrden.codigo_profesor = tds.filter(":eq(6)").find("select").val();


        /*
         itemOrden.id_materia = tds.filter(":eq(2)").text();
         itemOrden.codigo_materia = tds.filter(":eq(3)").text();
         itemOrden.nombre_paralelo = tds.filter(":eq(4)").text();
         itemOrden.numero_estudiante = tds.filter(":eq(5)").text();
         itemOrden.codigo_horario = tds.filter(":eq(6)").find("select").val();
         itemOrden.codigo_profesor = tds.filter(":eq(7)").find("select").val();
         itemOrden.mppd_id = tds.filter(":eq(8)").text();
         */
        if (itemOrden.numero_paralelo != "0" || itemOrden.numero_paralelo != 0) {
            items.push(itemOrden);
        }


    });
    arrParams.data = items;
    requestHttpAjax(link, arrParams, function (response) {
        showAlert(response.status, response.label, response.message);
    }, false);
}

