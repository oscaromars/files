/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * 
 * @returns {voids}
 * Created: Kleber Loayza(kloayza@uteg.edu.ec)
 * Updated: Grace Viteri (analistadesarrollo01@uteg.edu.ec)
 * date: Oct/23/18
 */
function habilitarSecciones() {
    var pais = $('#cmb_pais_dom').val();
    if (pais == 1) {
        $('#divCertvota').css('display', 'block');
    } else {
        $('#divCertvota').css('display', 'none');
    }
}
var itemList = [];
var total = 0;
$(document).ready(function() {
    $('#btn_pago_p').css('display', 'none');
    llenarDatosBen(obtDataBen());
    var unisol = $('#cmb_unidad_solicitud').val();
    if (unisol == 1) {
        $('#divmetodocan').css('display', 'none');
    } else if (unisol == 2) {
        $('#divmetodocan').css('display', 'block');
    }
    $('#cmb_pais_dom').change(function() {
        var link = $('#txth_base').val() + "/inscripcionadmision/index";
        var arrParams = new Object();
        arrParams.codarea = $(this).val();
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                $('#txt_codigoarea').val(data.area['name']);
            }
        }, true);
    });

    $('#cmb_tipo_dni').change(function() {
        if ($('#cmb_tipo_dni').val() == 'PASS') {
            $('#txt_cedula').removeClass("PBvalidation");
            $('#txt_pasaporte').addClass("PBvalidation");
            $('#Divpasaporte').show();
            $('#Divcedula').hide();
        } else if ($('#cmb_tipo_dni').val() == 'CED') {
            $('#txt_pasaporte').removeClass("PBvalidation");
            $('#txt_cedula').addClass("PBvalidation");
            $('#Divpasaporte').hide();
            $('#Divcedula').show();
        }
    });
    $('#cmb_unidad_solicitud').change(function() {
        var unisol = $('#cmb_unidad_solicitud').val();
        if (unisol == 1) {
            $('#divmetodocan').css('display', 'none');
            $('#divRequisitosCANP').css('display', 'none');
            $('#divRequisitosCANSP').css('display', 'none');
            $('#divRequisitosCANAD').css('display', 'none');
            $('#divRequisitosCANO').css('display', 'none');
            $('#divRequisitosEXA').css('display', 'none');
            $('#divRequisitosPRP').css('display', 'none');
        } else if (unisol == 2) {
            $('#divmetodocan').css('display', 'block');
        }
        var link = $('#txth_base').val() + "/pagosfrecuentes/index";
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.modalidad, "cmb_modalidad_solicitud");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    if (unisol == 2) {
                        var arrParams = new Object();
                        arrParams.nint_id = $('#cmb_unidad_solicitud').val();
                        arrParams.metodo = $('#cmb_metodo_solicitud').val();
                        arrParams.getmetodo = true;
                        requestHttpAjax(link, arrParams, function(response) {
                            if (response.status == "OK") {
                                data = response.message;
                                setComboData(data.metodos, "cmb_metodo_solicitud");
                                //Item.-
                                var arrParams = new Object();
                                arrParams.unidada = $('#cmb_unidad_solicitud').val();
                                arrParams.metodo = $('#cmb_metodo_solicitud').val();
                                arrParams.moda_id = $('#cmb_modalidad_solicitud').val();
                                arrParams.empresa_id = 1; // se coloca 1, porque solo se trabaja con uteg
                                arrParams.getitem = true;
                                requestHttpAjax(link, arrParams, function(response) {
                                    if (response.status == "OK") {
                                        data = response.message;
                                        setComboData(data.items, "cmb_item");
                                    }
                                    //Precio.
                                    var arrParams = new Object();
                                    arrParams.ite_id = $('#cmb_item').val();
                                    arrParams.getprecio = true;
                                    requestHttpAjax(link, arrParams, function(response) {
                                        if (response.status == "OK") {
                                            data = response.message;
                                            $('#txt_precio_item').val(data.precio);
                                        }
                                    }, true);
                                }, true);
                            }
                        }, true);
                    } else {
                        //Item.-
                        var arrParams = new Object();
                        arrParams.unidada = $('#cmb_unidad_solicitud').val();
                        arrParams.metodo = $('#cmb_metodo_solicitud').val();
                        arrParams.moda_id = $('#cmb_modalidad_solicitud').val();
                        arrParams.empresa_id = 1; // se coloca 1, porque solo se trabaja con uteg
                        arrParams.getitem = true;
                        requestHttpAjax(link, arrParams, function(response) {
                            if (response.status == "OK") {
                                data = response.message;
                                setComboData(data.items, "cmb_item");
                            }
                            //Precio.
                            var arrParams = new Object();
                            arrParams.ite_id = $('#cmb_item').val();
                            arrParams.getprecio = true;
                            requestHttpAjax(link, arrParams, function(response) {
                                if (response.status == "OK") {
                                    data = response.message;
                                    $('#txt_precio_item').val(data.precio);
                                }
                            }, true);
                        }, true);
                    }
                }
            }
        }, true);
    });
    $('#cmb_modalidad_solicitud').change(function() {
        var link = $('#txth_base').val() + "/pagosfrecuentes/index";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad_solicitud').val();
        arrParams.metodo = $('#cmb_metodo_solicitud').val();
        arrParams.moda_id = $('#cmb_modalidad_solicitud').val();
        arrParams.carrera_id = $('#cmb_carrera_solicitud').val();
        arrParams.empresa_id = 1; // se coloca 1, porque solo se trabaja con uteg
        arrParams.getitem = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.items, "cmb_item");
            }
            var arrParams = new Object();
            arrParams.ite_id = $('#cmb_item').val();
            arrParams.getprecio = true;
            requestHttpAjax(link, arrParams, function(response) {
                if (response.status == "OK") {
                    data = response.message;
                    $('#txt_precio_item').val(data.precio);
                }
            }, true);
        }, true);
    });
    $('#cmb_item').change(function() {
        var link = $('#txth_base').val() + "/pagosfrecuentes/index";
        var arrParams = new Object();
        arrParams.ite_id = $('#cmb_item').val();
        arrParams.getprecio = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                $('#txt_precio_item').val(data.precio);
            }
        }, true);
    });

    $('#btn_AgregarItem').click(function() {
        guardarItem();
        var dataItems = obtDataList();
        representarItems(dataItems);
    });

    // tabs create
    $('#paso1next').click(function() {
        guardarBenPagoTemp();
        $("a[data-href='#paso1']").attr('data-toggle', 'none');
        $("a[data-href='#paso1']").parent().attr('class', 'disabled');
        $("a[data-href='#paso1']").attr('data-href', $("a[href='#paso1']").attr('href'));
        $("a[data-href='#paso1']").removeAttr('href');
        $("a[data-href='#paso2']").attr('data-toggle', 'tab');
        $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
        $("a[data-href='#paso2']").trigger("click");
        representarItems(obtDataList());
    });
    $('#paso2back').click(function() {
        llenarDatosBen(obtDataBen());
        $("a[data-href='#paso2']").attr('data-toggle', 'none');
        $("a[data-href='#paso2']").parent().attr('class', 'disabled');
        $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
        $("a[data-href='#paso2']").removeAttr('href');
        $("a[data-href='#paso1']").attr('data-toggle', 'tab');
        $("a[data-href='#paso1']").attr('href', $("a[data-href='#paso1']").attr('data-href'));
        $("a[data-href='#paso1']").trigger("click");
    });
    $('#paso2next').click(function() {
        $("a[data-href='#paso2']").attr('data-toggle', 'none');
        $("a[data-href='#paso2']").parent().attr('class', 'disabled');
        $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
        $("a[data-href='#paso2']").removeAttr('href');
        $("a[data-href='#paso3']").attr('data-toggle', 'tab');
        $("a[data-href='#paso3']").attr('href', $("a[data-href='#paso3']").attr('data-href'));
        $("a[data-href='#paso3']").trigger("click");
        $('#lbl_total_factura').text("$" + total);
        llenarDatosFact(obtDataFact());
    });
    $('#paso3back').click(function() {
        $("a[data-href='#paso3']").attr('data-toggle', 'none');
        $("a[data-href='#paso3']").parent().attr('class', 'disabled');
        $("a[data-href='#paso3']").attr('data-href', $("a[href='#paso3']").attr('href'));
        $("a[data-href='#paso3']").removeAttr('href');
        $("a[data-href='#paso2']").attr('data-toggle', 'tab');
        $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
        $("a[data-href='#paso2']").trigger("click");
        representarItems(obtDataList());
    });
    $('#paso3next').click(function() {
        guardarFacturaTemp();
        guardarPagos();
        $("a[data-href='#paso2']").attr('data-toggle', 'none');
        $("a[data-href='#paso2']").parent().attr('class', 'disabled');
        $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
        $("a[data-href='#paso2']").removeAttr('href');
        $("a[data-href='#paso3']").attr('data-toggle', 'tab');
        $("a[data-href='#paso3']").attr('href', $("a[data-href='#paso3']").attr('data-href'));
        $("a[data-href='#paso3']").trigger("click");
    });

    /*$('input[name=opt_tipo_DNI]:radio').change(function () {
     if ($(this).val() == 1) {//ced
     $('#txt_dni_fac').attr("data-lengthMin", "10");
     $('#txt_dni_fac').attr("data-lengthMax", "10");
     $('#txt_dni_fac').attr("placeholder", $('#txth_ced_lb').val());
     $('label[for=txt_dni_fac]').text($('#txth_ced_lb').val() + "");
     } else if ($(this).val() == 2) { // ruc
     $('#txt_dni_fac').attr("data-lengthMin", "13");
     $('#txt_dni_fac').attr("data-lengthMax", "13");
     $('#txt_dni_fac').attr("placeholder", $('#txth_ruc_lb').val());
     $('label[for=txt_dni_fac]').text($('#txth_ruc_lb').val() + "");
     } else { // pasaporte
     $('#txt_dni_fac').attr("data-lengthMin", "7");
     $('#txt_dni_fac').attr("data-lengthMax", "13");
     $('#txt_dni_fac').attr("placeholder", $('#txth_ruc_lb').val());
     $('label[for=txt_dni_fac]').text($('#txth_pas_lb').val() + "");
     }
     });*/
    $('input[name=opt_tipo_DNI]:radio').change(function() {
        if ($(this).val() == 1) {
            $('#DivcedulaFac').css('display', 'block');
            $('#DivpasaporteFac').css('display', 'none');
            $('#DivRucFac').css('display', 'none');
            $('#txt_dni_fac').addClass("PBvalidation");
            $('#txt_ruc_fac').removeClass("PBvalidation");
            $('#txt_pasaporte_fac').removeClass("PBvalidation");
        } else if ($(this).val() == 2) {
            $('#DivRucFac').css('display', 'block');
            $('#DivpasaporteFac').css('display', 'none');
            $('#DivcedulaFac').css('display', 'none');
            $('#txt_ruc_fac').addClass("PBvalidation");
            $('#txt_dni_fac').removeClass("PBvalidation");
            $('#txt_pasaporte_fac').removeClass("PBvalidation");
        } else {
            $('#DivpasaporteFac').css('display', 'block');
            $('#DivcedulaFac').css('display', 'none');
            $('#DivRucFac').css('display', 'none');
            $('#txt_pasaporte_fac').addClass("PBvalidation");
            $('#txt_ruc_fac').removeClass("PBvalidation");
            $('#txt_dni_fac').removeClass("PBvalidation");
        }
    });
    if ($("input[name='opt_tipo_DNI']:checked").val() == "1") {
        $('#txt_dni_fac').addClass("PBvalidation");
        $('#txt_ruc_fac').removeClass("PBvalidation");
        $('#txt_pasaporte_fac').removeClass("PBvalidation");
    } else if ($("input[name='opt_tipo_DNI']:checked").val() == "2") {
        $('#txt_ruc_fac').addClass("PBvalidation");
        $('#txt_pasaporte_fac').removeClass("PBvalidation");
        $('#txt_dni_fac').removeClass("PBvalidation");
    } else {
        $('#txt_pasaporte_fac').addClass("PBvalidation");
        $('#txt_ruc_fac').removeClass("PBvalidation");
        $('#txt_dni_fac').removeClass("PBvalidation");


    }

});

