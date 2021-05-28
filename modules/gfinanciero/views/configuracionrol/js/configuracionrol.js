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
    $('#frm_salario, #frm_transporte, #frm_alimentacion').focusout(function() {
        let ref = parseFloat(removeMilesFormat($(this).val()));
        $(this).val(currencyFormat(ref, 2));
    });
});

/**
 * Function to update Item from model or record
 */
function update() {
    var link = $('#txth_base').val() + "/" + $('#txth_module').val() + "/configuracionrol/update";
    var arrParams = new Object();
    arrParams.salario_min = parseFloat(removeMilesFormat($("#frm_salario").val())).toFixed(2);
    arrParams.horas = $('#frm_horas').val();
    arrParams.beneficios = $('#frm_beneficios').val();
    arrParams.per_aporte = $('#frm_per_aporte').val();
    arrParams.aporte_mes = $('#frm_aporte_mes').val();
    arrParams.per_iess = $('#frm_per_iess').val();
    arrParams.iess_mes = $('#frm_iess_mes').val();
    arrParams.alimentacion = parseFloat(removeMilesFormat($("#frm_alimentacion").val())).toFixed(2);
    arrParams.alimentacion_mes = $('#frm_alimentacion_mes').val();
    arrParams.transporte = parseFloat(removeMilesFormat($("#frm_transporte").val())).toFixed(2);
    arrParams.transporte_mes = $('#frm_transporte_mes').val();

    if (parseInt(arrParams.horas) <= 0) {
        var msg = objLang.Please_Hours_must_be_greater_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (parseInt(arrParams.transporte) < 0) {
        var msg = objLang.Please_Transport_must_be_greater_or_equal_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (parseInt(arrParams.alimentacion) < 0) {
        var msg = objLang.Please_Feeding_must_be_greater_or_equal_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (parseInt(arrParams.salario_min) <= 0) {
        var msg = objLang.Please_Salary_must_be_greater_than_zero_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (parseInt(arrParams.per_aporte) <= 0 || parseInt(arrParams.per_aporte) > 100) {
        var msg = objLang.Please_Percentage_must_be_greater_than_0_and_less_than_100_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (parseInt(arrParams.per_iess) <= 0 || parseInt(arrParams.per_iess) > 100) {
        var msg = objLang.Please_Percentage_must_be_greater_than_0_and_less_than_100_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/" + $('#txth_module').val() + "/configuracionrol/edit";
                }, 3000);
            }
        }, true);
    }
}