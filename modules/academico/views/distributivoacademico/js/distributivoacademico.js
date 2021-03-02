$(document).ready(function() {
    recargarGridItem();
    $('#cmb_unidad_dis').change(function() {
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();
        if ($('#cmb_tipo_asignacion').val()!=7) {
          
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad", "Todos");
                var arrParams = new Object();                                               
                if (data.modalidad.length > 0) {
                    let mod_id = data.modalidad[0].id;                    
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
                }//                                   
            }
        }, false);
    }
        
        if ($('#cmb_unidad_dis').val() == 2) {
            $('#bloque6').css('display', 'block');
            $('#bloque2-1').css('display', 'none');
        } else {
            $('#bloque6').css('display', 'none');
            $('#bloque2-1').css('display', 'block');
        }
       
        
    });
    
    $('#cmb_modalidad').change(function() {
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        if ($('#cmb_unidad_dis').val()==2) {
             // Posgrado
            var arrParams = new Object();
            arrParams.uaca_id = $('#cmb_unidad_dis').val();
            arrParams.mod_id = $(this).val();                
            arrParams.getestudio = true;
            requestHttpAjax(link, arrParams, function(response) {
                if (response.status == "OK") {
                    data = response.message;
                    setComboDataselect(data.carrera, "cmb_programa", "Todos");
                }
            }, true);    
        } else {
            var arrParams = new Object();       
            arrParams.mod_id = $(this).val(); 
            arrParams.getperiodo = true;
            requestHttpAjax(link, arrParams, function(response) {
                if (response.status == "OK") {
                        data = response.message;
                        setComboDataselect(data.periodo, "cmb_periodo", "Todos");
                }
            }, true);
        }
        
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidad_dis').val();
        arrParams.mod_id = $(this).val();        
        arrParams.getjornada = true;
        requestHttpAjax(link, arrParams, function(response) {
              if (response.status == "OK") {
                  data = response.message;
                  setComboDataselect(data.jornada, "cmb_jornada", "Todos");
                  var arrParams = new Object();
                  if (data.jornada.length > 0) {
                      arrParams.uaca_id = $('#cmb_unidad_dis').val();
                      arrParams.mod_id = $('#cmb_modalidad').val();
                      arrParams.jornada_id = data.jornada[0].id;
                      arrParams.gethorario = true;
                      requestHttpAjax(link, arrParams, function(response) {
                          if (response.status == "OK") {
                              data = response.message;
                              setComboDataselect(data.horario, "cmb_horario", "Todos");
                          }
                      }, true);
                  }
              }
        }, false);                     
        
        if ($('#cmb_unidad_dis').val() == 1 && $(this).val()==1) {
            $('#bloque5').css('display', 'block');
        } else {
            $('#bloque5').css('display', 'none');
        }

        if ($('#cmb_unidad_dis').val() == 2 && $(this).val()==1) {
            $('#bloque7').css('display', 'block');
           
        } else {
            $('#bloque7').css('display', 'none');
           
        }

       
    });
       
   // $('#cmb_jornada').change(function() {
   //     var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
   //     var arrParams = new Object();
   //     arrParams.periodo_id = $('#cmb_periodo').val();
   //     arrParams.jornada_id = $(this).val();
   //     arrParams.getasignatura = true;
   //     requestHttpAjax(link, arrParams, function(response) {
   //         if (response.status == "OK") {
   //             data = response.message;
   //             setComboDataselect(data.asignatura, "cmb_materia", "Todos"); 
   //         }
   //     }, true);
   //     var arrParams = new Object();
    //    arrParams.uaca_id = $('#cmb_unidad_dis').val();
    //    arrParams.mod_id = $('#cmb_modalidad').val();
    //    arrParams.jornada_id = $(this).val();
    //    arrParams.gethorario = true;
    //    requestHttpAjax(link, arrParams, function(response) {
    //        if (response.status == "OK") {
    //            data = response.message;
    //            setComboDataselect(data.horario, "cmb_horario", "Todos");
    //        }
    //    }, true);           
   // });

    $('#btn_buscarData_dist').click(function() {
        searchModules();
    });
    
    $('#cmb_tipo_asignacion').change(function () {
        document.getElementById("cmb_profesor").disabled=true;
        tipo = $('#cmb_tipo_asignacion').val();        
        if ((tipo == 1) || ( tipo== 7)) {
            $('#bloque1').css('display', 'block');            
            $('#bloque2').css('display', 'block');
            $('#bloque2-1').css('display', 'block');
            $('#bloque3').css('display', 'block');
            $('#bloque4').css('display', 'block');
            if (tipo==7) {
                //setComboDataselect(data.modalidad, "cmb_modalidad", "Todos");
                
                var option_arr = "";
                option_arr += "<option value='0'> Todos </option>";
                option_arr += "<option value='1'> Online </option>";
                $("#cmb_modalidad").html(option_arr);
            }
        } else {
            $('#bloque1').css('display', 'none');            
            $('#bloque2').css('display', 'none');
            $('#bloque2-1').css('display', 'none');
            $('#bloque3').css('display', 'none');
            $('#bloque4').css('display', 'none');
        }
    });
    
    $('#cmb_profesor').change(function () {   
        var link = $('#txth_base').val() + "/academico/distributivocabecera/validadistributivo";
        var arrParams = new Object();
        arrParams.pro_id = $('#cmb_profesor').val();        
        arrParams.paca_id = $('#txth_idperiodo').val();
        arrParams.transaccion = "N";
        arrParams.getvalida = true;
        requestHttpAjax(link, arrParams, function(response) {                
            if (response.status == "NOOK") {                  
                showAlert(response.status, response.label, response.message);
                document.getElementById("cmb_tipo_asignacion").disabled=true;  
            } else {
                document.getElementById("cmb_tipo_asignacion").disabled=false;  
            }                              
        }, true);
        
    });  
    
    $('#cmb_programa').change(function () {   
        var link = $('#txth_base').val() + "/academico/distributivoacademico/new";
        var arrParams = new Object();
        arrParams.meun_id = $('#cmb_programa').val();                
        arrParams.getasignaturapos = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignaturapos, "cmb_materia", "Todos"); 
            }
        }, true);
    });  
        
});

