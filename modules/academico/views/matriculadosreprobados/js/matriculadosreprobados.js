$(document).ready(function () {
    $('#sendInformacionAdmitidoRepro').click(function () {
        habilitarSecciones();
        if ($('#txth_twer_id').val() == 0)
        {
            guardarAdmireprobado('Create', '1');
        } else {
            guardarAdmireprobado('Update', '1');
        }
    });
    $('#paso1next').click(function () {
        $("a[data-href='#paso1']").attr('data-toggle', 'none');
        $("a[data-href='#paso1']").parent().attr('class', 'disabled');
        $("a[data-href='#paso1']").attr('data-href', $("a[href='#paso1']").attr('href'));
        $("a[data-href='#paso1']").removeAttr('href');
        $("a[data-href='#paso2']").attr('data-toggle', 'tab');
        $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
        $("a[data-href='#paso2']").trigger("click");

    });
    $('#paso2back').click(function () {
        $("a[data-href='#paso2']").attr('data-toggle', 'none');
        $("a[data-href='#paso2']").parent().attr('class', 'disabled');
        $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
        $("a[data-href='#paso2']").removeAttr('href');
        $("a[data-href='#paso1']").attr('data-toggle', 'tab');
        $("a[data-href='#paso1']").attr('href', $("a[data-href='#paso1']").attr('data-href'));
        $("a[data-href='#paso1']").trigger("click");
    });
    $('#paso2next').click(function () {
        $("a[data-href='#paso2']").attr('data-toggle', 'none');
        $("a[data-href='#paso2']").parent().attr('class', 'disabled');
        $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
        $("a[data-href='#paso2']").removeAttr('href');
        $("a[data-href='#paso3']").attr('data-toggle', 'tab');
        $("a[data-href='#paso3']").attr('href', $("a[data-href='#paso3']").attr('data-href'));
        $("a[data-href='#paso3']").trigger("click");
    });

    $('#paso3back').click(function () {
        $("a[data-href='#paso3']").attr('data-toggle', 'none');
        $("a[data-href='#paso3']").parent().attr('class', 'disabled');
        $("a[data-href='#paso3']").attr('data-href', $("a[href='#paso3']").attr('href'));
        $("a[data-href='#paso3']").removeAttr('href');
        $("a[data-href='#paso2']").attr('data-toggle', 'tab');
        $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
        $("a[data-href='#paso2']").trigger("click");
    });
    $('#sendInformacionAdmitidoFinal').click(function () {
        var error = 0;
        var mensaje = "";
        if ($('#cmb_empresa').val() <= 0) {
            error++;
            mensaje += "Debe escoger la empresa\n";
        }
        if ($('#txt_fecha_solicitud').val() == '') {
            error++;
            mensaje += "Debe ingresar una fecha de solicitud\n";
        }
        if ($('#cmb_unidad_solicitudw').val() <= 0) {
            error++;
            mensaje += "Debe escoger una unidad academica\n";
        }
        if ($('#cmb_modalidad_solicitudw').val() <= 0) {
            error++;
            mensaje += "Debe escoger una modalidad\n";
        }
        if ($('#cmb_carrera_solicitudw').val() <= 0) {
            error++;
            mensaje += "Debe escoger carrera\n";
        }
        if ($('#cmb_metodo_solicitudw').val() <= 0) {
            error++;
            mensaje += "Debe escoger un metodo de ingreso\n";
        }
        if ($('#cmb_item_solicitudw').val() <= 0) {
            error++;
            mensaje += "Debe escoger un item\n";
        }
        if (error > 0) {
            var mesage = {
                wtmessage: mensaje, title: "Error"
            };
            showAlert("NO_OK", "success", mesage);
        } else {
            guardarAdmireprobado('Update', '3');
        }
    });
    $('#sendInformacionAdmitidoPendDos').click(function () {
        var error = 0;
        var pais = $('#cmb_pais_dom').val();
        if ($("#chk_mensaje1").prop("checked") && $("#chk_mensaje2").prop("checked")) {
            error = 0;
        } else {
            var mensaje = {
                wtmessage: "Debe Aceptar los términos de la Información.", title: "Exito"
            };
            error++;
            showAlert("NO_OK", "success", mensaje);
        }
        if ($('#txth_doc_titulo').val() == "") {
            error++;
            var mensaje = {wtmessage: "Debe adjuntar título.", title: "Información"};
            showAlert("NO_OK", "error", mensaje);
        } else {
            if ($('#txth_doc_dni').val() == "") {
                error++;
                var mensaje =
                        {wtmessage: "Debe adjuntar documento de identidad.", title: "Información"};
                showAlert("NO_OK", "error", mensaje);
            } else {
                if ($('#cmb_tipo_dni').val() == "CED")
                {
                    if (pais == 1) {
                        if ($('#txth_doc_certvota').val() == "")
                        {
                            error++;
                            var mensaje =
                                    {wtmessage: "Debe adjuntar certificado de votación.", title: "Información"};
                            showAlert("NO_OK", "error", mensaje);
                        }
                    }
                }
            }
        }
        if (error == 0) {
            guardarAdmireprobado('Update', '2');
        }
    });


    $('#paso1next').click(function () {
        $("a[data-href='#paso1']").attr('data-toggle', 'none');
        $("a[data-href='#paso1']").parent().attr('class', 'disabled');
        $("a[data-href='#paso1']").attr('data-href', $("a[href='#paso1']").attr('href'));
        $("a[data-href='#paso1']").removeAttr('href');
        $("a[data-href='#paso2']").attr('data-toggle', 'tab');
        $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
        $("a[data-href='#paso2']").trigger("click");
    });
    $('#cmb_ninteres').change(function () {
        $('#gridmateria').css('display', 'none');
        document.getElementById("cmb_periodo").options.item(0).selected = 'selected';
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/newreprobado";
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad", "Seleccionar");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_ninteres').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "cmb_carrera1", "Seleccionar");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidad').change(function () {
        $('#gridmateria').css('display', 'none');
        document.getElementById("cmb_periodo").options.item(0).selected = 'selected';
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/newreprobado";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_ninteres').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "cmb_carrera1", "Seleccionar");
            }
        }, true);
    });
    $('#cmb_carrera1').change(function () {
        $('#gridmateria').css('display', 'none');
        document.getElementById("cmb_periodo").options.item(0).selected = 'selected';
    });

    $('#btn_BuscarMateria').click(function () {
        actualizarMateriaGrid();
    });
    $('#btn_buscarData').click(function () {
        actualizarGrid();
    });
    $('#opt_declara_Dctosi').change(function () {
        if ($('#opt_declara_Dctosi').val() == 1) {
            $('#divDescuento').css('display', 'block');
            $("#opt_declara_Dctono").prop("checked", "");
        } else {
            $('#divDescuento').css('display', 'none');
        }

    });
    $('#opt_declara_Dctono').change(function () {
        if ($('#opt_declara_Dctono').val() == 2) {
            $('#divDescuento').css('display', 'none');
            $("#opt_declara_Dctosi").prop("checked", "");
        } else {
            $('#divDescuento').css('display', 'block');
        }
    });
    $('#opt_declara_si').change(function () {
        $('#txt_precio_itemw').val(0);
        if ($('#opt_declara_si').val() == 1) {
            $('#divDeclarabeca').css('display', 'block');
            //$('#votacion').css('display', 'none'); no se entiende este elemento
            $("#opt_declara_no").prop("checked", "");
        } else {
            $('#divDeclarabeca').css('display', 'none');
            //$('#votacion').css('display', 'block'); no se entiende este elemento
        }
    });
    $('#opt_declara_no').change(function () {
        asignarPrecio();
        if ($('#opt_declara_no').val() == 2) {
            $('#divDeclarabeca').css('display', 'none');
            $('#votacion').css('display', 'block');
            $("#opt_declara_si").prop("checked", "");
        } else {
            $('#divDeclarabeca').css('display', 'block');
            $('#votacion').css('display', 'none');
        }
    });
    $('#cmb_metodo_solicitudw').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/new";
        var arrParams = new Object();
        if ($('#cmb_metodo_solicitudw').val() == 2) {
            if ($('#cmb_unidad_solicitudw').val() == 1) {
                $('#divBeca').css('display', 'block');
            } else {
                $('#divBeca').css('display', 'none');
            }
        } else {
            $('#divBeca').css('display', 'none');
        }
        //item.-
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad_solicitudw').val();
        arrParams.metodo = $('#cmb_metodo_solicitudw').val();
        arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.getitem = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.items, "cmb_item_solicitudw");
            }
            //Precio.
            var arrParams = new Object();
            arrParams.ite_id = $('#cmb_item_solicitudw').val();
            arrParams.fecha = $('#txt_fecha_solicitud').val();
            arrParams.getprecio = true;
            requestHttpAjax(link, arrParams, function (response) {
                if (response.status == "OK") {
                    data = response.message;
                    $('#txt_precio_itemw').val(data.precio);
                }
            }, true);
        }, true);
        //Descuentos.
        arrParams.unidada = $('#cmb_unidad_solicitudw').val();
        arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
        arrParams.metodo = $('#cmb_metodo_solicitudw').val();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
        arrParams.fecha = $('#txt_fecha_solicitud').val();
        arrParams.getdescuento = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.descuento, "cmb_descuento_solicitudw");
            }
            //Precio con descuento.
            var arrParams = new Object();
            arrParams.descuento_id = $('#cmb_descuento_solicitudw').val();
            arrParams.ite_id = $('#cmb_item_solicitudw').val();
            arrParams.fecha = $('#txt_fecha_solicitud').val();
            arrParams.getpreciodescuento = true;
            requestHttpAjax(link, arrParams, function (response) {
                if (response.status == "OK") {
                    data = response.message;
                    $('#txt_precio_item2w').val(data.preciodescuento);
                }
            }, true);
        }, true);
    });

    $('#cmb_item_solicitudw').change(function () {
        asignarPrecio();
    });

    $('#cmb_empresa').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/new";
        var arrParams = new Object();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.getuacademias = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.unidad_academica, "cmb_unidad_solicitudw");
                var arrParams = new Object();
                if (data.unidad_academica.length > 0) {
                    //Here I am going to change the combo income method
                    var arrParams = new Object();
                    arrParams.nint_id = $('#cmb_unidad_solicitudw').val();
                    arrParams.getmetodo = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.metodos, "cmb_metodo_solicitudw");
                        }
                    }, true);
                    var arrParams = new Object();
                    arrParams.nint_id = $('#cmb_unidad_solicitudw').val();
                    arrParams.getmodalidad = true;
                    arrParams.empresa_id = $('#cmb_empresa').val();
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.modalidad, "cmb_modalidad_solicitudw");
                            if (data.modalidad.length > 0) {
                                var arrParams = new Object();
                                arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                                arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                                arrParams.empresa_id = $('#cmb_empresa').val();
                                arrParams.getcarrera = true;
                                requestHttpAjax(link, arrParams, function (response) {
                                    if (response.status == "OK") {
                                        data = response.message;
                                        setComboData(data.carrera, "cmb_carrera_solicitudw");
                                    }
                                    var arrParams = new Object();
                                    arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                                    arrParams.metodo = $('#cmb_metodo_solicitudw').val();
                                    arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                                    arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
                                    arrParams.empresa_id = $('#cmb_empresa').val();
                                    arrParams.getitem = true;
                                    requestHttpAjax(link, arrParams, function (response) {
                                        if (response.status == "OK") {
                                            data = response.message;
                                            setComboData(data.items, "cmb_item_solicitudw");
                                        }
                                        //Precio.
                                        var arrParams = new Object();
                                        arrParams.ite_id = $('#cmb_item_solicitudw').val();
                                        arrParams.fecha = $('#txt_fecha_solicitud').val();
                                        arrParams.getprecio = true;
                                        requestHttpAjax(link, arrParams, function (response) {
                                            if (response.status == "OK") {
                                                data = response.message;
                                                $('#txt_precio_itemw').val(data.precio);
                                            }
                                        }, true);
                                    }, true);
                                    //Descuentos.
                                    var arrParams = new Object();
                                    arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                                    arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                                    arrParams.metodo = $('#cmb_metodo_solicitudw').val();
                                    arrParams.empresa_id = $('#cmb_empresa').val();
                                    arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
                                    arrParams.fecha = $('#txt_fecha_solicitud').val();
                                    arrParams.getdescuento = true;
                                    requestHttpAjax(link, arrParams, function (response) {
                                        if (response.status == "OK") {
                                            data = response.message;
                                            setComboData(data.descuento, "cmb_descuento_solicitudw");
                                        }
                                        //Precio con descuento.
                                        var arrParams = new Object();
                                        arrParams.descuento_id = $('#cmb_descuento_solicitudw').val();
                                        arrParams.ite_id = $('#cmb_item_solicitudw').val();
                                        arrParams.fecha = $('#txt_fecha_solicitud').val();
                                        arrParams.getpreciodescuento = true;
                                        requestHttpAjax(link, arrParams, function (response) {
                                            if (response.status == "OK") {
                                                data = response.message;
                                                $('#txt_precio_item2w').val(data.preciodescuento);
                                            }
                                        }, true);
                                    }, true);
                                }, true);
                            }
                        }
                    }, true);
                }
            }
        }, true);
        //No mostrar el campo método ingreso cuando sea Unidad:Educación Continua.
        if (arrParams.empresa_id > 1) {
            $('#divMetodo').css('display', 'none');
            $('#divDocumento').css('display', 'none');
            $('#lbl_carrera').text('Programa');
        } else {
            $('#divMetodo').css('display', 'block');
            $('#divDocumento').css('display', 'block');
            $('#lbl_carrera').text('Carrera');
        }
    });

    $('#cmb_unidad_solicitudw').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/new";
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.modalidad, "cmb_modalidad_solicitudw");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                    arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                    arrParams.empresa_id = $('#cmb_empresa').val();
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.carrera, "cmb_carrera_solicitudw");
                        }
                        //Item.-
                        var arrParams = new Object();
                        arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                        arrParams.metodo = $('#cmb_metodo_solicitudw').val();
                        arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                        arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
                        arrParams.empresa_id = $('#cmb_empresa').val();
                        arrParams.getitem = true;
                        requestHttpAjax(link, arrParams, function (response) {
                            if (response.status == "OK") {
                                data = response.message;
                                setComboData(data.items, "cmb_item_solicitudw");
                            }
                            //Precio.
                            var arrParams = new Object();
                            arrParams.ite_id = $('#cmb_item_solicitudw').val();
                            arrParams.fecha = $('#txt_fecha_solicitud').val();
                            arrParams.getprecio = true;
                            requestHttpAjax(link, arrParams, function (response) {
                                if (response.status == "OK") {
                                    data = response.message;
                                    $('#txt_precio_itemw').val(data.precio);
                                }
                            }, true);
                        }, true);
                        //Descuentos.
                        var arrParams = new Object();
                        arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                        arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                        arrParams.metodo = $('#cmb_metodo_solicitudw').val();
                        arrParams.empresa_id = $('#cmb_empresa').val();
                        arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
                        arrParams.fecha = $('#txt_fecha_solicitud').val();
                        arrParams.getdescuento = true;
                        requestHttpAjax(link, arrParams, function (response) {
                            if (response.status == "OK") {
                                data = response.message;
                                setComboData(data.descuento, "cmb_descuento_solicitudw");
                            }
                            //Precio con descuento.
                            var arrParams = new Object();
                            arrParams.descuento_id = $('#cmb_descuento_solicitudw').val();
                            arrParams.ite_id = $('#cmb_item_solicitudw').val();
                            arrParams.fecha = $('#txt_fecha_solicitud').val();
                            arrParams.getpreciodescuento = true;
                            requestHttpAjax(link, arrParams, function (response) {
                                if (response.status == "OK") {
                                    data = response.message;
                                    $('#txt_precio_item2w').val(data.preciodescuento);
                                }
                            }, true);
                        }, true);

                    }, true);
                }
            }
        }, true);
        //métodos.
        var arrParams = new Object();
        arrParams.nint_id = $('#cmb_unidad_solicitudw').val();
        arrParams.metodo = $('#cmb_metodo_solicitudw').val();
        arrParams.getmetodo = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.metodos, "cmb_metodo_solicitudw");
                //Item.-
                var arrParams = new Object();
                arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                arrParams.metodo = $('#cmb_metodo_solicitudw').val();
                arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
                arrParams.empresa_id = $('#cmb_empresa').val();
                arrParams.getitem = true;
                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboData(data.items, "cmb_item_solicitudw");
                    }
                    //Precio.
                    var arrParams = new Object();
                    arrParams.ite_id = $('#cmb_item_solicitudw').val();
                    arrParams.fecha = $('#txt_fecha_solicitud').val();
                    arrParams.getprecio = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            $('#txt_precio_itemw').val(data.precio);
                        }
                    }, true);
                }, true);
                //Descuentos.
                var arrParams = new Object();
                arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                arrParams.metodo = $('#cmb_metodo_solicitudw').val();
                arrParams.empresa_id = $('#cmb_empresa').val();
                arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
                arrParams.fecha = $('#txt_fecha_solicitud').val();
                arrParams.getdescuento = true;
                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboData(data.descuento, "cmb_descuento_solicitudw");
                    }
                    //Precio con descuento.
                    var arrParams = new Object();
                    arrParams.descuento_id = $('#cmb_descuento_solicitudw').val();
                    arrParams.ite_id = $('#cmb_item_solicitudw').val();
                    arrParams.fecha = $('#txt_fecha_solicitud').val();
                    arrParams.getpreciodescuento = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            $('#txt_precio_item2w').val(data.preciodescuento);
                        }
                    }, true);
                }, true);
            }
        }, true);
        //Sólo mostrar el bloque de beca Fundación Cala cuando sea Unidad:Grado y Método:examen.                  
        if (arrParams.nint_id == 1) {
            if ($('#cmb_metodo_solicitudw') == 2) {
                $('#divBeca').css('display', 'block');
            } else {
                $('#divBeca').css('display', 'none');
            }
        } else {
            $('#divBeca').css('display', 'none');
        }
    });

    $('#cmb_modalidad_solicitudw').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/new";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad_solicitudw').val();
        arrParams.moda_id = $(this).val();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.getcarrera = true;
        arrParams.nint_id = $('#cmb_unidad_solicitudw').val();
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.carrera, "cmb_carrera_solicitudw");
                var arrParams = new Object();
                arrParams.nint_id = $('#cmb_unidad_solicitudw').val();
                arrParams.getmetodo = true;
                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboData(data.metodos, "cmb_metodo_solicitudw");
                    }
                    //Item.-
                    var arrParams = new Object();
                    arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                    arrParams.metodo = $('#cmb_metodo_solicitudw').val();
                    arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                    arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
                    arrParams.empresa_id = $('#cmb_empresa').val();
                    arrParams.getitem = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.items, "cmb_item_solicitudw");
                        }
                        //Precio.        
                        var arrParams = new Object();
                        arrParams.ite_id = $('#cmb_item_solicitudw').val();
                        arrParams.fecha = $('#txt_fecha_solicitud').val();
                        arrParams.getprecio = true;
                        requestHttpAjax(link, arrParams, function (response) {
                            if (response.status == "OK") {
                                data = response.message;
                                $('#txt_precio_itemw').val(data.precio);
                            }
                        }, true);
                    }, true);
                    //Descuentos.
                    var arrParams = new Object();
                    arrParams.unidada = $('#cmb_unidad_solicitudw').val();
                    arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
                    arrParams.metodo = $('#cmb_metodo_solicitudw').val();
                    arrParams.empresa_id = $('#cmb_empresa').val();
                    arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
                    arrParams.fecha = $('#txt_fecha_solicitud').val();
                    arrParams.getdescuento = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.descuento, "cmb_descuento_solicitudw");
                        }
                        //Precio con descuento.
                        var arrParams = new Object();
                        arrParams.descuento_id = $('#cmb_descuento_solicitudw').val();
                        arrParams.ite_id = $('#cmb_item_solicitudw').val();
                        arrParams.fecha = $('#txt_fecha_solicitud').val();
                        arrParams.getpreciodescuento = true;
                        requestHttpAjax(link, arrParams, function (response) {
                            if (response.status == "OK") {
                                data = response.message;
                                $('#txt_precio_item2w').val(data.preciodescuento);
                            }
                        }, true);
                    }, true);
                }, true);
            }
        }, true);
    });

    $('#cmb_descuento_solicitudw').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/new";
        //Precio con descuento.
        var arrParams = new Object();
        arrParams.descuento_id = $('#cmb_descuento_solicitudw').val();
        arrParams.ite_id = $('#cmb_item_solicitudw').val();
        arrParams.fecha = $('#txt_fecha_solicitud').val();
        arrParams.getpreciodescuento = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                $('#txt_precio_item2w').val(data.preciodescuento);
            }
        }, true);
    });

    $('#cmb_carrera_solicitudw').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/new";
        //Carrera.-
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad_solicitudw').val();
        arrParams.metodo = $('#cmb_metodo_solicitudw').val();
        arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
        arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.getitem = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.items, "cmb_item_solicitudw");
            }
            //Precio.
            var arrParams = new Object();
            arrParams.ite_id = $('#cmb_item_solicitudw').val();
            arrParams.fecha = $('#txt_fecha_solicitud').val();
            arrParams.getprecio = true;
            requestHttpAjax(link, arrParams, function (response) {
                if (response.status == "OK") {
                    data = response.message;
                    $('#txt_precio_itemw').val(data.precio);
                }
            }, true);
        }, true);
        //Descuentos.
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad_solicitudw').val();
        arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
        arrParams.metodo = $('#cmb_metodo_solicitudw').val();
        arrParams.empresa_id = $('#cmb_empresa').val();
        arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
        arrParams.fecha = $('#txt_fecha_solicitud').val();
        arrParams.getdescuento = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.descuento, "cmb_descuento_solicitudw");
            }
            //Precio con descuento.
            var arrParams = new Object();
            arrParams.descuento_id = $('#cmb_descuento_solicitudw').val();
            arrParams.ite_id = $('#cmb_item_solicitudw').val();
            arrParams.fecha = $('#txt_fecha_solicitud').val();
            arrParams.getpreciodescuento = true;
            requestHttpAjax(link, arrParams, function (response) {
                if (response.status == "OK") {
                    data = response.message;
                    $('#txt_precio_item2w').val(data.preciodescuento);
                }
            }, true);
        }, true);
    });
    $('#txt_fecha_solicitud').on('change', function (e) {
        cambioFechaSolicitud(e);
    });

    //Control del div de beneficiario
    $('#rdo_matricula').change(function () {
        if ($('#rdo_matricula').val() == 1) {
            $("#rdo_matricula_no").prop("checked", "");
            $('#matriculado').css('display', 'none');
            document.getElementById("cmb_unidadmat").options.item(0).selected = 'selected';
            document.getElementById("cmb_modamat").options.item(0).selected = 'selected';
            document.getElementById("cmb_carreramat").options.item(0).selected = 'selected';
        } else {
            $('#matriculado').css('display', 'block');
            document.getElementById("cmb_unidadmat").options.item(0).selected = 'selected';
            document.getElementById("cmb_modamat").options.item(0).selected = 'selected';
            document.getElementById("cmb_carreramat").options.item(0).selected = 'selected';
        }
    });

    $('#rdo_matricula_no').change(function () {
        if ($('#rdo_matricula_no').val() == 2) {
            $("#rdo_matricula").prop("checked", "");
            $('#matriculado').css('display', 'block');
            document.getElementById("cmb_unidadmat").options.item(0).selected = 'selected';
            document.getElementById("cmb_modamat").options.item(0).selected = 'selected';
            document.getElementById("cmb_carreramat").options.item(0).selected = 'selected';
        } else {
            $('#matriculado').css('display', 'none');
            document.getElementById("cmb_unidadmat").options.item(0).selected = 'selected';
            document.getElementById("cmb_modamat").options.item(0).selected = 'selected';
            document.getElementById("cmb_carreramat").options.item(0).selected = 'selected';
        }
    });

    $('#cmb_unidadmat').change(function () {
        //document.getElementById("cmb_periodo").options.item(0).selected = 'selected';
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/newreprobado";
        var arrParams = new Object();
        arrParams.unimat_id = $(this).val();
        arrParams.getmodamat = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidadmat, "cmb_modamat", "Seleccionar");
                var arrParams = new Object();
                if (data.modalidadmat.length > 0) {
                    arrParams.unimati = $('#cmb_unidadmat').val();
                    arrParams.modamat_id = data.modalidadmat[0].id;
                    arrParams.getcarreramat = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carreramat, "cmb_carreramat", "Seleccionar");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modamat').change(function () {
        //document.getElementById("cmb_periodo").options.item(0).selected = 'selected';
        var link = $('#txth_base').val() + "/academico/matriculadosreprobados/newreprobado";
        var arrParams = new Object();
        arrParams.unimati = $('#cmb_unidadmat').val();
        arrParams.modamat_id = $(this).val();
        arrParams.getcarreramat = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carreramat, "cmb_carreramat", "Seleccionar");
            }
        }, true);
    });

});
function cambioFechaSolicitud(e) {
    var link = $('#txth_base').val() + "/academico/matriculadosreprobados/new";
    //Precio.           
    var arrParams = new Object();
    arrParams.ite_id = $('#cmb_item_solicitudw').val();
    arrParams.fecha = $('#txt_fecha_solicitud').val();
    arrParams.getprecio = true;
    requestHttpAjax(link, arrParams, function (response) {
        if (response.status == "OK") {
            data = response.message;
            $('#txt_precio_itemw').val(data.precio);
        }
    }, true);
    //Descuentos.
    var arrParams = new Object();
    arrParams.unidada = $('#cmb_unidad_solicitudw').val();
    arrParams.moda_id = $('#cmb_modalidad_solicitudw').val();
    arrParams.metodo = $('#cmb_metodo_solicitudw').val();
    arrParams.empresa_id = $('#cmb_empresa').val();
    arrParams.carrera_id = $('#cmb_carrera_solicitudw').val();
    arrParams.fecha = $('#txt_fecha_solicitud').val();
    arrParams.getdescuento = true;
    requestHttpAjax(link, arrParams, function (response) {
        if (response.status == "OK") {
            data = response.message;
            setComboData(data.descuento, "cmb_descuento_solicitudw");
        }
        //Precio con descuento.
        var arrParams = new Object();
        arrParams.descuento_id = $('#cmb_descuento_solicitudw').val();
        arrParams.ite_id = $('#cmb_item_solicitudw').val();
        arrParams.fecha = $('#txt_fecha_solicitud').val();
        arrParams.getpreciodescuento = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                $('#txt_precio_item2w').val(data.preciodescuento);
            }
        }, true);
    }, true);
}
function asignarPrecio() {
    var link = $('#txth_base').val() + "/academico/matriculadosreprobados/new";
    //Precio.
    var arrParams = new Object();
    arrParams.ite_id = $('#cmb_item_solicitudw').val();
    arrParams.fecha = $('#txt_fecha_solicitud').val();
    arrParams.getprecio = true;
    requestHttpAjax(link, arrParams, function (response) {
        if (response.status == "OK") {
            data = response.message;
            $('#txt_precio_itemw').val(data.precio);
        }
    }, true);
    //Precio con descuento.
    var arrParams = new Object();
    arrParams.descuento_id = $('#cmb_descuento_solicitudw').val();
    arrParams.ite_id = $('#cmb_item_solicitudw').val();
    arrParams.getpreciodescuento = true;
    requestHttpAjax(link, arrParams, function (response) {
        if (response.status == "OK") {
            data = response.message;
            $('#txt_precio_item2w').val(data.preciodescuento);
        }
    }, true);
}
function newReprobadoPend() {
    window.location.href = $('#txth_base').val() + "/academico/matriculadosreprobados/new";
}
function newReprobado() {
    window.location.href = $('#txth_base').val() + "/academico/matriculadosreprobados/newreprobado";
}
function guardarAdmiMateriarep() {
    var link = $('#txth_base').val() + "/academico/matriculadosreprobados/save";
    var arrParams = new Object();
    var selected = '';
    arrParams.uniacademicamat = '';
    arrParams.modalidadmat = '';
    arrParams.carreprogmat = '';
    arrParams.uniacademica = $('#cmb_ninteres').val();
    arrParams.modalidad = $('#cmb_modalidad').val();
    arrParams.carreprog = $('#cmb_carrera1').val();
    arrParams.periodo = $('#cmb_periodo').val();
    arrParams.estadomat = $('#cmb_estado').val();
    arrParams.ids = $('#TbG_Admitido input[name=rb_admitido]:checked').val();
    $('#TbG_MATERIAS input[type=checkbox]').each(function () {
        if (this.checked) {
            selected += $(this).val() + ' ';
        }
    });
    if (selected != '') // reprobaron materias, debe guardarse las misma unidad, modalidad y carrera
    {
        arrParams.materia = selected;
        if ($('input:radio[name=rdo_matricula]:checked').val() == 1) {
            arrParams.uniacademicamat = $('#cmb_ninteres').val();
            arrParams.modalidadmat = $('#cmb_modalidad').val();
            arrParams.carreprogmat = $('#cmb_carrera1').val();
        }
        if ($('input:radio[name=rdo_matricula]:checked').val() == 2) {
            arrParams.uniacademicamat = $('#cmb_unidadmat').val();
            arrParams.modalidadmat = $('#cmb_modamat').val();
            arrParams.carreprogmat = $('#cmb_carreramat').val();
        }

    } else // aprobo todas materias, debe guardarse las matriculo unidad, matriculo modalidad, matriculo carrera y validadar que sea mayor a 0
    {
        if ($('input:radio[name=rdo_matricula]:checked').val() == 1) {
            arrParams.uniacademicamat = $('#cmb_ninteres').val();
            arrParams.modalidadmat = $('#cmb_modalidad').val();
            arrParams.carreprogmat = $('#cmb_carrera1').val();
        }
        if ($('input:radio[name=rdo_matricula]:checked').val() == 2) {
            arrParams.uniacademicamat = $('#cmb_unidadmat').val();
            arrParams.modalidadmat = $('#cmb_modamat').val();
            arrParams.carreprogmat = $('#cmb_carreramat').val();
                       
        }
    }
    if (arrParams.ids === undefined)
    {
        var mensaje = {wtmessage: "Seleccionar datos del admitido desde buscar DNI.", title: "Error"};
        showAlert("NO_OK", "Error", mensaje);
    } else {
        if ($('#cmb_ninteres option:selected').val() > '0') {
            if ($('#cmb_modalidad option:selected').val() > '0') {
                if ($('#cmb_carrera1 option:selected').val() > '0') {
                    if ($('#cmb_periodo option:selected').val() > '0') {
                        if ($('#cmb_estado option:selected').val() > '0') {
                            if ($('input:radio[name=rdo_matricula]:checked').val() == 2) {
                                if (selected == '') {
                                    if ($('#cmb_unidadmat option:selected').val() > '0') {
                                        if ($('#cmb_modamat option:selected').val() > '0') {
                                            if ($('#cmb_carreramat option:selected').val() > '0') {
                                                if (!validateForm()) {
                                                    requestHttpAjax(link, arrParams, function (response) {
                                                        showAlert(response.status, response.label, response.message);
                                                        setTimeout(function () {
                                                            window.location.href = $('#txth_base').val() + "/academico/matriculadosreprobados/index";
                                                        }, 3000);
                                                    }, true);
                                                }
                                            } else {
                                                var mensaje = {wtmessage: "Matriculó Carrera: El campo no debe estar vacío.", title: "Error"};
                                                showAlert("NO_OK", "Error", mensaje);
                                            }
                                        } else {
                                            var mensaje = {wtmessage: "Matriculó Modalidad: El campo no debe estar vacío.", title: "Error"};
                                            showAlert("NO_OK", "Error", mensaje);
                                        }
                                    } else {
                                        var mensaje = {wtmessage: "Matriculó Unidad Académica: El campo no debe estar vacío.", title: "Error"};
                                        showAlert("NO_OK", "Error", mensaje);
                                    }
                                } else {
                                    var mensaje = {wtmessage: "Todas las materias no estan aprobadas, no pudó matricularse.", title: "Error"};
                                    showAlert("NO_OK", "Error", mensaje);
                                }
                            } else {
                                if (!validateForm()) {
                                    requestHttpAjax(link, arrParams, function (response) {
                                        showAlert(response.status, response.label, response.message);
                                        setTimeout(function () {
                                            window.location.href = $('#txth_base').val() + "/academico/matriculadosreprobados/index";
                                        }, 3000);
                                    }, true);
                                }
                            }

                        } else {
                            var mensaje = {wtmessage: "Estado: El campo no debe estar vacío.", title: "Error"};
                            showAlert("NO_OK", "Error", mensaje);
                        }
                    } else {
                        var mensaje = {wtmessage: "Período: El campo no debe estar vacío.", title: "Error"};
                        showAlert("NO_OK", "Error", mensaje);
                    }
                } else {
                    var mensaje = {wtmessage: "Carrera /Programa: El campo no debe estar vacío.", title: "Error"};
                    showAlert("NO_OK", "Error", mensaje);
                }
            } else {
                var mensaje = {wtmessage: "Modalidad: El campo no debe estar vacío.", title: "Error"};
                showAlert("NO_OK", "Error", mensaje);
            }
        } else {
            var mensaje = {wtmessage: "Unidad Académica: El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "Error", mensaje);
        }
    }
}
function guardarAdmireprobado(accion, paso) {
    var ID = (accion == "Update") ? $('#txth_twer_id').val() : 0;
    var link = $('#txth_base').val() + "/academico/matriculadosreprobados/savereprobadostemp";
    var arrParams = new Object();
    arrParams.DATA_1 = dataInscripPart1(ID);
    arrParams.ACCION = accion;
    arrParams.PASO = paso;
    requestHttpAjax(link, arrParams, function (response) {
        var message = response.message;
        if (response.status == "OK") {
            if (accion == "Create") {
                $('#txth_twer_id').val(response.data.twre_id);
                paso1next();
            } else if (accion == "Update") {
                if (paso == 2) {
                    paso2next();
                } else if (paso == 3) {
                    showAlert(response.status, response.label, response.message);
                    window.location.href = $('#txth_base').val() + "/admision/interesados/index";
                }
            }
        }
    }, true);
}
function dataInscripPart1(ID) {
    var datArray = new Array();
    var objDat = new Object();
    objDat.twre_id = ID;
    objDat.ming_id = $('#cmb_empresa option:selected').val();
    objDat.pges_pri_nombre = $('#txt_primer_nombre').val();
    objDat.pges_pri_apellido = $('#txt_primer_apellido').val();
    objDat.tipo_dni = $('#cmb_tipo_dni option:selected').val();
    objDat.pges_cedula = $('#txt_cedula').val();
    objDat.pges_correo = $('#txt_correo').val();
    objDat.pais = $('#cmb_pais_dom option:selected').val();
    objDat.pges_celular = $('#txt_celular').val();
    objDat.unidad_academica = $('#cmb_unidad_solicitudw option:selected').val();
    objDat.modalidad = $('#cmb_modalidad_solicitudw option:selected').val();
    objDat.ming_id = ($('#cmb_metodo_solicitudw option:selected').val()) ? $('#cmb_metodo_solicitudw').val() : 0;
    objDat.carrera = $('#cmb_carrera_solicitudw option:selected').val() ? $('#cmb_carrera_solicitudw').val() : 0;
    objDat.ruta_doc_titulo = ($('#txth_doc_titulo').val() != '') ? $('#txth_doc_titulo').val() : '';
    objDat.ruta_doc_dni = ($('#txth_doc_dni').val() != '') ? $('#txth_doc_dni').val() : '';
    objDat.ruta_doc_certvota = ($('#txth_doc_certvota').val() != '') ? $('#txth_doc_certvota').val() : '';
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }

    if (mm < 10) {
        mm = '0' + mm
    }
    var fecha_actual = yyyy + '/' + mm + '/' + dd;
    objDat.fecha_solicitud = ($('#txt_fecha_solicitud').val() != '') ? $('#txt_fecha_solicitud').val() : fecha_actual;
    objDat.ite_id = $('#cmb_item_solicitudw option:selected').val();
    objDat.precio_item = $('#txt_precio_itemw').val();
    if ($('input[name=opt_declara_Dctosi]:checked').val() == 1) {
        objDat.sdes_id = $('#cmb_descuento_solicitudw').val();
        objDat.marcadescuento = 1;
    } else {
        objDat.sdes_id = 0;
        objDat.marcadescuento = 0;
    }
    if ($('input[name=opt_declara_si]:checked').val() == 1) {
        objDat.beca = 1;
    } else {
        objDat.beca = 0;
    }
    objDat.precio_item_desc = $('#txt_precio_item2w').val();
    objDat.observacionw = $('#txt_observacionw').val();
    objDat.ruta_doc_foto = '';
    objDat.ruta_doc_hojavida = '';
    objDat.ruta_doc_certificado = ($('#txth_doc_certificado').val() != '') ? $('#txth_doc_certificado').val() : '';
    objDat.twre_mensaje1 = ($("#chk_mensaje1").prop("checked")) ? '1' : '0';
    objDat.twre_mensaje2 = ($("#chk_mensaje2").prop("checked")) ? '1' : '0';
    datArray[0] = objDat;
    sessionStorage.dataReprobado_1 = JSON.stringify(datArray);
    return datArray;
}
function habilitarSecciones() {
    var pais = $('#cmb_pais_dom').val();
    if (pais == 1) {
        $('#divCertvota').css('display', 'block');
    } else {
        $('#divCertvota').css('display', 'none');
    }
}

