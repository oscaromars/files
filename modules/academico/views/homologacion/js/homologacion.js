/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    if($("#rol").val() == 37 ){
        $("#input_cedula").val($("#cedula").val());
        $("#input_cedula").attr("readonly","readonly");
        traer_estudiante();
    }
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
        contentType: "text/plain",
        Accept: "text/html",
        //processData: false,
        //async: false,
        url: "https://acade.uteg.edu.ec/homologacion_rest/post.php?accion=estudiante&cedula="+cedula+"&online="+vcheck,
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