$('#cmb_horario').change(function() {
    var link = $('#txth_base').val() + "/academico/distributivoacademico/new";        
    var arrParams = new Object();                                  
    arrParams.daho_id = $('#cmb_horario').val();                
    arrParams.getparalelo = true;    
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            data = response.message;
            setComboDataselect(data.paralelo, "cmb_paralelo", "Todos");
        }
    });
});

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
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_buscarData").val();
    arrParams.unidad = $("#cmb_unidad_dis").val();
    arrParams.periodo = $("#cmb_periodo").val();
    arrParams.modalidad = $("#cmb_modalidad").val();
    arrParams.materia = $("#cmb_materia").val();
    arrParams.jornada = $("#cmb_jornada").val();
    $("#Tbg_Distributivo_Aca").PbGridView("applyFilterData", arrParams);
}

function showListStudents(id) {
    var link = $('#txth_base').val() + "/academico/distributivoestudiante/index/" + id;
    window.location = link;
}

function edit() {
    var link = $('#txth_base').val() + "/academico/distributivoacademico/edit" + "?id=" + $("#txth_ids").val();
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
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
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
    arrParams.periodo = $('#txth_idperiodo').val();  
    console.log('ingresa2');
    /** Session Storage **/        
    if (sessionStorage.dts_asignacion_list) {
        console.log('ingresa3');
        var arr_Grid = JSON.parse(sessionStorage.dts_asignacion_list);
        //console.log(arr_Grid.length );
        //var conta = arr_Grid;
        
       // alert (conta);
        if (arr_Grid.length > 0) {   
            console.log('ingresa4');       
            console.log('session',sessionStorage.dts_asignacion_list);  
            arrParams.grid_docencia = sessionStorage.dts_asignacion_list;   
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function(response) {
                    showAlert(response.status, response.label, response.message);
                    if (response.status == "OK") {
                        //loadSessionCampos('dts_asignacion_list', '', '', '');                               
                        sessionStorage.removeItem('dts_asignacion_list');             
                        setTimeout(function() {
                            var link = $('#txth_base').val() + "/academico/distributivocabecera/index";
                            window.location = link;
                        }, 1000);
                    }
                }, true);
            }
        } else {
            showAlert('NO_OK', 'error', {"wtmessage": "No Existen datos agregados", "title": 'Información'});
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": "No Existen datos agregados", "title": 'Información'});
    }    
}

