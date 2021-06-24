$(document).ready(function() {
    $('#btn_guardarsemestre').click(function () {
        savesemestre();
    });
    $("#spanSemIntensivo").click(function() {
        if ($("#frm_semestre_intensivo").val() == "1") {
            $("#iconSemIntensivo").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_semestre_intensivo").val("0");
        } else {
            $("#iconSemIntensivo").attr("class", "glyphicon glyphicon-check");
            $("#frm_semestre_intensivo").val("1");
        }
    });
});

function savesemestre() {
    var link = $('#txth_base').val() + "/academico/semestreacademico/savesemestre";
    var arrParams = new Object();
    arrParams.nombre = $('#semestreacademico-saca_nombre').val();
    arrParams.descripcion = $('#semestreacademico-saca_descripcion').val();
    arrParams.ano = $('#semestreacademico-saca_anio').val();
    arrParams.estado = $('#semestreacademico-saca_estado').val();
    arrParams.intensivo = $('#frm_semestre_intensivo').val();
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/semestreacademico/index";
                    }, 3000);
            }
        }, true);
    }
}