$(document).ready(function () {
    $('#btn_guardarcalificacion').click(function() {
        cargarDocumento();
    });

    /*
    $('#cmb_unidad').change(function () {       
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/registro";
        $('#cmb_parcial').val('0');
        //$('#cmb_modalidad').val('0');
        var arrParams = new Object();          
        arrParams.pro_id     = $('#txth_proid').val();        
        arrParams.uaca_id    = $(this).val();
        arrParams.mod_id     = $('#cmb_modalidad').val();
        arrParams.paca_id    = $('#cmb_periodo').val();
        arrParams.getmateria = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.materia, "cmb_materia", "Seleccionar");
            }
        }, true);
    });

    */

    /*
    $('#cmb_modalidad').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/registro";
        var arrParams = new Object();
        arrParams.pro_id = $('#txth_proid').val();        
        arrParams.uaca_id = $('#cmb_unidad').val();
        arrParams.mod_id = $(this).val();
        arrParams.paca_id = $('#cmb_periodo').val();
        arrParams.getmateria = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.materia, "cmb_materia", "Seleccionar");
            }
        }, true);
    });
    */

    // En la pantalla de cargar archivo de calificaciones para que las materias cambien dependiendo del período y el profesor
    $('#cmb_periodo').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/cargararchivo";

        var arrParams = new Object();
        arrParams.paca_id = $('#cmb_periodo option:selected').val();
        arrParams.per_id = $('#cmb_profesor option:selected').val();
        arrParams.getasignaturas = true;

        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboAsignaturas(data.asignaturas, "cmb_asig");
            }
        }, true);
    });
    // En la pantalla de cargar archivo de calificaciones para que las materias cambien dependiendo del período y el profesor
    $('#cmb_profesor').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/cargararchivo";

        var arrParams = new Object();
        arrParams.paca_id = $('#cmb_periodo option:selected').val();
        arrParams.per_id  = $('#cmb_profesor option:selected').val();

        arrParams.getasignaturas = true;
        
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboAsignaturas(data.asignaturas, "cmb_asig");
            }
        }, true);
    });

    $('#btn_buscarDataregistro').click(function() {
        actualizarGridRegistro();
    });

    $('#btn_buscarDataestClfcns').click(function() {    
        searchCalificacionEstudiantes();
    });

     $('#cmb_profesor_clfc').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/index";
        var arrParams = new Object();
        arrParams.paca_id = $('#cmb_periodo_clfc').val();
        arrParams.uaca_id = $('#cmb_unidad_bus').val();
        arrParams.mod_id = $('#cmb_modalidad').val();
        arrParams.pro_id = $(this).val();
        arrParams.getasignaturas_prof = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignatura, "cmb_materiabus","Todos");
            }
        }, true);
    });

    $('#cmb_unidad').change(function () { 
        $('#cmb_profesor_rc').change();
    });

    $('#cmb_profesor_rc').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/registro";
        var arrParams = new Object();
        arrParams.pro_id  = $(this).val();
        arrParams.uaca_id = $('#cmb_unidad').val();
        arrParams.mod_id  = $('#cmb_modalidad').val();
        arrParams.paca_id = $('#cmb_periodo').val();
        arrParams.getmateria = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                console.log(data);
                setComboDataselect(data.materia   , "cmb_materia","Todos");
            }
        }, true);
    });
     
    $('#btn_download_acta').click(function() {    
        downloadDataClfc();
    });
    
});//Document ready

