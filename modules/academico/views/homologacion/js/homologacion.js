/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
   $("#input_cedula").val($("#cedula").val());
});

function traer_estudiante(){
    var cedula   = $("#input_cedula").val();

    if($.trim(cedula)==''){
        alert('Ingrese un estudiante a buscar');
        return false;
    }

    //var datos = Object.values(data.materias);console.log(datos);
    //////////////////////////////////////////////////////
    //BLANQUEMIENTO DE LA TABLA
    $("#tabla_materias").dataTable().fnDestroy();
    table = $('#tabla_materias').DataTable( {
        paging: false
    });
    table.destroy();
    $("#tabla_materias").dataTable().fnDestroy();
    $('#tabla_materias tbody').empty();

    $('#txt_estudiante').val("");
    $("#sel_periodo").html("");
    $("#sel_pflow").html("");
    $("#sel_nflow").html("");
    //////////////////////////////////////////////////////

    var vcheck = 1;
    if( $('#flexCheckDefault').prop('checked') ) 
        vcheck = 1; //si es 1 es ONLINE
    else
        vcheck = 2;

    //alert(vcheck);return false;
    $.ajax({
        //data: {"parametro1" : "valor1", "parametro2" : "valor2"},
        type: "GET",
        dataType: "json",
        cache      : false,
        contentType: false,
        processData: false,
        async: false,
        url: "https://acade.uteg.edu.ec/homologacion_rest_desa/post.php?accion=estudiante&cedula="+cedula+"&online="+vcheck,
        /*complete: function(results) {
            console.log(results);
        }*/
    })
    .done(function( data, textStatus, jqXHR ) {

        if ( console && console.log ) {
            console.log( data );

            //if(data.error != null){
            if($.trim(data.mensaje) != ""){
                alert(data.mensaje);
            }                  

            if(data.alumno){
                $("#txt_estudiante").val(data.alumno.apellido + ' ' + data.alumno.apellido_materno + ' ' + data.alumno.nombre);
            }
            /**** PERIODOS ****************************/
            var html = '';
            $.each(data.periodos, function (index, value) {
                html += "<option value='"+value.id_periodo+"'>"+value.nombre+"</option>";
            });
            $("#sel_periodo").html(html);
            /******************************************/

            /**** FLUJO *******************************/
            var html2 = '';
            if(data.flujo){
                $.each(data.flujo, function (index, value) {
                    html2 += "<option value='"+value.id_flujo+"'>"+value.nombre_carrera+"</option>";
                });
                $("#sel_pflow").html(html2);
                $("#sel_nflow").html(html2);
            }
            
            /******************************************/
            /**** MATERIAS ****************************/           
            $('#tabla_materias').DataTable( {
                data: data.materias,
                columns: [
                    { "data": "CARRERA" },
                    { "data": "MATERIA" },
                    { "data": "FLUJO2" },
                    { "data": "MATERIA_HOMOLOGAR" },
                    { "data": "NOTA" },
                    { "data": "PERIODO" },
                ],
                "initComplete": function(settings, json) {
                   
                    console.log("initComplete");
                },
                "language": {
                    "All"           : "Todos",
                    "decimal"       : "",
                    "emptyTable"    : "No data available in table",
                    "info"          : "Mostrando _START_ de _END_ de _TOTAL_ registros",
                    "infoEmpty"     : "Mostrando 0 de 0 de 0 registros",
                    "infoFiltered"  : "(filtrado de un total de _MAX_ entries)",
                    "infoPostFix"   : "",
                    "thousands"     : ",",
                    "lengthMenu"    : "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing"    : "Procesando...",
                    "search"        : "Búsqueda:",
                    "zeroRecords"   : "No se encontraron registros coincidentes",
                    "paginate": {
                        "first"   : "Primero",
                        "last"    : "Ultimo",
                        "next"    : "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending" : ": activar para ordenar la columna ascendente",
                        "sortDescending": ": activar para ordenar la columna descendente"
                    }
                },
                responsive : true,
                /*
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal( {
                            header: function ( row ) {
                                var data = row.data();
                                return 'Detalles';// for '+data[0]+' '+data[1];
                            }
                        } ),
                        renderer: function(api, rowIdx, columns){
                            var tabla = '<table class="table table-condensed table-striped" style="width:100%">';
                            var data = $.map( columns, function ( col, i ) {
                                if(i!=0){
                                    tabla += '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                                '<td style="padding-top: 6px;padding-bottom: 6px;"><b>'+col.title+':'+'</b></td> '+
                                                '<td style="padding-top: 6px;padding-bottom: 6px;">'+col.data+'</td>'+
                                             '</tr>';
                                }
                            });
                            tabla += '</table>';
                            return tabla;
                        }
                    }
                },
                */
                /*
                "columnDefs": [
                    {
                        "targets": [ 7 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 9 ],
                        "visible": false,
                        "searchable": false
                    },
                ]
                */
                select: true
            } );//tabla
            /******************************************/

            /******************************************/
            /**** VALORES *****************************/
            var aprobadas = 0;
            var cursando  = 0;

            if(data.aprobadas){
                $.each(data.aprobadas, function (index, value) {
                    if(value.aprobada =='S' || value.aprobada =='H')
                        aprobadas += parseInt(value.contador);
                    if(value.aprobada =='C')
                        cursando  += parseInt(value.contador);
                });
            }//
            console.log("aprobadas: "+aprobadas);

            $("#input_aprobadas").val(aprobadas);
            $("#input_cursando").val(cursando);
            /******************************************/
        }//if
     })
     .fail(function( jqXHR, textStatus, errorThrown ) {
         if ( console && console.log ) {
             alert('Error en conexion con WebService SIGA');
         }
    });
}//function traer_estudiante

