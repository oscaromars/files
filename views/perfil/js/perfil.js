/*
 * It is released under the terms of the following BSD License.
 * Authors:
 * Diana Lopez <dlopez@uteg.edu.ec>
 */

$(document).ready(function() {

    /* Nacimiento */
    $('#cmb_pais_nac').change(function() {
        var link = $('#txth_base').val() + "/perfil/update";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_nac");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function(response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_nac");
                        }
                    }, true);
                }
            }
        }, true);
        // actualizar codigo pais
        $("#lbl_codeCountry").text($("#cmb_pais_nac option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_pais_nac option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_pais_nac option:selected").attr("data-code"));
    });

    $('#cmb_prov_nac').change(function() {
        var link = $('#txth_base').val() + "/perfil/update";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_nac");
            }
        }, true);
    });

    /* Domicilio */
    $('#cmb_pais_dom').change(function() {
        var link = $('#txth_base').val() + "/perfil/update";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_dom");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function(response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_dom");
                        }
                    }, true);
                }
            }
        }, true);
        // actualizar codigo pais   
        $("#lbl_codeCountrydom").text($("#cmb_pais_dom option:selected").attr("data-code"));
    });

    $('#cmb_prov_dom').change(function() {
        var link = $('#txth_base').val() + "/perfil/update";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_dom");
            }
        }, true);
    });

    $('#cmb_raza_etnica').change(function() {
        var valor = $('#cmb_raza_etnica').val();
        if (valor == 6) {
            $("#txt_otra_etnia").removeAttr("disabled");
        } else {
            $("#txt_otra_etnia").attr('disabled', 'disabled');
            $("#txt_otra_etnia").val("");
        }
    });

    // tabs actualizar
    $('#paso1next').click(function() {
        save();
        $("a[data-href='#paso1']").attr('data-toggle', 'none');
        $("a[data-href='#paso1']").parent().attr('class', 'disabled');
        $("a[data-href='#paso1']").attr('data-href', $("a[href='#paso1']").attr('href'));
        $("a[data-href='#paso1']").removeAttr('href');
        $("a[data-href='#paso2']").attr('data-toggle', 'tab');
        $("a[data-href='#paso2']").attr('href', $("a[data-href='#paso2']").attr('data-href'));
        $("a[data-href='#paso2']").trigger("click");
    });

    $("#txt_doc_foto").change(function() { 
        mostrarImagen(this);
    });
});

/*GUARDAR INFORMACION EN TAB1 */
function save() {

    var arrParams = new Object();
    var link = $('#txth_base').val() + "/perfil/guardartab1";
    //FORM 1 datos personal
    arrParams.foto_persona = $('#txth_doc_foto').val();
    arrParams.pnombre_persona = $('#txt_primer_nombre').val();
    arrParams.snombre_persona = $('#txt_segundo_nombre').val();
    arrParams.papellido_persona = $('#txt_primer_apellido').val();
    arrParams.sapellido_persona = $('#txt_segundo_apellido').val();
    arrParams.genero_persona = $('#cmb_genero').val();
    arrParams.etnia_persona = $('#cmb_raza_etnica').val();
    arrParams.etnia_otra = $('#txt_otra_etnia').val();
    arrParams.ecivil_persona = $('#txt_estado_civil').val();
    arrParams.fnacimiento_persona = $('#txt_fecha_nacimiento').val();
    arrParams.pnacionalidad = $('#txt_nacionalidad').val();
    arrParams.pais_persona = $('#cmb_pais_nac').val();
    arrParams.provincia_persona = $('#cmb_prov_nac').val();
    arrParams.canton_persona = $('#cmb_ciu_nac').val();
    arrParams.correo_persona = $('#txt_ftem_correo').val();
    arrParams.correo_institucional = $('#txt_ftem_correo1').val();
    arrParams.telefono_persona = $('#txt_telefono').val();
    arrParams.celular_persona = $('#txt_celular').val();
    arrParams.tsangre_persona = $('#cmb_tipo_sangre').val();
    if ($('input[name=signup-ecu]:checked').val() == 1) {
        arrParams.nacecuador = 1;
    } else {
        arrParams.nacecuador = 0;
    }
    //FORM 1 Informacion de Contacto
    arrParams.nombre_contacto = $('#txt_nombres_contacto').val();
    arrParams.apellido_contacto = $('#txt_apellidos_contacto').val();
    arrParams.telefono_contacto = $('#txt_telefono_con').val();
    arrParams.celular_contacto = $('#txt_celular_con').val();
    arrParams.direccion_contacto = $('#txt_address_con').val();
    arrParams.parentesco_contacto = $('#cmb_parentesco_con').val();

    //FORM 2 Datos Domicilio
    arrParams.paisd_domicilio = $('#cmb_pais_dom').val();
    arrParams.provinciad_domicilio = $('#cmb_prov_dom').val();
    arrParams.cantond_domicilio = $('#cmb_ciu_dom').val();
    arrParams.telefono_domicilio = $('#txt_telefono_dom').val();
    arrParams.sector_domicilio = $('#txt_sector_dom').val();
    arrParams.callep_domicilio = $('#txt_cprincipal_dom').val();
    arrParams.calls_domicilio = $('#txt_csecundaria_dom').val();
    arrParams.numero_domicilio = $('#txt_numeracion_dom').val();
    arrParams.referencia_domicilio = $('#txt_referencia_dom').val();

    // alert('Entro JavaScript'+arrParams.toSource());
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/perfil/update";
            }, 3000);
        }, true);
    }
}

/* Cargado de Imagen en Expediente Profesor Autor : Omar Romero */
function mostrarImagen(input) { 
    if (input.files && input.files[0]) {  
        var reader = new FileReader();  
        reader.onload = function(e) {    $('#img_destino').attr('src', e.target.result);   }  
        reader.readAsDataURL(input.files[0]); 
    }
} 
function saveCropImage() {
    var objCropImg = getPosCoord();
    var arrParams = new Object();
    arrParams.crop_file = true;
    arrParams.x = objCropImg.x;
    arrParams.y = objCropImg.y;
    arrParams.w = objCropImg.w;
    arrParams.h = objCropImg.h;
    var link = $('#txth_base').val() + "/perfil/savepicture";
    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
            setTimeout(function() {
                location.href = $('#txth_base').val() + "/perfil/update";
            }, 3000);
        }
    }, true);
}