function paso1next() {
    $("a[data-href='#paso1']").attr('data-toggle', 'none');
    $("a[data-href='#paso1']").parent().attr('class', 'disabled');
    $("a[data-href='#paso1']").attr('data-href', $("a[href='#paso1']").attr('href'));
    $("a[data-href='#paso1']").removeAttr('href');
    $("a[data-href='#paso2']").attr('data-toggle', 'tab');
    $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
    $("a[data-href='#paso2']").trigger("click");
}
function paso2next() {
    $("a[data-href='#paso2']").attr('data-toggle', 'none');
    $("a[data-href='#paso2']").parent().attr('class', 'disabled');
    $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
    $("a[data-href='#paso2']").removeAttr('href');
    $("a[data-href='#paso3']").attr('data-toggle', 'tab');
    $("a[data-href='#paso3']").attr('href', $("a[data-href='#paso3']").attr('data-href'));
    $("a[data-href='#paso3']").trigger("click");
}
function searchAdmitido(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
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
function actualizarGrid() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var estadomat = $('#cmb_estado').val();
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_REPMATRICULA').PbGridView('applyFilterData', {'f_ini': f_ini, 'f_fin': f_fin, 'search': search, 'estadomat': estadomat});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var estadomat = $('#cmb_estado').val();
    window.location.href = $('#txth_base').val() + "/academico/matriculadosreprobados/expexcel?search=" + search + "&fecha_ini=" + f_ini + "&fecha_fin=" + f_fin + "&estadomat=" + estadomat;
}

