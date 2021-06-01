/* 
 * The PenBlu framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by PenBlu Software (http://www.penblu.com)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions 
 * are met:
 *
 *  - Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *  - Neither the name of PenBlu Software nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PenBlu is based on code by 
 * Yii Software LLC (http://www.yiisoft.com) Copyright © 2008
 *
 * Authors:
 * 
 * Eduardo Cueva <ecueva@penblu.com>
 * Byron Villacreses <developer@uteg.edu.ec>
 */

function retornarIndexArray(array, property, value) {
    var index = -1;
    for (var i = 0; i < array.length; i++) {
        //alert(array[i][property]+'-'+value)
        if (array[i][property] == value) {
            index = i;
            return index;
        }
    }
    return index;
}
function codigoExiste(value, property, lista) {
    if (lista) {
        var array = JSON.parse(lista);
        for (var i = 0; i < array.length; i++) {
            if (array[i][property] == value) {
                return false;
            }
        }
    }
    return true;
}

function findAndRemove(array, property, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            array.splice(i, 1);
        }
    }
    return array;
}

$(document).ready(function(){
    InicioFormulario();//Inicia Datos de Formulario
//    $('#view_pass_btn').on('mousedown', function() {
//        $('#frm_clave').attr("type","text");
//    }).on('mouseup mouseleave', function() {
//        $('#frm_clave').attr("type","password");
//    });
    $('#view_pass_btn').click(function(){
        if($('#frm_clave').attr("type") == "text"){
            $('#frm_clave').attr("type","password");
            $('#view_pass_btn > i').attr("class", "glyphicon glyphicon-eye-open");
        }else{
            $('#frm_clave').attr("type","text");
            $('#view_pass_btn > i').attr("class", "glyphicon glyphicon-eye-close");
        }
    });
    $("#generate_btn").click(function(){
        var newpass = generatePassword();
        $('#frm_clave').val(newpass);
        $('#frm_nueva_clave_repeat').val(newpass);
    });
    
    $('#btn_saveCreate').click(function () {
        guardarDatos('Create');
    });
    $('#btn_saveUpdate').click(function () {
        guardarDatos('Update');
    });
    
    /*DATOS DE TABLA ADD EMPRESA*/
    $('#btn_addEmpresa').click(function () {
        agregarEmpresaRolGrup('new');
    });
    
});

function InicioFormulario() {
    if (AccionTipo == "Update") {
        loadDataUpdate();
    } else if (AccionTipo == "Create") {
        loadDataCreate();
    }
}

function searchUsers(idbox, idgrid){
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#"+idbox).val();
    $("#"+idgrid).PbGridView("applyFilterData", arrParams);
}

function save(){
    var link = $('#txth_base').val() + "/usuario/save";
    var arrParams = new Object();
    arrParams.nombres     = $('#frm_nombres').val();
    arrParams.apellidos   = $('#frm_apellidos').val();
    arrParams.genero      = $('#cmb_genero').val();
    arrParams.fnacimiento = $('#frm_nacimiento').val();
    arrParams.email       = $('#frm_email').val();
    arrParams.celular     = $('#frm_celular').val();
    arrParams.user        = $('#frm_user').val();
    arrParams.pass        = $('#frm_clave').val();
    arrParams.passconfirm = $('#frm_nueva_clave_repeat').val();
    if (arrParams.pass != arrParams.passconfirm) {
        showAlert("NOOK", "Error", {"wtmessage": objLang.Password_are_differents__Please_enter_passwords_again_, "title": objLang.Success='Éxito'});
    } else {
        if(!validateForm()){
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
            }, true);
        }
    }
}

function updateUser(){
    
}

