$(document).ready(function () {
    recargarGridItem();

    $('#cmb_profesor').change(function () {
        var link = $('#txth_base').val() + "/academico/distributivocabecera/validadistributivo";
        var arrParams = new Object();
        arrParams.pro_id = $('#cmb_profesor').val();
        arrParams.paca_id = $('#cmb_periodo').val();
        arrParams.transaccion = "N";
        arrParams.getvalida = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "NOOK") {
                showAlert(response.status, response.label, response.message);
                document.getElementById("cmb_tipo_asignacion").disabled = true;
            } else {
                document.getElementById("cmb_tipo_asignacion").disabled = false;
                data = response.message;
                setComboDataselect(data.asignacion, "cmb_tipo_asignacion", "Todos");
            }
        }, false);

    });
    $('#cmb_periodo').change(function () {
        if($('#cmb_periodo').val()!=0){
            document.getElementById("cmb_periodo").disabled = true;
        }

    });

    //JLC: 22 ABRIL 2022
    $('#btn_limpiarbuscador').click(function () {
        limpiarBuscador();
    });
    //JLC: 22 ABRIL 2022

    $('#cmb_tipo_asignacion').change(function () {
        document.getElementById("cmb_profesor").disabled = true;
        tipo = $('#cmb_tipo_asignacion').val();

        switch (tipo) {
            case "1":
                $('#bloque1').css('display', 'block');
                $('#bloque2').css('display', 'block');
                /*if ($('#cmb_unidad_dis').val() == 2) {
                    $('#bloque2-1').css('display', 'none');
                    $('#bloque8').css('display', 'block');
                    $('#bloque6').css('display', 'block');
                    $('#bloque7').css('display', 'block');
                } else {
                    $('#bloque2-1').css('display', 'block');
                    $('#bloque8').css('display', 'none');
                    $('#bloque6').css('display', 'none');
                    $('#bloque7').css('display', 'none');
                }*/
                $('#bloque3').css('display', 'block');
                $('#bloque4').css('display', 'block');
                $('#bloque_p').css('display', 'block');
                $('#bloque_j').css('display', 'block');
                if ($('#cmb_modalidad').val() == 1) {
                    $('#bloque_n').css('display', 'block');
                }
                $('#bloque_h_otros').css('display', 'none');
                $('#bloque9').css('display', 'none');
                break;
            case "2":
                $('#bloque1').css('display', 'block');
                $('#bloque2-1').css('display', 'none');
                $('#bloque3').css('display', 'none');
                $('#bloque8').css('display', 'none');
                $('#bloque9').css('display', 'none');
                $('#bloque6').css('display', 'none');
                $('#bloque7').css('display', 'none');
                $('#bloque4').css('display', 'none');
                $('#bloque2').css('display', 'block');
                $('#bloque_n').css('display', 'none');
                $('#bloque_h_otros').css('display', 'none');
                break;
            case "3":
                $('#bloque1').css('display', 'block');
                $('#bloque2').css('display', 'none');
                $('#bloque2-1').css('display', 'none');
                $('#bloque3').css('display', 'none');
                $('#bloque4').css('display', 'none');
                $('#bloque8').css('display', 'none');
                $('#bloque9').css('display', 'block');
                $('#bloque6').css('display', 'none');
                $('#bloque7').css('display', 'none');
                $('#bloque_h_otros').css('display', 'none');
                $('#bloque_n').css('display', 'none');
                break;
            case "4":
                $('#bloque1').css('display', 'block');
                $('#bloque2').css('display', 'none');
                $('#bloque2-1').css('display', 'none');
                $('#bloque3').css('display', 'none');
                $('#bloque4').css('display', 'none');
                $('#bloque8').css('display', 'none');
                $('#bloque9').css('display', 'block');
                $('#bloque6').css('display', 'none');
                $('#bloque7').css('display', 'none');
                $('#bloque_h_otros').css('display', 'none');
                $('#bloque_n').css('display', 'none');
                break;
            case "6":
                $('#bloque1').css('display', 'none');
                $('#bloque2').css('display', 'none');
                $('#bloque2-1').css('display', 'none');
                $('#bloque3').css('display', 'none');
                $('#bloque4').css('display', 'none');
                $('#bloque8').css('display', 'none');
                $('#bloque6').css('display', 'none');
                $('#bloque7').css('display', 'none');
                $('#bloque9').css('display', 'none');
                $('#bloque_h_otros').css('display', 'block');
                $('#bloque_n').css('display', 'none');
                break;
            case "7":
                
                $('#bloque1').css('display', 'block');
                $('#bloque2').css('display', 'block');
                $('#bloque3').css('display', 'block');
                $('#bloque4').css('display', 'none');
                $('#bloque5').css('display', 'none');
                /*if ($('#cmb_unidad_dis').val() == 2) {
                    $('#bloque2-1').css('display', 'none');
                    $('#bloque8').css('display', 'block');
                    $('#bloque6').css('display', 'block');
                    $('#bloque7').css('display', 'block');
                } else {
                    $('#bloque2-1').css('display', 'block');
                    $('#bloque8').css('display', 'none');
                    $('#bloque6').css('display', 'none');
                    $('#bloque7').css('display', 'none');
                }*/
                $('#bloque_j').css('display', 'block');
                $('#bloque_p').css('display', 'none');
                $('#bloque_n').css('display', 'none');
                $('#bloque_h_otros').css('display', 'none');
                $('#bloque9').css('display', 'none');
                break;


            default:
                $('#bloque_h_otros').css('display', 'none');
                $('#bloque1').css('display', 'none');
                $('#bloque2').css('display', 'none');
                $('#bloque2-1').css('display', 'none');
                $('#bloque3').css('display', 'none');
                $('#bloque4').css('display', 'none');
                $('#bloque_n').css('display', 'none');
                $('#bloque6').css('display', 'none');
                $('#bloque7').css('display', 'none');
                $('#bloque8').css('display', 'none');
                $('#bloque9').css('display', 'none');
        }

    });

    // Activar asignaci??n de estudiantes
    $('#btn_asignar_estudiante').click(function() {
        asignararEstudiantesConfirm();
    });

    $('#cmb_unidad_dis').change(function () {
        $('#cmb_paralelo').val('0');
        $('#cmb_horario').val('0');
        $('#cmb_jornada').val('0');
        $('#cmb_materia').val('0');
        
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        
        switch ($('#cmb_unidad_dis').val()) {
            case "1":
                if ($('#cmb_tipo_asignacion').val() == 1) {
                    document.getElementById("cmb_materia").setAttribute("multiple","");
                    $('#bloque6').css('display', 'none');
                    $('#bloque2-1').css('display', 'block');
                    $('#bloque7').css('display', 'none');
                    $('#bloque8').css('display', 'none');
                } else if ($('#cmb_tipo_asignacion').val() >= 2 && $('#cmb_tipo_asignacion').val() <= 7){
                    document.getElementById("cmb_materia").removeAttribute("multiple");
                    $('#bloque6').css('display', 'none');
                    $('#bloque2-1').css('display', 'none');
                    $('#bloque7').css('display', 'none');
                    $('#bloque8').css('display', 'none');
                }
                break;

            case "2":
                if ($('#cmb_tipo_asignacion').val() == 1 || $('#cmb_tipo_asignacion').val() == 7) {
                    document.getElementById("cmb_materia").removeAttribute("multiple");
                    $('#bloque2-1').css('display', 'none');
                    $('#bloque6').css('display', 'block');
                    $('#bloque7').css('display', 'block');
                    $('#bloque8').css('display', 'block');
                }else if ($('#cmb_tipo_asignacion').val() >= 2 && $('#cmb_tipo_asignacion').val() <= 6){
                    document.getElementById("cmb_materia").removeAttribute("multiple");
                    $('#bloque6').css('display', 'none');
                    $('#bloque2-1').css('display', 'none');
                    $('#bloque7').css('display', 'none');
                    $('#bloque8').css('display', 'none');
                }
                //console.log('cmb_unidad_dis 2:');
                break;

        }
        //if ($('#cmb_tipo_asignacion').val() > 0) {
            var arrParams = new Object();
            arrParams.uaca_id = $(this).val();
            arrParams.paca_id = $('#cmb_periodo').val();
            arrParams.getmodalidad = true;
            requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                 setComboDataselect(data.modalidad, "cmb_modalidad","Todos");
                 setComboDataselect(data.periodomensualizado, "cmb_periodo_mensualizado","Todos");
                    /*var arrParams = new Object();
                    if (data.modalidad.length > 0) {
                        let mod_id = data.modalidad[0].id;
                        console.log("change");
                        arrParams.uaca_id = $('#cmb_unidad_dis').val();
                        arrParams.mod_id = mod_id;
                        arrParams.getjornada = true;
                        // requestHttpAjax(link, arrParams, function(response) {
                        //    if (response.status == "OK") {
                        //       data = response.message;
                        //       setComboDataselect(data.jornada, "cmb_jornada", "Todos");
                        //       var arrParams = new Object();
                        //       if (data.jornada.length > 0) {
                        //           arrParams.uaca_id = $('#cmb_unidad_dis').val();
                        //           arrParams.mod_id = mod_id;
                        //           arrParams.jornada_id = data.jornada[0].id;
                        //           arrParams.gethorario = true;
                        //           requestHttpAjax(link, arrParams, function(response) {
                        //               if (response.status == "OK") {
                        //                   data = response.message;
                        //                   setComboDataselect(data.horario, "cmb_horario", "Todos");
                        //               }
                        //           }, true);
                        //       }
                        //     }
                        //   }, false);
                    }//*/
                }
            }, true);
        //}

    });

    $('#cmb_modalidad').change(function () {
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        var unidad_academica = $('#cmb_unidad_dis').val();
        var tipo_asignacion = $('#cmb_tipo_asignacion').val();
        var cmb_modalidad = $('#cmb_modalidad').val();

        switch (cmb_modalidad)
        {

            case "1":
                //  console.log('cmb_modalidad :' + tipo_asignacion);
                if (tipo_asignacion != 2) {

                    if (unidad_academica == 1 || unidad_academica == 2) {
                        console.log('A :' + unidad_academica);
                        $('#bloque_n').css('display', 'block');
                    } else {
                        console.log('B :' + unidad_academica);
                        $('#bloque_n').css('display', 'none');
                    }
                    if (tipo_asignacion == "7") {
                        $('#bloque_n').css('display', 'none');
                    }
                }
                break;
            default :
                $('#bloque_n').css('display', 'none');
                break;

        }






        switch (unidad_academica)
        {
            case "2":
                // Posgrado
                var arrParams = new Object();
                arrParams.uaca_id = unidad_academica;
                arrParams.mod_id = $(this).val();
                arrParams.getestudio = true;
                requestHttpAjax(link, arrParams, function (response) {
                    if (response.status == "OK") {
                        data = response.message;
                        setComboDataselect(data.carrera, "cmb_programa", "Todos");
                    }
                }, true);
                break;

        }

        var arrParams = new Object();
        arrParams.uaca_id = unidad_academica;
        arrParams.mod_id = $(this).val();
        arrParams.getjornada = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.jornada, "cmb_jornada", "Todos");
                var arrParams = new Object();
                if (data.jornada.length > 0) {
                    arrParams.uaca_id = $('#cmb_unidad_dis').val();
                    arrParams.mod_id = $('#cmb_modalidad').val();
                    arrParams.maca_id = $('#cmb_programa').val();
                    arrParams.jornada_id = data.jornada[0].id;
                    arrParams.gethorario = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.horario, "cmb_horario", "Todos");
                        }
                    }, true);
                }
            }
        }, false);
    });

    $('#cmb_unidad_dis_asignacion').change(function () {
        var link = $('#txth_base').val() + "/academico/distributivoacademico/index";
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                 setComboDataselect(data.modalidad, "cmb_modalidad","Todos");
                
            }
        }, true);
    });

    $('#cmb_jornada').change(function () {
        if($('#cmb_unidad_dis').val()==1){
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        var arrParams = new Object();
        arrParams.periodo_id = $('#cmb_periodo').val();
        arrParams.mod_id = $('#cmb_modalidad').val();
        // arrParams.jornada_id = $(this).val();
        arrParams.getasignatura = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignatura, "cmb_materia", "Todos");
            }
        }, true);
    }

        // cambios para mostrar el horario del paralelo
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidad_dis').val();
        arrParams.mod_id = $('#cmb_modalidad').val();
        arrParams.maca_id = $('#cmb_programa').val();
        arrParams.jornada_id = $(this).val();
        arrParams.gethorario = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.horario, "cmb_horario", "Todos");
               }
        }, true);
     });

    $('#btn_buscarData_dist').click(function () {
        searchModules();
    });

    $('#cmb_programa').change(function () {
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        document.getElementById("cmb_paralelo").value = 0;
        document.getElementById("cmb_horario").value = 0;
        document.getElementById("cmb_materia").value = 0;
        var arrParams = new Object();
        arrParams.maca_id = $('#cmb_programa').val();
        arrParams.getasignaturapos = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignaturapos, "cmb_materia", "Todos");
            }
        }, true);
    });
    // cambios para mostrar el horario del paralelo
    $('#cmb_paralelo').change(function () {
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidad_dis').val();
        arrParams.mpp_id = $('#cmb_paralelo').val();
        arrParams.maca_id = $('#cmb_programa').val();
        arrParams.gethorario = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.horario, "cmb_horario", "Todos");
            }
        }, true);
    });

    new SlimSelect({
      select: '#cmb_materia',
      closeOnSelect: false,
      searchFocus: false,
      showContent: 'down'
      //hideSelectedOption: true
    });

    /*new SlimSelect({
      select: '#cmb_objmods',
      closeOnSelect: false,
      searchFocus: false,
      //hideSelectedOption: true
    })*/
});

