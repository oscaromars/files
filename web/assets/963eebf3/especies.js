/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Ndecimal = 2;

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

/*
 * Valida la Entrada del Enter
 */
function isEnter(e) {
    //retornar verdadereo si presiona Enter
    var key;
    if (window.event) // IE
    {
        key = e.keyCode;
        if (key == 13 || key == 9) {
            return true;
        }
    } else if (e.which) { // Netscape/Firefox/Opera
        key = e.which;
        // NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
        //var key = nav4 ? evt.which : evt.keyCode;	
        if (key == 13 || key == 9) {
            return true;
        }
    }
    return false;
}

function redondea(sVal, nDec) {
    var sepDecimal = ".";
    var n = parseFloat(sVal);
    var s = "0.00";
    if (!isNaN(n)) {
        n = Math.round(n * Math.pow(10, nDec)) / Math.pow(10, nDec);
        s = String(n);
        s += (s.indexOf(sepDecimal) == -1 ? sepDecimal : "") + String(Math.pow(10, nDec)).substr(1);
        s = s.substr(0, s.indexOf(sepDecimal) + nDec + 1);
    }
    return s;
}

function findAndRemove(array, property, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            array.splice(i, 1);
        }
    }
    return array;
}



$(document).ready(function () {
    InicioFormulario();

    $('#cmb_tramite').change(function () {
        obtenerEspecies();
    });

    $('#cmb_especies').change(function () {
        obtenerDataEspecies();
    });

    /*DATOS DE TABLA DETALLE*/
    $('#btn_addData').click(function () {
        agregarItemsProducto('new');
    });

    $('#btn_save').click(function () {
        //dataSolicitudPart1();
        guardarSolicitud('Create');
    });

    $('#btn_buscarDataPago').click(function () {
        actualizarGridSolEspecie();
    });
    $('#btn_buscarReviPago').click(function () {
        actualizarGridRevSolEspecie();
    });
    $('#btn_buscarEspecies').click(function () {
        actualizarGridEspeciesGeneradas();
    });

    $('#btn_savepago').click(function () {
        actualizarPago('File');
    });

    $('#btn_saveauto').click(function () {
        autorizaPago();
    });

    $('#cmb_ninteres').change(function () {
        var link = $('#txth_base').val() + "/academico/especies/new";
        var arrParams = new Object();
        arrParams.unidad = $('#cmb_ninteres').val();
        //arrParams.moda_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad", "Seleccionar");
                ///
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidad = $('#cmb_ninteres').val();
                    arrParams.moda_id = $('#cmb_modalidad').val();
                    arrParams.gettramite = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.tramite, "cmb_tramite", "Seleccionar");
                        }
                    }, true);
                }

                ///
            }
        }, true);
    });

    $('#cmb_estado').change(function () {
        if ($('#cmb_estado').val() == 2)
        {
            $('#Divobservacion').show();
        } else
        {
            $('#Divobservacion').hide();
        }
    });
    $('#cmb_unidad').change(function () {
        var link = $('#txth_base').val() + "/academico/especies/especiesgeneradas";
        var arrParams = new Object();
        arrParams.unidad = $('#cmb_unidad').val();
        //arrParams.moda_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad_esp", "Seleccionar");
                ///
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidad = $('#cmb_unidad').val();
                    arrParams.moda_id = $('#cmb_modalidad_esp').val();
                    arrParams.gettramite = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.tramite, "cmb_tramite_esp", "Seleccionar");
                        }
                    }, true);
                }

                ///
            }
        }, true);
    });

});

function InicioFormulario() {
    //recargarGridProducto();
    if (AccionTipo == "SubirPago") {
        loadDataPago();
        //loadDataUpdate();
    } else if (AccionTipo == "Create") {
        loadDataCreate();
    }
}
function loadDataCreate() {
    recargarGridProducto();
}
function loadDataPago() {
    mostrarGridEspecies(varSolicitud);
}


function obtenerEspecies() {
    var link = $('#txth_base').val() + "/academico/especies/new";
    var arrParams = new Object();
    arrParams.tra_id = $('#cmb_tramite').val();
    arrParams.getespecie = true;
    requestHttpAjax(link, arrParams, function (response) {
        if (response.status == "OK") {
            var data = response.message;
            setComboDataselect(data.especies, "cmb_especies", "Seleccionar");
        } else {
            $("#cmb_especies").html("<option value='0'>No Existen Datos</option>");
        }
    }, true);
}

