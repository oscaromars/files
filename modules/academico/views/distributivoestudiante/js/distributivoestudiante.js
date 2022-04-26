$(document).ready(function() {
    $('#btn_buscarData_dist').click(function() {
        searchModules();
    });
    $('#btn_saveData_dist').click(function() {
        save();
    });

    //alert("mensaje");
    //JLC. inicio.
    $('#chk_all').click(function() {
        
        var checked = $("#chk_all:checked").length;
        //alert(checked);
        //console.log('checked: ' + checked);

        if (checked == 1) {
            //console.log('marca ');
            $(".kv-row-checkbox").prop('checked', true);
            $('#id_txt_paralelo').css('display', 'block');
            $('#id_cmb_paralelo').css('display', 'block');
            //JLC: 19 ABRIL 2022
            //bloquear combo seleccion, y desmarcar checkbox seleccion.

            //console.log('verifica_seleccion_grid: ' + verifica_seleccion_grid());
            if (verifica_seleccion_grid()==1){
                //marcados todos los registros.
                bloquea_campos_grid(2);// no limpia y no deshabilita combo, y no deshabilita checkbox.
                document.getElementById('chk_all').checked=false;
                $('#id_cmb_paralelo').css('display', 'none');
                $('#id_txt_paralelo').css('display', 'none');
            }
            else{
                bloquea_campos_grid(1);//limpia y deshabilita combo, y deshabilita checkbox.
            }
        } else {
            //console.log('desmarca ');
            $(".kv-row-checkbox").prop('checked', false);
            $('#id_txt_paralelo').css('display', 'none');
            $('#id_cmb_paralelo').css('display', 'none');
            document.getElementById("cmb_paralelo_new").value = 0;
            //JLC: 19 ABRIL 2022
            //Desbloquear combo seleccion
            bloquea_campos_grid(0);            
        }
    });
    //JLC. fin.
    
});

function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.id = $("#txth_ids").val();
    $("#Tbg_Distributivo_Aca").PbGridView("applyFilterData", arrParams);
}

function cambiarparalelo(daca_id,est_id) { // function utilizada para el SearchboxList en evento getSource
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/cambioparalelo" + "?id="+daca_id +"&daes_id="+ est_id;
     window.location = link;
}

function getListStudent(search, response) { // function utilizada para el SearchboxList en evento getSource
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/edit" + "?id=" + $("#txth_ids").val();
    var arrParams = new Object();
    arrParams.search = search;
    arrParams.unidad = $('#txth_uids').val();
    arrParams.PBgetAutoComplete = true;
    requestHttpAjax(link, arrParams, function(rsp) {
        response(rsp);
    }, false, false, "json", "POST", null, false);
}

function showDataStudent(id, value) { // function utilizada para el SearchboxList en evento select
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/edit" + "?id=" + $("#txth_ids").val();
    var arrParams = new Object();
    arrParams.est_id = id;
    arrParams.PBgetDataEstudiante = true;
    $("#txth_esid").val(id);
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            $('#txt_buscarData').val("");
            $('#txt_nombres').val(response.data.nombres);
            $('#txt_apellidos').val(response.data.apellidos);
            $('#txt_carrera').val(response.data.carrera);
            $('#txt_matricula').val(response.data.matricula);
        }
    }, true);
}

function edit() {
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/edit" + "?id=" + $("#txth_ids").val();
    window.location = link;
}

function savechangeparalelo() {
    var link      = $('#txth_base').val() + "/academico/distributivoestudiante/savechangeparalelo"; 
    var arrParams = new Object();
    arrParams.daes_id = $("#txth_daes_id").val();
    arrParams.daca_id = $("#cmb_paralelo").val();
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            //searchModules();
          //  clearDataSearch();
          showAlert(response.status, response.label, response.message);
          
           setTimeout(function() {
                var link = $('#txth_base').val() + "/academico/distributivoacademico/index";
                window.location = link;
            }, 1000);
        } else {
            showAlert(response.status, response.label, response.message);
        }
    }, true);
}//function savechangeparalelo

function save() {
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/save";
    var keys = $('#grid').yiiGridView('getSelectedRows');
    // alert(keys);
    var arrParams = new Object();
    //arrParams.daca_id = $("#txth_ids").val();
    //arrParams.est_id = keys;

    //JLC: 19 ABRIL 2022. inicio.
    var checked = $("#chk_all:checked").length;
    console.log('checked: ' + checked);
    if (checked == 1) {
        if ( $("#cmb_paralelo_new option:selected").val() != 0){
            var items = [];
            //Recorre todos los items
            $('tbody tr').each(function() {
                var itemOrden ={};
                var tds = $(this).find("td");
                itemOrden.daes_id = tds.filter(":eq(4)").text();
                itemOrden.est_id  = tds.filter(":eq(7)").text();
                items.push(itemOrden);                
            });
            arrParams.lista_daes_id = items;
            arrParams.paralelo = $("#cmb_paralelo_new option:selected").val();

        }else{
            showAlert('NO_OK', 'error', {"wtmessage": 'Para guardar el Distributivo estudiante debe seleccionar un paralelo.', "title": 'Información'});
            return;
        }
    }else{
        var items = [];
        //Recorre por item seleccionado
        $('#grid input.byregister[type=checkbox]').each(function() {
            if (this.checked) {
                var itemOrden ={};
                itemOrden.daes_id = $(this).parent().parent().find('td').eq(4).text();
                itemOrden.daca_id = $(this).parent().parent().find('td').eq(5).find("select").val();
                itemOrden.est_id = $(this).parent().parent().find('td').eq(7).text();
                console.log('itemOrden.daes_id: '+ itemOrden.daes_id + ' itemOrden.daca_id: '+ itemOrden.daca_id);
                items.push(itemOrden);
            }
        });
        arrParams.lista_daes_id = items;
        arrParams.paralelo = 0;
    }
    //JLC: 19 ABRIL 2022. fin.

    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);

        if(response.status == 'OK'){
            setTimeout(function () {
                 var link = $('#txth_base').val() + "/academico/distributivoacademico/index";
                 window.location = link;
            }, 2000);
        }
    }, true);
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            searchModules();
            setTimeout(function() {
                showAlert(response.status, response.label, response.message);
            }, 1000);
        }
    }, true);
}

