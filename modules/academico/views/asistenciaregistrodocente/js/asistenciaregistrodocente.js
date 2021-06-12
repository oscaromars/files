 $(document).ready(function () {
    $('#btn_guardarasistencia').click(function() {
       cargarDocumentoAsistencia();
    });
    
    $('#cmb_unidad').change(function () {       
        $('#cmb_profesor_asis').change();
    });

    // En la pantalla de cargar archivo de calificaciones para que las materias cambien dependiendo del período y el profesor
    $('#cmb_periodo').change(function () {
        var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/cargararchivoasistencia";

        var arrParams = new Object();
        arrParams.paca_id = $('#cmb_periodo option:selected').val();
        arrParams.per_id = $('#cmb_profesor option:selected').val();
        arrParams.getasignaturas = true;

        requestHttpAjax(link, arrParams, function (response) {
            console.log(response);
            if (response.status == "OK") {
                data = response.message;
                setComboAsignaturas(data.asignaturas, "cmb_asig");      
            }
        }, true);
    });
    
    // En la pantalla de cargar archivo de calificaciones para que las materias cambien dependiendo del período y el profesor
    $('#cmb_profesor').change(function () {
        var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/cargararchivoasistencia";

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

    $('#cmb_profesor_asis').change(function () {
        var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/registro";
        var arrParams = new Object();
        arrParams.pro_id  = $('#cmb_profesor_asis').val();
        arrParams.uaca_id = $('#cmb_unidad').val();
        arrParams.mod_id  = $('#cmb_modalidad').val();
        arrParams.paca_id = $('#cmb_periodo').val();
        arrParams.getmateria = true;

        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                //console.log(data);
                setComboDataselect(data.materia   , "cmb_materia","Todos");
            }
        }, true);
    });

    //$('#cmb_profesor_asis').change();
    /*
    $('#cmb_profesor_rc').change(function () {
        var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/registro";
        var arrParams = new Object();
        arrParams.pro_id  = $(this).val();
        arrParams.uaca_id = $('#cmb_unidad').val();
        arrParams.mod_id  = $('#cmb_modalidad').val();
        arrParams.paca_id = $('#cmb_periodo').val();
        arrParams.getmateria = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.materia   , "cmb_materia","Todos");
            }
        }, true);

    });
    */
     
     $('#btn_buscarDataregistrosemanal').click(function() {
        actualizarGridRegistrosemanal(0);
    });
     
    $('#btn_buscarDataregistro').click(function() {
        actualizarGridRegistro(0);
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

/*

function actualizarGridRegistro() {
   
    var periodo = $('#cmb_periodo option:selected').val();
    var profesor = $('#txth_proid').val();    
    var materia = $('#cmb_materia option:selected').val();    
    var unidad = $('#cmb_unidad option:selected').val();
    var modalidad = $('#cmb_modalidad option:selected').val();  
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        // ver esa funcion PbGridView, se adapte a GridView
        $('#Tbg_RegCalificacion').GridView('applyFilterData', { 'periodo': periodo, 'profesor': profesor,
         'materia': materia, 'unidad': unidad, 'modalidad': modalidad });
        setTimeout(hideLoadingPopup, 2000);
    }
}*/

function setComboAsignaturas(arr_data, element_id) {
    var option_arr = "";
    for (var i = 0; i < arr_data.length; i++) {
        var id = arr_data[i].asi_id;
        var value = arr_data[i].asi_descripcion;

        option_arr += "<option value='" + id + "'>" + value + "</option>";
    }
    $("#" + element_id).html(option_arr);
}

function cargarDocumentoAsistencia() {
    var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/cargararchivoasistencia";

    var arrParams = new Object();
    
    arrParams.procesar_file = true;
    arrParams.emp_id = $('#cmb_empresa option:selected').val();
    arrParams.paca_id = $('#cmb_periodo option:selected').val();
    arrParams.uaca_id = $('#cmb_nunidad option:selected').val();
    arrParams.mod_id = $('#cmb_nmodalidad option:selected').val();
    arrParams.ecal_id = $('#cmb_parcial option:selected').val();
    arrParams.per_id = $('#cmb_profesor option:selected').val();
    arrParams.asi_id = $('#cmb_asig option:selected').val();
    arrParams.archivo = $('#txth_doc_adj_asistencia2').val() + "." + $('#txth_doc_adj_asistencia').val().split('.').pop();

    //console.log(arrParams);
    //console.log('$(#txth_doc_adj_asistencia2).val() =>:  ' +$('#txth_doc_adj_asistencia2').val());

    if ( $('#txth_doc_adj_asistencia2').val() == "" ){
        showAlert('NO_OK', 'error', {"wtmessage": 'Debe adjuntar el archivo de asistencia', "title": 'Información'});
        return;
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            console.log('Respuesta response.message.wtmessage:  '+response.message.wtmessage);   
            if ( response.message.wtmessage != ""){
                var responseMessage = response.message.wtmessage;
                var alumnosNoCalificados = responseMessage.split(": ")[1].split(", ");
                // var resAntes = responseLabel.split(": ")[0];
                response.message.wtmessage = responseMessage.split(": ")[0] + ": ";
                var mensajeInicial = response.message.wtmessage.split(". ")[0];
                var observaciones = response.message.wtmessage.split(". ")[1];
                
                //console.log(alumnosNoCalificados);
                //showAlert(response.status, response.label, response.message);

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
            }    
            /*showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/academico/asistenciaregistrodocente/cargararchivoasistencia";
            }, 5000);*/
        }, true);
    }
}//function cargarDocumentoAsistencia

// Botón para pasar a la pantalla de index
$("#aceptar_btn").click(function(){
    window.location.href = $('#txth_base').val() + "/academico/asistenciaregistrodocente/registro";
});

// Botón para pasar recargar la pantalla
$("#cancelar_btn").click(function(){
    window.location.href = $('#txth_base').val() + "/academico/asistenciaregistrodocente/cargararchivoasistencia";
});

function actualizarGridRegistro(dready = 0) {
    var arrParams = new Object();
    arrParams.periodo   = $('#cmb_periodo option:selected').val();
    arrParams.uaca_id   = $('#cmb_unidad').val();
    arrParams.modalidad = $('#cmb_modalidad option:selected').val();  
    arrParams.materia   = $('#cmb_materia option:selected').val();  
    arrParams.parcial   = $('#cmb_parcial').val();
    arrParams.profesor  = $('#cmb_profesor_asis').val();

    console.log(arrParams);

    var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/traermodelo";
 
    requestHttpAjax(link, arrParams, function (response) {
        console.log(response);
        //$('#gridResumen').dataTable().fnClearTable();
        //$('#gridResumen').dataTable().fnAddData(response);

        if ( $.fn.dataTable.isDataTable( '#gridResumen' ) ) {
            $("#gridResumen").dataTable().fnDestroy();
            table = $('#gridResumen').DataTable( {
                paging: false
            });
            table.destroy();
            $('#gridResumen tbody').empty(); 
        }//if


        var url_editor = $('#txth_base').val() + "/academico/asistenciaregistrodocente/actualizarnotaasistencia";

        editor = new $.fn.dataTable.Editor( {
            ajax:  url_editor,
            formOptions: {
                inline: {   
                    submit: 'all'
                },
                main: {   
                    submit: 'all'
                }
            },
            table: "#gridResumen",
            idSrc: "row_num",
            fields: [ 
                {
                    label: "U1",
                    name: "u1",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    },
                },
                {
                    label: "U2",
                    name: "u2",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
                {
                    name: "paca_id",
                    type: "hidden",
                },
                {
                    name: "est_id",
                    type: "hidden",
                },
                {
                    name: "pro_id",
                    type: "hidden",
                },
                {
                    name: "asi_id",
                    type: "hidden",
                },
                {
                    name: "uaca_id",
                    type: "hidden",
                },
                 {
                    name: "mod_id",
                    type: "hidden",
                },
                 {
                    name: "daes_id",
                    type: "hidden",
                },
         {
                    name: "daho_total_horas",
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

            //El siguiente for es para obtener el primer indice
            for (var key in o.data) {
              var indice = key;
              break;
            }

            var bandera = 0;
            if(action == 'edit'){
                 var maxi = 0;
                $.each(o.data[indice], function( index, value ) {
                            //console.log(index+" : "+value);
     
                  if(index == 'daho_total_horas' ){
                        maxi = value * 5;
                   }
                });
 
                $.each(o.data[indice], function( index, value ) {
                            console.log(index+" : "+value);
                     /*
                     if(index == 'u1' ||
                        index == 'u2' ||
                        index == 'u3' ||
                        index == 'u4' ){
                            if(value < 0 || value > 10){
                                alertify.success("El cambio no se ha registrado, los valores del componente Síncrona debe estar entre 0 a 10");
                                bandera = 1;
                            }//if
                        }//if   
                        */
                        /*
                          if(index == 'daho_total_horas' ){
                                var maxi = value * 5;
                           }
                           */
 
                    if(index == 'u1' ){
                        if(value < 0 || value > maxi){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a " + maxi);
                            bandera = 1;
                        }//if
                    }//if 
                    if(index == 'u2' ){
                        if(value < 0 || value > maxi){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a "+ maxi);
                            bandera = 1;
                        }//if
                    }//if   
                });
            }//if

            if(bandera == 1)
                return false;
        });

        editor.on('submitComplete', function (e, json, data, action) {
            if(action == 'edit'){
                alertify.success('Registro editado con exito');
            }//if
        });

        // Activate an inline edit on click of a table cell

        $('#gridResumen').on( 'click', 'tbody td:not(.child)', function (e) {
            if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
                return;
            }
            editor.inline( this );
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
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
                { data: "row_num"},
                { data: "matricula"},
                { data: "nombre" },
                { data: "materia"},
                { data: "u1"},
                { data: "u2"},
                { data: "paca_id"},
                { data: "est_id"},
                { data: "pro_id"},
                { data: "asi_id"},
                { data: "uaca_id"},
                { data: "mod_id"},
                { data: "daes_id"},
            { data: "daho_total_horas"},
        

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
                "emptyTable"    : "No hay datos cargados",
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
                { targets: [ 1,2,3,4 ], responsivePriority: 1},     
                {
                    "targets": [ 8,9,10,11,12,13,14,15 ], 
                    "visible": false,
                    "searchable": false
                },
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
        //} if
        $('.dataTables_length').addClass('bs-select');
        /*
        if (response.status == "OK") {
            data = response.message;
            setComboDataselect(data.parcial, "cmb_parcial", "Seleccionar");          
        }*/
    }, true);
}//function actualizarGridRegistro


function actualizarGridRegistrosemanal(dready = 0) {
    var arrParams = new Object();
    arrParams.periodo   = $('#cmb_periodo option:selected').val();
    arrParams.uaca_id   = $('#cmb_unidad').val();
    arrParams.modalidad = $('#cmb_modalidad option:selected').val();  
    arrParams.materia   = $('#cmb_materia option:selected').val();  
    arrParams.parcial   = $('#cmb_parcial').val();
    arrParams.profesor  = $('#cmb_profesor_asis').val();

    console.log(arrParams);

    var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/traermodelosemanal";
 
    requestHttpAjax(link, arrParams, function (response) {
        console.log(response);
        //$('#gridResumen').dataTable().fnClearTable();
        //$('#gridResumen').dataTable().fnAddData(response);

        if ( $.fn.dataTable.isDataTable( '#gridResumen' ) ) {
            $("#gridResumen").dataTable().fnDestroy();
            table = $('#gridResumen').DataTable( {
                paging: false
            });
            table.destroy();
            $('#gridResumen tbody').empty(); 
        }//if


        var url_editor = $('#txth_base').val() + "/academico/asistenciaregistrodocente/actualizarnotaasistenciasemanal";

        editor = new $.fn.dataTable.Editor( {
            ajax:  url_editor,
            formOptions: {
                inline: {   
                    submit: 'all'
                },
                main: {   
                    submit: 'all'
                }
            },
            table: "#gridResumen",
            idSrc: "row_num",
            fields: [ 
                {
                    label: "S1",
                    name: "s1",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    },
                },
                {
                    label: "S2",
                    name: "s2",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
         {
                    label: "S3",
                    name: "s3",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
         {
                    label: "S4",
                    name: "s4",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
         {
                    label: "S5",
                    name: "s5",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
         {
                    label: "S1",
                    name: "s6",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
         {
                    label: "S2",
                    name: "s7",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
         {
                    label: "S3",
                    name: "s8",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
         {
                    label: "S4",
                    name: "s9",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
         {
                    label: "S5",
                    name: "s0",
                    attr: {
                        type: "number",
                        min:"0",
                        max:"100"
                    }, 
                },
                {
                    name: "paca_id",
                    type: "hidden",
                },
                {
                    name: "est_id",
                    type: "hidden",
                },
                {
                    name: "pro_id",
                    type: "hidden",
                },
                {
                    name: "asi_id",
                    type: "hidden",
                },
                {
                    name: "uaca_id",
                    type: "hidden",
                },
                 {
                    name: "mod_id",
                    type: "hidden",
                },
                 {
                    name: "daes_id",
                    type: "hidden",
                },
         {
                    name: "daho_total_horas",
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

            //El siguiente for es para obtener el primer indice
            for (var key in o.data) {
              var indice = key;
              break;
            }

            var bandera = 0;
            if(action == 'edit'){

                 var maxim = 0;
                $.each(o.data[indice], function( index, value ) {
                            //console.log(index+" : "+value);
     
                  if(index == 'daho_total_horas' ){
                        maxim = value ;
                   }
                });


                $.each(o.data[indice], function( index, value ) {
                    
                     /*
                     if(index == 'u1' ||
                        index == 'u2' ||
                        index == 'u3' ||
                        index == 'u4' ){
                            if(value < 0 || value > 10){
                                alertify.success("El cambio no se ha registrado, los valores del componente Síncrona debe estar entre 0 a 10");
                                bandera = 1;
                            }//if
                        }//if   
                        */
     
                        
 
                    if(index == 's1' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a " + maxim);
                            bandera = 1;
                        }//if
                    }//if 
                    if(index == 's2' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a " + maxim);
                            bandera = 1;
                        }//if
                    }//if   
                     if(index == 's3' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a " + maxim);
                            bandera = 1;
                        }//if
                    }//if   
                     if(index == 's4' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a "+ maxim);
                            bandera = 1;
                        }//if
                    }//if   
                     if(index == 's5' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a " + maxim);
                            bandera = 1;
                        }//if
                    }//if   
                     if(index == 's6' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a " + maxim);
                            bandera = 1;
                        }//if
                    }//if   
                     if(index == 's7' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a " + maxim);
                            bandera = 1;
                        }//if
                    }//if   
                     if(index == 's8' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a " + maxim);
                            bandera = 1;
                        }//if
                    }//if   
                     if(index == 's9' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a 30" + maxim);
                            bandera = 1;
                        }//if
                    }//if   
                     if(index == 's0' ){
                        if(value < 0 || value > maxim){
                            alertify.error("El cambio no se ha registrado, los valores del componente debe estar entre 0 a " + maxim);
                            bandera = 1;
                        }//if
                    }//if   
                });
            }//if

            if(bandera == 1)
                return false;
        });

        editor.on('submitComplete', function (e, json, data, action) {
            if(action == 'edit'){
                alertify.success('Registro editado con exito');
            }//if
        });

        // Activate an inline edit on click of a table cell

        $('#gridResumen').on( 'click', 'tbody td:not(.child)', function (e) {
            if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
                return;
            }
            editor.inline( this );
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
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
                { data: "row_num"},
                { data: "matricula"},
                { data: "nombre" },
                { data: "materia"},
                { data: "s1"},
                { data: "s2"},
        { data: "s3"},
                { data: "s4"},
            { data: "s5"},
                { data: "s6"},
                { data: "s7"},
                { data: "s8"},
                { data: "s9"},
                { data: "s0"},
                { data: "paca_id"},
                { data: "est_id"},
                { data: "pro_id"},
                { data: "asi_id"},
                { data: "uaca_id"},
                { data: "mod_id"},
                { data: "daes_id"},
            { data: "daho_total_horas"},
        

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
                "emptyTable"    : "No hay datos cargados",
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
                { targets: [ 1,2,3,4 ], responsivePriority: 1},     
                {
                    "targets": [ 16,17,18,19,20,21,22,23 ], 
                    "visible": false,
                    "searchable": false
                },
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
        //} if
        $('.dataTables_length').addClass('bs-select');
        /*
        if (response.status == "OK") {
            data = response.message;
            setComboDataselect(data.parcial, "cmb_parcial", "Seleccionar");          
        }*/
    }, true);
}//function actualizarGridRegistro

 