function addCompany(){
    var empresa_id = $("#cmb_empresas").val();
    var empresa_name = $("#cmb_empresas :selected").text();
    var grupo_id = $("#cmb_grupos").val();
    var grupo_name = $("#cmb_grupos :selected").text();
    var rol_id = $("#cmb_roles").val();
    var rol_name = $("#cmb_roles :selected").text();
    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = empresa_id;
    tb_item[2] = grupo_id;
    tb_item[3] = rol_id;
    tb_item2[0] = 0;
    tb_item2[1] = empresa_name;
    tb_item2[2] = grupo_name;
    tb_item2[3] = rol_name;
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = {id: "deleteN", href: "", onclick:"", title: parent.objLang.Delete, class: "", tipo_accion: "delete"};
    var arrData =  JSON.parse(sessionStorage.grid_empresas);
    if(arrData.data){
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    }else{
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if(arrData.label){
        var item2 = arrData.label;
        tb_item2[0] = item2.length + 1;
        item2.push(tb_item2);
        arrData.label = item2;
    }else{
        var item2 = new Array();
        tb_item2[0] = 1;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if(arrData.btnactions){
        var item3 = arrData.btnactions;
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }else{
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_empresas = JSON.stringify(arrData);
    if(empresa_id != 0 && grupo_id != 0 && rol_id !=0){
        parent.addItemGridContent("grid_empresas");
        parent.closePopupIframe();
    }
}


function guardarDatos(accion) {
    var usuID = (accion == "Update") ? $('#txth_usu_id').val() : 0;
    var link = $('#txth_base').val() + "/usuario/save";
    var arrParams = new Object();
    arrParams.DATA = dataPersona(usuID,accion);
    arrParams.ACCION = accion;    
    var validation = validateForm();
    //if (!validation) {
        requestHttpAjax(link, arrParams, function (response) {
            var message = response.message;
            if (response.status == "OK") {
                showAlert(response.status, response.type, {"wtmessage": message.info, "title": response.label});
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/usuario/index";
                }, 3000);
            } else {
                showAlert(response.status, response.type, {"wtmessage": message.info, "title": response.label});
            }
        }, true);
    //}
}

function dataPersona(usuID,accion) {
    var datArray = new Array();
    var objDat = new Object();
    objDat.usu_id = usuID;//Genero Automatico
    objDat.per_id = (accion == "Update") ? $('#txth_per_id').val() : 0; 
    objDat.grol_id = (accion == "Update") ? $('#txth_grol_id').val() : 0;
    objDat.usu_user = $('#txt_username').val();
    objDat.per_pri_nombre = $('#txt_nombres').val();
    objDat.per_pri_apellido = $('#txt_apellido').val();
    objDat.per_cedula = $('#txt_cedula').val();
    objDat.per_fecha_nacimiento = $('#dtp_per_fecha_nacimiento').val();
    objDat.per_celular= $('#txt_celular').val();
    objDat.per_correo= $('#txt_email').val();
    objDat.usu_clave= $('#frm_clave').val();
    objDat.per_genero = $('#cmb_genero option:selected').val();
    objDat.emp_id = $('#cmb_empresa option:selected').val();
    objDat.gru_id = $('#cmb_grupo option:selected').val();
    objDat.rol_id = $('#cmb_rol option:selected').val();
    objDat.data_Empresa = (sessionStorage.dts_Empresas) ? sessionStorage.dts_Empresas : new Array();
    
    datArray[0] = objDat;
    sessionStorage.dataPersona = JSON.stringify(datArray);
    return datArray;
}

function deleteItem(id){
    var link = $('#txth_base').val() + "/usuario/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function (response) {
        showAlert(response.status, response.label, response.message);
        if(response.status == "OK"){
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_user_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
    }, true);
}



/* INFORMACION MULTIPLES EMPRESAS*/
function agregarEmpresaRolGrup(opAccion) {
    var tGrid = 'TbG_Empresas';
        if (opAccion != "edit") {
            //*********   AGREGAR ITEMS *********
            var arr_Grid = new Array();
            if (sessionStorage.dts_Empresas) {
                /*Agrego a la Sesion*/
                arr_Grid = JSON.parse(sessionStorage.dts_Empresas);
                var size = arr_Grid.length;
                if (size > 0) {
                    //Varios Items
                    //if (codigoExiste(nombre, 'pro_nombre', sessionStorage.dts_Empresas)) {//Verifico si el Codigo Existe  para no Dejar ingresar Repetidos
                        arr_Grid[size] = objEmpresa(size); 
                        sessionStorage.dts_Empresas = JSON.stringify(arr_Grid);
                        addVariosItemEmpresa(tGrid, arr_Grid, -1);
                        //limpiarDetalle();
                    //} else {
                      //  menssajeModal("OK", "error", "Item ya existe en su lista", "Información", "", "", "1");
                    //}
                } else {
                    /*Agrego a la Sesion*/
                    //Primer Items                    
                    arr_Grid[0] = objEmpresa(0);
                    sessionStorage.dts_Empresas = JSON.stringify(arr_Grid);
                    addPrimerItemEmpresa(tGrid, arr_Grid, 0);
                    limpiarDetalle();
                }
            } else {
                //No existe la Session
                //Primer Items                
                arr_Grid[0] = objEmpresa(0);
                sessionStorage.dts_Empresas = JSON.stringify(arr_Grid);
                addPrimerItemEmpresa(tGrid, arr_Grid, 0);
                limpiarDetalle();
            }
        } else {
            //data edicion
        }
}