$('#cmb_materia').change(function () {
    $('#cmb_horario').val('0');
    if ($('#cmb_unidad_dis').val() == 1) {
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        var arrParams = new Object();
        arrParams.asig_id = $('#cmb_materia').val();
        arrParams.paca_id = $('#cmb_periodo').val();
        arrParams.mod_id = $('#cmb_modalidad').val();
        arrParams.getparalelo = true;
        arrParams.getparaleloposgrado = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.paralelo, "cmb_paralelo", "Todos");
            }
        });
    }else{
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        var arrParams = new Object();
        arrParams.asig_id = $('#cmb_materia').val();
        arrParams.paca_id = $('#cmb_periodo').val();
        arrParams.mod_id = $('#cmb_modalidad').val();
        arrParams.maca_id = $('#cmb_programa').val();
        arrParams.getparaleloposgrado = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.paralelo, "cmb_paralelo", "Todos");
            }
        });
    }
});

/*$('#cmb_horario').change(function () {
    if ($('#cmb_unidad_dis').val() == 2) {
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        var arrParams = new Object();
        arrParams.hora_id = $('#cmb_horario').val()
        arrParams.getparaleloposgrado = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.paralelo, "cmb_paralelo", "Todos");
            }
        });
    }
});*/

// Recarga la Grid de Productos si Existe
function recargarGridItem() {
    var tGrid = 'TbG_Data';
    if (sessionStorage.grid_asignacion_list) {
        var arr_Grid = JSON.parse(sessionStorage.dts_asignacion_list);
        if (arr_Grid.length > 0) {
            $('#' + tGrid + ' > tbody').html("");
            for (var i = 0; i < arr_Grid.length; i++) {
                $('#' + tGrid + ' > tbody:last-child').append(retornaFila(i, arr_Grid, tGrid, true));
            }
        }
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

function searchModules() {
    var search    = $("#txt_buscarData").val();
    var unidad    = $('#cmb_unidad_dis_asignacion option:selected').val();
    var periodo   = $('#cmb_periodo_asignacion option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var materia   = $('#cmb_materia option:selected').val();
    var jornada   = $('#cmb_jornada option:selected').val();
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Tbg_Distributivo_Acagra').PbGridView('applyFilterData', {'search':search,'unidad': unidad,'periodo': periodo,'modalidad': modalidad,'materia': materia,'jornada': jornada});
        setTimeout(hideLoadingPopup, 2000);
    }


    /*var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search    = $("#txt_buscarData").val();
    arrParams.unidad    = $("#cmb_unidad_dis_asignacion").val();
    arrParams.periodo   = $("#cmb_periodo_asignacion").val();
    arrParams.modalidad = $("#cmb_modalidad1").val();
    arrParams.materia   = $("#cmb_materia").val();
    arrParams.jornada   = $("#cmb_jornada").val();

    $("#Tbg_Distributivo_Acagra").PbGridView("applyFilterData", arrParams);*/
}

function showListStudents(id) {
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/new/" + id;
    window.location = link;
}

function showListStudentsPosgrado(id) {
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/newposgrado/" + id;
    window.location = link;
}

function edit() {
    var link = $('#txth_base').val() + "/academico/distributivoacademico/editcab" + "?id=" + $("#txth_ids").val();
    window.location = link;
}

function reversar() {
    var link = $('#txth_base').val() + "/academico/distributivoacademico/reversar" + "?id=" + $("#txth_ids").val();
    window.location = link;
}

function editcab(id) {
//    var arrParams = new Object();
//    arrParams.PBgetFilter = true;
    //   arrParams.search = $("#txt_buscarData").val();
    //   arrParams.unidad = $("#cmb_unidad_dis").val();
    //   arrParams.periodo = $("#cmb_periodo").val();
    //   arrParams.modalidad = $("#cmb_modalidad").val();
    //   arrParams.materia = $("#cmb_materia").val();
    //  arrParams.jornada = $("#cmb_jornada").val();
    // $("#Tbg_Distributivo_Aca").PbGridView("applyFilterData", arrParams);
    var codigo = $('#txth_cabid').val();
    console.log('entra' + codigo);

    var link = $('#txth_base').val() + "/academico/distributivoacademico/editcab/" + codigo;
    window.location = link;
}


function update() {
    var link = $('#txth_base').val() + "/academico/distributivoacademico/update";
    var arrParams = new Object();
    arrParams.id = $('#txth_ids').val();
    arrParams.profesor = $('#cmb_profesor').val();
    arrParams.unidad = $('#cmb_unidad_dis').val();
    arrParams.modalidad = $('#cmb_modalidad').val();
    arrParams.periodo = $('#cmb_periodo').val();
    arrParams.jornada = $('#cmb_jornada').val();
    arrParams.materia = $('#cmb_materia').val();
    arrParams.horario = $("#cmb_horario option:selected").text();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function () {
                    var link = $('#txth_base').val() + "/academico/distributivoacademico/index";
                    window.location = link;
                }, 1000);
            }
        }, true);
    }
}