function obtenerDataEspecies() {
    var esp_id = $('#cmb_especies option:selected').val();
    if (esp_id != 0) {
        var link = $('#txth_base').val() + "/academico/especies/new";
        var arrParams = new Object();
        arrParams.esp_id = $('#cmb_especies').val();
        arrParams.getDataespecie = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                $('#txt_dsol_valor').val(redondea(data.especies[0]['esp_valor'], Ndecimal));
                calculaSubTotal();
                //setComboData(data.especies, "cmb_especies");
            } else {
                //$("#cmb_medicos").html("<option value='0'>No Existen Datos</option>");
            }
        }, true);
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Debe ingresar Datos Especies', "title": 'Información'});
    }

}

/* INCIO GRID DETALLE*/
function agregarItemsProducto(opAccion) {
    var tGrid = 'TbG_Productos';
    var nombre_especie = $('#cmb_especies option:selected').text();
    var tra_id = $('#cmb_tramite option:selected').val();
    var esp_id = $('#cmb_especies option:selected').val();
    //Verifica que tenga nombre producto y tenga foto
    //if ($('#txt_prod_nombre').val() != "" && $('#txth_producto_foto').val() != "") {
    if (tra_id != 0 && esp_id != 0) {
        //var valor = $('#txt_prod_nombre').val();
        if (opAccion != "edit") {
            //*********   AGREGAR ITEMS *********
            var arr_Grid = new Array();
            if (sessionStorage.dts_Producto) {
                /*Agrego a la Sesion*/
                arr_Grid = JSON.parse(sessionStorage.dts_Producto);
                var size = arr_Grid.length;
                if (size > 0) {
                    //Varios Items
                    if (codigoExiste(nombre_especie, 'esp_nombre', sessionStorage.dts_Producto)) {//Verifico si el Codigo Existe  para no Dejar ingresar Repetidos
                        arr_Grid[size] = objProducto(size);
                        sessionStorage.dts_Producto = JSON.stringify(arr_Grid);
                        addVariosItemProducto(tGrid, arr_Grid, -1);
                        limpiarDetalle();
                    } else {
                        showAlert('OK', 'error', {"wtmessage": 'Item ya existe en su lista", "Información', "title": 'Información'});
                    }
                } else {
                    /*Agrego a la Sesion*/
                    //Primer Items
                    arr_Grid[0] = objProducto(0);
                    sessionStorage.dts_Producto = JSON.stringify(arr_Grid);
                    addPrimerItemProducto(tGrid, arr_Grid, 0);
                    limpiarDetalle();
                }
            } else {
                //No existe la Session
                //Primer Items
                //arr_Grid[0] = objAntDep(retornarIndexArray(JSON.parse(sessionStorage.dts_Producto),'pro_nombre',valor),JSON.parse(sessionStorage.dts_Producto));
                arr_Grid[0] = objProducto(0);
                sessionStorage.dts_Producto = JSON.stringify(arr_Grid);
                addPrimerItemProducto(tGrid, arr_Grid, 0);
                limpiarDetalle();
            }
        } else {
            //data edicion
        }
        calcularTotalGrid();
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Debe ingresar Datos Válidos', "title": 'Información'});
    }
}
function limpiarDetalle() {
    $('#txt_dsol_cantidad').val("1");
    $('#txt_dsol_valor').val("0.00");
    $('#txt_dsol_total').val("0.00");
    $("#cmb_tramite option[value=0]").attr("selected", true);
    $("#cmb_especies option[value=0]").attr("selected", true);
    $('#txt_observacion').val(" ");

    //$('#txth_doc_adj_img').val(" ");    
    //$('#chk_envase').prop('checked', false);


}

function objProducto(indice) {
    var rowGrid = new Object();
    rowGrid.dsol_id = indice;
    rowGrid.csol_id = 0;
    rowGrid.uaca_id = $('#cmb_ninteres option:selected').val();
    rowGrid.uaca_nombre = $('#cmb_ninteres option:selected').text();
    rowGrid.tra_id = $('#cmb_tramite option:selected').val();
    rowGrid.tra_nombre = $('#cmb_tramite option:selected').text();
    rowGrid.esp_id = $('#cmb_especies option:selected').val();
    rowGrid.esp_nombre = $('#cmb_especies option:selected').text();
    rowGrid.est_id = $('#txth_idest').val();
    rowGrid.dsol_cantidad = $('#txt_dsol_cantidad').val();
    rowGrid.dsol_valor = $('#txt_dsol_valor').val();
    rowGrid.dsol_total = $('#txt_dsol_total').val();
    rowGrid.dsol_usuario_ingreso = "0";
    rowGrid.dsol_estado = 1;
    rowGrid.fpag_nombre = $('#cmb_fpago option:selected').text();
    rowGrid.dsol_observacion = $('#txt_observacion').val();
    rowGrid.dsol_archivo_extra = $('#txth_doc_adj_img').val();
    rowGrid.accion = "new";
    return rowGrid;
}

