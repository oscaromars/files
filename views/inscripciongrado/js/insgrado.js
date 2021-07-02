$(document).ready(function () {
	var unisol = $('#cmb_unidad').val();
    if (unisol == 1) {
        $('#divIdUnidad').css('display', 'none');
    } else if (unisol == 2) {
        $('#divIdUnidad').css('display', 'block');
    }
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
    $('#cmb_pais').change(function() {
        var link = $('#txth_base').val() + "/inscripciongrado/index";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_provincia");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function(response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciudad");
                        }
                    }, true);
                }

            }
        }, true);
    });

    $('#cmb_provincia').change(function() {
        var link = $('#txth_base').val() + "/inscripciongrado/index";
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
    $('#cmb_modalidad').change(function () {
        var link = $('#txth_base').val() + "/inscripciongrado/index";
        var arrParams = new Object();
        arrParams.uaca_id = $('#cmb_unidad').val();
        arrParams.moda_id = $(this).val();
        arrParams.eaca_id = $('#cmb_carrera').val();
        arrParams.empresa_id = 1;
        arrParams.getmalla = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.mallaca, "cmb_malla", "Seleccionar");
            }
        }, true);
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
