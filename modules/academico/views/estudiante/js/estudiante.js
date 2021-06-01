$(document).ready(function () {
    $('#btn_buscarDataest').click(function () {
        actualizarGrid();
    });
    $('#btn_guardarestudiante').click(function () {
        save();
    });
    $('#btn_modificarestudiante').click(function () {
        update();
    });
    $('#cmb_unidadbus').change(function () {
        var link = $('#txth_base').val() + "/academico/estudiante/index";
        document.getElementById("cmb_carrerabus").options.item(0).selected = 'selected';
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidadbus", "Todos");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidadbus').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "cmb_carrerabus", "Todos");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidadbus').change(function () {
        var link = $('#txth_base').val() + "/academico/estudiante/index";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidadbus').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "cmb_carrerabus", "Todos");
            }
        }, true);
    });
    // COMOBO PARA NEW ESTUDIANTE
    $('#cmb_unidad').change(function () {
        var link = $('#txth_base').val() + "/academico/estudiante/new";
        document.getElementById("cmb_carrera").options.item(0).selected = 'selected';
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad", "Seleccionar");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidad').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "cmb_carrera", "Seleccionar");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidad').change(function () {
        var link = $('#txth_base').val() + "/academico/estudiante/new";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "cmb_carrera", "Seleccionar");
            }
        }, true);
    });
    //COMBOS PARA MODIFICAR ESTUDIANTES
    $('#cmb_unidades').change(function () {
        var link = $('#txth_base').val() + "/academico/estudiante/edit";
        document.getElementById("cmb_carreras").options.item(0).selected = 'selected';
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidades", "Seleccionar");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidades').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "cmb_carreras", "Seleccionar");
                        }
                    }, true);
                }
            }
        }, true);
    });
    $('#cmb_modalidades').change(function () {
        var link = $('#txth_base').val() + "/academico/estudiante/edit";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidades').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "cmb_carreras", "Seleccionar");
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
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidadbus option:selected').val();
    var modalidad = $('#cmb_modalidadbus option:selected').val();
    var carrera = $('#cmb_carrerabus option:selected').val();
    var estado = $('#cmb_estadobus option:selected').val();

    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Tbg_Estudiantes').PbGridView('applyFilterData', {'search': search, 'f_ini': f_ini, 'f_fin': f_fin, 'unidad': unidad, 'modalidad': modalidad, 'carrera': carrera, 'estado': estado});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidadbus option:selected').val();
    var modalidad = $('#cmb_modalidadbus option:selected').val();
    var carrera = $('#cmb_carrerabus option:selected').val();
    var estado = $('#cmb_estadobus option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/estudiante/expexcel?search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&unidad=" + unidad + "&modalidad=" + modalidad + "&carrera=" + carrera + "&estado=" + estado;
}

function exportPdf() {
    var search = $('#txt_buscarData').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    var unidad = $('#cmb_unidadbus option:selected').val();
    var modalidad = $('#cmb_modalidadbus option:selected').val();
    var carrera = $('#cmb_carrerabus option:selected').val();
    var estado = $('#cmb_estadobus option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/estudiante/exppdf?pdf=1&search=" + search + "&f_ini=" + f_ini + "&f_fin=" + f_fin + "&unidad=" + unidad + "&modalidad=" + modalidad + "&carrera=" + carrera + "&estado=" + estado;
}
function save() {
    var link = $('#txth_base').val() + "/academico/estudiante/save";
    var arrParams = new Object();
    arrParams.per_id = $('#txth_ids').val();
    arrParams.unidad = $('#cmb_unidad').val();
    arrParams.modalidad = $('#cmb_modalidad').val();
    arrParams.carrera = $('#cmb_carrera').val();
    arrParams.categoria = $('#cmb_categoria option:selected').text();
    arrParams.matricula = $('#txt_matricula').val();
    if (arrParams.unidad == '0') {
        var mensaje = {wtmessage: "Unidad : El campo no debe estar vacío.", title: "Error"};
        showAlert("NO_OK", "error", mensaje);

    } else {
        if (arrParams.modalidad == '0') {
            var mensaje = {wtmessage: "Modalidad : El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
        } else {
            if (arrParams.carrera == '0') {
                var mensaje = {wtmessage: "Carrera : El campo no debe estar vacío.", title: "Error"};
                showAlert("NO_OK", "error", mensaje);
            } else {
                if ($('#cmb_categoria').val() == '0') {
                    var mensaje = {wtmessage: "Categoría : El campo no debe estar vacío.", title: "Error"};
                    showAlert("NO_OK", "error", mensaje);
                } else {
                    if (!validateForm()) {
                        requestHttpAjax(link, arrParams, function (response) {
                            showAlert(response.status, response.label, response.message);
                            if (!response.error) {
                                setTimeout(function () {
                                    window.location.href = $('#txth_base').val() + "/academico/estudiante/index";
                                }, 5000);
                            }
                        }, true);
                    }
                }
            }
        }
    }
}
function update() {
    var link = $('#txth_base').val() + "/academico/estudiante/update";
    var arrParams = new Object();
    arrParams.per_id = $('#txth_pids').val();
    arrParams.est_id = $('#txth_eids').val();
    arrParams.unidad = $('#cmb_unidades').val();
    arrParams.modalidad = $('#cmb_modalidades').val();
    arrParams.carrera = $('#cmb_carreras').val();
    arrParams.categoria = $('#cmb_categoria option:selected').text();
    arrParams.matricula = $('#txt_matricula').val();
    if (arrParams.unidad == '0') {
        var mensaje = {wtmessage: "Unidad : El campo no debe estar vacío.", title: "Error"};
        showAlert("NO_OK", "error", mensaje);

    } else {
        if (arrParams.modalidad == '0') {
            var mensaje = {wtmessage: "Modalidad : El campo no debe estar vacío.", title: "Error"};
            showAlert("NO_OK", "error", mensaje);
        } else {
            if (arrParams.carrera == '0') {
                var mensaje = {wtmessage: "Carrera : El campo no debe estar vacío.", title: "Error"};
                showAlert("NO_OK", "error", mensaje);
            } else {
                if ($('#cmb_categoria').val() == '0') {
                    var mensaje = {wtmessage: "Categoría : El campo no debe estar vacío.", title: "Error"};
                    showAlert("NO_OK", "error", mensaje);
                } else {
                    if (!validateForm()) {
                        requestHttpAjax(link, arrParams, function (response) {
                            showAlert(response.status, response.label, response.message);
                            if (!response.error) {
                                setTimeout(function () {
                                    window.location.href = $('#txth_base').val() + "/academico/estudiante/index";
                                }, 3000);
                            }
                        }, true);
                    }
                }
            }
        }
    }
}

function accion(id, estado) {
    var link = $('#txth_base').val() + "/academico/estudiante/estadoestudiante";
    var arrParams = new Object();
    arrParams.est_id = id;
    arrParams.estado = estado;
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (!response.error) {
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/academico/estudiante/index";
                }, 3000);
            }
        }, true);
    }
}

function estadoestudiante(id, estado) {
    var texto = '';
    if (estado == '0') {
        texto = 'inactivar';
    } else {
        texto = 'activar';
    }
    var mensj = "¿Seguro desea " + texto + " el estudiante?";
    var messagePB = new Object();
    messagePB.wtmessage = mensj;
    messagePB.title = texto;//"Eliminar";
    var objAccept = new Object();
    objAccept.id = "btnid2del";
    objAccept.class = "btn-primary";
    objAccept.value = "Aceptar";
    objAccept.callback = 'accion';
    var params = new Array(id, estado);
    objAccept.paramCallback = params;
    messagePB.acciones = new Array();
    messagePB.acciones[0] = objAccept;
    showAlert("warning", "warning", messagePB);
}
function edit() {
    var per_id = $('#txth_perids').val();
    var est_id = $('#txth_estids').val();    
    window.location.href = $('#txth_base').val() + "/academico/estudiante/edit?per_id=" + per_id + "&est_id=" + est_id;
}