function llenarDatosBen(benData) {
    var count = Object.keys(benData).length;
    if (count > 0) {
        if (benData['nombre'].length > 0) {
            $('#txt_primer_nombre').val(benData['nombre']);
        }
        if (benData['apellido'].length > 0) {
            $('#txt_primer_apellido').val(benData['apellido']);
        }
        if (benData['nombre'].length > 0) {
            $('#txt_pasaporte').val(benData['pasaporte']);
        }
        if (benData['correo'].length > 0) {
            $('#txt_correo').val(benData['correo']);
        }
        if (benData['celular'].length > 0) {
            $('#txt_celular').val(benData['celular']);
        }
        if (benData['cedula'].length > 0) {
            $('#txt_cedula').val(benData['cedula']);
        }
        if (benData['pais_id'].length > 0) {
            $('#cmb_pais_dom').val(benData['pais_id']);
        }
    }
}

function llenarDatosFact(factData) {
    var count = Object.keys(factData).length;
    if (count > 0) {
        if (factData['nombre_fac'].length > 0) {
            $('#txt_nombres_fac').val(factData['nombre_fac']);
        }
        if (factData['apellidos_fac'].length > 0) {
            $('#txt_apellidos_fac').val(factData['apellidos_fac']);
        }
        if (factData['dir_fac'].length > 0) {
            $('#txt_dir_fac').val(factData['dir_fac']);
        }
        if (factData['telfono_fac'].length > 0) {
            $('#txt_tel_fac').val(factData['telfono_fac']);
        }
        if (factData['dni_fac'].length > 0) {
            $('#txt_dni_fac').val(factData['dni_fac']);
        }
        if (factData['correo'].length > 0) {
            $('#txt_correo_factura').val(factData['correo']);
        }
        if (factData['total'] > 0) {
            $('#lbl_total_factura').text("$" + total);
        }
    }
}