function clearDataSearch() {
    $('#txt_buscarData').val("");
    $('#txt_nombres').val("");
    $('#txt_apellidos').val("");
    $('#txt_carrera').val("");
    $('#txt_matricula').val("");
    $("#txth_esid").val('');
}

function exportExcel() {
    var search = $('#txt_buscarData').val();
    var id = $('#txth_ids').val();
    window.location.href = $('#txth_base').val() + "/academico/distributivoestudiante/exportexcel?" +
        "id=" + id +
        "&search=" + search;
}

function exportPdf() {
    var search = $('#txt_buscarData').val();
    var id = $('#txth_ids').val();
    window.location.href = $('#txth_base').val() + "/academico/distributivoestudiante/exportpdf?pdf=1" +
        "&id=" + id +
        "&search=" + search;
}

//JLC: 19 ABRIL 2022
//Check individual y combo paralelo.
function bloquea_campos_grid(accion){
    var paralelo = $("#cmb_paralelo_new option:selected").val();
    //if ( paralelo != 0 ){
        var daes_id = 0;
        var campo_check = null;
        var campo_paralelo = null;
        if (accion == 1){
            $('#grid input.byregister[type=checkbox]').each(function() {
                daes_id = $(this).parent().parent().find('td').eq(4).text();
                daca_id = $(this).parent().parent().find('td').eq(6).text();
                est_id  = $(this).parent().parent().find('td').eq(7).text();
                console.log('****************************************************************************');
                console.log('********** daes_id : ' +daes_id);
                console.log('********** daca_id  : ' +daca_id);
                console.log('********** est_id  : ' +est_id);
                if (daes_id !=  ""){
                    console.log('******************************** daes_id LLENO: ' +daes_id);
                    campo_check = "cmb_check_estudiante_" + daes_id;
                    campo_paralelo = "cmb_paralelo_" + daes_id;
                }else if (daes_id == ""){
                    console.log('******************************** daes_id VACIO: ' +daes_id);
                    campo_check = "cmb_check_estudiante_" + est_id;
                    campo_paralelo = "cmb_paralelo_" + est_id;
                }
                console.log('******** campo_check: ' +campo_check);
                console.log('******** campo_paralelo: ' +campo_paralelo);

                //if (campo_check == ""){
                if (daes_id =="" && daca_id =="" && est_id==""){
                    document.getElementById(campo_check).disabled = true;
                    document.getElementById(campo_check).checked = false;
                    document.getElementById(campo_paralelo).disabled = true;
                    document.getElementById(campo_paralelo).value = 0;
                }else if (daes_id =="" && daca_id =="" && est_id!=""){
                    document.getElementById(campo_check).disabled = true;
                    document.getElementById(campo_check).checked = true;
                    document.getElementById(campo_paralelo).disabled = true;
                    document.getElementById(campo_paralelo).value = 0;
                }
                console.log('****************************************************************************');
                
            });
        }else if (accion == 0){
            $('#grid input.byregister[type=checkbox]').each(function() {
                daes_id   = $(this).parent().parent().find('td').eq(4).text()
                daca_id = $(this).parent().parent().find('td').eq(6).text();
                est_id = $(this).parent().parent().find('td').eq(7).text();
                if (daes_id !=  ""){
                    console.log('daes_id lleno: ' +daes_id);
                    campo_check = "cmb_check_estudiante_" + daes_id;
                    campo_paralelo = "cmb_paralelo_" + daes_id;
                }else if (daes_id == ""){
                    console.log('daes_id vacio: ' +daes_id);
                    campo_check = "cmb_check_estudiante_" + est_id;
                    campo_paralelo = "cmb_paralelo_" + est_id;
                }

                //if (campo_check == ""){
                if (daes_id =="" && daca_id =="" && est_id==""){
                    console.log('Tiene asigancion en DAES');
                    document.getElementById(campo_check).disabled = false;
                    document.getElementById(campo_check).checked = false;
                    document.getElementById(campo_paralelo).disabled = false;
                }else if (daes_id =="" && daca_id =="" && est_id !=""){
                    console.log('No Tiene asigancion en DAES');
                    document.getElementById(campo_check).disabled = false;
                    document.getElementById(campo_check).checked = false;
                    document.getElementById(campo_paralelo).disabled = false;
                }
            });
        }
    //}//fin if
}

function verifica_seleccion_grid(){
    var cant_reg=0;
    var cant_reg_seleccionados=0;
     $('#grid input.byregister[type=checkbox]').each(function() {
        cant_reg++;
        //console.log('cantidad registros: ' +cant_reg);
    });

    $('#grid input.byregister[type=checkbox]').each(function() {
        if (this.checked) {
           cant_reg_seleccionados++;
           //console.log('cantidad registros seleccinados: ' +cant_reg_seleccionados);
        }
    });

    //console.log('cantidad registros: ' +cant_reg);
    //console.log('cantidad registros seleccinados: ' +cant_reg_seleccionados);
    if ( cant_reg == cant_reg_seleccionados ){
        return 1;
    }else{
        return 0;
    }

}

//JLC: 19 ABRIL 2022