function actualizar() {
    console.log('ingresa1');
    var link = $('#txth_base').val() + "/academico/distributivoacademico/actualizar";
    var arrParams = new Object();
    arrParams.profesor = $('#txth_profid').val(); 
    arrParams.periodo = $('#txth_idperiodo').val(); 
    arrParams.id = $('#txth_cabid').val(); 
    console.log('ingresa2' + arrParams.id);
    /** Session Storage **/        
    if (sessionStorage.dts_asignacion_list) {
        console.log('ingresa3');
        var arr_Grid = JSON.parse(sessionStorage.dts_asignacion_list);
        //console.log(arr_Grid.length );
        //var conta = arr_Grid;
        
       // alert (conta);
        if (arr_Grid.length > 0) {   
            console.log('ingresa4');         
            arrParams.grid_docencia = sessionStorage.dts_asignacion_list;   
            if (!validateForm()) {
                requestHttpAjax(link, arrParams, function(response) {
                    showAlert(response.status, response.label, response.message);
                    console.log('ingresa5');
                    if (response.status == "OK") {
                        //loadSessionCampos('dts_asignacion_list', '', '', '');   
                        console.log('ingresa6');                            
                        sessionStorage.removeItem('dts_asignacion_list');             
                        setTimeout(function() {
                            var link = $('#txth_base').val() + "/academico/distributivocabecera/index";
                            window.location = link;
                        }, 1000);
                    }
                }, true);
            }
        } else {
            showAlert('NO_OK', 'error', {"wtmessage": "No Existen datos agregados", "title": 'Información'});
        }
    } else {
        showAlert('NO_OK', 'error', {"wtmessage": "No Existen datos agregados", "title": 'Información'});
    }    
}

function exportExcel() {
    var search = $('#txt_buscarData').val();
    var unidad = $('#cmb_unidad_dis').val();
    var modalidad = $('#cmb_modalidad').val();
    var periodo = $('#cmb_periodo').val();
    var asignatura = $('#cmb_materia').val();
    var jornada = $('#cmb_jornada').val();
    window.location.href = $('#txth_base').val() + "/academico/distributivoacademico/exportexcel?" +
        "search=" + search +
        "&unidad=" + unidad +
        "&modalidad=" + modalidad +
        "&periodo=" + periodo +
        "&asignatura=" + asignatura +
        "&jornada=" + jornada;
}

function exportPdf() {
    var search = $('#txt_buscarData').val();
    var unidad = $('#cmb_unidad_dis').val();
    var modalidad = $('#cmb_modalidad').val();
    var periodo = $('#cmb_periodo').val();
    var asignatura = $('#cmb_materia').val();
    var jornada = $('#cmb_jornada').val();
    window.location.href = $('#txth_base').val() + "/academico/distributivoacademico/exportpdf?pdf=1" +
        "&search=" + search +
        "&unidad=" + unidad +
        "&modalidad=" + modalidad +
        "&periodo=" + periodo +
        "&asignatura=" + asignatura +
        "&jornada=" + jornada;
}