function guardarBenPagoTemp() {
    var arrParams = new Object();
    arrParams.nombre = $('#txt_primer_nombre').val();
    arrParams.apellido = $('#txt_primer_apellido').val();
    arrParams.pasaporte = $('#txt_pasaporte').val();
    arrParams.correo = $('#txt_correo').val();
    arrParams.celular = $('#txt_celular').val();
    arrParams.pais_id = $('#cmb_pais_dom').val();
    arrParams.cedula = $('#txt_cedula').val();
    sessionStorage.setItem('datosBen', JSON.stringify(arrParams));
}

function guardarFacturaTemp() {
    var arrParams = new Object();
    arrParams.nombre_fac = $('#txt_nombres_fac').val();
    arrParams.apellidos_fac = $('#txt_apellidos_fac').val();
    arrParams.dir_fac = $('#txt_dir_fac').val();
    arrParams.telfono_fac = $('#txt_tel_fac').val();
    arrParams.tipo_dni_fac = $("input[name='opt_tipo_DNI']:checked").val();
    if (arrParams.tipo_dni_fac == 1) {
        arrParams.dni_fac = $('#txt_dni_fac').val();
    } else if (arrParams.tipo_dni_fac == 2) {
        arrParams.ruc_fac = $('#txt_ruc_fac').val();
    } else {
        arrParams.pasaporte_fac = $('#txt_pasaporte_fac').val();
    }
    arrParams.doc_correo = $('#txt_correo_factura').val();
    arrParams.total = total;
    sessionStorage.setItem('datosFactura', JSON.stringify(arrParams));
}

