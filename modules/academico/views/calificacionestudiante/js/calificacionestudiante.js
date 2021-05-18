$(document).ready(function () {
    
    $('#btn_buscarDataestClfcns').click(function() {
         searchCalificacionEstudiantesPorPeriodo();
    });


    // Combo para ver calificaciones por periodo
    $('#cmb_unidad_bus').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionestudiante/index";
        document.getElementById("cmb_carrera_bus").options.item(0).selected = 'selected';
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getmodalidad = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidad, "cmb_modalidad_bus", "Select");
                var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidad_bus').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "cmb_carrera_bus", "Select");
                        }
                    }, true);
                }
            }
        }, true);
    });
    /*$('#cmb_modalidad_bus').change(function () {
        var link = $('#txth_base').val() + "/academico/calificacionestudiante/index";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad_bus').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "cmb_carrera_bus", "Select");
            }
        }, true);
    });*/

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

function searchCalificacionEstudiantesPorPeriodo() {
  var arrParams = new Object();
  arrParams.PBgetFilter = true;

	unidad = $("#cmb_unidad_bus").val();
	modalidad = $("#cmb_modalidad_bus").val();
	carrera = $("#cmb_carrera_bus").val();	
    periodo = $("#cmb_periodo").val();

  if (!$(".blockUI").length) {
      //showLoadingPopup();
      // ver esa funcion PbGridView, se adapte a GridView
      $('#Tbg_Calificaciones').PbGridView('applyFilterData', { 'unidad': unidad, 'modalidad': modalidad, 'carrera': carrera,
       'periodo': periodo,'PBgetFilter': true});

    /*$('#Tbg_Calificaciones').PbGridView('applyFilterData', { 'profesor': profesor,'periodo': periodo,
       'materia': materia, 'unidad': unidad, 'modalidad': modalidad,'paralelo': paralelo ,'PBgetFilter': true});*/
      //setTimeout(hideLoadingPopup, 2000);
  }
}
