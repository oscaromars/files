var limitRows = 50;
$(document).ready(function() {
    recargarGridItem();

    $('#cmb_pais').change(function() {
        var link = $('#txth_base').val() + "/admision/inscripcion/new";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.provincias, "cmb_provincia", "Seleccionar");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function(response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.cantones, "cmb_canton", "Seleccionar");
                        }
                    }, true);
                }

            }
        }, true);
    });
    $('#cmb_provincia').change(function() {
        var link = $('#txth_base').val() + "/admision/inscripcion/new";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.cantones, "cmb_canton", "Seleccionar");
            }
        }, true);
    });
    $('#btn_AgregarItem').click(function() {
        agregarItems('new')
    });

    $('#cmb_unidad').change(function() {
        var link = $('#txth_base').val() + "/admision/inscripcion/new";
        var arrParams = new Object();
        arrParams.unidad = $(this).val();
        arrParams.carrera_id = $('#cmb_carrera').val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad", "Seleccionar");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidad = $('#cmb_unidad').val();
                    arrParams.moda_id = $('#cmb_modalidad').val();
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function(response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "cmb_carrera", "Seleccionar");
                        }
                    }, true);
                }
            }
        }, true);
    });

    $('#cmb_modalidad').change(function() {
        var link = $('#txth_base').val() + "/admision/inscripcion/new";
        var arrParams = new Object();
        arrParams.unidad = $('#cmb_unidad').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "cmb_carrera", "Seleccionar");
            }
        }, true);
    });
});

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

function agregarItems(opAccion) {
    var tGrid = 'TbG_Data';
    //Verifica que tenga nombre producto y tenga foto
    if ($('#cmb_tipo_documento').val() != 0 && $('#txt_documento').val() != "" && $('#txt_nombres1').val() != "" &&
        $('#txt_apellidos1').val() != "" && $('#cmb_pais').val() != 0 && $('#cmb_provincia').val() != 0 &&
        $('#cmb_canton').val() != 0 && $('#cmb_grupo_introductorio').val() != 0 &&
        $('#cmb_cumple_requisito').val() != 0 && $('#cmb_agente').val() != 0 && $('#txt_fecha_inscripcion').val() != "" &&
        $('#txt_pago_inscripcion').val() != "" && $('#txt_pago_total').val() != "" && $('#txt_fecha_pago').val() != "" &&
        $('#cmb_metodo_pago').val() != 0 && $('#cmb_estado_pago').val() != 0 && $('#cmb_modalidad').val() != 0 && $('#cmb_carrera').val() != 0
        && $('#txt_correo').val() != "") {
        if (opAccion == "new") {
            //verificar si ya existe documento en el grid y en la base de datos
            var documento = $('#txt_documento').val();
            var link = $('#txth_base').val() + "/admision/inscripcion/new";
            var arrParams = new Object();
            arrParams.dni = documento;
            arrParams.existDni = true;
            requestHttpAjax(link, arrParams, function(response) {
                if (response.status == "OK" && response.message.existe == false) {
                    //*********   AGREGAR ITEMS *********
                    var arr_Grid = new Array();
                    if (sessionStorage.dts_datosItem) {
                        /*Agrego a la Sesion*/
                        arr_Grid = JSON.parse(sessionStorage.dts_datosItem);
                        var size = arr_Grid.length;
                        if (size > limitRows) {
                            showAlert('NO_OK', 'error', { "wtmessage": "Solo puede subir hasta " + size + " registros. Guarde primero y luego continue con el proceso.", "title": 'Información' });
                            return;
                        }
                        if (size > 0) {
                            //if (codigoExiste(nombre, 'estandar_evi', sessionStorage.dts_datosItem)) {//Verifico si el Codigo Existe  para no Dejar ingresar Repetidos
                            arr_Grid[size] = objRegistro(size);
                            sessionStorage.dts_datosItem = JSON.stringify(arr_Grid);
                            addVariosItem(tGrid, arr_Grid, -1);
                            limpiarDetalle();
                            //} else {
                            //    showAlert('NO_OK', 'error', {"wtmessage": "Item ya existe en su lista", "title": 'Información'});
                            //}
                        } else {
                            /*Agrego a la Sesion*/
                            //Primer Items                   
                            arr_Grid[0] = objRegistro(0);
                            sessionStorage.dts_datosItem = JSON.stringify(arr_Grid);
                            addPrimerItem(tGrid, arr_Grid, 0);
                            limpiarDetalle();
                        }
                    } else {
                        //No existe la Session
                        //Primer Items
                        arr_Grid[0] = objRegistro(0);
                        sessionStorage.dts_datosItem = JSON.stringify(arr_Grid);
                        addPrimerItem(tGrid, arr_Grid, 0);
                        limpiarDetalle();
                    }
                } else {
                    showAlert('NO_OK', 'error', { "wtmessage": "Persona ya existe en la base de datos.", "title": 'Información' });
                }
            }, true);

        } else {
            //data edicion
        }
    } else {
        showAlert('NO_OK', 'error', { "wtmessage": "No existen datos ingresados.", "title": 'Información' });
    }
}

