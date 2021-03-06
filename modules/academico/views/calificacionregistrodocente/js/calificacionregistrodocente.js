$(document).ready(function () {

  if ( $('#cmb_periodo_rc option:selected').val() > 0 ){ 
  if (  $('#cmb_unidad_rc').val()  > 0 ){ 
  if ( $('#cmb_modalidad_rc option:selected').val()  > 0 ){ 
  if ( $('#cmb_materia option:selected').val()   > 0 ){  
  if ( $('#cmb_parcial').val()  > 0 ){ 
  if ( $('#cmb_profesor_rc').val()  > 0 ){ 
 showAlert('OK', 'success', {"wtmessage": 'Espere la carga de estudiantes..', "title": 'Información'});
    actualizarGridRegistro();
  }}}}}}

    $('#btn_guardarcalificacion').click(function() {
        cargarDocumento();
    });
    $('#btn_limpiarbuscador').click(function () {
        limpiarBuscador();
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
        arrParams.pro_id = $('#cmb_profesor option:selected').val();
        arrParams.mod_id = $('#cmb_modalidad_m option:selected').val();
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
        arrParams.pro_id  = $('#cmb_profesor option:selected').val();
        arrParams.mod_id  = $('#cmb_modalidad_m option:selected').val();

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

    $('#btn_buscarEducativa').click(function() {    
        searchEducativa();
    });

     $('#btn_buscarEducativaulas').click(function() {    
        searchEducativaulas();
    });


          $('#cmb_modalidad_aul').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/transferiraulas";
        var arrParams = new Object();
        arrParams.paca_id = $("#cmb_periodo_aul").val(); 
        arrParams.uaca_id = $("#cmb_unidad_aul").val();
        arrParams.mod_id = $(this).val();
        arrParams.getteraulas = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.arr_aulas, "cmb_aulas_aul","Todos");
            }
        }, true);
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


     $('#cmb_unidad_bus').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/index";
        var arrParams = new Object();
        arrParams.paca_id = $('#cmb_periodo_clfc').val();
        arrParams.uaca_id = $(this).val();
        arrParams.mod_id = $('#cmb_modalidad').val();
        arrParams.pro_id =  $('#cmb_profesor_clfc').val();
        arrParams.getasignaturas_uaca = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                 setComboDataselect(data.modalidad, "cmb_modalidad","Todos");
                 setComboDataselect(data.asignatura, "cmb_materiabus","Todos");
                 setComboDataselectpro(data.profesorup, "cmb_profesor_clfc","Todos");
            }
        }, true);
    });

     $('#cmb_periodo_clfc').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/index";
        var arrParams = new Object();
        arrParams.paca_id = $(this).val();
        arrParams.uaca_id = $('#cmb_unidad_bus').val();
        arrParams.mod_id = $('#cmb_modalidad').val();
        arrParams.pro_id = $('#cmb_profesor_clfc').val();
        arrParams.getasignaturas_prof_periodo = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignatura, "cmb_materiabus", "Todos");
                setComboDataselectpro(data.profesorup, "cmb_profesor_clfc","Todos");
            }
        }, true);
         });

      $('#cmb_modalidad').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/index";
        var arrParams = new Object();
        arrParams.paca_id = $('#cmb_periodo_clfc').val();
        arrParams.uaca_id = $('#cmb_unidad_bus').val();
        arrParams.mod_id = $(this).val();
        arrParams.pro_id = $('#cmb_profesor_clfc').val();
        arrParams.getasignaturas_bus = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignatura, "cmb_materiabus","Todos");
                 setComboDataselectpro(data.profesorup, "cmb_profesor_clfc","Todos");
            }
        }, true);
    });

    $('#cmb_periodo_rc').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/registro";
        var arrParams = new Object();
        arrParams.paca_id = $(this).val();
        arrParams.uaca_id = $('#cmb_unidad_rc').val();
        arrParams.mod_id = $('#cmb_modalidad_rc').val();
        arrParams.pro_id = $('#cmb_profesor_rc').val();
        arrParams.getasignaturas_prof_periodo_reg = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignatura, "cmb_materia","Todos");
                setComboDataselectpro(data.profesorreg, "cmb_profesor_rc","Todos");
            }
        }, true);
    });

    $('#cmb_modalidad_rc').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/registro";
        var arrParams = new Object();
        arrParams.paca_id = $('#cmb_periodo_rc').val();
        arrParams.uaca_id = $('#cmb_unidad_rc').val();
        arrParams.mod_id = $(this).val();
        arrParams.pro_id = $('#cmb_profesor_rc').val();
        arrParams.getasignaturas_bus_reg = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.asignatura, "cmb_materia","Todos");
                setComboDataselectpro(data.profesorreg, "cmb_profesor_rc","Todos");
            }
        }, true);
    });

    $('#cmb_unidad_rc').change(function () { 
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/registro";
        var arrParams = new Object();
        arrParams.paca_id = $('#cmb_periodo_rc').val();
        arrParams.uaca_id = $(this).val();
        arrParams.mod_id = $('#cmb_modalidad_rc').val();
        arrParams.pro_id =  $('#cmb_profesor_rc').val();
        arrParams.getasignaturas_uaca_reg = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                 setComboDataselect(data.modalidad, "cmb_modalidad_rc","Todos");
                 setComboDataselect(data.asignatura, "cmb_materia","Todos");
                 setComboDataselectpro(data.profesorreg, "cmb_profesor_rc","Todos");
            }
        }, true);
    });


    $('#cmb_profesor_rc').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/registro";
        var arrParams = new Object();
        arrParams.pro_id  = $(this).val();
        arrParams.uaca_id = $('#cmb_unidad_rc').val();
        arrParams.mod_id  = $('#cmb_modalidad_rc').val();
        arrParams.paca_id = $('#cmb_periodo_rc').val();
        arrParams.getmateria = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.materia, "cmb_materia","Todos");

            }
        }, true);
    });
     
    $('#btn_download_acta').click(function() {    
        downloadDataClfc();
    });
    
});//Document ready