function iniciarHomologacion(){
    var cedula  = $("#input_cedula").val();
    var periodo = $("#sel_periodo").val();

    //alert("cedula: "+cedula+" - periodo: "+periodo);
    var data;

    var vcheck = 1;
    if( $('#flexCheckDefault').prop('checked') ) 
        vcheck = 1; //si es 1 es ONLINE
    else
        vcheck = 2;

    data = new FormData();
    data.append( 'accion' , "estudiante" );
    data.append( 'cedula' , cedula );
    data.append( 'periodo', periodo);
    data.append( 'online' , vcheck);

    $.ajax({
        data: data,
        type: "POST",
        //dataType: "json",
        cache      : false,
        contentType: false,
        processData: false,
        async: false,
        url: "https://acade.uteg.edu.ec/homologacion_rest_desa/post.php",
        /*complete: function(results) {
            console.log(results);
        }*/
    })
    .done(function( data, textStatus, jqXHR ) {
        alert("Homologacion Realizada con Exito");
     });
}//function iniciar_homologacion

function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_buscarData").val();    
    arrParams.periodo = $("#cmb_periodo").val();    
    arrParams.estado = $("#cmb_estado").val();
    $("#Tbg_Distributivo_Aca").PbGridView("applyFilterData", arrParams);
}

function exportExcel() {
    var search = $('#txt_buscarData').val();    
    var periodo = $('#cmb_periodo').val();    
    var estado = $("#cmb_estado").val();
    window.location.href = $('#txth_base').val() + "/academico/distributivocabecera/exportexcel?" +
        "search=" + search +        
        "&periodo=" + periodo + 
        "&estado=" + estado;   
}

function exportPdf() {
    var search = $('#txt_buscarData').val();    
    var periodo = $('#cmb_periodo').val();    
    var estado = $("#cmb_estado").val();
    window.location.href = $('#txth_base').val() + "/academico/distributivocabecera/exportpdf?pdf=1" +
        "&search=" + search +        
        "&periodo=" + periodo +
        "&estado=" + estado;   
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/academico/distributivocabecera/deletecab";
    var arrParams = new Object();
    arrParams.id = id;
    //alert('id:'+id);
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);        
        if (response.status == "OK") {              
            setTimeout(function() {   
                searchModules();
            }, 1000);
        }
    }, true);
}

function saveReview() {
    var link = $('#txth_base').val() + "/academico/distributivocabecera/savereview";
    var arrParams = new Object();
    arrParams.id = $('#txth_ids').val();
    arrParams.resultado = $('#cmb_estado').val();
    arrParams.observacion = $('#txt_detalle').val();
    //alert('id:'+id);
    
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
            setTimeout(function() {
                var link = $('#txth_base').val() + "/academico/distributivocabecera/index";
                window.location = link;
            }, 1000);
        }
    }, true);
     
}