function addPrimerItemProducto(TbGtable, lista, i) {
    /*Remuevo la Primera fila*/
    $('#' + TbGtable + ' >table >tbody').html("");
    /*Agrego a la Tabla de Detalle*/
    $('#' + TbGtable + ' tr:last').after(retornaFilaProducto(i, lista, TbGtable, true));
}

function addVariosItemProducto(TbGtable, lista, i) {
    i = ($('#' + TbGtable + ' tr').length) - 1;
    $('#' + TbGtable + ' tr:last').after(retornaFilaProducto(i, lista, TbGtable, true));
}

function retornaFilaProducto(c, Grid, TbGtable, op) {
    var strFila = "";
    strFila += '<td style="display:none; border:none;">' + Grid[c]['dsol_id'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['uaca_id'] + '</td>';
    strFila += '<td>' + Grid[c]['uaca_nombre'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['tra_id'] + '</td>';
    strFila += '<td>' + Grid[c]['tra_nombre'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['esp_id'] + '</td>';
    strFila += '<td>' + Grid[c]['esp_nombre'] + '</td>';
    //strFila += '<td>' + Grid[c]['fpag_nombre'] + '</td>';
    strFila += '<td>' + Grid[c]['dsol_cantidad'] + '</td>';
    strFila += '<td>' + Grid[c]['dsol_valor'] + '</td>';
    strFila += '<td>' + Grid[c]['dsol_total'] + '</td>';
    //strFila += '<td></td>';
    //strFila += '<td></td>';



    strFila += '<td>';
    //Cuando hay Actualizacion de Datos
    if (AccionTipo == "Create") {
        //var imgFoto=(Grid[c]['accion']=='edit')?Grid[c]['pro_foto']:$('#txth_producto_foto').val();
        //strFila += (Grid[c]['pro_foto'] != "") ? '<a data-title="'+ Grid[c]['pro_nombre'] +'" data-lightbox="image-1" href="' + $('#txth_imgfolder').val() + $('#txt_ftem_cedula').val()+'_'+$('#txth_ftem_id').val() + '/productos/' + imgFoto + '">Ver Foto</a>' : '<span class="label label-danger">No Tiene Foto</span>';
        strFila += '<a onclick="eliminarItemsProducto(\'' + Grid[c]['dsol_id'] + '\',\'' + TbGtable + '\')" ><span class="glyphicon glyphicon-trash"></span></a>';

    } else {
        //strFila += (Grid[c]['pro_foto'] != "") ? '<a data-title="'+ Grid[c]['pro_nombre'] +'" data-lightbox="image-1" href="' + $('#txth_imgfolder').val() + $('#txt_ftem_cedula').val() + '/productos/' + $('#txth_producto_foto').val() + '">Ver Foto</a>' : '<span class="label label-danger">No Tiene Foto</span>';
    }
    strFila += '</td>';

    //strFila += '<td>';//¿Está seguro de eliminar este elemento?
    //strFila +='<a class="btn-img" onclick="eliminarItemsProducto('+Grid[c]['DEP_ID']+',\''+TbGtable+'\')" >'+imgCol+'</a>';
    //strFila += '<a onclick="eliminarItemsProducto(\'' + Grid[c]['dsol_id'] + '\',\'' + TbGtable + '\')" ><span class="glyphicon glyphicon-trash"></span></a>';
    //strFila += '</td>';

    if (op) {
        strFila = '<tr>' + strFila + '</tr>';
    }
    return strFila;
}

// Recarga la Grid de Productos si Existe
function recargarGridProducto() {
    var tGrid = 'TbG_Productos';
    if (sessionStorage.dts_Producto) {
        var arr_Grid = JSON.parse(sessionStorage.dts_Producto);
        if (arr_Grid.length > 0) {
            $('#' + tGrid + ' > tbody').html("");
            for (var i = 0; i < arr_Grid.length; i++) {
                $('#' + tGrid + ' > tbody:last-child').append(retornaFilaProducto(i, arr_Grid, tGrid, true));
            }
        }
    }
}

function mostrarGridEspecies(Grid) {
    var tGrid = 'TbG_Productos';
    var datArray = new Array();
    //alert(Grid);
    if (Grid.length > 0) {
        $('#' + tGrid + ' > tbody').html("");
        for (var i = 0; i < Grid.length; i++) {
            datArray[i] = objProductoUpdate(i, Grid);
            $('#' + tGrid + ' > tbody:last-child').append(retornaFilaProducto(i, datArray, tGrid, true));
        }
        sessionStorage.dts_Producto = JSON.stringify(datArray);
    }
}

function objProductoUpdate(i, Grid) {
    var rowGrid = new Object();
    rowGrid.dsol_id = Grid[i]['dsol_id'];
    rowGrid.csol_id = Grid[i]['csol_id'];
    rowGrid.uaca_id = Grid[i]['uaca_id'];
    rowGrid.uaca_nombre = $('#cmb_ninteres option:selected').text();//Grid[i]['uaca_nombre'];
    rowGrid.tra_id = Grid[i]['tra_id'];
    rowGrid.tra_nombre = Grid[i]['tra_nombre'];
    rowGrid.esp_id = Grid[i]['esp_id'];
    rowGrid.esp_nombre = Grid[i]['esp_rubro'];
    rowGrid.est_id = Grid[i]['est_id'];
    rowGrid.dsol_cantidad = Grid[i]['dsol_cantidad'];
    rowGrid.dsol_valor = Grid[i]['dsol_valor'];
    rowGrid.dsol_total = Grid[i]['dsol_total'];
    rowGrid.dsol_observacion = Grid[i]['dsol_observacion'];
    rowGrid.dsol_archivo_extra = Grid[i]['dsol_archivo_extra'];
    rowGrid.dsol_usuario_ingreso = Grid[i]['dsol_usuario_ingreso'];
    rowGrid.dsol_estado = Grid[i]['dsol_estado'];
    rowGrid.accion = "edit";
    return rowGrid;
}

function eliminarItemsProducto(val, TbGtable) {
    var ids = "";
    //var count=0;
    if (sessionStorage.dts_Producto) {
        var Grid = JSON.parse(sessionStorage.dts_Producto);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function () {
                ids = $(this).find("td").eq(0).html();
                if (ids == val) {
                    var array = findAndRemove(Grid, 'dsol_id', ids);
                    sessionStorage.dts_Producto = JSON.stringify(array);
                    //if (count==0){sessionStorage.removeItem('detalleGrid')} 
                    $(this).remove();
                }
            });
        }
    }
}

/* FIN GRID DETALLE*/

function pedidoEnterGrid(valor, control) {
    if (valor) {//Si el usuario Presiono Enter= True
        control.value = redondea(control.value, Ndecimal);
        //var p_venta=parseFloat(control.value);
        var cant = control.value;
        var precio = parseFloat($('#txt_dsol_valor').val());
        var total = redondea(precio * cant, Ndecimal);
        $('#txt_dsol_total').val(redondea(total, Ndecimal));
        //calculaTotal(cant,Ids);
        //calcularTotalGrid();
    }
}
//045000950 ext 9010
function calculaSubTotal() {
    var cant = parseFloat($('#txt_dsol_cantidad').val());
    var precio = parseFloat($('#txt_dsol_valor').val());
    var total = redondea(precio * cant, Ndecimal);
    $('#txt_dsol_total').val(redondea(total, Ndecimal));
}



function calculaTotal(cant, Ids) {
    var precio = 0;
    var valor = 0;
    var total = 0;
    var vtot = 0;
    var TbGtable = 'TbG_Productos';
    $('#' + TbGtable + ' tr').each(function () {
        var idstable = $(this).find("td").eq(0).html();
        if (idstable == Ids) {
            precio = $(this).find("td").eq(5).html();
            valor = redondea(precio * cant, Ndecimal);
            $(this).find("td").eq(6).html(valor);
            editarDataItem(Ids, cant, valor)
        }
        if (idstable != '') {
            vtot = parseFloat($(this).find("td").eq(6).html());
            total += (vtot > 0) ? vtot : 0;
        }
    });
    $('#lbl_total').text(redondea(total, Ndecimal))
}

function calcularTotalGrid() {
    var sumTotal = 0;
    var cantidad = 0;
    var precio = 0;
    if (sessionStorage.dts_Producto) {
        var Grid = JSON.parse(sessionStorage.dts_Producto);
        if (Grid.length > 0) {
            for (var i = 0; i < Grid.length; i++) {
                cantidad = parseFloat(Grid[i]['dsol_cantidad']);
                precio = parseFloat(Grid[i]['dsol_valor']);
                if (cantidad > 0) {
                    sumTotal += cantidad * precio;
                }
            }

        }
    }
    $('#lbl_total').text(redondea(sumTotal, Ndecimal))
}



function guardarSolicitud() {
    //var pacID = (accion == "Update") ? $('#txth_pac_id').val() : 0;
    //var perID = (accion == "Update") ? $('#txth_per_id').val() : 0;
    accion = "Create";
    var total = parseFloat($('#lbl_total').text());
    if (/*$('#cmb_especies option:selected').val() != 0 &&*/ total > 0) {
        if (total > 0) {
            var link = $('#txth_base').val() + "/academico/especies/save";
            var arrParams = new Object();
            //arrParams.DATA = dataPersona(pacID, perID);
            arrParams.DTS_CAB = (accion == "Create") ? cabLista() : new Array,
                    arrParams.DTS_DET = (accion == "Create") ? detLista() : new Array,
                    arrParams.ACCION = accion;
            var validation = validateForm();
            if (!validation) {
                requestHttpAjax(link, arrParams, function (response) {
                    var message = response.message;
                    if (response.status == "OK") {
                        showAlert(response.status, response.type, {"wtmessage": message.info, "title": response.label});
                        //limpiarDatos();
                        //sessionStorage.removeItem('dts_Producto');
                        sessionStorage.clear();
                        setTimeout(function () {
                            parent.window.location.href = $('#txth_base').val() + "/academico/especies/solicitudalumno";
                        }, 2000);
                    } else {
                        showAlert(response.status, response.type, {"wtmessage": message.info, "title": response.label});
                    }
                }, true);
            }
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Debe ingresar Datos Especies', "title": 'Información'});
    }

}

function cabLista() {
    var arrayList = new Array();
    var rowGrid = new Object();
    //empid,est_id,uaca_id,mod_id,fpag_id,csol_total,csol_usuario_ingreso,csol_estado,csol_fecha_creacion,csol_estado_logico
    rowGrid.empid = 1;
    rowGrid.uaca_id = $('#cmb_ninteres option:selected').val();
    rowGrid.mod_id = $('#cmb_modalidad option:selected').val();
    rowGrid.fpag_id = $('#cmb_fpago option:selected').val();
    rowGrid.est_id = $('#txth_idest').val();
    rowGrid.csol_total = parseFloat($('#lbl_total').text());
    rowGrid.csol_usuario_ingreso = 1;
    arrayList[0] = rowGrid;
    //sessionStorage.dataPersona = JSON.stringify(datArray);
    //return JSON.stringify(arrayList);
    return rowGrid;
}

function detLista() {
    var TbGtable = 'TbG_Productos';
    var arrayList = new Array;
    var c = 0;        
    //Usa los datos del Session Stores
    if (sessionStorage.dts_Producto) {
        var Grid = JSON.parse(sessionStorage.dts_Producto);
        if (Grid.length > 0) {
            for (var i = 0; i < Grid.length; i++) {
                //[{"dsol_id":0,"csol_id":0,"uaca_id":"1","uaca_nombre":"Grado","tra_id":"1","tra_nombre":"Académicos",
                //"esp_id":"11","esp_nombre":"Certificado de conducta","est_id":0,"dsol_cantidad":"1.00","dsol_valor":"5.00",
                //"dsol_total":"5.00","dsol_observacion":"","dsol_usuario_ingreso":"0","dsol_estado":1,"accion":"new"}
                if (parseFloat(Grid[i]['dsol_cantidad']) > 0) {
                    var rowGrid = new Object();
                    rowGrid.dsol_id = Grid[i]['dsol_id'];
                    rowGrid.csol_id = Grid[i]['csol_id'];
                    rowGrid.uaca_id = Grid[i]['uaca_id'];
                    rowGrid.tra_id = Grid[i]['tra_id'];
                    rowGrid.dsol_observacion = Grid[i]['dsol_observacion'];
                    rowGrid.dsol_archivo_extra = Grid[i]['dsol_archivo_extra'];
                    rowGrid.esp_id = Grid[i]['esp_id'];
                    rowGrid.est_id = $('#txth_idest').val();
                    rowGrid.dsol_cantidad = Grid[i]['dsol_cantidad'];
                    rowGrid.dsol_valor = redondea(Grid[i]['dsol_valor'], Ndecimal);
                    rowGrid.dsol_total = redondea(Grid[i]['dsol_total'], Ndecimal);
                    arrayList[c] = rowGrid;
                    c += 1;
                }
            }
        }
    }
    //return JSON.stringify(arrayList);
    return arrayList;
}

function loadDataUpdate() {
    mostrarGridProducto(varproducto);
}

function mostrarGridProducto(Grid) {
    var tGrid = 'TbG_Productos';
    var datArray = new Array();
    if (Grid.length > 0) {
        $('#' + tGrid + ' > tbody').html("");
        for (var i = 0; i < Grid.length; i++) {
            datArray[i] = objProductoUpdate(i, Grid)
            $('#' + tGrid + ' > tbody:last-child').append(retornaFilaProducto(i, datArray, tGrid, true));
        }
        sessionStorage.dts_Producto = JSON.stringify(datArray);
    }
}

function actualizarGridSolEspecie() {
    var search = '';//$('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var f_estado = $('#cmb_estado').val();
    var f_pago = $('#cmb_fpago').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Solicitudes').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'f_pago': f_pago, 'f_estado': f_estado, 'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function actualizarGridRevSolEspecie() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var f_pago = $('#cmb_fpago').val();
    var f_estado = $('#cmb_estado').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Solicitudes').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'f_pago': f_pago, 'f_estado': f_estado, 'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function actualizarPago() {
    proceso = "File";
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/especies/cargarpago";
    var csol_id = parseInt($('#lbl_num_solicitud').text());
    if ($('#txth_doc_adj_pago').val() != "") {
        arrParams.procesar_file = true;
        arrParams.tipo_proceso = proceso;
        arrParams.csol_id = csol_id;
        arrParams.archivo = $('#txth_doc_adj_pago').val() + "." + $('#txth_doc_adj_leads2').val().split('.').pop();
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/especies/solicitudalumno";
                }, 3000);
            }, true);
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Debe adjuntar un Documento de Pago', "title": 'Información'});
    }

}

