/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    recargarGridItem();

    $('#btn_AgregarItem').click(function () {
        agregarItems('new')
        //guardarItem();
        //var dataItems = obtDataList();
        //representarItems(dataItems);
    });

    $('#cmb_modelo').change(function () {
        var link = $('#txth_base').val() + "/repositorio/repositorio/index";
        var arrParams = new Object();
        arrParams.mod_id = $('#cmb_modelo').val();
        arrParams.get_funciones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.funciones, "cmb_categoria", "Todos");
                var arrParams = new Object();
                arrParams.fun_id = $('#cmb_categoria').val();
                arrParams.get_componentes = true;
                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboDataselect(data.componentes, "cmb_componente", "Todos");
                        var arrParams = new Object();
                        arrParams.comp_id = $('#cmb_componente').val();
                        arrParams.fun_id = $('#cmb_categoria').val();
                        arrParams.get_estandares = true;
                        requestHttpAjax(link, arrParams, function (response) {
                            if (response.status == "OK") {
                                data = response.message;
                                setComboDataselect(data.estandares, "cmb_estandar", "Todos");
                            }
                        }, true);
                    }
                }, true);
            }
        }, true);
    });

    $('#cmb_categoria').change(function () {
        var link = $('#txth_base').val() + "/repositorio/repositorio/index";
        var arrParams = new Object();
        arrParams.fun_id = $('#cmb_categoria').val();
        arrParams.get_componentes = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.componentes, "cmb_componente", "Todos");
                var arrParams = new Object();
                arrParams.comp_id = $('#cmb_componente').val();
                arrParams.fun_id = $('#cmb_categoria').val();
                arrParams.get_estandares = true;
                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboDataselect(data.estandares, "cmb_estandar", "Todos");
                    }
                }, true);
            }
        }, true);
    });

    $('#cmb_componente').change(function () {
        var link = $('#txth_base').val() + "/repositorio/repositorio/index";
        var arrParams = new Object();
        arrParams.comp_id = $('#cmb_componente').val();
        arrParams.fun_id = $('#cmb_categoria').val();
        arrParams.get_estandares = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.estandares, "cmb_estandar", "Todos");
            }
        }, true);
    });


    $('#cmb_modelo_evi').change(function () {
        var link = $('#txth_base').val() + "/repositorio/repositorio/index";
        var arrParams = new Object();
        arrParams.mod_id = $('#cmb_modelo_evi').val();
        arrParams.get_funciones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.funciones, "cmb_funcion_evi","Seleccionar");
                var arrParams = new Object();
                arrParams.fun_id = $('#cmb_funcion_evi').val();
                arrParams.get_componentes = true;
                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboDataselect(data.componentes, "cmb_componente_evi","Seleccionar");
                        var arrParams = new Object();                        
                        arrParams.comp_id = $('#cmb_componente_evi').val();
                        arrParams.fun_id = $('#cmb_funcion_evi').val();
                        arrParams.get_estandares = true;
                        requestHttpAjax(link, arrParams, function (response) {
                            if (response.status == "OK") {
                                data = response.message;
                                setComboDataselect(data.estandares, "cmb_estandar_evi", "Seleccionar");
                            }
                        }, true);
                    }
                }, true);
            }
        }, true);
    });

    $('#cmb_funcion_evi').change(function () {
        var link = $('#txth_base').val() + "/repositorio/repositorio/index";
        var arrParams = new Object();
        arrParams.fun_id = $('#cmb_funcion_evi').val();
        arrParams.get_componentes = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.componentes, "cmb_componente_evi","Seleccionar");
                var arrParams = new Object();
                if (data.componentes.length > 0) {
                            $('#Divcomponente').show();
                        }
                        else{
                            $('#Divcomponente').hide();
                        }
                arrParams.comp_id = $('#cmb_componente_evi').val();
                arrParams.fun_id = $('#cmb_funcion_evi').val();
                arrParams.get_estandares = true;
                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboDataselect(data.estandares, "cmb_estandar_evi","Seleccionar");
                    }
                }, true);
            }
        }, true);
    });

    $('#cmb_componente_evi').change(function () {
        var link = $('#txth_base').val() + "/repositorio/repositorio/index";
        var arrParams = new Object();
        arrParams.comp_id = $('#cmb_componente_evi').val();
        arrParams.fun_id = $('#cmb_funcion_evi').val();
        arrParams.get_estandares = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.estandares, "cmb_estandar_evi","Seleccionar");
            }
        }, true);
    });

    $('#btn_buscarData').click(function () {
        actualizarGrid();
    });
});

