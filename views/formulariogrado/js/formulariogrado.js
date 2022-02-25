
$(document).ready(function () {

    $('#btn_buscarAspirante').click(function () {
        actualizarGridAspirante();
    });
    $('#btn_editaraspirantegrado').click(function () {
        editaspirantegrado();
    });
    $('#btn_actualizaraspirantegrado').click(function () {
        updateaspirantegrado();
    });
    $('#cmb_modalidad').change(function() {
        var link = $('#txth_base').val() + "/formulariogrado/index";
        var arrParams = new Object();
        arrParams.unidada = $('#cmb_unidad').val();
        arrParams.moda_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.carrera, "cmb_carrera", "Seleccionar");
            }
        }, true);
    });
    $('#cmb_carrera_asp').change(function () {
        var link = $('#txth_base').val() + "/formulariogrado/aspirantegrado";
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidad_asp').val();
        //arrParams.moda_id = $(this).val();
        arrParams.carrera = $(this).val();
        arrParams.empresa_id = 1;
        arrParams.getmodalidades = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.modalidades, "cmb_modalidad_asp", "Seleccionar");
            }
        }, true);
    });

    /* codigo de area en datos personales*/

    $('#cmb_pais').change(function () {
        var link = $('#txth_base').val() + "/formulariogrado/index";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_provincia");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciudad");
                        }
                    }, true);
                }
            }
        }, true);
        // actualizar codigo pais
        $("#lbl_codeCountry").text($("#cmb_pais option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_pais option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_pais option:selected").attr("data-code"));
    });

    $('#cmb_provincia').change(function () {
        var link = $('#txth_base').val() + "/formulariogrado/index";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciudad");
            }
        }, true);
    });

    $('#cmb_paisEdit').change(function () {
        var link = $('#txth_base').val() + "/formulariogrado/edit";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_provinciaEdit");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_cantonEdit");
                        }
                    }, true);
                }
            }
        }, true);
        // actualizar codigo pais
        $("#lbl_codeCountry").text($("#cmb_paisEdit option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_paisEdit option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_paisEdit option:selected").attr("data-code"));
    });

    $('#cmb_provinciaEdit').change(function () {
        var link = $('#txth_base').val() + "/formulariogrado/edit";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_cantonEdit");
            }
        }, true);
    });

    $('#cmb_tipo_dni').change(function () {
        if ($('#cmb_tipo_dni').val() == 'PASS') {
            $('#txt_cedula').removeClass("PBvalidation");
            $('#txt_pasaporte').addClass("PBvalidation");
            $('#Divpasaporte').show();
            $('#Divcedula').hide();
        } else if ($('#cmb_tipo_dni').val() == 'CED')
        {
            $('#txt_pasaporte').removeClass("PBvalidation");
            $('#txt_cedula').addClass("PBvalidation");
            $('#Divpasaporte').hide();
            $('#Divcedula').show();
        }
    });

    $("#txt_cedula").change(function(){
        var link = $('#txth_base').val() + "/formulariogrado/index";
        var arrParams = new Object();
        arrParams.cedulacons = $('#txt_cedula').val();
        arrParams.getcedula = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
             data = response.message;
             persids = data.persids;
             if(persids == null){
                var mensaje = {wtmessage: "La persona no esta registrado como aspirante, no se guardara la información", title: "Información"};
                showAlert("NO_OK", "error", mensaje);
                $('#txth_personaid').val('');
                $('#Divboton').css('display', 'none');
            }else{

               $('#Divboton').css('display', 'block');
               $('#txth_personaid').val(persids);
             }
            }
        }, true);

      });

      $("#txt_pasaporte").change(function(){
        var link = $('#txth_base').val() + "/formulariogrado/index";
        var arrParams = new Object();
        arrParams.cedulacons = $('#txt_pasaporte').val();
        arrParams.getcedula = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
             data = response.message;
             persids = data.persids;
             if(persids == null){
                var mensaje = {wtmessage: "La persona no esta vs como aspirante, no se guardara la información", title: "Información"};
                showAlert("NO_OK", "error", mensaje);
                $('#txth_personaid').val('');
                $('#Divboton').css('display', 'none');
            }else{
                $('#Divboton').css('display', 'block');
                $('#txth_personaid').val(persids);
             }
            }
        }, true);

      });
    // tabs create
    $('#paso1next').click(function () {
        var carrera = $("#cmb_carrera option:selected").val();
        var modalidad = $("#cmb_modalidad option:selected").val();
        var periodo = $("#cmb_periodo option:selected").val();
           if (carrera > 0 ){ a = 1;} else { a = 0; }
                if (modalidad > 0 ){ b = 1;} else { b = 0; }
                        if (periodo > 0 ){ c = 1;} else { c = 0; }
        elem= a + b + c ;

        if (elem < 3 ){

               var mensaje = {wtmessage: "Seleccione Carrera, periodo y modalidad!", title: "Información"};
                showAlert("NO_OK", "error", mensaje);
   
        } else { 
        let lcedula = $("#txt_cedula").val();
        localStorage.setItem("cedula", lcedula);
        //window.location.href = '#';
        let lcedulas =localStorage.getItem("cedula");
        $("#txt_cedula2").val(lcedulas);
        $("a[data-href='#paso1']").attr('data-toggle', 'none');
        $("a[data-href='#paso1']").parent().attr('class', 'disabled');
        $("a[data-href='#paso1']").attr('data-href', $("a[href='#paso1']").attr('href'));
        $("a[data-href='#paso1']").removeAttr('href');
        $("a[data-href='#paso2']").attr('data-toggle', 'tab');
        $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
        $("a[data-href='#paso2']").trigger("click");
         }
    });
    $('#paso2back').click(function () {
        $("a[data-href='#paso2']").attr('data-toggle', 'none');
        $("a[data-href='#paso2']").parent().attr('class', 'disabled');
        $("a[data-href='#paso2']").attr('data-href', $("a[href='#paso2']").attr('href'));
        $("a[data-href='#paso2']").removeAttr('href');
        $("a[data-href='#paso1']").attr('data-toggle', 'tab');
        $("a[data-href='#paso1']").attr('href', $("a[data-href='#paso1']").attr('data-href'));
        $("a[data-href='#paso1']").trigger("click");
    });

    /*GUARDAR INFORMACION*/

    $('#btn_save_1').click(function () {
        guardarInscripcionGrado();
    });

    //Control del div de discapacidad
    $('#signup-dis').change(function () {
        if ($('#signup-dis').val() == 1) {
            $('#discapacidad').css('display', 'block');
            $("#signup-dis_no").prop("checked", "");
        } else {
            $('#discapacidad').css('display', 'none');
        }
    });

    $('#signup-dis_no').change(function () {
        if ($('#signup-dis_no').val() == 2) {
            $('#discapacidad').css('display', 'none');
            $("#signup-dis").prop("checked", "");
        } else {
            $('#discapacidad').css('display', 'block');
        }
    });

    //Control del div de homologacion
    $('#signup-hom').change(function () {
        if ($('#signup-hom').val() == 1) {
            $('#homologacion').css('display', 'block');
            $("#signup-hom_no").prop("checked", "");
        } else {
            $('#homologacion').css('display', 'none');
        }
    });

    $('#signup-hom_no').change(function () {
        if ($('#signup-hom_no').val() == 2) {
            $('#homologacion').css('display', 'none');
            $("#signup-hom").prop("checked", "");
        } else {
            $('#homologacion').css('display', 'block');
        }
    });

    $('#cmb_tipofinanciamiento').change(function () {
        if ($('#cmb_tipofinanciamiento').val() == '3') {
            $('#txt_instituto').addClass("PBvalidation");
            $('#divinstituto').show();
        } else
        {
            $('#txt_instituto').val('');
            $('#txt_instituto').removeClass("PBvalidation");
            $('#divinstituto').hide();
        }
    });

    $('#cmb_financiamientoEdit').change(function () {
        if ($('#cmb_financiamientoEdit').val() == '3') {
            $('#txt_institutoEdit').addClass("PBvalidation");
            $('#divinstitutoEdit').show();
        } else
        {
            $('#txt_institutoEdit').val('');
            $('#txt_institutoEdit').removeClass("PBvalidation");
            $('#divinstitutoEdit').hide();
        }
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

function camposnulos(campo) {
    if ($(campo).val() == "")
    {
        $(campo).removeClass("PBvalidation");
    } else
    {
        $(campo).addClass("PBvalidation");
    }
}

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function actualizarGridAspirante() {
    var search = $('#txt_buscarAspirante').val();
    var periodo = $('#cmb_periodo_asp option:selected').val();
    var unidad = $('#cmb_unidad_asp option:selected').val();
    var carreras = $('#cmb_carrera_asp option:selected').val();
    var modalidad = $('#cmb_modalidad_asp option:selected').val();

    if (!$(".blockUI").length) {
        showLoadingPopup();
    $('#grid_registro_aspirante').PbGridView('applyFilterData', {'search': search, 'periodo': periodo, 'unidad': unidad, 'carreras': carreras, 'modalidad': modalidad});
        setTimeout(hideLoadingPopup, 2000);
    }
}
function editaspirantegrado() {
    var link = $('#txth_base').val() + "/formulariogrado/edit" + "?id=" + $("#frm_per_id").val();
    window.location = link;
}

function updateaspirantegrado() {
    //var ID = $('#txth_igra_id').val();
    var link = $('#txth_base').val() + "/formulariogrado/update";
    var arrParams = new Object();
    arrParams.per_id = $("#frm_per_id").val();
    arrParams.cedula = $('#txt_cedulaEdit').val();
    arrParams.pasaporte = $('#txt_pasaporteEdit').val();
    arrParams.primer_nombre = $('#txt_primer_nombreEdit').val();
    arrParams.segundo_nombre = $('#txt_segundo_nombreEdit').val();
    arrParams.primer_apellido = $('#txt_primer_apellidoEdit').val();
    arrParams.segundo_apellido = $('#txt_segundo_apellidoEdit').val();
    arrParams.cuidad_nac = $('#cmb_ciudadEdit').val();
    arrParams.fecha_nac = $('#txt_fecha_nacimientoEdit').val();
    arrParams.nacionalidad = $('#cmb_nacionalidadEdit').val();
    arrParams.estado_civil = $('#cmb_estadocivilEdit').val();

    //Datos Contacto
    arrParams.pais = $('#cmb_paisEdit').val();
    arrParams.provincia = $('#cmb_provinciaEdit').val();
    arrParams.canton = $('#cmb_cantonEdit').val();
    arrParams.parroquia = $('#txt_parroquiaEdit').val();
    arrParams.dir_domicilio = $('#txt_domicilioEdit').val();
    arrParams.celular = $('#txt_celEdit').val();
    arrParams.telefono = $('#txt_phoneEdit').val();
    arrParams.correo = $('#txt_correoEdit').val();

    //Datos en caso de emergencias
    arrParams.dir_trabajo = $('#txt_trabajo_direccionEdit').val();
    arrParams.cont_emergencia = $('#txt_contc_emergenciasEdit').val();
    arrParams.parentesco = $('#cmb_parentescoEdit').val();
    arrParams.tel_emergencia = $('#txt_cel_contactoEdit').val();
    arrParams.dir_personacontacto = $('#txt_institutoEdit').val();

    //Datos de financiamiento
    arrParams.financiamiento = $('#cmb_financiamientoEdit').val();
    arrParams.instituto = $('#txt_institutoEdit').val();

    //TAB 2
    arrParams.igra_ruta_doc_documento = $('#txth_doc_documento').val();
    arrParams.igra_ruta_doc_titulo = $('#txth_doc_titulo').val();
    arrParams.igra_ruta_doc_dni = $('#txth_doc_dni').val();
    arrParams.igra_ruta_doc_certvota = $('#txth_doc_certvota').val();
    arrParams.igra_ruta_doc_foto = $('#txth_doc_foto').val();
    arrParams.igra_ruta_doc_comprobantepago = $('#txth_doc_comprobantepago').val();
    arrParams.igra_ruta_doc_record = $('#txth_doc_record').val();
    arrParams.igra_ruta_doc_certificado = $('#txth_doc_nosancion').val();
    arrParams.igra_ruta_doc_syllabus = $('#txth_doc_syllabus').val();
    arrParams.igra_ruta_doc_homologacion = $('#txth_doc_especievalorada').val();


    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            //var message = response.message;
            if (response.status == "OK") {
                setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/formulariogrado/aspirantegrado";
                    }, 5000);
            }
        }, true);
    }
}

function exportExcelaspirantegrado() {
    var search = $('#txt_buscarAspirante').val();
    var periodo = $('#cmb_periodo_asp option:selected').val();
    var unidad = $('#cmb_unidad_asp option:selected').val();
    var carreras = $('#cmb_carrera_asp option:selected').val();
    var modalidad = $('#cmb_modalidad_asp option:selected').val();
    window.location.href = $('#txth_base').val() + "/formulariogrado/expexcelaspirantegrado?search=" + search + "&periodo=" + periodo + "&unidad=" + unidad + "&carreras=" + carreras + "&modalidad=" + modalidad;
}