function save() {
    console.log('ingresa1');
    var link = $('#txth_base').val() + "/academico/distributivoacademico/save";
    var arrParams = new Object();
    arrParams.profesor = $('#cmb_profesor').val();
    arrParams.periodo = $('#cmb_periodo').val();
    console.log("Periodo:"+arrParams.periodo);
    /** Session Storage **/
    if (sessionStorage.dts_asignacion_list) {
        console.log('ingresa3');
        var arr_Grid = JSON.parse(sessionStorage.dts_asignacion_list);
        //console.log(arr_Grid.length );
        //var conta = arr_Grid;

        // alert (conta);
        if (arr_Grid.length > 0) {
            console.log('ingresa4');
            console.log('session', sessionStorage.dts_asignacion_list);
            arrParams.grid_docencia = sessionStorage.dts_asignacion_list;
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function (response) {
                    showAlert(response.status, response.label, response.message);
                    if (response.status == "OK") {
                        //loadSessionCampos('dts_asignacion_list', '', '', '');
                        sessionStorage.removeItem('dts_asignacion_list');
                        setTimeout(function () {
                            var link = $('#txth_base').val() + "/academico/distributivocabecera/index";
                            window.location = link;
                        }, 1000);
                    }
                }, true);
            }
        } else {
            showAlert('NO_OK', 'error', {"wtmessage": "No Existen datos agregados", "title": 'Informaci??n'});
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": "No Existen datos agregados", "title": 'Informaci??n'});
    }
}

