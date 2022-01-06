$(document).ready(function() {

});

function deleteItem(id) {
    var link = $('#txth_base').val() + "/academico/estudioacademico/deletestudio";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            //searchModules('boxgrid', 'grid_list')
            //window.location = window.location.href;
            window.location.href = $('#txth_base').val() + "/academico/estudioacademico/index";
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}