/**  Asignación  **/
function addAsignacion(opAccion) {
    

    var tGrid = 'TbG_Data';
    var tasi_id = $("#cmb_tipo_asignacion").val(); 
    var uni_id = $("#cmb_unidad_dis").val();       
    if ((tasi_id==1) || (tasi_id==7)){       
        var uni_id = $("#cmb_unidad_dis").val();            
        var mod_id = $("#cmb_modalidad").val();        
        if (uni_id == 1) {
           var paca_id = $("#cmb_periodo").val();
        }
        else{
            var eaca_id = $("#cmb_programa").val();
        }
           var jor_id = $("#cmb_jornada").val();
        var asi_id = $("#cmb_materia").val();        
        var hor_id = $("#cmb_horario").val();        
        var par_id = $("#cmb_paralelo").val();
        var hor_onl = $("#txt_num_estudiantes").val();
        
        //fechas inicio y fecha fin
        var fecha_inicio = $("#txt_fecha_ini").val();
        var fecha_fin = $("#txt_fecha_fin").val();

        console.log('tipo asig: ' + tasi_id + 'asig: ' + asi_id + 'horario: ' + hor_id + 'paral: ' + par_id + 'unidad: ' + uni_id + mod_id + paca_id + jor_id);

        if (uni_id == 0 || mod_id == 0 || paca_id == 0 || jor_id == 0 || asi_id == 0 || hor_id == 0 || par_id == 0) {
            fillDataAlert();
            return;
        }
    }  
    //Recorrer el session storage para verificar validaciones.    
    var res = 0;
    res = validar(tasi_id,asi_id,hor_id,par_id,uni_id,mod_id,paca_id,jor_id);    
    if (res == 1) {         
        showAlert('NO_OK', 'error', {"wtmessage": "Ya existe esta asignación.", "title": 'Información'});            
    } else if   (res == 2) {         
        showAlert('NO_OK', 'error', {"wtmessage": "Ya existe el registro en el mismo horario para el mismo docente.", "title": 'Información'});         
    } else if (uni_id==1 && mod_id==1 && hor_onl=="") {
        showAlert('NO_OK', 'error', {"wtmessage": "Debe ingresar número de estudiantes.", "title": 'Información'});           
    } else if (uni_id==2 && mod_id==1 && (fecha_inicio=="" || fecha_fin=="")) {
            showAlert('NO_OK', 'error', {"wtmessage": "Debe ingresar fecha inicio y fecha fin.", "title": 'Información'});           
    } else {
        if (opAccion != "edit") {            
            //*********   Agregar materias *********
            var arr_Grid = new Array();
            if (sessionStorage.dts_asignacion_list) {
               

                /*Agrego a la Sesion*/
                arr_Grid = JSON.parse(sessionStorage.dts_asignacion_list);
                console.log ('cuando se llena' + arr_Grid);
                var size = arr_Grid.length;
                if (size > 0) {                                    
                    arr_Grid[size] = objDistributivo(size);
                    sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                    addVariosItem(tGrid, arr_Grid, -1);
                    limpiarDetalle();               
                } else {
                    /*Agrego a la Sesion*/
                    //Primer Items                   
                    arr_Grid[0] = objDistributivo(0);
                    sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                    addPrimerItem(tGrid, arr_Grid, 0);
                    limpiarDetalle();
                }
            } else {
                //No existe la Session
                //Primer Items
                arr_Grid[0] = objDistributivo(0);
                sessionStorage.dts_asignacion_list = JSON.stringify(arr_Grid);
                addPrimerItem(tGrid, arr_Grid, 0);
                limpiarDetalle();
            }
        } else {
            //data edicion
        }
    }
    console.log('session-add',sessionStorage.dts_asignacion_list);
}