function eliminarbloque(id) {
    var link = $('#txth_base').val() + "/academico/distributivoacademico/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            window.location.href = $('#txth_base').val() + "/academico/bloqueacademico/index";
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}


function eliminarItems(val, daca_id, TbGtable) {
    console.log('session', sessionStorage.dts_asignacion_list);
    var ids = "";

    //var count=0;
    if (sessionStorage.dts_asignacion_list) {
        var Grid = JSON.parse(sessionStorage.dts_asignacion_list);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function () {
                ids = $(this).find("td").eq(0).html();
                if (ids == val) {
                    var array = findAndRemove(Grid, 'Id', val);
                    sessionStorage.dts_asignacion_list = JSON.stringify(array);
                    //if (count==0){sessionStorage.removeItem('detalleGrid')}
                    $(this).remove();
                }
            });
        }
    }
    if (daca_id != 0) {
        var arrParams = new Object();
        arrParams.id=val;
        arrParams.daca_id=daca_id;

        var link = $('#txth_base').val() + "/academico/distributivoacademico/delete";
        requestHttpAjax(link, arrParams, function (response) {
         showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location = $('#txth_base').val() + "/academico/distributivoacademico/editcab/" + daca_id;
                }, 2000);
            }else{
                setTimeout(function() {
                    window.location = $('#txth_base').val() + "/academico/distributivoacademico/editcab/" + daca_id;
                }, 2000);
            }
            
        }, true);
    }
}

function test() {

    $.post('test', {someData: 'pppp'}, function (response) {
        // append the new row to the top of our table
        $('#daca_id').find('tbody').prepend(response);
    });

}

function actualizar() {
    // console.log('ingresa1');
    var link = $('#txth_base').val() + "/academico/distributivoacademico/actualizar";
    var arrParams = new Object();
    arrParams.profesor = $('#txth_proid').val();
    arrParams.periodo = $('#cmb_periodo').val();
    arrParams.id = $('#txth_cabid').val();
    //console.log('profesor: ' + arrParams.profesor);
    //console.log('cabid: ' + arrParams.id);
    //console.log('periodo: ' + arrParams.periodo);
    /** Session Storage **/
    if (sessionStorage.dts_asignacion_list) {
        // console.log('ingresa3');
        var arr_Grid = JSON.parse(sessionStorage.dts_asignacion_list);
        //console.log(arr_Grid.length );
        //var conta = arr_Grid;

        // alert (conta);
        if (arr_Grid.length > 0) {
            //  console.log('ingresa4');
            arrParams.grid_docencia = sessionStorage.dts_asignacion_list;
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function (response) {
                    showAlert(response.status, response.label, response.message);
                    //     console.log('ingresa5');
                    if (response.status == "OK") {
                        //loadSessionCampos('dts_asignacion_list', '', '', '');
                        //    console.log('ingresa6');
                        sessionStorage.removeItem('dts_asignacion_list');
                        setTimeout(function () {
                            var link = $('#txth_base').val() + "/academico/distributivocabecera/index";
                            window.location = link;
                        }, 1000);
                    }
                }, true);
            }
        } else {
            showAlert('NO_OK', 'error', {"wtmessage": "No Existen datos agregados", "title": 'Informaci??n'});
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": "No Existen datos agregados", "title": 'Informaci??n'});
    }
}