function autorizaPago() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/especies/autorizarpago";
    var csol_id = parseInt($('#lbl_num_solicitud').text());
    if ($('#cmb_estado option:selected').val() != 0) {
        arrParams.csol_id = csol_id;
        arrParams.estado = $('#cmb_estado').val();
        arrParams.observacion = $('#cmb_observacion').val();
        arrParams.accion = "AutorizaPago";
        arrParams.est_id = $('#txth_est_id').val();
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/especies/revisarpago";
                }, 3000);
            }, true);
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": 'Debe Selecionar Estado de Solicitud', "title": 'Información'});
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

function actualizarGridEspeciesGeneradas() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad').val();
    var modalidad = $('#cmb_modalidad_esp').val();
    var tramite = $('#cmb_tramite_esp').val();
    var estdocerti = $('#cmb_estadocertificado').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_Solicitudes').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'unidad': unidad, 'modalidad': modalidad, 'search': search, 'tramite': tramite, 'estdocerti': estdocerti});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad').val();
    var modalidad = $('#cmb_modalidad_esp').val();
    var tramite = $('#cmb_tramite_esp').val();
    var estdocerti = $('#cmb_estadocertificado').val();

    window.location.href = $('#txth_base').val() + "/academico/especies/expexcelespecies?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad + "&tramite=" + tramite + "&estdocerti=" + estdocerti;
}

function exportPdf() {
    var search = $('#txt_buscarDataPago').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidad').val();
    var modalidad = $('#cmb_modalidad_esp').val();
    var tramite = $('#cmb_tramite_esp').val();
    var estdocerti = $('#cmb_estadocertificado').val();

    window.location.href = $('#txth_base').val() + "/academico/especies/exppdfespecies?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + '&unidad=' + unidad + "&modalidad=" + modalidad + "&tramite=" + tramite + "&estdocerti=" + estdocerti;
}
function generarCodigocer(egen_id, egen_numero_solicitud, per_cedula) {
    var link = $('#txth_base').val() + "/academico/especies/generacetificodigo";
    var arrParams = new Object();
    arrParams.egen_id = egen_id;
    arrParams.egen_numero_solicitud = egen_numero_solicitud;
    arrParams.per_cedula = per_cedula;
    if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/especies/especiesgeneradas";
                }, 3000);
            }, true);
        }
}