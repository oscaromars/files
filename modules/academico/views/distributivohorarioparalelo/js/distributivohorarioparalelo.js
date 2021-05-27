$(document).ready(function () {
    $('#btn_buscarData').click(function () {
        actualizarGrid();
    });
});

 $(function(){
      $('#modalButton').click(function(){
           $('#modalView').modal('show').find('#modalContentView').load($(this).attr('value'));
      });
  });



function actualizarGrid() {
    var search = $('#txt_buscarData').val();
    

    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#grid_dhp_list').PbGridView('applyFilterData', {'search': search});
        setTimeout(hideLoadingPopup, 2000);
    }
      }