function exportExcel() {
    var search    = $("#txt_buscarData").val();
    var unidad    = $('#cmb_unidad_dis_asignacion option:selected').val();
    var periodo   = $('#cmb_periodo_asignacion option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var materia   = $('#cmb_materia option:selected').val();
    var jornada   = $('#cmb_jornada option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/distributivoacademico/exportexcel?" +
            "search=" + search +
            "&unidad=" + unidad +
            "&modalidad=" + modalidad +
            "&periodo=" + periodo +
            "&asignatura=" + materia +
            "&jornada=" + jornada;
}

function exportPdf() {
    var search    = $("#txt_buscarData").val();
    var unidad    = $('#cmb_unidad_dis_asignacion option:selected').val();
    var periodo   = $('#cmb_periodo_asignacion option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();
    var materia   = $('#cmb_materia option:selected').val();
    var jornada   = $('#cmb_jornada option:selected').val();
    window.location.href = $('#txth_base').val() + "/academico/distributivoacademico/exportpdf?pdf=1" +
            "&search=" + search +
            "&unidad=" + unidad +
            "&modalidad=" + modalidad +
            "&periodo=" + periodo +
            "&asignatura=" + materia +
            "&jornada=" + jornada;
}

/**  Asignaci??n  **/
function addAsignacion(opAccion) {


    var tGrid = 'TbG_Data';
    var tasi_id = $("#cmb_tipo_asignacion").val();
    var uni_id = $("#cmb_unidad_dis").val();
    // nuevos campos
    //var pro_id = $("#cmb_profesor").val();
    //var paca_id = $("#cmb_periodo").val();

    if ($("#cmb_tipo_asignacion").val() == 0 || $("#cmb_profesor").val() == 0 || $("#cmb_periodo").val() == 0) {
        fillDataAlert();
        return;
    }else {
        // Tipo de asignaci??n tutor??a
        if ((tasi_id == 2)) {
            var uni_id = $("#cmb_unidad_dis").val();
            var mod_id = $("#cmb_modalidad").val();
            if (uni_id == 1) {
                var paca_id = $("#cmb_periodo").val();
            }

            console.log('tipo asig: ' + tasi_id + 'asig: ' + asi_id + 'horario: ');

            if (uni_id == 0 || mod_id == 0) {
                fillDataAlert();
                return;
            }
        }
        // Tipo de asignaci??n investigaci??n & vinculaci??n
        if ((tasi_id >= 3) && (tasi_id >= 4)) {
            var uni_id = $("#cmb_unidad_dis").val();
            var acca_id = $("#cmb_campo_amplio").val();
            
            console.log('tipo asig: ' + tasi_id + 'uni_id: ' + uni_id + ' acca_id: ' + acca_id);

            if (uni_id == 0 || acca_id == 0) {
                fillDataAlert();
                return;
            }
        }
        // Tipo de asignaci??n Otra asignaci??n
        if ((tasi_id == 6)) {
            var txt_horas_otros = $("#txt_horas_otros").val();
        }
        // Tipo de asignaci??n Docencia
        if ((tasi_id == 1)) {
            var asi_id = $("#cmb_materia").val();
            var asi_name = $("#cmb_materia :selected").text();
            for (var i = 0; i < asi_id.length; i++) {
                console.log('asi array: '+asi_id[i]);                
                                 
                var uni_id = $("#cmb_unidad_dis").val();
                var mod_id = $("#cmb_modalidad").val();
                if (uni_id == 1) {
                    var paca_id = $("#cmb_periodo").val();
                    if(i==0){
                        asi_id[i];
                        asi_name[i];
                        var estado = 1;        
                    } else{
                        asi_id[i];
                        asi_name[i];
                        var estado = 0;
                    }
                } else {
                    //fechas inicio y fecha fin
                    var eaca_id = $("#cmb_programa").val();
                    asi_id;
                    var estado = 1;
                    var pame_id = $("#cmb_periodo_mensualizado").val();
                    var fecha_inicio = $("#txt_fecha_ini").val();
                    var fecha_fin = $("#txt_fecha_fin").val();
                }
                var jor_id = $("#cmb_jornada").val();
                
                var hor_id = $("#cmb_horario").val();
                var par_id = $("#cmb_paralelo").val();
                var hor_onl = $("#txt_num_estudiantes").val();
                if (hor_onl == "") {
                    hor_onl = "0";
                }

            }

                
                if (uni_id == 2) {
                    if (uni_id == 0 || mod_id == 0 || eaca_id == 0 || pame_id == 0 || jor_id == 0 || asi_id == 0 || hor_id == 0 || par_id == 0 || fecha_inicio == '' || fecha_fin == '') {
                        fillDataAlert();
                        return;
                    }
                } else {
                    if (uni_id == 0 || mod_id == 0 || paca_id == 0 || jor_id == 0 || asi_id == 0 || hor_id == 0 || par_id == 0) {
                        fillDataAlert();
                        return;
                    }
                }
    
        }

        // Tipo de asignaci??n Docencia Tutor
        if ((tasi_id == 7)) {
            var uni_id = $("#cmb_unidad_dis").val();
            var mod_id = $("#cmb_modalidad").val();
            //  var jor_id = $("#cmb_jornada").val();
            var asi_id = $("#cmb_materia").val();
            // var hor_id = $("#cmb_horario").val();
            //   var par_id = $("#cmb_paralelo").val();
            // var hor_onl = $("#txt_num_estudiantes").val();
            var asi_id = $("#cmb_materia").val();
            //fechas inicio y fecha fin

            console.log('tipo asig: ' + tasi_id + 'asig: ' + asi_id);

            if (uni_id == 0 || mod_id == 0 || paca_id == 0 || asi_id == 0) {
                fillDataAlert();
                return;
            }
        }
        //Recorrer el session storage para verificar validaciones.

        var res = 0;
        res = validar(tasi_id, asi_id, hor_id, par_id, uni_id, mod_id, paca_id, pame_id, jor_id, txt_horas_otros);

        if (res == 10) {
            showAlert('NO_OK', 'error', {"wtmessage": "Ya existe esta asignaci??n, para el paralelo anterior.", "title": 'Informaci??n'});
        } else if (res == 1) {
            showAlert('NO_OK', 'error', {"wtmessage": "Ya existe esta asignaci??n.", "title": 'Informaci??n'});
        } else if (res == 2) {
            showAlert('NO_OK', 'error', {"wtmessage": "Ya existe el registro en el mismo horario para el mismo docente.", "title": 'Informaci??n'});
        } else if ($("#cmb_modalidad").val() == 1 && hor_onl == "0") {
            showAlert('NO_OK', 'error', {"wtmessage": "Debe ingresar n??mero de estudiantes.", "title": 'Informaci??n'});
        } else if ($("#cmb_modalidad").val() == 1 && hor_onl < 0) {
            showAlert('NO_OK', 'error', {"wtmessage": "Ingreso incorrecto del n??mero de estudiantes. No debe ingresar valores negativos", "title": 'Informaci??n'});
        } else if ($("#cmb_modalidad").val() == 1 && hor_onl > 100) {
            showAlert('NO_OK', 'error', {"wtmessage": "Ingreso incorrecto del n??mero de estudiantes. No debe ingresar mayor a 100 estudiantes", "title": 'Informaci??n'});
        } else if ((tasi_id == 6 && (txt_horas_otros == ""))) {
            showAlert('NO_OK', 'error', {"wtmessage": "Debe ingresar n??mero de horas.", "title": 'Informaci??n'});
        } else if (uni_id == 2 && (fecha_inicio == "" || fecha_fin == "")) {
            showAlert('NO_OK', 'error', {"wtmessage": "Debe ingresar fecha inicio y fecha fin.", "title": 'Informaci??n'});
        } else {

            

            if (opAccion != "edit") {
                //*********   Agregar materias *********
                var arr_Grid = new Array();
                if (sessionStorage.dts_asignacion_list) {
                    /*Agrego a la Sesion*/
                    arr_Grid = JSON.parse(sessionStorage.dts_asignacion_list);
                    var size = arr_Grid.length;
                    if (size > 0) {
                        if(tasi_id==1 && uni_id==1){
                        console.log('cuando se llena grado' + uni_id);
                            for (var i = 0; i < asi_id.length; i++) {
                                if (asi_id.length > 1 ) {
                                    if (i==0) {
                                        var asi_name_mssg = $("#cmb_materia :selected").text();
                                        var asi_nombre_mssg = asi_name_mssg.split('  ');
                                        showAlert('OK', 'OK', {"wtmessage": "La asignaci??n principal es: " + asi_nombre_mssg[0], "title": 'Informaci??n'});
                                    }
                                }
                                arr_Grid.push(objDistributivo(i));
                                sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                                addVariosItem(tGrid, arr_Grid, -1);
                            }
                            limpiarDetalle();
                            
                        }else if(tasi_id==1 && uni_id==2){
                            console.log('?????? ' + uni_id);
                            arr_Grid[size] = objDistributivo(size);
                            sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                            addVariosItem(tGrid, arr_Grid, -1);
                            limpiarDetalle();
                        }else{
                            arr_Grid[size] = objDistributivo(size);
                            sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                            addVariosItem(tGrid, arr_Grid, -1);
                            limpiarDetalle();
                            console.log('cuando se llena * 1');
                        }
                    } else {
                        /*Agrego a la Sesion*/
                        //Primer Items
                        arr_Grid[0] = objDistributivo(0);
                        sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                        addPrimerItem(tGrid, arr_Grid, 0);
                        limpiarDetalle();
                        console.log('cuando se llena ** 1');
                    }
                } else {
                    //No existe la Session
                    //Primer Items
                    if(tasi_id==1 && uni_id==1){
                        console.log('cuando se llena grado' + uni_id);
                        for (var i = 0; i < asi_id.length; i++) {
                            if (asi_id.length > 1 ) {
                                    if (i==0) {
                                        var asi_name_mssg = $("#cmb_materia :selected").text();
                                        var asi_nombre_mssg = asi_name_mssg.split('  ');
                                        showAlert('OK', 'OK', {"wtmessage": "La asignaci??n principal es: " + asi_nombre_mssg[0], "title": 'Informaci??n'});
                                        
                                    }
                                }
                            arr_Grid.push(objDistributivo(i));
                            sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                            addPrimerItem(tGrid, arr_Grid, i);
                        }
                        limpiarDetalle();
                        
                    }else if(tasi_id==1 && uni_id==2){
                        console.log('?????? ' + uni_id);
                        arr_Grid[0] = objDistributivo(0);
                        sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                        addPrimerItem(tGrid, arr_Grid, 0);
                        limpiarDetalle();
                    }else{
                        arr_Grid[0] = objDistributivo(0);
                        sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                        addPrimerItem(tGrid, arr_Grid, 0);
                        limpiarDetalle();
                        console.log('cuando se llena * 1');
                    }
                        
                }
            } else {
                //data edicion
            }
        }
        console.log('cuando se llena: ' + arr_Grid.length);
        console.log('session-add', sessionStorage.dts_asignacion_list);
    }
}

function validar(tasi_id, asi_id, hor_id, par_id, idUnidadAcademica, idModalidad, idPeriodo, idPeriodoAcadMen, idJornada, txt_horas_otros) {
    var arr_Grid1 = new Array();
    var estado = 0;

    if (sessionStorage.dts_asignacion_list) {
        arr_Grid1 = JSON.parse(sessionStorage.dts_asignacion_list);

        var size_arr = arr_Grid1.length;
        for (var i = 0; i <= size_arr; i++) {
            if (i < size_arr) {
                console.log('tasi_id:' + tasi_id);
                switch (tasi_id) {
                    case "1":
                        switch(idUnidadAcademica){
                            case "1":
                                if (arr_Grid1[i]['hor_id'] == hor_id) {
                                    estado = 10;
                                }
                                break;
                            case "2":
                                if (arr_Grid1[i]['hor_id'] == hor_id && arr_Grid1[i]['pame_id'] == idPeriodoAcadMen) {
                                    estado = 10;
                                }
                                break;
                        }
                        if ((arr_Grid1[i]['tasi_id'] == tasi_id)
                                && arr_Grid1[i]['uni_id'] == idUnidadAcademica
                                && arr_Grid1[i]['mod_id'] == idModalidad
                                && arr_Grid1[i]['paca_id'] == idPeriodo
                                && arr_Grid1[i]['pame_id'] == idPeriodoAcadMen
                                && arr_Grid1[i]['jor_id'] == idJornada
                                && arr_Grid1[i]['asi_id'] == asi_id
                                && arr_Grid1[i]['hor_id'] == hor_id
                                && arr_Grid1[i]['par_id'] == par_id) {
                            console.log('paralelo: ' + arr_Grid1[i]['par_id'] + '-' + par_id);
                            estado = 1;
                        }
                        break;
                    case "2":
                        if ((arr_Grid1[i]['tasi_id'] == tasi_id)
                                && arr_Grid1[i]['uni_id'] == idUnidadAcademica
                                && arr_Grid1[i]['mod_id'] == idModalidad
                                ) {
                            console.log('2');
                            estado = 1;
                        }
                        break;


                    case "7":
                        if ((arr_Grid1[i]['tasi_id'] == tasi_id)
                                && arr_Grid1[i]['uni_id'] == idUnidadAcademica
                                && arr_Grid1[i]['mod_id'] == idModalidad
                                && arr_Grid1[i]['asi_id'] == asi_id
                                ) {
                            console.log('7');
                            estado = 1;
                        }
                        break;

                    case "3":
                        if (arr_Grid1[i]['tasi_id'] == tasi_id) { //otros tipos de asignaciones
                            estado = 1;
                            console.log('3');
                        }
                        break;
                    case "4":
                        if (arr_Grid1[i]['tasi_id'] == tasi_id) { //otros tipos de asignaciones
                            estado = 1;
                            console.log('4');
                        }
                        break;
                    case "6":
                        if (arr_Grid1[i]['tasi_id'] == tasi_id) { //otros tipos de asignaciones
                            estado = 1;
                            console.log('4');
                        }
                        break;
                }

            }
        }
    }
    return estado;
}
function objDistributivo(indice) {
    var rowGrid = new Object();
    rowGrid.indice = NewGuid();

    rowGrid.daca_id = 0;
    rowGrid.tasi_id = $("#cmb_tipo_asignacion").val();
    rowGrid.tasi_name = $("#cmb_tipo_asignacion :selected").text();
    switch ($("#cmb_tipo_asignacion").val()) {
        case "1":
            var asi_id = $("#cmb_materia").val();
            var asi_name = $("#cmb_materia :selected").text();
            var asi_nombre =asi_name.split('  ');
            rowGrid.uni_id = $("#cmb_unidad_dis").val();
            rowGrid.uni_name = $("#cmb_unidad_dis :selected").text();
            rowGrid.acca_id = null;
            rowGrid.acca_name = 'N/A';
            rowGrid.mod_id = $("#cmb_modalidad").val();
            rowGrid.mod_name = $("#cmb_modalidad :selected").text();
            rowGrid.paca_id = $("#cmb_periodo").val();
            rowGrid.jor_id = $("#cmb_jornada").val();
            rowGrid.jor_name = $("#cmb_jornada :selected").text();
            rowGrid.asi_id = asi_id[indice];
            rowGrid.asi_name = asi_nombre[indice];
            rowGrid.asi_relacion = asi_id[0];
            rowGrid.hor_id = $("#cmb_horario").val();
            rowGrid.hor_name = $("#cmb_horario :selected").text();
            rowGrid.par_id = $("#cmb_paralelo").val();
            rowGrid.num_estudiantes = $("#txt_num_estudiantes").val();
            if (indice <1) {
                rowGrid.estado = 1;
            }else{
                rowGrid.estado = 0;
            }
            if ($("#cmb_unidad_dis").val() == 2) {
                rowGrid.asi_id = $("#cmb_materia").val();
                rowGrid.asi_name = $("#cmb_materia :selected").text();
                rowGrid.asi_relacion = $("#cmb_materia").val();
                rowGrid.estado = 1;
                rowGrid.pame_id = $("#cmb_periodo_mensualizado").val();
                rowGrid.fecha_inicio = $("#txt_fecha_ini").val();
                rowGrid.fecha_fin = $("#txt_fecha_fin").val();
                rowGrid.programa = $("#cmb_programa").val();

            } else if ($("#cmb_unidad_dis").val() == 1) {
                rowGrid.asi_id = asi_id[indice];
                rowGrid.asi_name = asi_nombre[indice];
                rowGrid.asi_relacion = asi_id[0];
                if (indice <1) {
                rowGrid.estado = 1;
                }else{
                    rowGrid.estado = 0;
                }
                rowGrid.fecha_inicio = 'N/A';
                rowGrid.fecha_fin = 'N/A';
            } else {
                rowGrid.fecha_inicio = 'N/A';
                rowGrid.fecha_fin = 'N/A';
            }
            rowGrid.txt_horas_otros = '0';
            break;
        
        case "2":
            rowGrid.uni_id = $("#cmb_unidad_dis").val();
            rowGrid.uni_name = $("#cmb_unidad_dis :selected").text();
            rowGrid.acca_id = null;
            rowGrid.acca_name = 'N/A';
            rowGrid.mod_id = $("#cmb_modalidad").val();
            rowGrid.mod_name = $("#cmb_modalidad :selected").text();
            rowGrid.paca_id = $("#cmb_periodo").val();
            rowGrid.jor_id = $("#cmb_jornada").val();
            rowGrid.jor_name = 'N/A';
            rowGrid.asi_id = null;
            rowGrid.asi_name = 'N/A';
            rowGrid.asi_relacion = null;
            rowGrid.hor_id = $("#cmb_horario").val();
            rowGrid.hor_name = 'N/A';
            rowGrid.par_id = $("#cmb_paralelo").val();
            rowGrid.num_estudiantes = '0';
            rowGrid.estado = 1;
            rowGrid.fecha_inicio = 'N/A';
            rowGrid.fecha_fin = 'N/A';
            rowGrid.txt_horas_otros = '0';
            rowGrid.programa = null;
            rowGrid.pame_id = null;
            break;
        
        case "3":
            rowGrid.uni_id = $("#cmb_unidad_dis").val();
            rowGrid.uni_name = $("#cmb_unidad_dis :selected").text();
            rowGrid.acca_id = $("#cmb_campo_amplio").val();
            rowGrid.acca_name = $("#cmb_campo_amplio :selected").text();
            rowGrid.mod_id = $("#cmb_modalidad").val();
            rowGrid.mod_name = 'N/A';
            rowGrid.paca_id = $("#cmb_periodo").val();
            rowGrid.jor_id = $("#cmb_jornada").val();
            rowGrid.jor_name = 'N/A';
            rowGrid.asi_id = null;
            rowGrid.asi_name = 'N/A';
            rowGrid.asi_relacion = null;
            rowGrid.hor_id = $("#cmb_horario").val();
            rowGrid.hor_name = 'N/A';
            rowGrid.par_id = $("#cmb_paralelo").val();
            rowGrid.num_estudiantes = '0';
            rowGrid.estado = 1;
            rowGrid.fecha_inicio = 'N/A';
            rowGrid.fecha_fin = 'N/A';
            rowGrid.txt_horas_otros = '0';
            rowGrid.programa = null;
            rowGrid.pame_id = null;
            break;

        case "4":
            rowGrid.uni_id = $("#cmb_unidad_dis").val();
            rowGrid.uni_name = $("#cmb_unidad_dis :selected").text();
            rowGrid.acca_id = $("#cmb_campo_amplio").val();
            rowGrid.acca_name = $("#cmb_campo_amplio :selected").text();
            rowGrid.mod_id = $("#cmb_modalidad").val();
            rowGrid.mod_name = 'N/A';
            rowGrid.paca_id = $("#cmb_periodo").val();
            rowGrid.jor_id = $("#cmb_jornada").val();
            rowGrid.jor_name = 'N/A';
            rowGrid.asi_id = null;
            rowGrid.asi_name = 'N/A';
            rowGrid.asi_relacion = null;
            rowGrid.hor_id = $("#cmb_horario").val();
            rowGrid.hor_name = 'N/A';
            rowGrid.par_id = $("#cmb_paralelo").val();
            rowGrid.num_estudiantes = '0';
            rowGrid.estado = 1;
            rowGrid.fecha_inicio = 'N/A';
            rowGrid.fecha_fin = 'N/A';
            rowGrid.txt_horas_otros = '0';
            rowGrid.programa = null;
            rowGrid.pame_id = null;
            break;

        case "6":
            rowGrid.uni_id = $("#cmb_unidad_dis").val();
            rowGrid.uni_name = 'N/A';
            rowGrid.acca_id = $("#cmb_campo_amplio").val();
            rowGrid.acca_name = 'N/A';
            rowGrid.mod_id = $("#cmb_modalidad").val();
            rowGrid.mod_name = 'N/A';
            rowGrid.paca_id = $("#cmb_periodo").val();
            rowGrid.jor_id = $("#cmb_jornada").val();
            rowGrid.jor_name = 'N/A';
            rowGrid.asi_id = $("#cmb_materia").val();
            rowGrid.asi_name = 'N/A';
            rowGrid.asi_relacion = 'N/A';
            rowGrid.hor_id = $("#cmb_horario").val();
            rowGrid.hor_name = 'N/A';
            rowGrid.par_id = $("#cmb_paralelo").val();
            rowGrid.num_estudiantes = '0';
            rowGrid.estado = 1;
            rowGrid.fecha_inicio = 'N/A';
            rowGrid.fecha_fin = 'N/A';
            rowGrid.txt_horas_otros = $("#txt_horas_otros").val();
            rowGrid.programa = null;
            rowGrid.pame_id = null;
            break;


        case "7":
            rowGrid.uni_id = $("#cmb_unidad_dis").val();
            rowGrid.uni_name = $("#cmb_unidad_dis :selected").text();
            rowGrid.acca_id = null;
            rowGrid.acca_name = 'N/A';
            rowGrid.mod_id = $("#cmb_modalidad").val();
            rowGrid.mod_name = $("#cmb_modalidad :selected").text();
            rowGrid.paca_id = $("#cmb_periodo").val();
            rowGrid.jor_id = $("#cmb_jornada").val();
            rowGrid.jor_name = $("#cmb_jornada :selected").text();
            rowGrid.asi_id = $("#cmb_materia").val();
            rowGrid.asi_name = $("#cmb_materia :selected").text();
            rowGrid.asi_relacion = $("#cmb_materia").val();
            rowGrid.hor_id = $("#cmb_horario").val();
            rowGrid.hor_name = $("#cmb_horario :selected").text();
            rowGrid.par_id = $("#cmb_paralelo").val();
            rowGrid.num_estudiantes = $("#txt_num_estudiantes").val();
            rowGrid.estado = 1;
            if ($("#cmb_unidad_dis").val() == 2) {
                rowGrid.pame_id = $("#cmb_periodo_mensualizado").val();
                rowGrid.fecha_inicio = $("#txt_fecha_ini").val();
                rowGrid.fecha_fin = $("#txt_fecha_fin").val();
                rowGrid.programa = $("#cmb_programa").val();
            } else {
                rowGrid.fecha_inicio = 'N/A';
                rowGrid.fecha_fin = 'N/A';
            }
            rowGrid.txt_horas_otros = '0';
            break;

        default :
            rowGrid.uni_id = $("#cmb_unidad_dis").val();
            rowGrid.uni_name = 'N/A';
            rowGrid.acca_id = null;
            rowGrid.acca_name = 'N/A';
            rowGrid.mod_id = $("#cmb_modalidad").val();
            rowGrid.mod_name = 'N/A';
            rowGrid.paca_id = $("#cmb_periodo").val();
            rowGrid.jor_id = $("#cmb_jornada").val();
            rowGrid.jor_name = 'N/A';
            rowGrid.asi_id = null;
            rowGrid.asi_name = 'N/A';
            rowGrid.asi_relacion = null;
            rowGrid.hor_id = $("#cmb_horario").val();
            rowGrid.hor_name = 'N/A';
            rowGrid.par_id = $("#cmb_paralelo").val();
            rowGrid.num_estudiantes = '0';
            rowGrid.estado = 1;
            rowGrid.fecha_inicio = 'N/A';
            rowGrid.fecha_fin = 'N/A';
            rowGrid.txt_horas_otros = '0';
            rowGrid.programa = null;
            rowGrid.pame_id = null;
            break;
    }



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
    i = ($('#' + TbGtable + ' tr').length) - 1;
    $('#' + TbGtable + ' tr:last').after(retornaFila(i, lista, TbGtable, true));
}

function limpiarDetalle() {
    //$('#cmb_unidad_dis option[value="0"]').attr("selected", true);
    document.getElementById("cmb_unidad_dis").value = 0;
    document.getElementById("cmb_unidad_dis").value = 0;
    document.getElementById("cmb_periodo_mensualizado").value = 0;
    document.getElementById("cmb_campo_amplio").value = 0;
    //document.getElementById("cmb_periodo").value = 0;

    document.getElementById("cmb_jornada").value = 0;
    document.getElementById("cmb_materia").value = 0;
    document.getElementById("cmb_horario").value = 0;
    document.getElementById("cmb_paralelo").value = 0;
    document.getElementById("txt_num_estudiantes").value = "0";
    document.getElementById("txt_fecha_ini").value = "";
    document.getElementById("txt_fecha_fin").value = "";
    document.getElementById("txt_horas_otros").value = "";
}

function fillDataAlert() {
    var type = "alert";
    var label = "error";
    var status = "NO_OK";
    var messagew = {};
    messagew = {
        "wtmessage": "Llene todos los campos obligatorios", //objLang.Must_be_Fill_all_information_in_fields_with_label___,
        "title": objLang.Error,
        "acciones": [{
                "id": "btnalert",
                "class": "btn-primary clclass praclose",
                "value": objLang.Accept
            }],
    };
    showResponse(type, status, label, messagew);
}

function retornaFila(c, Grid, TbGtable, op) {
    //var RutaImagenAccion='ruta IMG'//$('#txth_rutaImg').val();
    var strFila = "";
    strFila += '<td style="display:none; border:none;">' + Grid[c]['indice'] + '</td>';
    strFila += '<td style="display:none; border:none;">' + Grid[c]['daca_id'] + '</td>';
    strFila += '<td>' + Grid[c]['tasi_name'] + '</td>';
    strFila += '<td>' + Grid[c]['asi_name'] + '</td>';
    strFila += '<td>' + Grid[c]['uni_name'] + '</td>';
    strFila += '<td>' + Grid[c]['acca_name'] + '</td>';

    strFila += '<td style="display:none; border:none;">' + '</td>';
    strFila += '<td>' + Grid[c]['mod_name'] + '</td>';
    strFila += '<td>' + Grid[c]['num_estudiantes'] + '</td>';

    strFila += '<td style="display:none; border:none;">' + '</td>';
    strFila += '<td>' + Grid[c]['jor_name'] + '</td>';
    strFila += '<td>' + Grid[c]['hor_name'] + '</td>';
    strFila += '<td>' + Grid[c]['fecha_inicio'] + '</td>';
    strFila += '<td>' + Grid[c]['fecha_fin'] + '</td>';
    strFila += '<td>' + Grid[c]['txt_horas_otros'] + '</td>';
    strFila += '<td class="text-center">';//??Est?? seguro de eliminar este elemento?
    strFila += '<a onclick="eliminarItems(\'' + Grid[c]['indice'] + '\',0 ,\'' + TbGtable + '\')" ><span class="glyphicon glyphicon-trash"></span></a>';
    //<span class='glyphicon glyphicon-remove'></span>
    strFila += '</td>';

    if (op) {
        strFila = '<tr>' + strFila + '</tr>';
    }
    return strFila;
}

function findAndRemove(array, property, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value || array[i]['indice'] == value) {
            array.splice(i, 1);
        }
    }
    return array;
}


function NewGuid() {
    var sGuid = "";
    for (var i = 0; i < 32; i++) {
        sGuid += Math.floor(Math.random() * 0xF).toString(0xF);
    }
    return sGuid;
}


function insertarEst(){
    $('#confirmModal').modal('toggle');
}


function asignararEstudiantesConfirm(){
    // S??lo llama a la funci??n del controlador}
    var link = $('#txth_base').val() + "/academico/distributivoacademico/saveasignarestudiante";
    var arrParams = new Object();
    arrParams.paca_id = $('#cmb_periodo_modal option:selected').val();//JLC: 22 ABRIL 2022

    
        requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
            
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/distributivoacademico/index";
                }, 3000);
            
        }, true);
    
}

//JLC: 22 ABRIL 2022
function limpiarBuscador(){
    window.location.href = $('#txth_base').val() + "/academico/distributivoacademico/index";
}
//JLC: 22 ABRIL 2022