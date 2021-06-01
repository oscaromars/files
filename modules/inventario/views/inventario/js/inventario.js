
$(document).ready(function () {
    $('#cmb_tipo_bien').change(function () {
        var link = $('#txth_base').val() + "/inventario/inventario/index";
        var arrParams = new Object();        
        arrParams.tipobien_id = $(this).val();
        arrParams.getcategoria = true;      
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {                
                data = response.message;                
                setComboDataselect(data.categorias, "cmb_categoria","Todos");
            }
        }, true);
    });   
    
    $('#cmb_departamento').change(function () {
        var link = $('#txth_base').val() + "/inventario/inventario/index";
        var arrParams = new Object();        
        arrParams.dpto_id = $(this).val();
        arrParams.getarea = true;     
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;                
                setComboDataselect(data.areas, "cmb_area","Todos");
            }
        }, true);
    });
    
    $('#btn_buscarDataInv').click(function () {          
        llenarGrid();       
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

function llenarGrid() {
    var codigo = $('#txt_buscarData').val();
    var tipo_bien = $('#cmb_tipo_bien').val();
    var categoria = $('#cmb_categoria').val();
    var departamento = $('#cmb_departamento').val();
    var area = $('#cmb_area').val();
    
    //Buscar almenos una clase con el nombre para ejecutar
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $('#Tbg_Listar').PbGridView('applyFilterData', {'codigo': codigo, 'tipo_bien': tipo_bien, 'categoria': categoria, 'departamento': departamento, 'area': area});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function exportExcel() {
    var codigo = $('#txt_buscarData').val();
    var tipo_bien = $('#cmb_tipo_bien').val();
    var categoria = $('#cmb_categoria').val();
    var departamento = $('#cmb_departamento').val();
    var area = $('#cmb_area').val();
    window.location.href = $('#txth_base').val() + "/inventario/inventario/expexcel?search=" + codigo + "&tipo_bien=" + tipo_bien + "&categoria=" + categoria + "&departamento=" + departamento + "&area=" + area;
}

function exportPdf() {
    var codigo = $('#txt_buscarData').val();
    var tipo_bien = $('#cmb_tipo_bien').val();
    var categoria = $('#cmb_categoria').val();
    var departamento = $('#cmb_departamento').val();
    var area = $('#cmb_area').val();
    window.location.href = $('#txth_base').val() + "/inventario/inventario/exppdf?pdf=1&search=" + codigo + "&tipo_bien=" + tipo_bien + "&categoria=" + categoria + "&departamento=" + departamento + "&area=" + area;
}