function objDistributivo(indice) {
    var rowGrid = new Object();
    rowGrid.indice = NewGuid();   
    
    rowGrid.daca_id = 0;
    rowGrid.tasi_id = $("#cmb_tipo_asignacion").val();
    rowGrid.tasi_name = $("#cmb_tipo_asignacion :selected").text();
    if (($("#cmb_tipo_asignacion").val() == 1) || ($("#cmb_tipo_asignacion").val() == 7)){
        rowGrid.uni_id = $("#cmb_unidad_dis").val();    
        rowGrid.uni_name = $("#cmb_unidad_dis :selected").text();  
        rowGrid.mod_id = $("#cmb_modalidad").val();
        rowGrid.mod_name = $("#cmb_modalidad :selected").text();
        rowGrid.paca_id = $("#cmb_periodo").val();
        rowGrid.jor_id = $("#cmb_jornada").val();
        rowGrid.jor_name = $("#cmb_jornada :selected").text();
        rowGrid.asi_id = $("#cmb_materia").val();
        rowGrid.asi_name = $("#cmb_materia :selected").text();
        rowGrid.hor_id = $("#cmb_horario").val();
        rowGrid.hor_name = $("#cmb_horario :selected").text(); 
        rowGrid.par_id = $("#cmb_paralelo").val();
        rowGrid.num_estudiantes = $("#txt_num_estudiantes").val();
        rowGrid.fecha_inicio = $("#txt_fecha_ini").val();
        rowGrid.fecha_fin = $("#txt_fecha_fin").val();
    } else {
        rowGrid.uni_id = $("#cmb_unidad_dis").val();    
        rowGrid.uni_name = 'N/A';
        rowGrid.mod_id = $("#cmb_modalidad").val();
        rowGrid.mod_name = 'N/A';
        rowGrid.paca_id = $("#cmb_periodo").val();
        rowGrid.jor_id = $("#cmb_jornada").val();
        rowGrid.jor_name = 'N/A';
        rowGrid.asi_id = $("#cmb_materia").val();
        rowGrid.asi_name = 'N/A';
        rowGrid.hor_id = $("#cmb_horario").val();
        rowGrid.hor_name = 'N/A';
        rowGrid.par_id = $("#cmb_paralelo").val();
        rowGrid.num_estudiantes = 'N/A';   
        rowGrid.fecha_inicio = 'N/A';  
        rowGrid.fecha_fin =  'N/A';   
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
    document.getElementById("cmb_modalidad").value = 0;
    document.getElementById("cmb_periodo").value = 0;
    document.getElementById("cmb_jornada").value = 0;
    document.getElementById("cmb_materia").value = 0;
    document.getElementById("cmb_horario").value = 0;
    document.getElementById("cmb_paralelo").value = 0;          
    document.getElementById("txt_num_estudiantes").value = "";   
    document.getElementById("txt_fecha_ini").value = "";   
    document.getElementById("txt_fecha_fin").value = "";  
}

function fillDataAlert() {
    var type = "alert";
    var label = "error";
    var status = "NO_OK";
    var messagew = {};
    messagew = {
        "wtmessage": "Llene todos los campos obligatorios",//objLang.Must_be_Fill_all_information_in_fields_with_label___,
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

    strFila += '<td style="display:none; border:none;">' + '</td>';
    strFila += '<td>' + Grid[c]['mod_name'] + '</td>';
    strFila += '<td>' + Grid[c]['num_estudiantes'] + '</td>';
        
    strFila += '<td style="display:none; border:none;">' + '</td>';    
    strFila += '<td>' + Grid[c]['jor_name'] + '</td>';
    strFila += '<td>' + Grid[c]['hor_name'] + '</td>';
    
    strFila += '<td class="text-center">';//¿Está seguro de eliminar este elemento?   
    strFila += '<a onclick="eliminarItems(\'' + Grid[c]['indice'] + '\',\'' + TbGtable + '\')" ><span class="glyphicon glyphicon-trash"></span></a>';
    //<span class='glyphicon glyphicon-remove'></span>
    strFila += '</td>';

    if (op) {
        strFila = '<tr>' + strFila + '</tr>';
    }
    return strFila;
}

function eliminarItems(val, TbGtable) {
    console.log('session',sessionStorage.dts_asignacion_list);
    var ids = "";
    //var count=0;
    if (sessionStorage.dts_asignacion_list) {
        var Grid = JSON.parse(sessionStorage.dts_asignacion_list);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function () {
                ids = $(this).find("td").eq(0).html();               
                if (ids == val) {
                    var array = findAndRemove(Grid, 'indice', val);
                    sessionStorage.dts_asignacion_list = JSON.stringify(array);
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

function validar(tasi_id,asi_id,hor_id,par_id,idUnidadAcademica,idModalidad,idPeriodo,idJornada){
    var arr_Grid1 = new Array();
    var estado = 0;
    
    if (sessionStorage.dts_asignacion_list) {
        arr_Grid1 = JSON.parse(sessionStorage.dts_asignacion_list);       
        
        var size_arr = arr_Grid1.length;           
        for (var i=0; i<= size_arr; i++) {               
            if (i < size_arr) {
                if ((tasi_id ==1) || (tasi_id ==7) ) { // tipo de asignación docencia                    
                    // if ((arr_Grid1[i]['tasi_id'] == tasi_id) && (arr_Grid1[i]['asi_id'] == asi_id) && (arr_Grid1[i]['hor_id'] == hor_id) && (arr_Grid1[i]['par_id'] == par_id)) {                         
                    //     estado = 1;
                    //     return estado;
                    // } 
                    // if ((arr_Grid1[i]['tasi_id'] == tasi_id) && (arr_Grid1[i]['asi_id'] != asi_id) && (arr_Grid1[i]['hor_id'] == hor_id)) {                         
                    //     estado = 2;
                    //     return estado;
                    // }

                    if ((arr_Grid1[i]['tasi_id'] == tasi_id)
                        && arr_Grid1[i]['uni_id'] == idUnidadAcademica
                        && arr_Grid1[i]['mod_id'] == idModalidad
                        && arr_Grid1[i]['paca_id'] == idPeriodo
                        && arr_Grid1[i]['jor_id'] == idJornada
                        && arr_Grid1[i]['asi_id'] == asi_id
                        && arr_Grid1[i]['hor_id'] == hor_id
                        && arr_Grid1[i]['par_id'] == par_id) {      

                        estado = 1;
                    } 
                    
                }   else {
                    if (arr_Grid1[i]['tasi_id'] == tasi_id) { //otros tipos de asignaciones                        
                        estado = 1;
                        return estado;
                    }
                }    
            }                      
        }                            
    }        
    return estado;
}

function NewGuid() {
    var sGuid = "";
    for (var i = 0; i < 32; i++) {
        sGuid += Math.floor(Math.random() * 0xF).toString(0xF);
    }
    return sGuid;
}

