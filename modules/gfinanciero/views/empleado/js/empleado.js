$(document).ready(function() {
    $(".spanAccStatus").click(function() {
        if ($(this).prev().val() == "1") {
            $(this).find('i:first-child').attr("class", "glyphicon glyphicon-unchecked");
            $(this).prev().val("0");
        } else {
            $(this).find('i:first-child').attr("class", "glyphicon glyphicon-check");
            $(this).prev().val("1");
        }
    });
    $(".spanAccStatus.frm_disc").click(function() {
        if ($(this).prev().val() == "1") {
            $('.csdiscp').show();
        } else {
            $('.csdiscp').hide();
        }
    });
    $('#btn_buscarData').click(function() {
        searchModules();
    });
    $('#cmb_pai_nac').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_pro_nac");
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
    });

    $('#cmb_pro_nac').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
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

    $('#cmb_pai_dom').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
        var arrParams = new Object();
        arrParams.pai_id = $(this).val();
        arrParams.getprovincias = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.provincias, "cmb_pro_dom");
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
    });

    $('#cmb_pro_dom').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
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

    $('#cmb_grupo').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
        var arrParams = new Object();
        arrParams.id = $(this).val();
        arrParams.getRoles = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.roles, "cmb_rol");
                $('#reg_pass').val(data.reg_pass);
                $('#long_pass').val(data.long_pass);
                $('#desc_pass').val(data.seg_desc);
                $('#frm_clave').attr('data-lengthMin', data.long_pass);
                $('#frm_clave_repeat').attr('data-lengthMin', data.long_pass);
            }
        }, true);
    });

    $('#frm_salario').focusout(function() {
        let ref = parseFloat(removeMilesFormat($(this).val()));
        $(this).val(currencyFormat(ref, 2));
    });

    $('#cmb_departamento').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
        var arrParams = new Object();
        arrParams.dep_id = $(this).val();
        arrParams.getsubdep = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.subdeps, "cmb_subdepartamento");
            }
        }, true);
    });

    $('#cmb_cargo').change(function() {
        var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
        var arrParams = new Object();
        arrParams.id = $(this).val();
        arrParams.getSalario = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                $('#frm_salario').val(currencyFormat(parseFloat(data.salario), 2));
            }
        }, true);
    });

    $('#view_pass_btn').click(function() {
        if ($('#frm_clave').attr("type") == "text") {
            $('#frm_clave').attr("type", "password");
            $('#view_pass_btn > i').attr("class", "glyphicon glyphicon-eye-open");
        } else {
            $('#frm_clave').attr("type", "text");
            $('#view_pass_btn > i').attr("class", "glyphicon glyphicon-eye-close");
        }
    });
    $("#generate_btn").click(function() {
        var newpass = generatePassword();
        $('#frm_clave').val(newpass);
        $('#frm_clave_repeat').val(newpass);
    });
    $('#frm_correo').focusout(function() {
        $('#frm_username').val($(this).val());
    });
});

/**
 * Function to apply filter action to gridview
 */
function searchModules() {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_search").val();
    $("#grid_list").PbGridView("applyFilterData", arrParams);
}

/**
 * Function to go edit form
 */