function exportPdf() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var estadomat = $('#cmb_estado').val();
    window.location.href = $('#txth_base').val() + "/academico/matriculadosreprobados/exportpdf?pdf=1&search=" + search + "&fecha_ini=" + f_ini + "&fecha_fin=" + f_fin + "&estadomat=" + estadomat;
}
function actualizarMateriaGrid() {
    if ($('#cmb_ninteres option:selected').val() > '0') {
        if ($('#cmb_modalidad option:selected').val() > '0') {
            if ($('#cmb_carrera1 option:selected').val() > '0') {
                if ($('#cmb_periodo option:selected').val() > '0') {
                    $('#gridmateria').css('display', 'block');
                    var unidad = $('#cmb_ninteres option:selected').val();
                    var modalidad = $('#cmb_modalidad option:selected').val();
                    var carrera = $('#cmb_carrera1 option:selected').val();
                    var periodo = $('#cmb_periodo option:selected').val();
                    if (!$(".blockUI").length) {
                        showLoadingPopup();
                        $('#TbG_MATERIAS').PbGridView('applyFilterData', {'unidad': unidad, 'modalidad': modalidad, 'carrera': carrera, 'periodo': periodo});
                        setTimeout(hideLoadingPopup, 2000);
                    }
                } else {
                    var mensaje = {wtmessage: "Período: El campo no debe estar vacío.", title: "Error"};
                    showAlert("NO_OK", "Error", mensaje);
                }
            } else {
                var mensaje = {wtmessage: "Carrera /Programa: El campo no debe estar vacío.", title: "Error"};
                showAlert("NO_OK", "Error", mensaje);
            }
        } else {
            var mensaje = {wtmessage: "Modalidad: El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "Error", mensaje);
        }
    } else {
        var mensaje = {wtmessage: "Unidad Académica: El campo no debe estar vacío.", title: "Error"};
        showAlert("NO_OK", "Error", mensaje);
    }

}