function guardarPagos() {
    var link = $('#txth_base').val() + "/pagosfrecuentes/savepayment";
    var arrParams = new Object();
    total = 1;
    if (total == 0) {
        mensaje("No ha seleccionado productos para la factura.");
    } else {
        arrParams.dataBenList = obtDataBen();
        arrParams.dataFacturaList = obtDataFact();
        arrParams.dataItems = obtDataList();
        var len_ben = Object.keys(arrParams.dataBenList).length;
        var len_fact = Object.keys(arrParams.dataFacturaList).length;
        var len_item = Object.keys(arrParams.dataItems).length;
        if (len_ben > 0 && len_fact > 0 && len_item > 0) {
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function(response) {
                    if (response.message.estado == 1) {
                        showAlert("OK", "success", response.message);
                        sessionStorage.clear();
                        setTimeout(function() {
                            var bohre = $('#txth_base').val() + "/pagosfrecuentes/botonpago?docid=" + response.message.iddoc + "&popup=1";
                            $('#btn_pago_p').attr("href", bohre);
                            $('#btn_pago_p').trigger("click");
                        }, 3000); //descomentar cuando termine de guardar bien
                    } else {
                        showAlert("No_OK", "error", response.message);
                        sessionStorage.clear();
                        window.location.href = $('#txth_base').val() + "/pagosfrecuentes/index";
                    }
                }, true);
            }
        } else {
            sessionStorage.clear();
            setTimeout("location.reload(true);", 5);
            window.location.href = $('#txth_base').val() + "/pagosfrecuentes/index";
        }
    }
}