function guardarItem() {
    var funcion_id = $('#cmb_funcion_evi').val();
    var componente_id = $('#cmb_componente_evi').val();
    var estandar_id = $('#cmb_estandar_evi option:selected').html();
    var tipo_id = $('#cmb_tipo').val();
    var nombre_imagen = $('#txth_docarchivo').val();
    var fecha_archivo = $('#txt_fecha_documento').val();
    var descripcion = $('#txt_descripcion').val();


    var datalist = obtDataList();
    var dataitem = {
        funcion: funcion_id,
        componente: componente_id,
        estandar: estandar_id,
        tipo: tipo_id,
        imagen: nombre_imagen,
        fecha: fecha_archivo,
        descripcion: descripcion
    }
    //if (!existeitem(item_id)) {
    //alert('Agrega al storage');
    datalist.push(dataitem);
    sessionStorage.setItem('datosItem', JSON.stringify(datalist));
    /*} else {
     var mensaje = {wtmessage: "El item ya se encuentra ingresado.", title: "Exito"};
     showAlert("OK", "success", mensaje);
     }*/
}

function obtDataList() {
    var storedListItems = sessionStorage.getItem('datosItem');
    if (storedListItems === null) {
        itemList = [];
    } else {
        itemList = JSON.parse(storedListItems);
    }
    return itemList;
}

function representarItems(dataItems) {
    $("#dataListItem").html("");
    var html = " <div class='grid-view'>" +
            "<table class='table table-striped table-bordered dataTable'>" +
            "<tbody>" +
            "  <tr><th>Función</th> <th>Componente</th><th>Estándar</th><th>Imagen</th><th>Tipo</th> <th>Documento</th> <th>Fecha</th></tr>";
    total = 0;
    for (i = 0; i < dataItems.length; i++) {
        html += "<tr><td>" + dataItems[i]['funcion'] + "</td> <td>" + dataItems[i]['componente'] + "</td> <td>" + dataItems[i]['estandar'] + "</td> <td>" + dataItems[i]['imagen'] + "</td> <td>" + dataItems[i]['tipo'] + "</td> <td>" + dataItems[i]['documento'] + "</td> <td>" + dataItems[i]['fecha'] + "</td><td><button type='button' class='btn btn-link' onclick='eliminaritem(" + dataItems[i]['item_id'] + ")'> <span class='glyphicon glyphicon-remove'></span> </button></td></tr>";
        //total = total + parseInt(dataItems[i]['precio'], 10);
    }
    html += "<tr height='40'><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
    html += "</tbody>";
    html += "    </table>" + "</div>";
    $("#dataListItem").html(html);
}

function actualizarGrid() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var modelo = $('#cmb_modelo').val();
    var categoria = $('#cmb_categoria').val();
    var componente = $('#cmb_componente').val();
    var estandar = $('#cmb_estandar').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Tbg_Listar').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'search': search, 'mod_id': modelo, 'cat_id': categoria, 'comp_id': componente, 'est_id': estandar});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function setComboDataselect(arr_data, element_id, texto) {
    var option_arr = "";
    option_arr += "<option value= '0'>" + texto + "</option>";
    for (var i = 0; i < arr_data.length; i++) {
        var id = arr_data[i].id;
        var value = arr_data[i].name;

        option_arr += "<option value='" + id + "'>" + value + "</option>";
    }
    $("#" + element_id).html(option_arr);
}


