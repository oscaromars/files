$(document).ready(function() {
    $("#frm_acc_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconAcc").attr("class", $(this).val());
        else {
            $("#iconAcc").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });
    $("#spanAccStatus").click(function() {
        if ($("#frm_status").val() == "1") {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_status").val("0");
        } else {
            $("#iconAccStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_status").val("1");
        }
    });
    $('#btn_buscarData').click(function() {
        searchModules('boxgrid', 'grid_list');
    });
    $('#cmb_cat').change(function() {
        var link = $('#txth_base').val() + "/gpr/unidad/index";
        var arrParams = new Object();
        arrParams.categoria = $(this).val();
        arrParams.getentidades = true;
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK") {
                data = response.message;
                setComboData(data.entidad, "cmb_ent");
            }
        }, true);
    });
    /*$('.share-contact').slimScroll({
        height: '220px'
    });
    $('.chk-cal').click(function() {
        if ($(this).parent().find("input").val() == "1") {
            $(this).find("i").attr("class", "fa fa-square-o");
            $(this).parent().find("input").val("0");
        } else {
            $(this).find("i").attr("class", "fa fa-check-square");
            $(this).parent().find("input").val("1");
        }
        let in_value = $(this).parent().find("input").val();
        let in_class = $(this).find("i").attr("class");
        let data_id = $(this).parent().find("input").attr("data-id");
        objContact = new Array();
        $('div#external-events > div.input-group').each(function() {
            if (data_id == "all") {
                $(this).find("input").val(in_value);
                $(this).find("i").attr("class", in_class);
            }
            if ($(this).find("input").val() == 1 && $(this).find("input").attr("data-id") != "all") {
                objContact.push($(this).find("input").attr("data-id"));
            }
        });

    });*/
});

function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#txt_buscarData").val();
    arrParams.categoria = $("#cmb_cats").val();
    arrParams.entidad = $("#cmb_ent").val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

/*function fillContactObject() {
    objContact = new Array();
    $('div#external-events > div.input-group').each(function() {
        let id = $(this).find("input").attr("data-id");
        if ($(this).find("input").val() == 1 && id != "all")
            objContact.push(id);
    });
}

fillContactObject();

function desmarcarContactos() {
    objContact = new Array();
    $('div#external-events > div.input-group').each(function() {
        $(this).find("input").val("0");
        $(this).find("i").attr("class", "fa fa-square-o");
    });
}
*/
function edit() {
    var link = $('#txth_base').val() + "/gpr/unidad/edit" + "?id=" + $("#frm_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/gpr/unidad/update";
    var arrParams = new Object();
    arrParams.id = $("#frm_id").val();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.categoria = $('#cmb_cat').val();
    arrParams.entidad = $('#cmb_ent').val();
    arrParams.estado = $('#frm_status').val();
    arrParams.tunidad = $('#cmb_tunidad').val();
    //arrParams.usuarios = objContact;
    if ($('#cmb_cat').val() == 0) {
        var msg = objLang.Please_select_a_Category_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_ent').val() == 0) {
        var msg = objLang.Please_select_an_Entity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_tunidad').val() == 0) {
        var msg = objLang.Please_select_a_Type_of_Unit_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    /*if (objContact.length == 0) {
        var msg = objLang.The_Subunit_must_have_at_least_1_responsible_;
        shortModal(msg, objLang.Error, "error");
        return;
    }*/
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function save() {
    var link = $('#txth_base').val() + "/gpr/unidad/save";
    var arrParams = new Object();
    arrParams.nombre = $('#frm_name').val();
    arrParams.desc = $('#frm_desc').val();
    arrParams.categoria = $('#cmb_cat').val();
    arrParams.entidad = $('#cmb_ent').val();
    arrParams.estado = $('#frm_status').val();
    arrParams.tunidad = $('#cmb_tunidad').val();
    //arrParams.usuarios = objContact;
    if ($('#cmb_cat').val() == 0) {
        var msg = objLang.Please_select_a_Category_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_ent').val() == 0) {
        var msg = objLang.Please_select_an_Entity_Name_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if ($('#cmb_tunidad').val() == 0) {
        var msg = objLang.Please_select_a_Type_of_Unit_;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    /*if (objContact.length == 0) {
        var msg = objLang.The_Subunit_must_have_at_least_1_responsible_;
        shortModal(msg, objLang.Error, "error");
        return;
    }*/
    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/gpr/unidad/index";
                }, 3000);
            }
        }, true);
    }
}

function deleteItem(id) {
    var link = $('#txth_base').val() + "/gpr/unidad/delete";
    var arrParams = new Object();
    arrParams.id = id;
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            searchModules('boxgrid', 'grid_list')
                //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}