function addPrimerItem(TbGtable, lista, i) {
    $('#' + TbGtable + ' >table >tbody').html("");
    /*Agrego a la Tabla de Detalle*/
    $('#' + TbGtable + ' tr:last').after(retornaFila(i, lista, TbGtable, true));
}

function addVariosItem(TbGtable, lista, i) {
    i = ($('#' + TbGtable + ' tr').length) - 1;
    $('#' + TbGtable + ' tr:last').after(retornaFila(i, lista, TbGtable, true));
}

function retornaFila(c, Grid, TbGtable, op) {
    var strFila = "";
    strFila += '<td style="display:none; border:none;">' + Grid[c]['indice'] + '</td>';
    strFila += '<td>' + Grid[c]['imae_documento'] + '</td>';
    strFila += '<td>' + Grid[c]['imae_primer_nombre'] + '</td>';
    strFila += '<td>' + Grid[c]['imae_primer_apellido'] + '</td>';

    strFila += '<td style="display:none; border:none;">' + Grid[c]['pro_id'] + '</td>';
    strFila += '<td>' + Grid[c]['provincia'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['can_id'] + '</td>';
    strFila += '<td>' + Grid[c]['canton'] + '</td>';
    strFila += '<td>' + Grid[c]['carrera'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['cemp_id'] + '</td>';
    strFila += '<td>' + Grid[c]['tipo_convenio'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['gint_id'] + '</td>';
    strFila += '<td>' + Grid[c]['grupo_introductorio'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['imae_agente'] + '</td>';
    strFila += '<td>' + Grid[c]['agente'] + '</td>';
    strFila += '<td>' + Grid[c]['imae_fecha_inscripcion'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['imae_estado_pago'] + '</td>';
    strFila += '<td>' + Grid[c]['estado_pago'] + '</td>';

    strFila += '<td>'; //¿Está seguro de eliminar este elemento?   
    strFila += '<a onclick="eliminarItems(\'' + Grid[c]['indice'] + '\',\'' + TbGtable + '\')" ><span class="glyphicon glyphicon-trash"></span></a>';
    strFila += '</td>';

    if (op) {
        strFila = '<tr>' + strFila + '</tr>';
    }
    return strFila;
}

function limpiarDetalle() {
    $('#txt_documento').val("");
    $('#txt_nombres1').val("");
    $('#txt_nombres2').val("");
    $('#txt_apellidos1').val("");
    $('#txt_apellidos2').val("");
    $('#txt_fecha_inscripcion').val("");
    $('#txt_revision').val("");
    $('#txt_pago_inscripcion').val("");
    $('#txt_pago_total').val("");
    $('#txt_fecha_pago').val("");
    $('#txt_convenio_listo').val("");
    $('#txt_matricula').val("");
    $('#txt_titulo').val("");
    $('#txt_correo').val("");
    $('#txt_celular').val("");
    $('#txt_telefono').val("");
    $('#txt_ocupacion').val("");

    $('#cmb_tipo_documento').val(0);
    $('#cmb_pais').val(1);
    $('#cmb_provincia').val(0);
    $('#cmb_canton').val(0);
    $('#cmb_tipo_convenio').val(0);
    $('#cmb_grupo_introductorio').val(0);
    $('#cmb_cumple_requisito').val(0);
    $('#cmb_agente').val(0);
    $('#cmb_metodo_pago').val(0);
    $('#cmb_estado_pago').val(0);
    $('#cmb_institucion').val(0);
    $('#cmb_modalidad').val(0);
    $('#cmb_carrera').val(0);
}

function objRegistro(indice) {
    var rowGrid = new Object();
    rowGrid.indice = indice;

    rowGrid.tipo_documento = $('#cmb_tipo_documento option:selected').text();
    rowGrid.pais = $('#cmb_pais option:selected').text();
    rowGrid.provincia = $('#cmb_provincia option:selected').text();
    rowGrid.canton = $('#cmb_canton option:selected').text();
    rowGrid.tipo_convenio = $('#cmb_tipo_convenio option:selected').text();
    rowGrid.grupo_introductorio = $('#cmb_grupo_introductorio option:selected').text();
    rowGrid.cumple_requisito = $('#cmb_cumple_requisito option:selected').text();
    rowGrid.agente = $('#cmb_agente option:selected').text();
    rowGrid.metodo_pago = $('#cmb_metodo_pago option:selected').text();
    rowGrid.estado_pago = $('#cmb_estado_pago option:selected').text();
    rowGrid.carrera = $('#cmb_carrera option:selected').text();
    rowGrid.institucion = $('#cmb_institucion option:selected').text();

    rowGrid.imae_tipo_documento = $('#cmb_tipo_documento').val();
    rowGrid.pai_id = $('#cmb_pais').val();
    rowGrid.pro_id = $('#cmb_provincia').val();
    rowGrid.can_id = $('#cmb_canton').val();
    rowGrid.cemp_id = $('#cmb_tipo_convenio').val();
    rowGrid.gint_id = $('#cmb_grupo_introductorio').val();
    rowGrid.imae_cumple_requisito = $('#cmb_cumple_requisito').val();
    rowGrid.imae_agente = $('#cmb_agente').val();
    rowGrid.fpag_id = $('#cmb_metodo_pago').val();
    rowGrid.imae_estado_pago = $('#cmb_estado_pago').val();
    rowGrid.uaca_id = $('#cmb_unidad').val();
    rowGrid.mod_id = $('#cmb_modalidad').val();
    rowGrid.eaca_id = $('#cmb_carrera').val();
    rowGrid.ins_id = $('#cmb_institucion').val();

    rowGrid.imae_documento = $('#txt_documento').val();
    rowGrid.imae_primer_nombre = $('#txt_nombres1').val();
    rowGrid.imae_segundo_nombre = $('#txt_nombres2').val();
    rowGrid.imae_primer_apellido = $('#txt_apellidos1').val();
    rowGrid.imae_segundo_apellido = $('#txt_apellidos2').val();
    rowGrid.imae_fecha_inscripcion = $('#txt_fecha_inscripcion').val();
    rowGrid.imae_revisar_urgente = $('#txt_revision').val();
    rowGrid.imae_pago_inscripcion = $('#txt_pago_inscripcion').val();
    rowGrid.imae_valor_maestria = $('#txt_pago_total').val();
    rowGrid.imae_fecha_pago = $('#txt_fecha_pago').val();
    rowGrid.imae_convenios = $('#txt_convenio_listo').val();
    rowGrid.imae_matricula = $('#txt_matricula').val();
    rowGrid.imae_titulo = $('#txt_titulo').val();
    rowGrid.imae_correo = $('#txt_correo').val();
    rowGrid.imae_celular = $('#txt_celular').val();
    rowGrid.imae_convencional = $('#txt_telefono').val();
    rowGrid.imae_ocupacion = $('#txt_ocupacion').val();
    //rowGrid.pro_otros = ($("#chk_otros").prop("checked")) ? 1 : 0;
    rowGrid.accion = "new";
    return rowGrid;
}

function eliminarItems(val, TbGtable) {
    var ids = "";
    //var count=0;
    if (sessionStorage.dts_datosItem) {
        var Grid = JSON.parse(sessionStorage.dts_datosItem);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function() {
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

function findAndRemove(array, property, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            array.splice(i, 1);
        }
    }
    return array;
}

function saveInscripcion() {
    var accion = "Create";
    var link = $('#txth_base').val() + "/admision/inscripcion/save";
    var arrParams = new Object();
    if (sessionStorage.dts_datosItem) {
        var arr_Grid = JSON.parse(sessionStorage.dts_datosItem);
        if (arr_Grid.length > 0) {
            arrParams.dataItems = sessionStorage.dts_datosItem;
            arrParams.ACCION = accion;
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                limpiarDetalle();
                sessionStorage.removeItem('dts_datosItem')
                setTimeout(function() {
                    parent.window.location.href = $('#txth_base').val() + "/admision/inscripcion/index";
                }, 2000);
            }, true);
        } else {
            showAlert('NO_OK', 'error', { "wtmessage": "No existen datos.", "title": 'Información' });
        }
    } else {
        showAlert('NO_OK', 'error', { "wtmessage": "No existen datos.", "title": 'Información' });
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

function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_buscarData").val();
    arrParams.txt_fecha_ini = $("#txt_fecha_ini").val();
    arrParams.txt_fecha_fin = $("#txt_fecha_fin").val();
    arrParams.cmb_agente = $("#cmb_agente").val();
    arrParams.cmb_tipo_convenio = $("#cmb_tipo_convenio").val();
    arrParams.cmb_grupo_introductorio = $("#cmb_grupo_introductorio").val();
    $("#grid_inscr_list").PbGridView("applyFilterData", arrParams);
}

function exportExcel() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var agente = $("#cmb_agente").val();
    var convenio = $("#cmb_tipo_convenio").val();
    var grupo = $("#cmb_grupo_introductorio").val();
    window.location.href = $('#txth_base').val() + "/admision/inscripcion/expexcel?search=" + search +
        "&fecha_ini=" + f_ini + "&fecha_fin=" + f_fin +
        "&agente" + agente + "&convenio=" + convenio +
        "&grupo" + grupo;
}

function eliminarRegistro(id) {
    var mensj = "¿Seguro desea eliminar registro?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'borrarRegistro';
    var params = new Array(id, 0);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}

function borrarRegistro(id, temp) {
    var link = $('#txth_base').val() + "/admision/inscripcion/delete";
    var arrParams = new Object();
    arrParams.reg_id = id;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/admision/inscripcion/index";
                }, 3000);
            }
        }, true);
    }
}

