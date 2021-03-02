/*
 * It is released under the terms of the following BSD License.
 * Authors:
 * Kleber Loayza <kloayza@uteg.edu.ec>
 */

$(document).ready(function () {
    /* codigo de area en datos personales*/

    /* Nacimiento */
    $('#cmb_pais_nac').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        arrParams.getarea = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_nac");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_nac");
                        }
                    }, true);
                }

            }
        }, true);
        $("#lbl_codeCountry").text($("#cmb_pais_nac option:selected").attr("data-code"));
        $("#lbl_codeCountrycon").text($("#cmb_pais_nac option:selected").attr("data-code"));
        $("#lbl_codeCountrycell").text($("#cmb_pais_nac option:selected").attr("data-code"));
    });

    $('#cmb_prov_nac').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_nac");
            }
        }, true);
    });

    /* Domicilio */
    $('#cmb_pais_dom').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_prov_dom");
                var arrParams = new Object();
                if (data.provincias.length > 0) {
                    arrParams.prov_id = data.provincias[0].id;
                    arrParams.getcantones = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboData(data.cantones, "cmb_ciu_dom");
                        }
                    }, true);
                }
            }
        }, true);
        $("#lbl_codeCountrydom").text($("#cmb_pais_dom option:selected").attr("data-code"));
    });

    $('#cmb_prov_dom').change(function () {
        var link = $('#txth_base').val() + "/ficha/create";
        var arrParams = new Object();
        arrParams.prov_id = $(this).val();
        arrParams.getcantones = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.cantones, "cmb_ciu_dom");
            }
        }, true);
    });

    $('#cmb_raza_etnica').change(function () {
        var valor = $('#cmb_raza_etnica').val();
        if (valor == 6) {
            $("#txt_otra_etnia").removeAttr("disabled");
        } else {
            $("#txt_otra_etnia").attr('disabled', 'disabled');
            $("#txt_otra_etnia").val("");
        }
    });

    /*GUARDAR INFORMACION*/
    $('#btn_save_1').click(function () {
        var arrParams = new Object();
        var link = $('#txth_base').val() + "/admision/ficha/guardarficha";
        arrParams.persona_id = $('#txth_ids').val();
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
        arrParams.celular_persona = $('#txt_celular').val();
        arrParams.tsangre_persona = $('#cmb_tipo_sangre').val();
        if ($('input[name=signup-ecu]:checked').val() == 1) {
            arrParams.nacecuador = 1;
        } else {
            arrParams.nacecuador = 0;
        }
        arrParams.nombre_contacto = $('#txt_nombres_contacto').val();
        arrParams.apellido_contacto = $('#txt_apellidos_contacto').val();
        arrParams.telefono_contacto = $('#txt_telefono_con').val();
        arrParams.celular_contacto = $('#txt_celular_con').val();
        arrParams.direccion_contacto = $('#txt_address_con').val();
        arrParams.parentesco_contacto = $('#cmb_parentesco_con').val();

        arrParams.paisd_domicilio = $('#cmb_pais_dom').val();
        arrParams.provinciad_domicilio = $('#cmb_prov_dom').val();
        arrParams.cantond_domicilio = $('#cmb_ciu_dom').val();
        arrParams.telefono_domicilio = $('#txt_telefono_dom').val();
        arrParams.sector_domicilio = $('#txt_sector_dom').val();
        arrParams.callep_domicilio = $('#txt_cprincipal_dom').val();
        arrParams.calls_domicilio = $('#txt_csecundaria_dom').val();
        arrParams.numero_domicilio = $('#txt_numeracion_dom').val();
        arrParams.referencia_domicilio = $('#txt_referencia_dom').val();

        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function (response) {
                showAlert(response.status, response.label, response.message);
                setTimeout(function () {
                    window.location.href = $('#txth_base').val() + "/admision/interesados/index";
                }, 3000);
            }, true);
        }
    });

});

function guardarFichaAspirante() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/ficha/guardarficha";
    arrParams.persona_id = $('#txth_ids').val(); 
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
    arrParams.celular_persona = $('#txt_celular').val();
    arrParams.tsangre_persona = $('#cmb_tipo_sangre').val();
    if ($('input[name=signup-ecu]:checked').val() == 1) {
        arrParams.nacecuador = 1;
    } else {
        arrParams.nacecuador = 0;
    }
    arrParams.nombre_contacto = $('#txt_nombres_contacto').val();
    arrParams.apellido_contacto = $('#txt_apellidos_contacto').val();
    arrParams.telefono_contacto = $('#txt_telefono_con').val();
    arrParams.celular_contacto = $('#txt_celular_con').val();
    arrParams.direccion_contacto = $('#txt_address_con').val();
    arrParams.parentesco_contacto = $('#cmb_parentesco_con').val();
    arrParams.paisd_domicilio = $('#cmb_pais_dom').val();
    arrParams.provinciad_domicilio = $('#cmb_prov_dom').val();
    arrParams.cantond_domicilio = $('#cmb_ciu_dom').val();
    arrParams.telefono_domicilio = $('#txt_telefono_dom').val();
    arrParams.sector_domicilio = $('#txt_sector_dom').val();
    arrParams.callep_domicilio = $('#txt_cprincipal_dom').val();
    arrParams.calls_domicilio = $('#txt_csecundaria_dom').val();
    arrParams.numero_domicilio = $('#txt_numeracion_dom').val();
    arrParams.referencia_domicilio = $('#txt_referencia_dom').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function (response) {
            showAlert(response.status, response.label, response.message);
            setTimeout(function () {              
                if ($('#txth_mat').val()) {
                    window.location.href = $('#txth_base').val() + "/academico/admitidos/matriculado";
                } else {
                    window.location.href = $('#txth_base').val() + "/admision/interesados/index";
                }
            }, 3000);
        }, true);
    }
}