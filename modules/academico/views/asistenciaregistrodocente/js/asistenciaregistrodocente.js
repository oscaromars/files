 $(document).ready(function () {
    $('#btn_guardarasistencia').click(function() {
       cargarDocumentoAsistencia();
    });
    
    $('#cmb_periodo').change(function(){
        listarAsignaturas();
    });
    $('#cmb_materia').change(function(){
        listarParalelos();
    });
    $('#cmb_parcial').change(function(){
        listarSesiones();
    });
    // $('#grid_marcacion_list').change(function(){
    //     crearInputs();
    // });
    //$('#cmb_unidad').change(function () {       
    //    $('#cmb_profesor_asis').change();
    //});

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


      $('#cmb_unidad').change(function () {
       // $('#cmb_profesor_asis').change();
        var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/registro";
        var arrParams = new Object();
        arrParams.pro_id  = $('#cmb_profesor_asis').val();
        arrParams.uaca_id = $(this).val();
        arrParams.mod_id  = $('#cmb_modalidad').val();
        arrParams.paca_id = $('#cmb_periodo').val();
        arrParams.getuni = true;

        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                //console.log(data);
                setComboDataselectpro(data.profesorup,"cmb_profesor_asis","Todos");
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
     
 $('#btn_buscarDataregistrodin').click(function() {
        actualizarGridRegistrodin(0);
    });

     $('#btn_buscarDataregistrosemanal').click(function() {
        actualizarGridRegistrosemanal(0);
    });
     
    $('#btn_buscarDataregistro').click(function() {
        actualizarGridRegistro(0);
    });
    $('#btn_buscarDataregistroM').click(function() {
        actualizarGridMarcacion();
        // crearInputs();
        //actualizarGridRegistro(0);
    });
    $('#btn_crearAsistencia').click(function() {
        crearAsistencias();
    });
    // $('#checkAll').click(function() {
    //     var cant = $('#cantidad_registros').val();
    //     alert(cant);
    //     // if($(this).is(":checked")){
    //     if($(this).prop('checked')){
    //         for($i = 1; $i < cant; $i++){
    //             $( "#entrada_".$i ).prop( "checked", true );
    //         }
    //     }else{
    //         for($i = 1; $i < cant; $i++){
    //             $( "#entrada_".$i ).prop( "checked", true );
    //         }
    //     }
    // });
    $('#checkAll').click(function () {    
        $('input:checkbox').prop('checked', this.checked);  
        if($(this).prop("checked") == true){
            console.log("Checkbox is checked.");
            var texto = "Deseleccionar todos los estudiantes";
        }
        else if($(this).prop("checked") == false){
            console.log("Checkbox is unchecked.");
            var texto = "Seleccionar todos los estudiantes";
        }  
        // texto.replace("Seleccionar", "Deseleccionar");
        // alert($('#text_seleccion').text());
        // alert($('input:checkbox').prop('checked', this.checked));
        $('#text_seleccion').html('<label id="text_seleccion" class="form-check-label" for="checkAll">'+texto+'</label>');
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

function setComboDataselectpro(arr_data, element_id, texto) {
    var option_arr = "";
    option_arr += "<option value= '0'>" + texto + "</option>";
    for (var i = 0; i < arr_data.length; i++) {
        var id = arr_data[i].pro_id;
        var value = arr_data[i].nombres;

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


      if (arrParams.modalidad != 1) {
 
  // showAlert('NO_OK', 'error','wtmessage'); 
  showAlert('NO_OK', 'error', {"wtmessage": 'Solo el Registro de Asistencia Online es por periodo, otras modalidades deben utilizar el registro semanal', "title": 'Información'});

   window.location.href = $('#txth_base').val() + "/academico/asistenciaregistrodocente/registrosemanal";
} 

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



      if (arrParams.modalidad == 1) {
 
  // showAlert('NO_OK', 'error','wtmessage'); 
  showAlert('NO_OK', 'error', {"wtmessage": 'El Registro de Asistencia Online debe realizarse por periodo', "title": 'Información'});

   window.location.href = $('#txth_base').val() + "/academico/asistenciaregistrodocente/registro";
} 

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

 ////////////////////////////////////////////////////////////////////////////////////////////////////
function actualizarGridRegistrodin(dready = 0) {
    //Listado de parametros para ser enviados al servidor para desplegar la inforacion del grid
    var arrParams       = new Object();
    arrParams.periodo   = $('#cmb_periodo option:selected').val();
    arrParams.uaca_id   = $('#cmb_unidad').val();
    arrParams.modalidad    = $('#cmb_modalidad option:selected').val();  
    arrParams.materia   = $('#cmb_materia option:selected').val();  
    arrParams.parcial   = $('#cmb_parcial').val();
    arrParams.profesor  = $('#cmb_profesor_asis').val();

    console.log(arrParams);

    //URL para actualizar el grid
    var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/traermodelodin";
    
    //Llamado del ajax
    requestHttpAjax(link, arrParams, function (response) {
        console.log(response);

////////////////////////////////////////////////////////////////////////////////////////
          if ( $.fn.dataTable.isDataTable( '#gridResumen' ) ) {
            $("#gridResumen").dataTable().fnDestroy();
            table = $('#gridResumen').DataTable( {
                paging: false
            });
            table.destroy();
            $('#gridResumen tbody').empty(); 
        }//if 
////////////////////////////////////////////////////////////////////////////////////////


 if (arrParams.modalidad == 1) {

        //Esta es la funcion en el controlador que actualizara las notas
        var url_editor = $('#txth_base').val() + "/academico/asistenciaregistrodocente/actualizarnotaasistencia";

}


 if (arrParams.modalidad != 1) {

        //Esta es la funcion en el controlador que actualizara las notas
        var url_editor = $('#txth_base').val() + "/academico/asistenciaregistrodocente/actualizarnotaasistenciasemanal";

}



        //Armamos el componente editor, aqui el indicamos que campos del grid son editables
        editor = new $.fn.dataTable.Editor( {
            ajax:  url_editor,
            table: "#gridResumen",
            //La variable idSrc es para saber que linea estamos editando
            //Esto es importante para que al regresar del controlador con la respuesta
            //nos actualize el campo correcto y no tengamos q reiniciar pantalla
            idSrc: "row_num",
            //Esto form option al poner submit all enviaremos toda la fila de datos
            //ya que necesitamos algunos parametros para actualizar el regsitro
            formOptions: {
                inline: {   
                    submit: 'all'
                },
                main: {   
                    submit: 'all'
                }
            },
            fields: [ 

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
            var componentes = response['componentes'];
            
            var bandera = 0;

            for (var key in o.data) {
              var indice = key;
              break;
            }
            console.log(componentes);
            if(action == 'edit'){
                $.each(o.data[indice], function( index, value ) {
                    if(componentes[index]){
                        if(value < 0 || value > parseInt(componentes[index]['notamax'])){
                            alertify.error("El cambio no se ha registrado, los valores del componente Asíncrona debe estar entre 0 a "+componentes[index]['notamax']);
                            bandera = 1;
                        }//if
                    }//if
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
            
       /* editor.on( 'opened', function ( e, json, data ) {       
            $('#DTE_Field_nparcial').addClass("form-control");
            //$('#DTE_Field_est_nivel').attr('data-container',"body");
            //$('#DTE_Field_est_nivel').selectpicker();
        }); */

        $('#gridResumen').on( 'click', 'tbody ul.dtr-details li', function (e) {
            // Edit the value, but this selector allows clicking on label as well
            editor.inline( $('span.dtr-data', this) );
        } );

        $("#html_thead").html(''); 
        var html = `
            <tr>
                <th class='no-sort'><i class='fa fa-clone' aria-hidden='true'></i></th>
                <th></th>
                <th>No.</th>
                <th>Matricula</th>
                <th>Nombre</th>
                <th>Materia</th>`;       

        var columnas1 =[
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
                { data: "row_num", editable: "row_num"},
                { data: "matricula"},
                { data: "nombre" },
                { data: "materia"},
               /*   { data: "nparcial"}
              { data: "paralelo"} */
                ]; 
        
        var centrar = [];
        var numeroCols = 3;

        $.each( response['componentes'], function( key, value ) {
            var element = {};
            element.data = key;
            columnas1.push(element);         

            editor.add( {
                label    : key,
                name     : key,
                attr: {
                    type: "number",
                    min : "1",
                    max : value.notamax
                },
            });
            numeroCols++;
            //centrar.push(numeroCols);
            html += '<th>'+key+'</th>';
        });
        numeroCols++;
        console.log("# de columnnas = "+numeroCols);
        
        numeroCols++
        centrar.push(numeroCols+1);
        centrar.push(numeroCols+2);
        centrar.push(numeroCols+3);
        centrar.push(numeroCols+4);
        centrar.push(numeroCols+5);
        centrar.push(numeroCols+6);
        centrar.push(numeroCols+7);
        centrar.push(numeroCols+8);

        var arrcolumnDefs = new Array();
        var centrarArr = {};
        centrarArr.targets   = centrar;
        centrarArr.visible = false;
        centrarArr.searchable = false;

        arrcolumnDefs.push(centrarArr); 

        /*
        columnDefs: [   
                { targets: "no-sort", "orderable": false, "order": [],},
                { targets: 4, responsivePriority: 1},      
                
                {
                    "targets": [ 14,15,16,17,18,19,20,21 ],
                    "visible": false,
                    "searchable": false
                },  
            
            ],
        */

        console.log(centrarArr);

        var columnas2 =[ 
              /*  { data: "total"}, */
                { data: "paca_id"},
                { data: "est_id"},
                { data: "pro_id"},
                { data: "asi_id"},
                { data: "uaca_id"},
                { data: "mod_id"},
                { data: "daes_id"},
            { data: "daho_total_horas"},
            ];

        var columnas = columnas1.concat(columnas2);

        html += `<th>paca_id</th>
                 <th>est_id</th>
                 <th>pro_id</th>
                 <th>asi_id</th>               
                 <th>uaca_id</th>                
                 <th>mod_id</th>
                 <th>daes_id</th>
                 <th>daho_total_horas</th>
            </tr>`;
        $("#html_thead").html(html);

        table = $('#gridResumen').DataTable({
            "dom": '<"top"Bf>rt<"bottom"lp><"clear">',
            "data": response['data'],
            //"dom": "Bfrtip",
            buttons: [
                /*{  extend: 'create',editor: editor }, */
                {  extend: 'edit', editor: editor  },
                /*{  extend: 'remove', editor: editor  },*/
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            orderable  : false,
            //"bDestroy" : true,
            destroy    : true,
            //retrieve   : true,
            "columns": columnas,
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
            /*
            columnDefs: [   
                { targets: "no-sort", "orderable": false, "order": [],},
                { targets: 4, responsivePriority: 1},      
                
                {
                    "targets": [ 14,15,16,17,18,19,20,21 ],
                    "visible": false,
                    "searchable": false
                },  
                
            ],
            */
            columnDefs: [centrarArr],
            select: {
                style:    'os',
                selector: 'td.select-checkbox'
            },
            select: true,
            /*
            initComplete: function( settings, json ) {
                $('#DTE_Field_nparcial').addClass("form-control");
            }*/
            //order: [2, 'asc'],
            //rowGroup: {
                //dataSrc: "empresaNombre"
        //                    },
        });
        //} if
        $('.dataTables_length').addClass('bs-select');
    }, true);
}

function actualizarGridMarcacion() {
    var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/marcarasistencia";
    var arrParams = new Object();
    periodo   = $('#cmb_periodo option:selected').val();
    uaca_id   = $('#cmb_unidad option:selected').val();
    modalidad = $('#cmb_modalidad option:selected').val();  
    materia   = $('#cmb_materia option:selected').val();  
    profesor  = $('#cmb_profesor_asis').val();
    sesion    = $('#cmb_sesion option:selected').val();
    // tabla  = $('#grid_marcacion_list').html();
    // alert(tabla);

    console.log(arrParams);
 
    filtros = 1;

        showLoadingPopup();
        setTimeout(function() {
            //windows.location.href = $('#txth_base').val() + "/academico/registro/index";    
            parent.window.location.href = link+'?modalidad='+modalidad+'&periodo='+periodo+'&uaca_id='+uaca_id+'&profesor='+profesor+'&materia='+materia+'&sesion='+sesion+'&PBgetFilter='+filtros;
            hideLoadingPopup();
            }, 1000);
        // setTimeout(function() {
        //     let tabla  = $('#grid_marcacion_list').html();
        //     //tabla.replace('<td name="txt_m_"></td>',
        //     tabla.replace('txt_m_',
        //                 //'<td><input type="text"  class="form-control" value="" spellcheck="false" data-ms-editor="true" ></td>');
        //                 'txt_m_2');
        //     $('#grid_marcacion_list').html(tabla);
        // }, 4000);
        // alert(tabla);
        
    }
    
    // function crearInputs(){
        
//         // setTimeout(function() {
//             tabla  = $('#grid_marcacion_list').html();
//             tabla.replace('<td name="txt_m_"></td>',
//             // tabla.replace('txt_m_',
//                         '<td><input type="text"  class="form-control" value="" spellcheck="false" data-ms-editor="true" ></td>');
//                         // 'txt_m_2');
//             $('#grid_marcacion_list').html(tabla);
//         // }, 1000);
//         alert(tabla);
// }

function listarSesiones(){
    var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/listarsesiones";
    var asi_id = $('#cmb_materia option:selected').val();
    var paca_id = $('#cmb_periodo option:selected').val();
    var pro_id = $('#cmb_profesor_asis option:selected').val();
    var paralelo = $('#cmb_parcial option:selected').val();
    data = new FormData();
    data.append( 'paca_id' , paca_id );
    data.append( 'pro_id', pro_id);
    data.append( 'asi_id', asi_id);
    data.append( 'paralelo', paralelo);
    //alert(link);
    //DBE
    $.ajax({
        data: data,
        type: "POST",
        dataType: "json",
        cache      : false,
        contentType: false,
        processData: false,
        async: false,
        url: link,
        success: function (data) {
            var datos = JSON.stringify(data);
            var obj = JSON.parse(datos);
            var html = '<option value="0" selected="">Seleccionar</option>';
            var id = 0;
            var count = 0;
            //alert(obj);
            $.each(obj.allModels, function( index, value ) {
                var data = (value);                    
                $.each(data, function( index2, value2 ) {
                    if(index2 == 'id'){
                        id = value2;
                    }
                    if(index2 == 'nombre'){
                        html = html + `<option value=${id} >${value2}</option>`;
                        count++;
                    }
                }); 
            });
            if(count>0){
                $('#cmb_sesion').prop("disabled",false); 
                $("#cmb_sesion")[0].selectedIndex=0;
            }
            $("#cmb_sesion").html(html);
            // alert(html);
        }
    });
}
function listarAsignaturas(){
    var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/listarasignaturas";
    var paca_id = $('#cmb_periodo option:selected').val();
    var pro_id = $('#cmb_profesor_asis option:selected').val();
    data = new FormData();
    data.append( 'paca_id' , paca_id );
    data.append( 'pro_id', pro_id);
    //alert(link);
    //DBE
    $.ajax({
        data: data,
        type: "POST",
        dataType: "json",
        cache      : false,
        contentType: false,
        processData: false,
        async: false,
        url: link,
        success: function (data) {
            var datos = JSON.stringify(data);
            var obj = JSON.parse(datos);
            var html = '<option value="0" selected="">Seleccionar</option>';
            var id = 0;
            var count = 0;
            //alert(obj);
            $.each(obj.allModels, function( index, value ) {
                var data = (value);                    
                $.each(data, function( index2, value2 ) {
                    if(index2 == 'id'){
                        id = value2;
                    }
                    if(index2 == 'nombre'){
                        html = html + `<option value=${id} >${value2}</option>`;
                        count++;
                    }
                }); 
            });
            if(count>0){
                $('#cmb_materia').prop("disabled",false); 
                $("#cmb_materia")[0].selectedIndex=0;
            }
            $("#cmb_materia").html(html);
            //  alert(html);
        }
    });
}


function listarParalelos(){
    var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/listarparalelos";
    var asi_id = $('#cmb_materia option:selected').val();
    var mod_id = $('#cmb_modalidad option:selected').val();
    var paca_id = $('#cmb_periodo option:selected').val();
    var pro_id = $('#cmb_profesor_asis option:selected').val();
    data = new FormData();
    data.append( 'asi_id' , asi_id );
    data.append( 'mod_id', mod_id);
    data.append( 'paca_id' , paca_id);
    data.append( 'pro_id' , pro_id);
    //alert(link);
    //DBE
    $.ajax({
        data: data,
        type: "POST",
        dataType: "json",
        cache      : false,
        contentType: false,
        processData: false,
        async: false,
        url: link,
        success: function (data) {
            var datos = JSON.stringify(data);
            var obj = JSON.parse(datos);
            var html = '<option value="0" selected="">Seleccionar</option>';
            var id = 0;
            var count = 0;
            //alert(obj);
            $.each(obj.allModels, function( index, value ) {
                var data = (value);                    
                $.each(data, function( index2, value2 ) {
                    if(index2 == 'id'){
                        id = value2;
                    }
                    if(index2 == 'nombre'){
                        html = html + `<option value=${id} >${value2}</option>`;
                        count++;
                    }
                }); 
            });
            /*
            if(count>0){
               $('#cmb_parcial').prop("disabled",false); 
               $("#cmb_parcial")[0].selectedIndex=0;
            }else{
               $('#cmb_parcial').prop("disabled",true); 
           }
            $("#cmb_parcial").html(html);*/
            // alert(html);
        }
    });
}
function crearAsistencias(){
    var count = $('#cantidad_registros').val();
    //alert('Asistencias Creadas '+count);
    var link = $('#txth_base').val() + "/academico/asistenciaregistrodocente/registrarasistencia";
    var contenido = '';
    var pro = $('#cmb_profesor_asis option:selected').val();
    var paca = $('#txth_periodo').val();
    var materia =$('#txth_materia').val();
    var sesion =$('#txth_sesion').val();
    var unidad =$('#txth_unidad').val();
    // if($('#cmb_sesion option:selected').val() != 0){
        for($i = 1; $i <= count; $i++){
            var id = $('#id_'+$i).text();
            var asi_id = $('#asi_id_'+$i).text();
            var paca_id = $('#paca_id_'+$i).text();
            var mod_id = $('#mod_id_'+$i).text();
            var pro_id = $('#pro_id_'+$i).text();
            var est_id = $('#est_id_'+$i).text();
            var daho_id = $('#daho_id_'+$i).text();
            var rmtm_id = $('#rmtm_id'+$i).text();
            var parcial = $('#parcial_'+$i).text();
            var atraso = $('#entrada_'+$i).val();
            var retiro = $('#salida_'+$i).val();
            var check = $('#entrada_'+$i).prop('checked', this.checked); 
            // var ch = $('#entrada_'+$i+'::checked').length();
            $('#txt_inicio_'+$i).each(function () {    
                $('#txt_inicio_'+$i+':checkbox').prop('checked', this.checked);  
                if($(this).prop("checked") == true){
                    console.log("Checkbox_"+$i+" is checked.");
                    contenido = contenido+'per_id: '+id+'-'+asi_id+'-'+paca_id+'-'+mod_id+'-'+pro_id+'-'+est_id+'-'+daho_id+'-'+rmtm_id+'-'+atraso+'-'+retiro+'-'+parcial+',';
                }
                else if($(this).prop("checked") == false){
                    console.log("Checkbox is unchecked.");
                }  
            });
            
        }
    // }else{
    //     alert('Escoja una sesion para continuar.');
    // }
    contenido = contenido.slice(0, -1);
    alert('Listado '+contenido);
    var arrParams = new Object();
    arrParams.contenido = contenido;
    arrParams.profesor = pro;
    arrParams.periodo = paca;
    arrParams.materia = materia;
    arrParams.sesion = sesion;
    arrParams.unidad = unidad;
    arrParams.sesion = $('#cmb_sesion option:selected').val();


        arrParams.DATAS = sessionStorage.dts_datosItemplan
        requestHttpAjax(link, arrParams, function (response) {
            var message = response.message;
            if (response.status == "OK") {
                showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
                setTimeout(function () {
                    //parent.window.location.href = $('#txth_base').val() + "/academico/asistenciaregistrodocente/registrarasistencia";
                }, 4000);
            } else {
                showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
            }
        }, true);
        
        
    // $.ajax({
    //     data: data,
    //     type: "POST",
    //     dataType: "json",
    //     cache      : false,
    //     contentType: false,
    //     processData: false,
    //     async: false,
    //     url: link,
    //     success: function (data) {
    //         var datos = JSON.stringify(data);
    //         var obj = JSON.parse(datos);
    //         var html = '<option value="0" selected="">Seleccionar</option>';
    //         var id = 0;
    //         var count = 0;
    //         //alert(obj);
    //         $.each(obj.allModels, function( index, value ) {
    //             var data = (value);                    
    //             $.each(data, function( index2, value2 ) {
    //                 if(index2 == 'id'){
    //                     id = value2;
    //                 }
    //                 if(index2 == 'nombre'){
    //                     html = html + `<option value=${id} >${value2}</option>`;
    //                     count++;
    //                 }
    //             }); 
    //         });
    //         if(count>0){
    //             $('#cmb_materia').prop("disabled",false); 
    //             $("#cmb_materia")[0].selectedIndex=0;
    //         }
    //         $("#cmb_materia").html(html);
    //         //  alert(html);
    //     }
    // });
}