function edit() {
    var codigo = $('#txth_imae_id').val();
    //var tper_id = $('#txth_tper_id').val();
    window.location.href = $('#txth_base').val() + "/admision/inscripcion/edit?codigo=" + codigo /*+ "&tper_id=" + tper_id*/ ;
}

function update() {
    var link = $('#txth_base').val() + "/admision/inscripcion/update";
    var arrParams = new Object();
    arrParams.imae_id = $('#txth_imae_id').val();
    arrParams.convenio = $('#cmb_tipo_convenio').val();
    arrParams.grupo_introductorio = $('#cmb_grupo_introductorio').val();
    arrParams.pais = $('#cmb_pais').val();
    arrParams.provincia = $('#cmb_provincia').val();
    arrParams.canton = $('#cmb_canton').val();
    arrParams.unidad = $('#cmb_unidad').val();
    arrParams.modalidad = $('#cmb_modalidad').val();
    arrParams.carrera = $('#cmb_carrera').val();
    arrParams.tipo_documento = $('#cmb_tipo_documento').val();
    arrParams.documento = $('#txt_documento').val();
    arrParams.primer_nombre = $('#txt_nombres1').val();
    arrParams.segundo_nombre = $('#txt_nombres2').val();
    arrParams.primer_apellido = $('#txt_apellidos1').val();
    arrParams.segundo_apellido = $('#txt_apellidos2').val();
    arrParams.revisar_urgente = $('#txt_revision').val();
    arrParams.cumple_requisito = $('#cmb_cumple_requisito').val();
    arrParams.agente = $('#cmb_agente').val();
    arrParams.fecha_inscripcion = $('#txt_fecha_inscripcion').val();
    arrParams.fecha_pago = $('#txt_fecha_pago').val();
    arrParams.pago_inscripcion = $('#txt_pago_inscripcion').val();
    arrParams.valor_maestria = $('#txt_pago_total').val();
    arrParams.forma_pago = $('#cmb_metodo_pago').val();
    arrParams.estado_pago = $('#cmb_estado_pago').val();
    arrParams.convenios = $('#txt_convenio_listo').val();
    arrParams.matricula = $('#txt_matricula').val();
    arrParams.titulo = $('#txt_titulo').val();
    arrParams.institucion = $('#cmb_institucion').val();
    arrParams.correo = $('#txt_correo').val();
    arrParams.celular = $('#txt_celular').val();
    arrParams.convencional = $('#txt_telefono').val();
    arrParams.ocupacion = $('#txt_ocupacion').val();

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status) {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/admision/inscripcion/index";
                }, 3000);
            }
        }, true);
    }
}

function generarSolicitud(id) {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/admision/inscripcion/generarsolicitud";
    arrParams.id = id;    
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {
                //actualizarGridPagoExterno();
                window.location.href = $('#txth_base').val() + "/admision/inscripcion/index";
            }, 3000);
        }, true);
    }
}