
$(document).ready(function () {
    $('#btn_buscarData').click(function () {
        actualizarGrid();
    });
    $('#btn_grabar').click(function () {
        grabarPromocion();
    });
    $('#btn_modificar').click(function () {
        modificarPromocion();
    });
    $('#btn_enviar').click(function () {
        modificarParalelo();
    });
    $('#btn_matricular').click(function () {
        grabarMatriculacion();
    });
    $('#modificarMatriculacion').click(function () {
        modificarMatriculacion();
    });
    /*****************************************************/
    /* Filtro para busqueda en index Promoción Programa */
    /***************************************************/
    $('#cmb_unidadbus').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculacionposgrados/index";
        document.getElementById("cmb_programabus").options.item(0).selected = 'selected';
        var arrParams = new Object();
        arrParams.uni_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidadbus", "Todos");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidadbus').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getprograma = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.programa, "cmb_programabus", "Todos");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidadbus').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculacionposgrados/index";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidadbus').val();
        arrParams.moda_id = $(this).val();
        arrParams.getprograma = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.programa, "cmb_programabus", "Todos");
            }
        }, true);
    });

    /*****************************************************/
    /* Filtro en crear Promoción Programa */
    /***************************************************/
    $('#cmb_unidad').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculacionposgrados/newpromocion";
        document.getElementById("cmb_programa").options.item(0).selected = 'selected';
        var arrParams = new Object();
        arrParams.uni_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad", "Seleccionar");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidad').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getprograma = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.programa, "cmb_programa", "Seleccionar");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidad').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculacionposgrados/newpromocion";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad').val();
        arrParams.moda_id = $(this).val();
        arrParams.getprograma = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.programa, "cmb_programa", "Seleccionar");
            }
        }, true);
    });

    /*****************************************************/
    /* Filtro en crear Promoción Programa */
    /***************************************************/

    $('#cmb_promocion').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculacionposgrados/new";
        var arrParams = new Object();
        $('#txt_cupodisponible').val('');
        arrParams.promocion_id = $(this).val();
        arrParams.getparalelos = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.paralelos, "cmb_paralelo", "Seleccionar");
            }
        }, true);
    });

    $('#cmb_paralelo').change(function () {
        var link = $('#txth_base').val() + "/academico/matriculacionposgrados/new";
        var arrParams = new Object();
        arrParams.cupo_id = $(this).val();
        arrParams.getcupo = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                $('#txt_cupodisponible').val(data.cupo);
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
function actualizarGrid() {
    var search = $('#txt_buscarData').val();
    var unidad = $('#cmb_unidadbus option:selected').val();
    var modalidad = $('#cmb_modalidadbus option:selected').val();
    var programa = $('#cmb_programabus option:selected').val();
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#TbG_PROGRAMA').PbGridView('applyFilterData', {'search': search, 'unidad': unidad, 'modalidad': modalidad, 'programa': programa});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var search = $('#txt_buscarData').val();
    var unidad = $('#cmb_unidadbus option:selected').val();
    var modalidad = $('#cmb_modalidadbus option:selected').val();
    var programa = $('#cmb_programabus option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/matriculacionposgrados/expexcel?search=" + search + "&unidad=" + unidad + "&modalidad=" + modalidad + "&programa=" + programa;
}

function exportPdf() {
    var search = $('#txt_buscarData').val();
    var unidad = $('#cmb_unidadbus option:selected').val();
    var modalidad = $('#cmb_modalidadbus option:selected').val();
    var programa = $('#cmb_programabus option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/matriculacionposgrados/exppdf?pdf=1&search=" + search + "&unidad=" + unidad + "&modalidad=" + modalidad + "&programa=" + programa;
}

function newPrograma() {
    window.location.href = $('#txth_base').val() + "/academico/matriculacionposgrados/newpromocion";
}

function grabarPromocion() {
    var link = $('#txth_base').val() + "/academico/matriculacionposgrados/savepromocion";
    var arrParams = new Object();

    arrParams.anio = $('#txt_anio').val();
    arrParams.mes = $('#cmb_mes').val();
    arrParams.unidad = $('#cmb_unidad').val();
    arrParams.modalidad = $('#cmb_modalidad').val();
    arrParams.programa = $('#cmb_programa').val();
    arrParams.paralelo = $('#txt_paralelo').val();
    arrParams.cupo = $('#txt_cupo').val();
    arrParams.grupo = $('#txt_grupo').val();
    arrParams.modalidadText = $('#cmb_modalidad option:selected').text();
    arrParams.nombreprograma = $("#cmb_programa option:selected").text();
        
    if (arrParams.mes == 0 || arrParams.modalidad == 0 || arrParams.programa == 0)
    {
        showAlert('NO_OK', 'error', {"wtmessage": "Debe seleccionar opciones de las listas.", "title": 'Error'});
    } else
    {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                if (!response.error) {
                    setTimeout(function () {
                        window.location.href = $('#txth_base').val() + "/academico/matriculacionposgrados/index";
                    }, 5000);
                }


            }, true);
        }
    }


}

function modificarPromocion() {
    var link = $('#txth_base').val() + "/academico/matriculacionposgrados/updatepromocion";
    var arrParams = new Object();
    arrParams.progid = $('#txth_progid').val();
    arrParams.anio = $('#txt_anio').val();
    arrParams.mes = $('#cmb_mes').val();
    arrParams.unidad = $('#cmb_unidad').val();
    arrParams.modalidad = $('#cmb_modalidad').val();
    arrParams.programa = $('#cmb_programa').val();
    arrParams.grupo = $('#txt_grupo').val();
    arrParams.modalidadText = $('#cmb_modalidad option:selected').text();
    arrParams.nombreprograma = $("#cmb_programa option:selected").text();
    if (arrParams.mes == 0 || arrParams.modalidad == 0 || arrParams.programa == 0)
    {
        showAlert('NO_OK', 'error', {"wtmessage": "Debe seleccionar opciones de las listas.", "title": 'Error'});
    } else
    {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                if (!response.error) {
                    setTimeout(function () {
                        window.location.href = $('#txth_base').val() + "/academico/matriculacionposgrados/index";
                    }, 5000);
                }


            }, true);
        }
    }
}