/* AGREGAR OPCIONES A GRID */
function agregarItems(opAccion) {
    var tGrid = 'TbG_Data';
    var nombre = $('#cmb_estandar_evi option:selected').text();
    //Verifica que tenga nombre producto y tenga foto
    if ($('#cmb_modelo_evi').val() != 0 && $('#cmb_funcion_evi').val() != 0 && $('#txth_docarchivo').val() != ""
            && $('#cmb_estandar_evi').val() != 0 && $('#cmb_tipo_evi').val() != 0 && $('#txt_fecha_documento_evi').val() != "") {
        var valor = $('#cmb_estandar_evi option:selected').text();
        if (opAccion != "edit") {
            //*********   AGREGAR ITEMS *********
            var arr_Grid = new Array();
            if (sessionStorage.dts_datosItem) {
                /*Agrego a la Sesion*/
                arr_Grid = JSON.parse(sessionStorage.dts_datosItem);
                var size = arr_Grid.length;
                if (size > 0) {
                    //Varios Items
                    //if (codigoExiste(nombre, 'estandar_evi', sessionStorage.dts_datosItem)) {//Verifico si el Codigo Existe  para no Dejar ingresar Repetidos
                        arr_Grid[size] = objProducto(size);
                        sessionStorage.dts_datosItem = JSON.stringify(arr_Grid);
                        addVariosItem(tGrid, arr_Grid, -1);
                        limpiarDetalle();
                    //} else {
                    //    showAlert('NO_OK', 'error', {"wtmessage": "Item ya existe en su lista", "title": 'Información'});
                    //}
                } else {
                    /*Agrego a la Sesion*/
                    //Primer Items                   
                    arr_Grid[0] = objProducto(0);
                    sessionStorage.dts_datosItem = JSON.stringify(arr_Grid);
                    addPrimerItem(tGrid, arr_Grid, 0);
                    limpiarDetalle();
                }
            } else {
                //No existe la Session
                //Primer Items
                arr_Grid[0] = objProducto(0);
                sessionStorage.dts_datosItem = JSON.stringify(arr_Grid);
                addPrimerItem(tGrid, arr_Grid, 0);
                limpiarDetalle();
            }
        } else {
            //data edicion
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": "No Existe datos Y/o Imagen", "title": 'Información'});
    }
}


function objProducto(indice) {
    var rowGrid = new Object();
    rowGrid.indice = indice;
    rowGrid.dre_id = 0;
    rowGrid.componente_evi = "";
            
    rowGrid.modelo_evi = $('#cmb_modelo_evi option:selected').text();
    rowGrid.funcion_evi = $('#cmb_funcion_evi option:selected').text();
    if($('#cmb_componente_evi option:selected').text() != "Seleccionar"){
        rowGrid.componente_evi = $('#cmb_componente_evi option:selected').text();
    }    

    rowGrid.est_id = $('#cmb_estandar_evi').val();
    rowGrid.estandar_evi = $('#cmb_estandar_evi option:selected').text();
    rowGrid.dre_tipo = $('#cmb_tipo_evi').val();
    rowGrid.tipo_evi = $('#cmb_tipo_evi option:selected').text();

    rowGrid.dre_codificacion = '';
    rowGrid.dre_ruta = $('#txth_doc_archivo_ruta').val();
    rowGrid.dre_imagen = $('#txth_doc_archivo').val();//$('#txt_doc_archivo').val();
    rowGrid.dre_descripcion = $('#txt_descripcion').val();
    rowGrid.dre_fecha_archivo = $('#txt_fecha_documento_evi').val();
    rowGrid.dre_estado = 1;
    rowGrid.dre_estado_logico = 1;
    //rowGrid.pro_otros = ($("#chk_otros").prop("checked")) ? 1 : 0;
    rowGrid.accion = "new";
    return rowGrid;
}

function addPrimerItem(TbGtable, lista, i) {
    /*Remuevo la Primera fila*/
    $('#' + TbGtable + ' >table >tbody').html("");
    /*Agrego a la Tabla de Detalle*/
    $('#' + TbGtable + ' tr:last').after(retornaFila(i, lista, TbGtable, true));
}

function addVariosItem(TbGtable, lista, i) {
    //i=(i==-1)?($('#'+TbGtable+' tr').length)-1:i;
    i = ($('#' + TbGtable + ' tr').length) - 1;
    //$('#'+TbGtable+' >table >tbody').append(retornaFilaProducto(i,lista,TbGtable,true));
    $('#' + TbGtable + ' tr:last').after(retornaFila(i, lista, TbGtable, true));
}

function retornaFila(c, Grid, TbGtable, op) {
    //var RutaImagenAccion='ruta IMG'//$('#txth_rutaImg').val();
    var strFila = "";
    strFila += '<td style="display:none; border:none;">' + Grid[c]['indice'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['dre_id'] + '</td>';
    strFila += '<td>' + Grid[c]['modelo_evi'] + '</td>';
    strFila += '<td>' + Grid[c]['funcion_evi'] + '</td>';
    strFila += '<td>' + Grid[c]['componente_evi'] + '</td>';

    strFila += '<td style="display:none; border:none;">' + Grid[c]['est_id'] + '</td>';
    strFila += '<td>' + Grid[c]['estandar_evi'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['dre_tipo'] + '</td>';
    strFila += '<td>' + Grid[c]['tipo_evi'] + '</td>';

    strFila += '<td>' + Grid[c]['dre_imagen'] + '</td>';
    strFila += '<td>' + Grid[c]['dre_fecha_archivo'] + '</td>';
    strFila += '<td>';//¿Está seguro de eliminar este elemento?   
    strFila += '<a onclick="eliminarItems(\'' + Grid[c]['indice'] + '\',\'' + TbGtable + '\')" ><span class="glyphicon glyphicon-trash"></span></a>';
    //<span class='glyphicon glyphicon-remove'></span>
    strFila += '</td>';

    if (op) {
        strFila = '<tr>' + strFila + '</tr>';
    }
    return strFila;
}

function eliminarItems(val, TbGtable) {
    var ids = "";
    //var count=0;
    if (sessionStorage.dts_datosItem) {
        var Grid = JSON.parse(sessionStorage.dts_datosItem);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function () {
                ids = $(this).find("td").eq(0).html();
                if (ids == val) {
                    var array = findAndRemove(Grid, 'indice', ids);
                    sessionStorage.dts_datosItem = JSON.stringify(array);
                    //if (count==0){sessionStorage.removeItem('detalleGrid')} 
                    $(this).remove();
                }
            });
        }
    }
}

// Recarga la Grid de Productos si Existe
function recargarGridItem() {
    var tGrid = 'TbG_Data';
    if (sessionStorage.dts_datosItem) {
        var arr_Grid = JSON.parse(sessionStorage.dts_datosItem);
        if (arr_Grid.length > 0) {
            $('#' + tGrid + ' > tbody').html("");
            for (var i = 0; i < arr_Grid.length; i++) {
                $('#' + tGrid + ' > tbody:last-child').append(retornaFila(i, arr_Grid, tGrid, true));
            }
        }
    }
}

function mostrarGridUpdate(Grid) {
    var tGrid = 'TbG_Data';
    var datArray = new Array();
    if (Grid.length > 0) {
        $('#' + tGrid + ' > tbody').html("");
        for (var i = 0; i < Grid.length; i++) {
            datArray[i] = objItemUpdate(i, Grid)
            $('#' + tGrid + ' > tbody:last-child').append(retornaFila(i, datArray, tGrid, true));
        }
        sessionStorage.dts_datosItem = JSON.stringify(datArray);
    }
}

function objItemUpdate(i, Grid) {
    var rowGrid = new Object();
    rowGrid.pro_id = Grid[i]['ProId'];
    rowGrid.pro_ftem_id = Grid[i]['FtemId'];
    rowGrid.accion = "edit";
    return rowGrid;
}

function limpiarDetalle() {
    $('#txt_fecha_documento_evi').val("");
    $('#txt_descripcion').val("");

    $('#txth_doc_archivo').val('');
    $('#txth_doc_archivo_ruta').val('');
    $('#txt_doc_archivo').fileinput('clear');
    //$('#txt_doc_archivo').fileinput('refresh');

    //Quita los Alertas
    //removeIco('#txt_prod_nombre');
    //removeIco('#txt_detalle_uso');
    //$('#txt_producto_foto').fileinput('upload');
    //$('#txth_producto_foto').val("");
    //$('#txt_producto_foto').val("");
    //$('#txt_producto_foto').fileinput('enable');

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


function saveEvidencia() {
    //var medID = (accion == "Update") ? $('#txth_med_id').val() : 0;
    var accion = "Create";
    var link = $('#txth_base').val() + "/repositorio/repositorio/evidencia";
    var arrParams = new Object();
    if (sessionStorage.dts_datosItem) {
        var arr_Grid = JSON.parse(sessionStorage.dts_datosItem);
        if (arr_Grid.length > 0) {
            arrParams.DATA = sessionStorage.dts_datosItem
            arrParams.ACCION = accion;
            requestHttpAjax(link, arrParams, function (response) {
                var message = response.message;
                if (response.status == "OK") {
                    showAlert(response.status, response.type, {"wtmessage": message.info, "title": response.label});
                    limpiarDetalle();
                    sessionStorage.removeItem('dts_datosItem')
                    //var renderurl = $('#txth_base').val() + "/repositorio/reposritorio/index";
                    //window.location = renderurl;
                    setTimeout(function () {
                        parent.window.location.href = $('#txth_base').val() + "/repositorio/repositorio/index";
                    }, 2000);
                } else {
                    showAlert(response.status, response.type, {"wtmessage": message.info, "title": response.label});
                }
            }, true);
        } else {
            //arrParams.DATA = new Array();
            showAlert('NO_OK', 'error', {"wtmessage": "No Existe datos ", "title": 'Información'});
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": "No Existe datos ", "title": 'Información'});
    }
}

function exportExcel() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var modelo = $('#cmb_modelo').val();
    var categoria = $('#cmb_categoria').val();
    var componente = $('#cmb_componente').val();
    var estandar = $('#cmb_estandar').val();
    window.location.href = $('#txth_base').val() + "/repositorio/repositorio/expexcel?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&mod_id=" + modelo + "&cat_id=" + categoria + "&comp_id=" + componente + "&est_id=" + estandar;
}

function exportPdf() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var modelo = $('#cmb_modelo').val();
    var categoria = $('#cmb_categoria').val();
    var componente = $('#cmb_componente').val();
    var estandar = $('#cmb_estandar').val();
    window.location.href = $('#txth_base').val() + "/repositorio/repositorio/exppdf?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&mod_id=" + modelo + "&cat_id=" + categoria + "&comp_id=" + componente + "&est_id=" + estandar;
}

function borrarRegistro(id) {
    var link = $('#txth_base').val() + "/repositorio/repositorio/eliminar";
    var arrParams = new Object();
    arrParams.ids = id;    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/repositorio/repositorio/index";
                }, 2000);
            }
        }, true);
    }
}

function removerArchivo(ids) {   
    var mensj = "¿Seguro desea eliminar el archivo?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'borrarRegistro';
    var params = new Array(ids, 0);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}