function setComboAsignaturas(arr_data, element_id) {
    var option_arr = "";
    for (var i = 0; i < arr_data.length; i++) {
        var id = arr_data[i].asi_id;
        var value = arr_data[i].asi_descripcion;

        option_arr += "<option value='" + id + "'>" + value + "</option>";
    }
    $("#" + element_id).html(option_arr);
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

function cargarDocumento() {
    var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/cargararchivo";

    var arrParams = new Object();
    
    arrParams.procesar_file = true;

    arrParams.asi = $('#cmb_asig option:selected').val();
    arrParams.ecal_id = $('#cmb_parcial option:selected').val();
    arrParams.per_id = $('#cmb_profesor option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_calificacion2').val() + "." + $('#txth_doc_adj_calificacion').val().split('.').pop();

    // console.log(arrParams);

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            var responseMessage = response.message.wtmessage;
            var alumnosNoCalificados = responseMessage.split(": ")[1].split(", ");
            // var resAntes = responseLabel.split(": ")[0];
            response.message.wtmessage = responseMessage.split(": ")[0] + ": ";
            var mensajeInicial = response.message.wtmessage.split(". ")[0];
            var observaciones = response.message.wtmessage.split(". ")[1];

            // showAlert(response.status, response.label, response.message);
            $('#confirmModal').modal('toggle');
            $(".modal-title").append('<b>' + response.label + '<b>');
            $(".modal-body").append('<p>' + mensajeInicial + '</p>');
            
            if(alumnosNoCalificados[0] != ""){
                $(".modal-body").append('<p>' + observaciones + '</p>');
                $(".modal-body").append('<ul class="modal-list"></ul>');
                for (var i = 0; i < alumnosNoCalificados.length; i++) {
                    var alumno = alumnosNoCalificados[i];
                    if(alumno.length == 0){ continue; }
                    $(".modal-list").append('<li>' + alumno + '</li>');
                }
            }
            

            // shortModal(response.message, response.label, response.status );
            /*setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/index";
            }, 5000);*/
        }, true);
    }
}

// Botón para pasar a la pantalla de index
$("#aceptar_btn").click(function(){
    window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/index";
});

// Botón para pasar recargar la pantalla
$("#cancelar_btn").click(function(){
    window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/cargararchivo";
});


function searchCalificacionEstudiantes() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;

    //arrParams.search = $("#txt_buscarData").val();

    periodo = $("#cmb_periodo_clfc").val();
    unidad = $("#cmb_unidad_bus").val();
    modalidad = $("#cmb_modalidad").val();
    materia = $("#cmb_materiabus").val();
    profesor = $("#cmb_profesor_clfc").val();

    if(profesor == null ||  profesor == -1){
        var mensaje = {wtmessage: "No hay un prosesor o no ha sido asignado", title: "Exito"};
        showAlert("FALSE", "success", mensaje);
        return;
    }

    if (!$(".blockUI").length) {
        //showLoadingPopup();
        // ver esa funcion PbGridView, se adapte a GridView
        $('#Tbg_Calificaciones').PbGridView('applyFilterData', { 'profesor': profesor,'periodo': periodo,
         'materia': materia, 'unidad': unidad, 'modalidad': modalidad,'PBgetFilter': true});
        //setTimeout(hideLoadingPopup, 2000);
    }
}


function downloadDataClfc() {

 //var ron_id = $('#frm_ron_id').val();
    /* console.log(ron_id); */
     periodo = $("#cmb_periodo_clfc").val();
     unidad = $("#cmb_unidad_bus").val();
     modalidad = $("#cmb_modalidad").val();
     materia = $("#cmb_materiabus").val();
     profesor = $("#cmb_profesor_clfc").val();

    window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/exportpdfclfc?pdf=1&paca="+periodo+"&unidad="+unidad+"&materia="+materia+"&profesor="+profesor;

}