function limpiarBuscador(){
    //alert($('#txth_base').val());
    window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/index";
 }

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

 

function cargarDocumento() {
    var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/cargararchivo";

    var arrParams = new Object();
    
    arrParams.procesar_file = true;

    arrParams.asi_id = $('#cmb_asig option:selected').val();
    arrParams.ecal_id = $('#cmb_parcial option:selected').val();
    arrParams.pro_id = $('#cmb_profesor option:selected').val();
    arrParams.paca_id = $('#cmb_periodo option:selected').val();
    arrParams.mod_id = $('#cmb_modalidad_m option:selected').val();
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
    estudiante = $("#cmb_buscarest").val();

    if(profesor == null ||  profesor == -1){
        var mensaje = {wtmessage: "No hay un profesor o no ha sido asignado", title: "Exito"};
        showAlert("FALSE", "success", mensaje);
        return;
    }

    if (!$(".blockUI").length) {
        //showLoadingPopup();
        // ver esa funcion PbGridView, se adapte a GridView
        $('#Tbg_Calificaciones').PbGridView('applyFilterData', { 'profesor': profesor,'periodo': periodo,
         'materia': materia, 'unidad': unidad, 'modalidad': modalidad,'estudiante': estudiante,'PBgetFilter': true});
        //setTimeout(hideLoadingPopup, 2000);
    }
}

function searchEducativa() {
    periodo = $("#cmb_periodo_all").val();
    unidad = $("#cmb_unidad_all").val();
    modalidad = $("#cmb_modalidad_all").val();
    parcial = $("#cmb_parcial_all").val();
    nparcial ="cmb_parcial_all";

    if (parcial == 0) {
        document.getElementById(nparcial).style.borderColor = '#aa0000';
        //showAlert('FALSE', 'success', {"wtmessage": 'Elija Parcial', "title": 'Información'});

    } else {
        window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/educativa?paca="+periodo+"&unidad="+unidad+"&modalidad="+modalidad+"&parcial="+parcial;
    }
 
}

function searchEducativaulas() {
    periodo = $("#cmb_periodo_aul").val();
    unidad = $("#cmb_unidad_aul").val();
    modalidad = $("#cmb_modalidad_aul").val();
    aula = $("#cmb_aulas_aul").val();
    parcial = $("#cmb_parcial_aul").val();
    
 if (parcial == 0) {
document.getElementById('cmb_parcial_aul').style.borderColor = '#aa0000';

 } else {

window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/transferiraulas?paca="+periodo+"&unidad="+unidad+"&modalidad="+modalidad+"&aula="+aula+"&parcial="+parcial;
 

}}


function transferAula(id,ecal_id) {
  showLoadingPopup();
window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/transferer?eduasid="+id+"&parcial="+ecal_id;
  //hideLoadingPopup();
}