function objEmpresa(indice) {
    var rowGrid = new Object();
    rowGrid.indice = indice;
    rowGrid.eper_id = 0;
    rowGrid.emp_id = $('#cmb_empresa option:selected').val();
    rowGrid.empresa= $('#cmb_empresa option:selected').text();
    rowGrid.gru_id = $('#cmb_grupo option:selected').val();
    rowGrid.grupo= $('#cmb_grupo option:selected').text();
    rowGrid.rol_id = $('#cmb_rol option:selected').val();
    rowGrid.rol= $('#cmb_rol option:selected').text();
    rowGrid.accion = "new";
    return rowGrid;
}

function addPrimerItemEmpresa(TbGtable, lista, i) {
    /*Remuevo la Primera fila*/
    $('#' + TbGtable + ' >table >tbody').html("");
    /*Agrego a la Tabla de Detalle*/
    $('#' + TbGtable + ' tr:last').after(retornaFilaEmpresa(i, lista, TbGtable, true));
}

function addVariosItemEmpresa(TbGtable, lista, i) {
    //i=(i==-1)?($('#'+TbGtable+' tr').length)-1:i;
    i = ($('#' + TbGtable + ' tr').length) - 1;
    $('#' + TbGtable + ' tr:last').after(retornaFilaEmpresa(i, lista, TbGtable, true));
}

function retornaFilaEmpresa(c, Grid, TbGtable, op) {
    var strFila = "";
    strFila += '<td style="display:none; border:none;">' + Grid[c]['indice'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['eper_id'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['emp_id'] + '</td>';
    strFila += '<td>' + Grid[c]['empresa'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['gru_id'] + '</td>';
    strFila += '<td>' + Grid[c]['grupo'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['rol_id'] + '</td>';
    strFila += '<td>' + Grid[c]['rol'] + '</td>';
  
    strFila += '<td>';
    //Cuando hay Actualizacion de Datos   
    strFila += '</td>';
    //strFila +='<td>'+ Grid[c]['xxxxx']+'</td>';
    strFila += '<td>';//¿Está seguro de eliminar este elemento?   
    strFila += '<a onclick="eliminarItemsEmpresa(\'' + Grid[c]['indice'] + '\',\'' + TbGtable + '\')" ><span class="glyphicon glyphicon-trash"></span></a>';
    strFila += '</td>';

    if (op) {
        strFila = '<tr>' + strFila + '</tr>';
    }
    return strFila;
}

// Recarga la Grid de Productos si Existe
function recargarGridEmpresa() {
    var tGrid = 'TbG_Empresas';
    if (sessionStorage.dts_Empresas) {
        var arr_Grid = JSON.parse(sessionStorage.dts_Empresas);
        if (arr_Grid.length > 0) {
            $('#' + tGrid + ' > tbody').html("");
            for (var i = 0; i < arr_Grid.length; i++) {
                $('#' + tGrid + ' > tbody:last-child').append(retornaFilaEmpresa(i, arr_Grid, tGrid, true));
            }
        }
    }
}

function eliminarItemsEmpresa(val, TbGtable) {
    var ids = "";
    //var count=0;
    if (sessionStorage.dts_Empresas) {
        var Grid = JSON.parse(sessionStorage.dts_Empresas);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function () {
                ids = $(this).find("td").eq(0).html();
                if (ids == val) {
                    var array = findAndRemove(Grid, 'indice', ids);
                    sessionStorage.dts_Empresas = JSON.stringify(array);
                    //if (count==0){sessionStorage.removeItem('detalleGrid')} 
                    $(this).remove();
                }
            });
        }
    }
}

/* FIN INFORMACION DE OTROS USOS OPCION 2*/
function loadDataUpdate() { 
    mostrarGridGRE(varEgrData);
}

function loadDataCreate() {
    recargarGridEmpresa()
}

function mostrarGridGRE(Grid){ 
    var tGrid='TbG_Empresas';  
    var datArray = new Array();
    if(Grid.length > 0){        
        $('#' + tGrid + ' > tbody').html("");
        for(var i=0; i<Grid.length; i++){
            datArray[i]=objEGRUpdate(i,Grid)
            $('#' + tGrid + ' > tbody:last-child').append(retornaFilaEmpresa(i, datArray, tGrid, true));
        }
        sessionStorage.dts_Empresas = JSON.stringify(datArray);
    }
}

function objEGRUpdate(i,Grid) {
    var rowGrid = new Object(); 
    rowGrid.indice = i;
    rowGrid.eper_id = Grid[i]['eper_id'];//
    rowGrid.emp_id = Grid[i]['emp_id'];
    rowGrid.empresa= Grid[i]['emp_razon_social'];
    rowGrid.gru_id = Grid[i]['gru_id'];
    rowGrid.grupo= Grid[i]['gru_nombre'];
    rowGrid.rol_id = Grid[i]['rol_id'];
    rowGrid.rol= Grid[i]['rol_nombre'];
    rowGrid.accion = "edit";
    return rowGrid;
}


