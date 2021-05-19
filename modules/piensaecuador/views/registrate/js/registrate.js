/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    $('#cmb_provincia').change(function() {
        var link = $('#txth_base').val() + "/piensaecuador/registrate/index";
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

    $('#cmb_ocupacion').change(function() {
        var link = $('#txth_base').val() + "/piensaecuador/registrate/index";
        var arrParams = new Object();
        arrParams.ocu_id = $(this).val();
        arrParams.getocupaciones = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.ocupaciones, "cmb_ocupacion");
            }
        }, true);
    });

    $('#registrar').click(function() {
        var link = $('#txth_base').val() + "/piensaecuador/registrate/save";
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
        arrParams.genero = $('#cmb_genero').val();
        arrParams.fechanac = $('#txt_fecha_nacimiento').val();
        arrParams.niv_interes = $('#cmb_nivel_estudio').val();
        arrParams.pro_id = $('#cmb_provincia').val();
        arrParams.can_id = $('#cmb_ciudad').val();
        arrParams.ocu_id = $('#cmb_ocupacion').val();
        arrParams.eve_id = $('#cmb_evento').val();
        //Verificación de los checkboxes.
        var intereses = [];
        var i = 0;
        if ($('#chk_1').prop('checked')) {
            intereses[i] = { "interes_id": 1 };
            i = intereses.length;
        }
        if ($('#chk_2').prop('checked')) {
            intereses[i] = { "interes_id": 2 };
            i = intereses.length;
        }
        if ($('#chk_3').prop('checked')) {
            intereses[i] = { "interes_id": 3 };
            i = intereses.length;
        }
        if ($('#chk_4').prop('checked')) {
            intereses[i] = { "interes_id": 4 };
            i = intereses.length;
        }
        if ($('#chk_5').prop('checked')) {
            intereses[i] = { "interes_id": 5 };
            i = intereses.length;
        }
        if ($('#chk_6').prop('checked')) {
            intereses[i] = { "interes_id": 6 };
            i = intereses.length;
        }
        arrParams.intereses = intereses;
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                if (!response.error) {
                    setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/piensaecuador/registrate/index";
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

});