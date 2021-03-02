$(document).ready(function() {
    $("#cmb_country").change(function() {
        var link = $('#txth_base').val() + "/academico/perfil/index";
        var arrParams = new Object();
        arrParams.pai_id = $("#cmb_country").val();
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK")
                console.log(response.message);
            setComboData(response.message['arr_pro'], "cmb_state");
            setComboData(response.message['arr_can'], "cmb_city");
        }, true);
    });

    $("#cmb_state").change(function() {
        var link = $('#txth_base').val() + "/academico/perfil/index";
        var arrParams = new Object();
        arrParams.pro_id = $("#cmb_state").val();
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK")
                setComboData(response.message, "cmb_city");
        }, true);
    });

    $("#cmb_document_type").change(function() {
        var link = $('#txth_base').val() + "/academico/perfil/index";
        var arrParams = new Object();
        arrParams.type_doc = $("#cmb_document_type").val();
        arrParams.per_id = $("#per_id").val();
        /* console.log(arrParams); */
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                var data_type = "alfanumerico";
                if (arrParams.type_doc === "CED") {
                    data_type = "cedula";
                } else if (arrParams.type_doc === "RUC") {
                    data_type = "number";
                }
                document.getElementById("txt_document_id").value = response.message;
                $("#txt_document_id").attr("data-type", data_type);
                /* console.log(response); */
            }
        }, true);
    });

    $('#view_pass_btn').click(function() {
        if ($('#txt_password').attr("type") == "text") {
            $('#txt_password').attr("type", "password");
            $('#view_pass_btn > i').attr("class", "glyphicon glyphicon-eye-open");
        } else {
            $('#txt_password').attr("type", "text");
            $('#view_pass_btn > i').attr("class", "glyphicon glyphicon-eye-close");
        }
    });
});

function update() {
    var link = $('#txth_base').val() + "/academico/perfil/update";
    var arrParams = new Object();
    arrParams.per_id = $("#per_id").val();
    arrParams.first_nombre = $('#txt_first_name').val();
    arrParams.first_apellido = $('#txt_first_lastname').val();
    arrParams.type_doc = $('#cmb_document_type').val();
    arrParams.gender = $('#cmb_gender').val();
    arrParams.other_ethnicity = $('#txt_other_ethnicity').val();
    arrParams.nationality = $('#txt_nationality').val();
    arrParams.pro_id = $('#cmb_state').val();
    arrParams.eciv_id = $('#cmb_civil_state').val();
    arrParams.phone = $('#txt_cellphone').val();
    /* arrParams.nac_ecu = $('#cmb_ciu_nac').val(); */
    if ($('input[name=signup-ecu]:checked').val() == 1) {
        arrParams.nac_ecu = 1;
    } else {
        arrParams.nac_ecu = 0;
    }
    arrParams.second_name = $('#txt_second_name').val();
    arrParams.second_lastname = $('#txt_second_lastname').val();
    arrParams.dni = $('#txt_document_id').val();
    arrParams.etn_id = $('#cmb_ethnicity').val();
    arrParams.birth_date = $('#txt_birth_date').val();
    arrParams.pai_id = $('#cmb_country').val();
    arrParams.can_id = $('#cmb_city').val();
    arrParams.mail = $('#txt_mail').val();
    arrParams.tsan_id = $('#cmb_blood_type').val();
    arrParams.mail_acc = $('#txt_mail_access').val();
    arrParams.password = $('#txt_password').val();
    arrParams.confirm_password = $('#txt_confirm_password').val();
    if (arrParams.password != arrParams.confirm_password) {
        showAlert("NOOK", "Error", { "wtmessage": objLang.Passwords_are_differents__please_enter_passwords_again_, "title": objLang.Error });
    } else {
        if (!validateForm()) {
            requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                if (response.status == "OK") {
                    setTimeout(function() {
                        window.location.href = $('#txth_base').val() + "/academico/matriculacion/index";
                    }, 3000);
                }
            }, true);
        }
    }
}