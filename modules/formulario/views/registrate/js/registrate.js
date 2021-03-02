/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    $('#cmb_provincia').change(function() {
        var link = $('#txth_base').val() + "/formulario/registrate/index";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciudad");
            }
        }, true);
    });
    
    $('#cmb_unidad').change(function() {
        var link = $('#txth_base').val() + "/formulario/registrate/index";
        var arrParams = new Object();
        arrParams.uaca_id = $(this).val();
        arrParams.getcarrera = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.carr_prog, "cmb_carrera_programa");
            }
        }, true);
    });
    
    $('#registrar').click(function() {
        var link = $('#txth_base').val() + "/formulario/registrate/save";
        var arrParams = new Object();
        arrParams.tipoidentifica = $('#cmb_tipo_dni').val();
        if ($('#cmb_tipo_dni').val() == 'CED') {
            arrParams.identificacion = $('#txt_cedula').val();
        } else {
            arrParams.identificacion = $('#txt_pasaporte').val();
        }
        arrParams.nombres = $('#txt_nombre').val();
        arrParams.apellidos = $('#txt_apellido').val();
        arrParams.correo = $('#txt_correo').val();
        arrParams.celular = $('#txt_celular').val();
        arrParams.telefono = $('#txt_telefono').val();        
        arrParams.pro_id = $('#cmb_provincia').val();
        arrParams.can_id = $('#cmb_ciudad').val();
        arrParams.institucion = $('#txt_institucion').val();
        arrParams.unidad = $('#cmb_unidad').val();
        arrParams.carrera_programa = $('#cmb_carrera_programa').val();
       
        if ($('input[name=signup-si]:checked').val() == 1) {
            arrParams.estudio_anterior = 1;
            arrParams.institucion_acad = $('#cmb_institucion').val();
            arrParams.carrera_ant = $('#txt_carrera').val();
        } else {
            arrParams.estudio_anterior = 0;
            arrParams.institucion_acad = 0;
            arrParams.carrera_ant = "";
        }         
        
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                if (!response.error) {
                    setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/formulario/registrate/index";
                    }, 5000);
                }
            }, true);
        }
    });

    $('#cmb_tipo_dni').change(function() {
        if ($('#cmb_tipo_dni').val() == 'PASS') {
            $('#txt_cedula').removeClass("PBvalidation");
            $('#txt_pasaporte').addClass("PBvalidation");
            $('#Divpasaporte').show();
            $('#Divcedula').hide();
        } else if ($('#cmb_tipo_dni').val() == 'CED') {
            $('#txt_pasaporte').removeClass("PBvalidation");
            $('#txt_cedula').addClass("PBvalidation");
            $('#Divpasaporte').hide();
            $('#Divcedula').show();
        }
    });

    //Control del div de discapacidad
    $('#signup-si').change(function () {
        if ($('#signup-si').val() == 1) {
            $('#otro_estudio').css('display', 'block');
            $("#signup-no").prop("checked", "");
        } else {
            $('#otro_estudio').css('display', 'none');
        }
    });

    $('#signup-no').change(function () {
        if ($('#signup-no').val() == 0) {
            $('#otro_estudio').css('display', 'none');
            $("#signup-si").prop("checked", "");
        } else {
            $('#otro_estudio').css('display', 'block');
        }
    });
});