function activateCron(cronid) {
  modalidades = $("#txth_modalidades").val();
  // currente = $(this).val();
  // currente = $("input").val();

 //var elem = document.getElementById('F2').value;
 //alert( element );
  currente = $("#F2").val();
  idf= "#F"+cronid;
  id= "F"+cronid;
  currente=$(idf).val();;

 if (currente == undefined  ||  currente == '') {
document.getElementById(id).style.borderColor = '#aa0000';

 //showAlert('FALSE', 'success', {"wtmessage": 'Seleccione la fecha a ejecutarse!', "title": 'Información'});

 
}else { 



window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/activacron?cronid="+cronid+"&fecha="+currente+"&moda="+modalidades;
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

 
if(materia != 0 ) {

    window.location.href = $('#txth_base').val() + "/academico/calificacionregistrodocente/exportpdfclfc?pdf=1&paca="+periodo+"&unidad="+unidad+"&materia="+materia+"&profesor="+profesor;
 
 } else {

    showAlert('NO_OK', 'error', {"wtmessage": 'Para Generar el Acta, Seleccione Docente y Asignatura', "title": 'Información'});

 }

}

var table = '';

//function actualizarGridRegistro
function actualizarGridRegistro(dready = 0) {
    //Listado de parametros para ser enviados al servidor para desplegar la inforacion del grid
    var arrParams       = new Object();
    arrParams.periodo   = $('#cmb_periodo_rc option:selected').val();
    arrParams.uaca_id   = $('#cmb_unidad_rc').val();
    arrParams.mod_id    = $('#cmb_modalidad_rc option:selected').val();  
    arrParams.materia   = $('#cmb_materia option:selected').val();  
    arrParams.parcial   = $('#cmb_parcial').val();
    arrParams.profesor  = $('#cmb_profesor_rc').val();
    arrParams.grupo     = $('#frm_arr_grupo').val();

    //URL para actualizar el grid
    var link = $('#txth_base').val() + "/academico/calificacionregistrodocente/traermodelo";
    
    //Llamado del ajax
    requestHttpAjax(link, arrParams, function (response) {
        //console.log(response);
        //Esta es la funcion en el controlador que actualizara las notas
        var url_editor = $('#txth_base').val() + "/academico/calificacionregistrodocente/actualizarnota";
        var isreg = response['isreg'];
        if (isreg){
            var contenedor = document.getElementById('periodocalif');
            contenedor.innerHTML = "<b style='color:green'>REGISTRO DE CALIFICACIONES ESTARÁ ABIERTO HASTA "+isreg['fin']+ "</b>";
            var bandera_edi = 1;
        }else{
            var contenedor = document.getElementById('periodocalif');
            contenedor.innerHTML = "<b style='color:red'>EL PERIODO DE CALIFICACIONES ESTÁ CERRADO</b>";
            var bandera_edi = 0;
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
                {
                    name: "ccal_id",
                    type:  "hidden",
                },
                {
                    name: "nparcial",
                    type:  "hidden",
                },
                {
                    name: "mod_id",
                    type:  "hidden",
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
            //console.log(componentes);
            if(action == 'edit' ){
                $.each(o.data[indice], function( index, value ) {
                    if(componentes[index]){
                        if(value < 0 || value > parseInt(componentes[index]['notamax'])){
                            alertify.error("El cambio no se ha registrado, los valores del componente "+componentes[index]['nombre']+" debe estar entre 0 a "+componentes[index]['notamax']);
                            bandera = 1;
                        }//if
                    }//if    
                
                });
            }

            if(bandera == 1) return false;
        });

        editor.on('submitComplete', function (e, json, data, action) {
            console.log(data);
            if(action == 'edit'){
                alertify.success('Registro editado con exito');
            }//if
            //actualizarGridRegistro(0);
        });

        // Activate an inline edit on click of a table cell
            
        editor.on( 'opened', function ( e, json, data ) {       
            $('#DTE_Field_nparcial').addClass("form-control");
            //$('#DTE_Field_est_nivel').attr('data-container',"body");
            //$('#DTE_Field_est_nivel').selectpicker();
        });

        

        $("#html_thead").html(''); 
        var html = `
            <tr>
                <th class='no-sort'><i class='fa fa-clone' aria-hidden='true'></i></th>
                <th></th>
                <th>No.</th>
                <th>Matricula</th>
                <th>Nombre</th>
                <th>Materia</th>
                <th>Parcial</th>
                <th>Paralelo</th>`;       

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
                { data: "nparcial"},
                { data: "paralelo"}
                ]; 
        
        var centrar = [];
        var numeroCols = 6;

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
        
        
        numeroCols++;
        
        console.log("# de columnnas = "+numeroCols);

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


        var columnas2 =[ 
                { data: "total"},
                { data: "paca_id"},
                { data: "est_id"},
                { data: "pro_id"},
                { data: "asi_id"},
                { data: "ecal_id"},
                { data: "uaca_id"},
                { data: "ccal_id"},
                { data: "mod_id"},
            ];

        var columnas = columnas1.concat(columnas2);

        html += `<th>Total</th>
                 <th>paca_id</th>
                 <th>est_id</th>
                 <th>pro_id</th>
                 <th>asi_id</th>
                 <th>ecal_id</th>
                 <th>uaca_id</th>
                 <th>ccal_id</th>
                 <th>mod_id</th>
            </tr>`;

        $("#tablacontenedor").html(`<table id="gridResumen" class="display compact responsive nowrap" style="width:100%">
            <thead id="html_thead"></thead></table>`);

        $("#html_thead").html(html);
        //$("#html_thead").parent().html('');
        //$("#html_thead").html('');
        console.log(arrcolumnDefs);

        $('#gridResumen').on( 'click', 'tbody td:not(.child)', function (e) {
            if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
                return;
            }
            editor.inline( this );
        });

        $('#gridResumen').on( 'click', 'tbody ul.dtr-details li', function (e) {
            // Edit the value, but this selector allows clicking on label as well
            editor.inline( $('span.dtr-data', this) );
        } );




        table = $('#gridResumen').DataTable({
            "dom": '<"top"Bf>rt<"bottom"lp><"clear">',
            "data": response['data'],
            //"dom": "Bfrtip",
            buttons: [
                /*{  extend: 'create',editor: editor }, */
                {  extend: 'edit', editor: editor  },
                /*{  extend: 'remove', editor: editor  },*/
            ],
            "lengthMenu": [[ -1,10, 25, 60], ["All",10, 25, 60]],
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