function edit() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/update";
    var lbl = "";
    var limit = parseInt($('#frm_filesize').val());
    var per_foto = $('#txth_foto')[0].files;
    var per_fileExt = $('#txth_foto').val().split('.').pop();
    var per_filesize = $('#txth_foto')[0].size;
    var per_filename = $('#txth_foto').val().replace(/.*(\/|\\)/, '');
    var ced_foto = $('#frm_ced_foto')[0].files;
    var ced_fileExt = $('#frm_ced_foto').val().split('.').pop();
    var ced_filesize = $('#frm_ced_foto')[0].size;
    var ced_filename = $('#frm_ced_foto').val().replace(/.*(\/|\\)/, '');
    var contr_pdf = $('#frm_contr_file')[0].files;
    var contr_fileExt = $('#frm_contr_file').val().split('.').pop();
    var contr_filesize = $('#frm_contr_file')[0].size;
    var contr_filename = $('#frm_contr_file').val().replace(/.*(\/|\\)/, '');
    var entr_pdf = $('#frm_entr_file')[0].files;
    var entr_fileExt = $('#frm_entr_file').val().split('.').pop();
    var entr_filesize = $('#frm_entr_file')[0].size;
    var entr_filename = $('#frm_entr_file').val().replace(/.*(\/|\\)/, '');
    var arrParams = new Object();
    arrParams.id = $('#per_id').val();
    arrParams.code = $('#frm_id').val();
    arrParams.pri_nombre = $('#frm_pri_nombre').val();
    arrParams.seg_nombre = $('#frm_seg_nombre').val();
    arrParams.pri_apellido = $('#frm_pri_apellido').val();
    arrParams.seg_apellido = $('#frm_seg_apellido').val();
    arrParams.ecivil = $('#cmb_ecivil').val();
    arrParams.genero = $('#cmb_genero').val();
    arrParams.correo = $('#frm_correo').val();
    arrParams.celular = $('#frm_celular').val();
    arrParams.telefono = $('#frm_telefono').val();
    arrParams.telefonoc = $('#frm_telefonoc').val();
    arrParams.extension = $('#frm_extension').val();
    arrParams.tipo_sangre = $('#cmb_tipo_sangre').val();
    arrParams.raza_etnica = $('#cmb_raza_etnica').val();
    arrParams.f_nacimiento = $('#frm_nacimiento').val();
    arrParams.cedula = $('#frm_cedula').val();
    arrParams.ruc = $('#frm_ruc').val();
    arrParams.pasaporte = $('#frm_pasaporte').val();
    arrParams.nacionalidad = $('#frm_nacionalidad').val();
    arrParams.pai_nac = $('#cmb_pai_nac').val();
    arrParams.pro_nac = $('#cmb_pro_nac').val();
    arrParams.ciu_nac = $('#cmb_ciu_nac').val();
    arrParams.ecua = $('#frm_ecua').val();
    arrParams.pai_dom = $('#cmb_pai_dom').val();
    arrParams.pro_dom = $('#cmb_pro_dom').val();
    arrParams.ciu_dom = $('#cmb_ciu_dom').val();
    arrParams.tel2_dom = $('#frm_tel2_dom').val();
    arrParams.sector_dom = $('#frm_sector_dom').val();
    arrParams.callepri_dom = $('#frm_callepri_dom').val();
    arrParams.callesec_dom = $('#frm_callesec_dom').val();
    arrParams.numeracion_dom = $('#frm_numeracion_dom').val();
    arrParams.referencia_dom = $('#frm_referencia_dom').val();
    arrParams.banco = $('#cmb_banco').val();
    arrParams.cc_banco = $('#frm_cc_banco').val();
    arrParams.tpago = $('#cmb_tpago').val();
    arrParams.cta_contable = $('#autocomplete-cuenta').val();
    arrParams.f_ingreso = $('#frm_ingreso').val();
    arrParams.cod_vend = $('#frm_cod_vend').val();
    arrParams.per_foto = per_foto[0];
    arrParams.ced_foto = ced_foto[0];
    arrParams.contr_pdf = contr_pdf[0];
    arrParams.entr_pdf = entr_pdf[0];
    arrParams.departamento = $('#cmb_departamento').val();
    arrParams.subdepartamento = $('#cmb_subdepartamento').val();
    arrParams.tipContrato = $('#cmb_tipContrato').val();
    arrParams.cargo = $('#cmb_cargo').val();
    arrParams.salario = removeMilesFormat($('#frm_salario').val());
    arrParams.f_ssocial = $('#frm_fecha_ssocial').val();
    arrParams.freserva = $('#frm_freserva').val();
    arrParams.dtercero = $('#frm_dtercero').val();
    arrParams.dcuarto = $('#frm_dcuarto').val();
    arrParams.sobretiempo = $('#frm_sobretiempo').val();
    arrParams.t_empleado = $('#cmb_t_empleado').val();
    arrParams.tipContribuyente = $('#cmb_tipContribuyente').val();
    arrParams.cargas = $('#frm_cargas').val();
    arrParams.discapacidad = $('#frm_discapacidad').val();
    arrParams.tip_disc = $('#frm_tip_disc').val();
    arrParams.per_disc = $('#frm_per_disc').val();
    arrParams.username = $('#frm_username').val();
    arrParams.clave = $('#frm_clave').val();
    arrParams.clave_repeat = $('#frm_clave_repeat').val();
    arrParams.grupo = $('#cmb_grupo').val();
    arrParams.rol = $('#cmb_rol').val();
    arrParams.observacion = $('#txta_observacion').val();

    var reg_pass = $('#reg_pass').val();
    reg_pass = reg_pass.replace("/^", '^');
    reg_pass = reg_pass.replace("$/", '$');
    var long_pass = $('#long_pass').val();
    var patter = reg_pass.replace(/VAR/g, long_pass);
    var reg_exp = new RegExp(patter);

    if (arrParams.clave != "" && arrParams.clave != arrParams.clave_repeat) {
        lbl = objLang.Password_are_differents__Please_enter_passwords_again_;
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (arrParams.clave != "" && !arrParams.clave.match(reg_exp)) {
        lbl = objLang.Password_does_not_meet_the_Security_Group_Criteria_ + ' ' + $('#desc_pass').val();
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (per_foto.length > 0 && per_fileExt.toLowerCase() != 'jpeg' && per_fileExt.toLowerCase() != 'png' && per_fileExt.toLowerCase() != 'jpg') {
        lbl = '<b>' + objLang.Photo + ': </b>' + objLang._file__extension_is_invalid__Only__extensions__are_allowed_;
        lbl = lbl.replace(/\{file\}/g, per_filename);
        lbl = lbl.replace(/\{extensions\}/g, 'jpeg/png/jpg');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (per_foto.length > 0 && per_filesize > limit) {
        lbl = '<b>' + objLang.Photo + ': </b>' + objLang._file__is_too_large__maximum_file_size_is__sizeLimit__;
        lbl = lbl.replace(/\{file\}/g, per_filename);
        lbl = lbl.replace(/\{sizeLimit\}/g, limit / 1024 / 1024 + 'MB');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (ced_foto.length > 0 && ced_fileExt.toLowerCase() != 'jpeg' && ced_fileExt.toLowerCase() != 'png' && ced_fileExt.toLowerCase() != 'jpg') {
        lbl = '<b>' + $('label[for=frm_ced_foto]').text() + ': </b>' + objLang._file__extension_is_invalid__Only__extensions__are_allowed_;
        lbl = lbl.replace(/\{file\}/g, ced_filename);
        lbl = lbl.replace(/\{extensions\}/g, 'jpeg/png/jpg');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (ced_foto.length > 0 && ced_filesize > limit) {
        lbl = '<b>' + $('label[for=frm_ced_foto]').text() + ': </b>' + objLang._file__is_too_large__maximum_file_size_is__sizeLimit__;
        lbl = lbl.replace(/\{file\}/g, ced_filename);
        lbl = lbl.replace(/\{sizeLimit\}/g, limit / 1024 / 1024 + 'MB');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (contr_pdf.length > 0 && contr_fileExt.toLowerCase() != 'pdf') {
        lbl = '<b>' + $('label[for=frm_contr_file]').text() + ': </b>' + objLang._file__extension_is_invalid__Only__extensions__are_allowed_;
        lbl = lbl.replace(/\{file\}/g, contr_filename);
        lbl = lbl.replace(/\{extensions\}/g, 'pdf');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (contr_pdf.length > 0 && contr_filesize > limit) {
        lbl = '<b>' + $('label[for=frm_contr_file]').text() + ': </b>' + objLang._file__is_too_large__maximum_file_size_is__sizeLimit__;
        lbl = lbl.replace(/\{file\}/g, contr_filename);
        lbl = lbl.replace(/\{sizeLimit\}/g, limit / 1024 / 1024 + 'MB');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (entr_pdf.length > 0 && entr_fileExt.toLowerCase() != 'pdf') {
        lbl = '<b>' + $('label[for=frm_entr_file]').text() + ': </b>' + objLang._file__extension_is_invalid__Only__extensions__are_allowed_;
        lbl = lbl.replace(/\{file\}/g, entr_filename);
        lbl = lbl.replace(/\{extensions\}/g, 'pdf');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (entr_pdf.length > 0 && entr_filesize > limit) {
        lbl = '<b>' + $('label[for=frm_entr_file]').text() + ': </b>' + objLang._file__is_too_large__maximum_file_size_is__sizeLimit__;
        lbl = lbl.replace(/\{file\}/g, entr_filename);
        lbl = lbl.replace(/\{sizeLimit\}/g, limit / 1024 / 1024 + 'MB');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true, true);
    }
}

/**
 * Function to save Item from model or record
 */
function save() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/save";
    var lbl = "";
    var limit = parseInt($('#frm_filesize').val());
    var per_foto = $('#txth_foto')[0].files;
    var per_fileExt = $('#txth_foto').val().split('.').pop();
    var per_filesize = $('#txth_foto')[0].size;
    var per_filename = $('#txth_foto').val().replace(/.*(\/|\\)/, '');
    var ced_foto = $('#frm_ced_foto')[0].files;
    var ced_fileExt = $('#frm_ced_foto').val().split('.').pop();
    var ced_filesize = $('#frm_ced_foto')[0].size;
    var ced_filename = $('#frm_ced_foto').val().replace(/.*(\/|\\)/, '');
    var contr_pdf = $('#frm_contr_file')[0].files;
    var contr_fileExt = $('#frm_contr_file').val().split('.').pop();
    var contr_filesize = $('#frm_contr_file')[0].size;
    var contr_filename = $('#frm_contr_file').val().replace(/.*(\/|\\)/, '');
    var entr_pdf = $('#frm_entr_file')[0].files;
    var entr_fileExt = $('#frm_entr_file').val().split('.').pop();
    var entr_filesize = $('#frm_entr_file')[0].size;
    var entr_filename = $('#frm_entr_file').val().replace(/.*(\/|\\)/, '');
    var arrParams = new Object();
    arrParams.id = $('#per_id').val();
    arrParams.pri_nombre = $('#frm_pri_nombre').val();
    arrParams.seg_nombre = $('#frm_seg_nombre').val();
    arrParams.pri_apellido = $('#frm_pri_apellido').val();
    arrParams.seg_apellido = $('#frm_seg_apellido').val();
    arrParams.ecivil = $('#cmb_ecivil').val();
    arrParams.genero = $('#cmb_genero').val();
    arrParams.correo = $('#frm_correo').val();
    arrParams.celular = $('#frm_celular').val();
    arrParams.telefono = $('#frm_telefono').val();
    arrParams.telefonoc = $('#frm_telefonoc').val();
    arrParams.extension = $('#frm_extension').val();
    arrParams.tipo_sangre = $('#cmb_tipo_sangre').val();
    arrParams.raza_etnica = $('#cmb_raza_etnica').val();
    arrParams.f_nacimiento = $('#frm_nacimiento').val();
    arrParams.cedula = $('#frm_cedula').val();
    arrParams.ruc = $('#frm_ruc').val();
    arrParams.pasaporte = $('#frm_pasaporte').val();
    arrParams.nacionalidad = $('#frm_nacionalidad').val();
    arrParams.pai_nac = $('#cmb_pai_nac').val();
    arrParams.pro_nac = $('#cmb_pro_nac').val();
    arrParams.ciu_nac = $('#cmb_ciu_nac').val();
    arrParams.ecua = $('#frm_ecua').val();
    arrParams.pai_dom = $('#cmb_pai_dom').val();
    arrParams.pro_dom = $('#cmb_pro_dom').val();
    arrParams.ciu_dom = $('#cmb_ciu_dom').val();
    arrParams.tel2_dom = $('#frm_tel2_dom').val();
    arrParams.sector_dom = $('#frm_sector_dom').val();
    arrParams.callepri_dom = $('#frm_callepri_dom').val();
    arrParams.callesec_dom = $('#frm_callesec_dom').val();
    arrParams.numeracion_dom = $('#frm_numeracion_dom').val();
    arrParams.referencia_dom = $('#frm_referencia_dom').val();
    arrParams.banco = $('#cmb_banco').val();
    arrParams.cc_banco = $('#frm_cc_banco').val();
    arrParams.tpago = $('#cmb_tpago').val();
    arrParams.cta_contable = $('#autocomplete-cuenta').val();
    arrParams.f_ingreso = $('#frm_ingreso').val();
    arrParams.cod_vend = $('#frm_cod_vend').val();
    arrParams.per_foto = per_foto[0];
    arrParams.ced_foto = ced_foto[0];
    arrParams.contr_pdf = contr_pdf[0];
    arrParams.entr_pdf = entr_pdf[0];
    arrParams.departamento = $('#cmb_departamento').val();
    arrParams.subdepartamento = $('#cmb_subdepartamento').val();
    arrParams.tipContrato = $('#cmb_tipContrato').val();
    arrParams.cargo = $('#cmb_cargo').val();
    arrParams.salario = removeMilesFormat($('#frm_salario').val());
    arrParams.f_ssocial = $('#frm_fecha_ssocial').val();
    arrParams.freserva = $('#frm_freserva').val();
    arrParams.dtercero = $('#frm_dtercero').val();
    arrParams.dcuarto = $('#frm_dcuarto').val();
    arrParams.sobretiempo = $('#frm_sobretiempo').val();
    arrParams.t_empleado = $('#cmb_t_empleado').val();
    arrParams.tipContribuyente = $('#cmb_tipContribuyente').val();
    arrParams.cargas = $('#frm_cargas').val();
    arrParams.discapacidad = $('#frm_discapacidad').val();
    arrParams.tip_disc = $('#frm_tip_disc').val();
    arrParams.per_disc = $('#frm_per_disc').val();
    arrParams.username = $('#frm_username').val();
    arrParams.clave = $('#frm_clave').val();
    arrParams.clave_repeat = $('#frm_clave_repeat').val();
    arrParams.grupo = $('#cmb_grupo').val();
    arrParams.rol = $('#cmb_rol').val();
    arrParams.observacion = $('#txta_observacion').val();

    var reg_pass = $('#reg_pass').val();
    reg_pass = reg_pass.replace("/^", '^');
    reg_pass = reg_pass.replace("$/", '$');
    var long_pass = $('#long_pass').val();
    var patter = reg_pass.replace(/VAR/g, long_pass);
    var reg_exp = new RegExp(patter);

    if (arrParams.clave != "" && arrParams.clave != arrParams.clave_repeat) {
        lbl = objLang.Password_are_differents__Please_enter_passwords_again_;
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (arrParams.clave != "" && !arrParams.clave.match(reg_exp)) {
        lbl = objLang.Password_does_not_meet_the_Security_Group_Criteria_ + ' ' + $('#desc_pass').val();
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (per_foto.length <= 0) {
        lbl = '<b>' + objLang.Photo + ': </b>' + objLang.Please_attach_a_File_Name_in_format__format__;
        lbl = lbl.replace(/\{format\}/g, 'jpeg/png/jpg');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (per_fileExt.toLowerCase() != 'jpeg' && per_fileExt.toLowerCase() != 'png' && per_fileExt.toLowerCase() != 'jpg') {
        lbl = '<b>' + objLang.Photo + ': </b>' + objLang._file__extension_is_invalid__Only__extensions__are_allowed_;
        lbl = lbl.replace(/\{file\}/g, per_filename);
        lbl = lbl.replace(/\{extensions\}/g, 'jpeg/png/jpg');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (per_filesize > limit) {
        lbl = '<b>' + objLang.Photo + ': </b>' + objLang._file__is_too_large__maximum_file_size_is__sizeLimit__;
        lbl = lbl.replace(/\{file\}/g, per_filename);
        lbl = lbl.replace(/\{sizeLimit\}/g, limit / 1024 / 1024 + 'MB');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (ced_foto.length <= 0) {
        lbl = '<b>' + $('label[for=frm_ced_foto]').text() + ': </b>' + objLang.Please_attach_a_File_Name_in_format__format__;
        lbl = lbl.replace(/\{format\}/g, 'jpeg/png/jpg');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (ced_fileExt.toLowerCase() != 'jpeg' && ced_fileExt.toLowerCase() != 'png' && ced_fileExt.toLowerCase() != 'jpg') {
        lbl = '<b>' + $('label[for=frm_ced_foto]').text() + ': </b>' + objLang._file__extension_is_invalid__Only__extensions__are_allowed_;
        lbl = lbl.replace(/\{file\}/g, ced_filename);
        lbl = lbl.replace(/\{extensions\}/g, 'jpeg/png/jpg');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (ced_filesize > limit) {
        lbl = '<b>' + $('label[for=frm_ced_foto]').text() + ': </b>' + objLang._file__is_too_large__maximum_file_size_is__sizeLimit__;
        lbl = lbl.replace(/\{file\}/g, ced_filename);
        lbl = lbl.replace(/\{sizeLimit\}/g, limit / 1024 / 1024 + 'MB');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (contr_pdf.length <= 0) {
        lbl = '<b>' + $('label[for=frm_contr_file]').text() + ': </b>' + objLang.Please_attach_a_File_Name_in_format__format__;
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (contr_fileExt.toLowerCase() != 'pdf') {
        lbl = '<b>' + $('label[for=frm_contr_file]').text() + ': </b>' + objLang._file__extension_is_invalid__Only__extensions__are_allowed_;
        lbl = lbl.replace(/\{file\}/g, contr_filename);
        lbl = lbl.replace(/\{extensions\}/g, 'pdf');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (contr_filesize > limit) {
        lbl = '<b>' + $('label[for=frm_contr_file]').text() + ': </b>' + objLang._file__is_too_large__maximum_file_size_is__sizeLimit__;
        lbl = lbl.replace(/\{file\}/g, contr_filename);
        lbl = lbl.replace(/\{sizeLimit\}/g, limit / 1024 / 1024 + 'MB');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (entr_pdf.length <= 0) {
        lbl = '<b>' + $('label[for=frm_entr_file]').text() + ': </b>' + objLang.Please_attach_a_File_Name_in_format__format__;
        lbl = lbl.replace(/\{format\}/g, 'pdf');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (entr_fileExt.toLowerCase() != 'pdf') {
        lbl = '<b>' + $('label[for=frm_entr_file]').text() + ': </b>' + objLang._file__extension_is_invalid__Only__extensions__are_allowed_;
        lbl = lbl.replace(/\{file\}/g, entr_filename);
        lbl = lbl.replace(/\{extensions\}/g, 'pdf');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }
    if (entr_filesize > limit) {
        lbl = '<b>' + $('label[for=frm_entr_file]').text() + ': </b>' + objLang._file__is_too_large__maximum_file_size_is__sizeLimit__;
        lbl = lbl.replace(/\{file\}/g, entr_filename);
        lbl = lbl.replace(/\{sizeLimit\}/g, limit / 1024 / 1024 + 'MB');
        shortModal(lbl, objLang.Error, 'error');
        return;
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
                }, 3000);
            }
        }, true, true);
    }
}

/**
 * Function to delete Item from model or record
 * 
 * @param {int} id - Id of Element to Delete from model or record
 * @param {int} cod - Cod of Element to Delete from model or record
 * @return {void} No return any value.
 */
function deleteItem(id, cod) {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            searchModules();
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}

/**
 * Function to download Excel from gridview
 */
function exportExcel() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/expexcel?search=" + search;
}

/**
 * Function to download Pdf from gridview
 */
function exportPdf() {
    var search = $('#txt_search').val();
    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/exppdf?pdf=1&search=" + search;
}

/**
 * Function to do an action by response callback 
 *
 * @param {string} id - Code info from model or record
 * @return {void} No return any value.
 */
function fillDataEmployee(data) {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
    var arrParams = new Object();
    arrParams.id = data[0];
    arrParams.getDataEmpleado = true;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            let data = response.message.empleado;
            $('#per_id').val(data.id);
            $('#frm_pri_nombre').val(data.pri_nombre);
            $('#frm_seg_nombre').val(data.seg_nombre);
            $('#frm_pri_apellido').val(data.pri_apellido);
            $('#frm_seg_apellido').val(data.seg_apellido);
            $('#cmb_tipo_sangre').val(data.tipo_sangre);
            $('#cmb_genero').val(data.genero);
            $('#cmb_raza_etnica').val(data.etcnia);
            $('#cmb_ecivil').val(data.estado_civil);
            $('#frm_celular').val(data.celular);
            $('#frm_correo').val(data.correo);
            $('#frm_cedula').val(data.cedula);
            $('#frm_ruc').val(data.ruc);
            $('#frm_pasaporte').val(data.pasaporte);
            $('#frm_nacimiento').val(data.fecha_nac);
            $('#frm_nacionalidad').val(data.nacionalidad);
            if (data.foto != "")
                $('#img_destino').attr('src', $('#ctr_rqImg').val() + data.foto);
            $('#cmb_pai_nac').val(data.nac_pais);
            $('#cmb_pro_nac').val(data.nac_provincia);
            $('#cmb_ciu_nac').val(data.nac_ciudad);
            $('#cmb_pai_dom').val(data.domicilio_pais);
            $('#cmb_pro_dom').val(data.domicilio_provincia);
            $('#cmb_ciu_dom').val(data.domicilio_ciudad);
            $('#frm_tel2_dom').val(data.domicilio_celular);
            $('#frm_telefono').val(data.domicilio_telefono);
            $('#frm_callepri_dom').val(data.domicilio_cpri);
            $('#frm_callesec_dom').val(data.domicilio_csec);
            $('#frm_sector_dom').val(data.domicilio_sector);
            $('#frm_numeracion_dom').val(data.domicilio_num);
            $('#frm_referencia_dom').val(data.domicilio_referencia);
            //$('#').val(data.trabajo_direccion);
            $('#frm_extension').val(data.trabajo_extension);
            //$('#').val(data.trabajo_nombre);
            $('#frm_telefonoc').val(data.trabajo_telefono);
            $('#frm_username').val(data.username);

            // colocando grupo
            $('#cmb_grupo').val(data.gru_id);
            var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/empleado/index";
            var arrParams = new Object();
            arrParams.id = data.gru_id;
            arrParams.getRoles = true;
            var rol_id = data.rol_id;
            requestHttpAjax(link, arrParams, function(response) {
                if (response.status == "OK") {
                    data = response.message;
                    setComboData(data.roles, "cmb_rol");
                    $('#reg_pass').val(data.reg_pass);
                    $('#long_pass').val(data.long_pass);
                    $('#desc_pass').val(data.seg_desc);
                    $('#frm_clave').attr('data-lengthMin', data.long_pass);
                    $('#frm_clave_repeat').attr('data-lengthMin', data.long_pass);
                    $('#cmb_rol').val(rol_id);
                }
            }, true);

            $('#frm_ecua').val(data.nac_ecu);
            if (data.nac_ecu == '1') {
                $('#frm_ecua').next().find('i:first-child').attr("class", "glyphicon glyphicon-check");
            } else {
                $('#frm_ecua').next().find('i:first-child').attr("class", "glyphicon glyphicon-unchecked");
            }
        }
    }, true);
}

/**
 * Function to do an action by response callback 
 *
 * @param {Array} data - Data info from model or record
 * @return {void} No return any value.
 */
function putAccountData(data) {
    let id = data[0];
    let name = data[1];
    $('#frm_marcadesc').val(name);
}