function eliminarParalelo(id, ids) {
    var mensj = "¿Seguro desea eliminar paralelo?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = "Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'borrarParalelo';
    var params = new Array(id, ids);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}

function borrarParalelo(id, temp) {
    var link = $('#txth_base').val() + "/academico/matriculacionposgrados/deleteparalelo";
    var arrParams = new Object();
    arrParams.par_id = id;
    arrParams.pro_id = temp;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/matriculacionposgrados/indexparalelo?ids=" + btoa(temp);
                }, 3000);
            }
        }, true);
    }
}

function edit() {
    var codigo = $('#txth_progid').val();
    window.location.href = $('#txth_base').val() + "/academico/matriculacionposgrados/editpromocion?ids=" + codigo;
}

function update() {
    var sins = $('#txth_sins_id').val();
    var adm_id = $('#txth_adm_id').val();
    var perid = $('#txth_per_id').val();
     window.location.href = $('#txth_base').val() + "/academico/matriculacionposgrados/update?sids=" + sins + "&adm=" + adm_id + "&perid=" + perid;    
}

function modificarParalelo() {
    var link = $('#txth_base').val() + "/academico/matriculacionposgrados/updateparalelo";
    var arrParams = new Object();
    arrParams.paraid = $('#txth_parid').val();
    arrParams.progid = $('#txth_proid').val();
    arrParams.cupo = $('#txt_cupo').val();
    arrParams.cupoanterior = $('#txth_cupoviejo').val();
    if (arrParams.cupo >= arrParams.cupoanterior) {
        arrParams.disponible = parseInt($('#txt_cupodisponible').val()) + (parseInt(arrParams.cupo) - (arrParams.cupoanterior));
    } else {
        arrParams.disponible = parseInt($('#txt_cupodisponible').val()) - (parseInt(arrParams.cupoanterior) - (arrParams.cupo));
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    parent.location.href = $('#txth_base').val() + "/academico/matriculacionposgrados/indexparalelo?ids=" + btoa(arrParams.progid);
                }, 3000);
            }


        }, true);
    }
}

function grabarMatriculacion() {
    var link = $('#txth_base').val() + "/academico/matriculacionposgrados/savematriculacion";
    var arrParams = new Object();
    arrParams.personaid = $('#txth_per_id').val();
    arrParams.admitidoid = $('#txth_adm_id').val();
    arrParams.cupodisponible = $('#txt_cupodisponible').val();
    arrParams.matricula = $('#txt_matricula').val();
    arrParams.promocion = $('#cmb_promocion').val();
    arrParams.paralelo = $('#cmb_paralelo').val();
    //arrParams.nombreprograma = $("#cmb_programa option:selected").text();
    if (arrParams.promocion == 0 || arrParams.paralelo == 0)
    {
        showAlert('NO_OK', 'error', {"wtmessage": "Debe seleccionar opciones de las listas.", "title": 'Error'});
    } else if (arrParams.cupodisponible == 0) {
        showAlert('NO_OK', 'error', {"wtmessage": "No hay cupo disponible para este paralelo.", "title": 'Error'});
    } else
    {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                if (!response.error) {
                    setTimeout(function () {
                        window.location.href = $('#txth_base').val() + "/academico/admitidos/matriculado";
                    }, 5000);
                }


            }, true);
        }
    }

}

function modificarMatriculacion() {
    var link = $('#txth_base').val() + "/academico/matriculacionposgrados/updatematriculacion";
    var arrParams = new Object();
    arrParams.personaid = $('#txth_per_id').val();
    arrParams.admitidoid = $('#txth_adm_id').val();
    arrParams.cupodisponible = $('#txt_cupodisponible').val();
    arrParams.matricula = $('#txt_matricula').val();
    arrParams.promocion = $('#cmb_promocion').val();
    arrParams.paralelo = $('#cmb_paralelo').val();
    if (arrParams.promocion == 0 || arrParams.paralelo == 0)
    {
        showAlert('NO_OK', 'error', {"wtmessage": "Debe seleccionar opciones de las listas.", "title": 'Error'});
    } else if (arrParams.cupodisponible == 0) {
        showAlert('NO_OK', 'error', {"wtmessage": "No hay cupo disponible para este paralelo.", "title": 'Error'});
    } else
    {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                if (!response.error) {
                    setTimeout(function () {
                        parent.location.href = $('#txth_base').val() + "/academico/admitidos/matriculado";
                    }, 3000);
                }


            }, true);
        }
    }
}