//function actualizarGridRegistro
function actualizarGridRegistro(dready = 0) {
    var arrParams = new Object();
    arrParams.periodo   = $('#cmb_periodo option:selected').val();
    arrParams.uaca_id   = $('#cmb_unidad').val();
    arrParams.modalidad = $('#cmb_modalidad option:selected').val();  
    arrParams.materia   = $('#cmb_materia option:selected').val();  
    arrParams.parcial   = $('#cmb_parcial').val();
    arrParams.profesor  = $('#cmb_profesor_rc').val();

    var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/traermodelo";
 
    requestHttpAjax(link, arrParams, function (response) {
        //console.log(response);
        //$('#gridResumen').dataTable().fnClearTable();
        //$('#gridResumen').dataTable().fnAddData(response);

        //Si la unidad academica es 3 significa que es Master
        if($("#cmb_unidad").val() == 3){
            if ( $.fn.dataTable.isDataTable( '#gridResumen' ) ) {
                $("#gridResumen").dataTable().fnDestroy();
                table = $('#gridResumen').DataTable( {
                    paging: false
                });
                table.destroy();
                $('#gridResumen tbody').empty();
                $("#html_thead").html('');  
            }

            //$("#gridResumen").DataTable().fnDestroy();
            //$('#gridResumen tbody').empty(); 

            var html = `
                <tr>
                    <th class='no-sort'><i class='fa fa-clone' aria-hidden='true'></i></th>
                    <th></th>
                    <th>Matricula</th>
                    <th>Nombre</th>
                    <th>Materia</th>
                    <th>Parcial</th>
                    <th>Asíncrona</th>
                    <th>Autónoma</th>
                    <th>Evaluación</th>
                    <th>Trabajo Final</th>
                    <th>Total</th>
                </tr>`;
            $("#html_thead").html(html);

            var url_editor = $('#txth_base').val() + "/academico/calificacionregistrodocente/actualizarnota";

            editor = new $.fn.dataTable.Editor( {
                ajax  : url_editor,
                table : "#gridResumen",
                idSrc : "ccal_id",
                fields: [ 
                    /*
                    {
                        label: "Matricula",
                        name: "matricula",
                        type: "text", 
                    },
                    {
                        label: "Matricula",
                        name: "nombre",
                        type: "text", 
                    },
                    {
                        label: "Materia",
                        name: "materia",
                        type: "text", 
                    },
                    */
                    {
                        label: "Asíncrona",
                        name: "Asíncrona",
                        attr: {
                            type: "number",
                            min:"0",
                            max:"100"
                        },
                    },
                    {
                        label: "Autónoma",
                        name: "Autónoma",
                        attr: {
                            type: "number",
                            min:"0",
                            max:"100"
                        }, 
                    },
                    {
                        label: "Evaluación",
                        name: "Evaluación",
                        attr: {
                            type: "number",
                            min:"0",
                            max:"100"
                        },
                    },
                    {
                        label: "Trabajo Final",
                        name: "Trabajo final",
                        attr: {
                            type: "number",
                            min:"0",
                            max:"100"
                        }, 
                    },
                ],
                i18n: {
                    close: 'Cerrar',
                    create: {
                        button: "Nuevo",
                        title:  "Crear un nuevo registro",
                        submit: "Crear"
                    },
                    edit: {
                        button: "Modificar",
                        title:  "Registros a modificar",
                        submit: "Actualizar"
                    },
                    remove: {
                        button: "Eliminar",
                        title:  "Eliminar",
                        submit: "Eliminar",
                        confirm: {
                            _: "Estas seguro de eliminar los %d registro?",
                            1: "Estas seguro de eliminar 1 registro?"
                        }
                    },
                    error: {
                        system: "Se ha producido un error, comunicar al departamento de sistemas"
                    },
                    datetime: {
                        previous: 'Anterior',
                        next:     'Siguiente',
                        months:   [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
                        weekdays: [ 'Dom', 'Lun', 'Mar', 'Mir', 'Jue', 'Vie', 'Sab' ],
                        hour : 'Hora'
                    }
                }
            });

            editor.on( 'preSubmit', function ( e, o, action ) {
                console.log(o);
                for (var key in o.data) {
                  var indice = key;
                  break;
                }

                /*
                if(action == 'edit'){
                    $.each(o.data[indice], function( index, value ) {
                        //alert(value);
                        if(value < 0 || value > 100){
                            alert("El cambio no se ha registrado, los valores de las calificaciones deben estar en el rango de 0 a 100");
                            return false;
                        }    
                    });
                }//if
                */
            });

            // Activate an inline edit on click of a table cell

            $('#gridResumen').on( 'click', 'tbody td:not(.child)', function (e) {
                if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
                    return;
                }
                editor.inline(this);
            });
            
            $('#gridResumen').DataTable({
                "dom": '<"top"Bf>rt<"bottom"lp><"clear">',
                "data": response,
                //"dom": "Bfrtip",
                buttons: [
                    /*{  extend: 'create',editor: editor }, */
                    {  extend: 'edit', editor: editor  },
                    /*{  extend: 'remove', editor: editor  },*/
                ],
                orderable  : false,
                "bDestroy" : true,
                destroy    : true,
                retrieve   : true,
                "columns": [
                    {   // Responsive control column
                        data: null,
                        defaultContent: '',
                        className: 'control',
                        orderable: false
                    },
                    {   // Checkbox select column
                        data: null,
                        defaultContent: '',
                        className: 'select-checkbox',
                        orderable: false
                    },
                    { data: "matricula",  editField: "matricula" },
                    { data: "nombre" },
                    { data: "materia"},
                    { data: "nparcial"},
                    { data: "par_nombre"},
                    { data: "Asíncrona"},
                    { data: "Autónoma"},
                    { data: "Evaluación"},
                    { data: "Trabajo final"},
                    { data: "total"}
                ],
                /*
                "ajax":{
                    url: url,
                },
                */
                "bInfo" : false,
                /*
                FixedHeader: {
                    leftColumns: 1
                },
                */
                "language": {
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
                    },

                },
                "rowHeight": 'auto',
                "initComplete": function(settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({ trigger : 'focus hover' }) ;
                    //l.stop();
                    $(".spinner").hide();
                    $(".no-sort").removeClass("sorting_asc");
                },
                //responsive : true,
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
                
                columnDefs: [   
                    { targets: "no-sort", "orderable": false, "order": [],},
                    { targets: 1, responsivePriority: 1},        
                ],
                
                select: {
                    style:    'os',
                    selector: 'td.select-checkbox'
                },
                //order: [4, 'asc'],
                //rowGroup: {
                    //dataSrc: "empresaNombre"
                //                    },
            });

            $('.dataTables_length').addClass('bs-select');
        }else{
            if ( $.fn.dataTable.isDataTable( '#gridResumen' ) ) {
                $("#gridResumen").dataTable().fnDestroy();
                table = $('#gridResumen').DataTable( {
                    paging: false
                });
                table.destroy();
                $('#gridResumen tbody').empty();
                $("#html_thead").html('');  
            }//if

            var html = `
                <tr>
                    <th class='no-sort'><i class='fa fa-clone' aria-hidden='true'></i></th>
                    <th></th>
                    <th>No.</th>
                    <th>Matricula</th>
                    <th>Nombre</th>
                    <th>Materia</th>
                    <th>Parcial</th>
                    <th>Asíncrona</th>
                    <th>Síncrona</th>
                    <th>Autónoma</th>
                    <th>Evaluación</th>
                    <th>Examen</th>
                    <th>Total</th>
                    <th>paca_id</th>
                    <th>est_id</th>
                    <th>pro_id</th>
                    <th>asi_id</th>
                    <th>ecal_id</th>
                    <th>uaca_id</th>
                    <th>ccal_id_id</th>
                </tr>`;
            $("#html_thead").html(html);

            var url_editor = $('#txth_base').val() + "/academico/calificacionregistrodocente/actualizarnota";

            editor = new $.fn.dataTable.Editor( {
                ajax:  url_editor,
                //legacyAjax: true,
                /*
                ajax: {
                    data: function(d) {
                            //d.unidad = $('#cmb_unidad').val();
                            console.log(d.data[d.DT_RowID]);
                            //paca_id = d.data[d.matricula]['paca_id'];
                            //est_id  = d.data[d.matricula]['est_id'];
                            //pro_id  = d.data[d.matricula]['pro_id'];
                            //asi_id  = d.data[d.matricula]['asi_id'];
                            console.log(d);
                          },
                    type: "POST",
                    url : url_editor
                },
                */
                table: "#gridResumen",
                idSrc: "row_num",
                formOptions: {
                    inline: {   
                        submit: 'all'
                    },
                    main: {   
                        submit: 'all'
                    }
                },
                fields: [ 
                    /*
                    {
                        label: "Matricula",
                        name: "matricula",
                        type: "text", 
                    },
                    {
                        label: "Matricula",
                        name: "nombre",
                        type: "text", 
                    },
                    */
                    {
                        name: "nparcial",
                        type:  "hidden",
                    },
                    {
                        name: "ccal_id",
                        type:  "hidden",
                    },
                    {
                        label: "Asíncrona",
                        name: "Asíncrona",
                        attr: {
                            type: "number",
                            min:"0",
                            max:"100"
                        },
                    },
                    {
                        label: "Síncrona",
                        name: "Síncrona",
                        attr: {
                            type: "number",
                            min:"0",
                            max:"100"
                        },
                    },
                    {
                        label: "Autónoma",
                        name: "Autónoma",
                        attr: {
                            type: "number",
                            min:"0",
                            max:"100"
                        }, 
                    },
                    {
                        label: "Evaluación",
                        name: "Evaluación",
                        attr: {
                            type: "number",
                            min:"0",
                            max:"100"
                        },
                    },
                    {
                        label: "Examen",
                        name: "Examen",
                        attr: {
                            type: "number",
                            min:"0",
                            max:"100"
                        }, 
                    },
                    {   name: "paca_id",
                        type: "hidden"
                    },
                    {   name: "est_id",
                        type: "hidden",
                    },
                    {   name: "pro_id",
                        type: "hidden",
                    },
                    {   name: "asi_id",
                        type: "hidden",
                    },
                    {   name: "ecal_id",
                        type: "hidden",
                    },
                    {   name: "uaca_id",
                        type: "hidden",
                    },
                ],
                i18n: {
                    close: 'Cerrar',
                    create: {
                        button: "Nuevo",
                        title:  "Crear un nuevo registro",
                        submit: "Crear"
                    },
                    edit: {
                        button: "Modificar",
                        title:  "Registros a modificar",
                        submit: "Actualizar"
                    },
                    remove: {
                        button: "Eliminar",
                        title:  "Eliminar",
                        submit: "Eliminar",
                        confirm: {
                            _: "Estas seguro de eliminar los %d registro?",
                            1: "Estas seguro de eliminar 1 registro?"
                        }
                    },
                    error: {
                        system: "Se ha producido un error, comunicar al departamento de sistemas"
                    },
                    datetime: {
                        previous: 'Anterior',
                        next:     'Siguiente',
                        months:   [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
                        weekdays: [ 'Dom', 'Lun', 'Mar', 'Mir', 'Jue', 'Vie', 'Sab' ],
                        hour : 'Hora'
                    }
                }
            });

            editor.on( 'preSubmit', function ( e, o, action ) {
                console.log(o);
                
                var bandera = 0;

                for (var key in o.data) {
                  var indice = key;
                  break;
                }

                if(action == 'edit'){
                    $.each(o.data[indice], function( index, value ) {
                        
                        if(index == 'Asíncrona'){
                            if(value < 0 || value > 10){
                                alertify.success("El cambio no se ha registrado, los valores del componente Asíncrona debe estar entre 0 a 10");
                                bandera = 1;
                            }//if
                        }//if

                        if(index == 'Síncrona'){
                            if(value < 0 || value > 10){
                                alertify.success("El cambio no se ha registrado, los valores del componente Síncrona debe estar entre 0 a 10");
                                bandera = 1;
                            }//if
                        }//if

                        if(index == 'Autónoma'){
                            if(value < 0 || value > 20){
                                alertify.success("El cambio no se ha registrado, los valores del componente Autónoma debe estar entre 0 a 20");
                                bandera = 1;
                            }//if
                        }//if

                        if(index == 'Evaluación'){
                            if(value < 0 || value > 20){
                                alertify.success("El cambio no se ha registrado, los valores del componente Evaluación debe estar entre 0 a 20");
                                bandera = 1;
                            }//if
                        }//if

                        if(index == 'Examen'){
                            if(value < 0 || value > 40){
                                alertify.success("El cambio no se ha registrado, los valores del componente Examen debe estar entre 0 a 40");
                                bandera = 1;
                            }//if
                        }//if

                        if(index == 'Trabajo final'){
                            if(value < 0 || value > 50){
                                alertify.success("El cambio no se ha registrado, los valores del componente Examen debe estar entre 0 a 50");
                                bandera = 1;
                            }//if
                        }//if

                        /*
                        if(value < 0 || value > 100){
                            alert("El cambio no se ha registrado, los valores de las calificaciones deben estar en el rango de 0 a 100");
                            return false;
                        }else
                            return true;
                            */
                    });
                }//if

                if(bandera == 1) return false;
            });

            editor.on('submitComplete', function (e, json, data, action) {
                if(action == 'edit'){
                    alertify.success('Registro editado con exito');
                }//if
                //actualizarGridRegistro(0);
            });

            // Activate an inline edit on click of a table cell
            
            $('#gridResumen').on( 'click', 'tbody td:not(.child)', function (e) {
                if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
                    return;
                }
                editor.inline( this );
            });
            
            editor.on( 'opened', function ( e, json, data ) {       
                $('#DTE_Field_nparcial').addClass("form-control");
                //$('#DTE_Field_est_nivel').attr('data-container',"body");
                //$('#DTE_Field_est_nivel').selectpicker();
            });

            $('#gridResumen').on( 'click', 'tbody ul.dtr-details li', function (e) {
                // Edit the value, but this selector allows clicking on label as well
                editor.inline( $('span.dtr-data', this) );
            } );
            
            $('#gridResumen').DataTable({
                "dom": '<"top"Bf>rt<"bottom"lp><"clear">',
                "data": response,
                //"dom": "Bfrtip",
                buttons: [
                    /*{  extend: 'create',editor: editor }, */
                    {  extend: 'edit', editor: editor  },
                    /*{  extend: 'remove', editor: editor  },*/
                ],
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                orderable  : false,
                //"bDestroy" : true,
                //destroy    : true,
                //retrieve   : true,
                "columns": [
                    {   // Responsive control column
                        data: null,
                        defaultContent: '',
                        className: 'control',
                        orderable: false
                    },
                    {   // Checkbox select column
                        data: null,
                        defaultContent: '',
                        className: 'select-checkbox',
                        orderable: false
                    },
                    /*
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    */
                    { data: "row_num", editable: "row_num"},
                    { data: "matricula"},
                    { data: "nombre" },
                    { data: "materia"},
                    { data: "nparcial"},
                    { data: "Asíncrona"},
                    { data: "Síncrona"},
                    { data: "Autónoma"},
                    { data: "Evaluación"},
                    { data: "Examen"},
                    { data: "total"},
                    { data: "paca_id"},
                    { data: "est_id"},
                    { data: "pro_id"},
                    { data: "asi_id"},
                    { data: "ecal_id"},
                    { data: "uaca_id"},
                    { data: "ccal_id"},
                ],
                /*
                "ajax":{
                    url: url,
                },
                */
                "bInfo" : false,
                /*
                FixedHeader: {
                    leftColumns: 1
                },
                */
                "language": {
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
                    },

                },
                "rowHeight": 'auto',
                "initComplete": function(settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({ trigger : 'focus hover' }) ;
                    //l.stop();
                    $(".spinner").hide();
                    $(".no-sort").removeClass("sorting_asc");
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
                
                columnDefs: [   
                    { targets: "no-sort", "orderable": false, "order": [],},
                    { targets: 4, responsivePriority: 1},      
                    {
                        "targets": [ 14,15,16,17,18,19,20 ],
                        "visible": false,
                        "searchable": false
                    },  
                ],
                
                select: {
                    style:    'os',
                    selector: 'td.select-checkbox'
                },
                select: true,
                /*
                initComplete: function( settings, json ) {
                    $('#DTE_Field_nparcial').addClass("form-control");
                }*/
                order: [2, 'asc'],
                //rowGroup: {
                    //dataSrc: "empresaNombre"
            //                    },
            });
            //} if
            $('.dataTables_length').addClass('bs-select');
        }//else
        /*
        if (response.status == "OK") {
            data = response.message;
            setComboDataselect(data.parcial, "cmb_parcial", "Seleccionar");          
        }*/
    }, true);
}