function mensaje(lv_mensaje) {
    var messagePB = new Object();
    messagePB.wtmessage = lv_mensaje;
    messagePB.title = "Mensaje del Sistema";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    //objAccept.callback = 'fnsuscribirLista';
    var params = new Array();
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("OK", "info", messagePB);
}

function getItemsIds() {
    var newList = [];
    var lstcurrent = obtDataList();
    for (i = 0; i < lstcurrent.length; i++) {
        newList.push(lstcurrent[i]['item_id']);
    }
    return JSON.stringify(newList);
}

function guardarItem() {
    var unidad_id = $('#cmb_unidad_solicitud').val();
    var unidad_txt = $('#cmb_unidad_solicitud option:selected').html();
    var modalidad_id = $('#cmb_modalidad_solicitud').val();
    var txt_modalidad = $('#cmb_modalidad_solicitud option:selected').html();
    var item_id = $('#cmb_item').val();
    var txt_item = $('#cmb_item option:selected').html();
    var txt_precio = $('#txt_precio_item').val();
    var datalist = obtDataList();
    var dataitem = {
        item_id: item_id,
        unidad_id: unidad_id,
        unidad: unidad_txt,
        modalidad_id: modalidad_id,
        modalidad: txt_modalidad,
        item: txt_item,
        precio: txt_precio
    }
    if (!existeitem(item_id)) {
        //alert('Agrega al storage');
        datalist.push(dataitem);
        sessionStorage.setItem('datosItem', JSON.stringify(datalist));
    } else {
        var mensaje = { wtmessage: "El item ya se encuentra ingresado.", title: "Exito" };
        showAlert("OK", "success", mensaje);
    }
}

function existeitem(item_id) {
    var lstcurrent = obtDataList();
    for (i = 0; i < lstcurrent.length; i++) {
        if (lstcurrent[i]['item_id'] == item_id) {
            return true;
        }
    }
    return false;
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

function obtDataBen() {
    var storedListBen = sessionStorage.getItem('datosBen');
    if (storedListBen === null) {
        benList = [];
    } else {
        benList = JSON.parse(storedListBen);
    }
    return benList;
}

function obtDataFact() {
    var storedListFact = sessionStorage.getItem('datosFactura');
    if (storedListFact === null) {
        factList = [];
    } else {
        factList = JSON.parse(storedListFact);
    }
    return factList;
}

function representarItems(dataItems) {
    $("#dataListItem").html("");
    html = " <div class='grid-view'>" +
        "<table class='table table-striped table-bordered dataTable'>" +
        "<tbody>" +
        "  <tr><th>Unidad Academica</th> <th>Modalidad</th> <th>Item</th> <th>Precio</th></tr>";
    total = 0;
    for (i = 0; i < dataItems.length; i++) {
        html += "<tr><td>" + dataItems[i]['unidad'] + "</td> <td>" + dataItems[i]['modalidad'] + "</td> <td>" + dataItems[i]['item'] + "</td> <td>$" + dataItems[i]['precio'] + "</td><td><button type='button' class='btn btn-link' onclick='eliminaritem(" + dataItems[i]['item_id'] + ")'> <span class='glyphicon glyphicon-remove'></span> </button></td></tr>";
        total = total + parseInt(dataItems[i]['precio'], 10);
    }
    html += "<tr height='40'><th>Total</th><th></th><th></th><th>$" + total + "</th><th></th></tr>";
    html += "</tbody>";
    html += "    </table>" + "</div>";
    $("#dataListItem").html(html);
}

function eliminaritem(indice) {
    var tmp = JSON.parse(sessionStorage.getItem('datosItem'));
    var newArr = [];
    for (it = 0; it < parseInt(tmp.length); it++) {
        if (parseInt(tmp[it].item_id) !== parseInt(indice)) {
            newArr.push(tmp[it]);
        }
    }
    sessionStorage.setItem('datosItem', JSON.stringify(newArr));
    representarItems(obtDataList());
}

function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}

function PagoDinners(solicitud) {
    var link = $('#txth_base').val() + "/pagosfrecuentes/savepagodinner";
    var arrParams = new Object();
    arrParams.sins_id = solicitud;
    alert('solicitud-proc:PagoDinner:' + solicitud);
    requestHttpAjax(link, arrParams, function(response) {
        var message = response.message;
        if (response.status == "OK") {
            showLoadingPopup();
            setTimeout(function() {}, 1000);
        }
    });
}