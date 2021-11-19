$(document).ready(function() {
    $("#cmb_pais").change(function() {
        var link = $('#txth_base').val() + "/academico/profesor/new";
        var arrParams = new Object();
        arrParams.pai_id = $("#cmb_pais").val();
        console.log(arrParams);
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK")
                console.log(response.message);
            setComboData(response.message['arr_pro'], "cmb_provincia");
            setComboData(response.message['arr_can'], "cmb_canton");
            //  setComboData(response.message,"cmb_canton");
        }, true);
    });
    $('input[type="checkbox"]').on('change', function() {
       $(this).siblings('input[type="checkbox"]').prop('checked', false);
       element  = document.getElementById("content");
       element1 = document.getElementById("content1");
       element2 = document.getElementById("content2");
       element3 = document.getElementById("content3");
       check2 = document.getElementById("chk_institucion2");
       if (check2.checked) {
            element.style.display  = 'block';
            element1.style.display = 'block';
            element2.style.display = 'block';
            element3.style.display = 'block';
        }
        else {
            element.style.display  = 'none';
            element1.style.display = 'none';
            element2.style.display = 'none';
            element3.style.display = 'none';
        }
    });

    $("#cmb_provincia").change(function() {
        var link = $('#txth_base').val() + "/academico/profesor/new";
        var arrParams = new Object();
        arrParams.pro_id = $("#cmb_provincia").val();
        console.log(arrParams);
        requestHttpAjax(link, arrParams, function(response) {
            if (response.status == "OK")
                setComboData(response.message, "cmb_canton");
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
        console.log("entra");
        var newpass = generatePasswordSemi();
        $('#frm_clave').val(newpass);
    });

    function generatePasswordSemi() {
        var ramdonPass = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!¡@#$&/()=?¿-+*^{}[]";
        var newpass = "";
        for (var i = 0; i < 6; i++)
            newpass += ramdonPass.charAt(Math.floor(Math.random() * ramdonPass.length));
        return newpass;
    }

    $("#frm_asi_image").keyup(function() {
        if ($(this).val() != "")
            $("#iconAsi").attr("class", $(this).val());
        else {
            $("#iconAsi").attr("class", $(this).attr("data-alias"));
            $(this).val($(this).attr("data-alias"));
        }
    });

    $("#spanAsiStatus").click(function() {
        if ($("#frm_asi_status").val() == "1") {
            $("#iconAsiStatus").attr("class", "glyphicon glyphicon-unchecked");
            $("#frm_asi_status").val("0");
        } else {
            $("#iconAsiStatus").attr("class", "glyphicon glyphicon-check");
            $("#frm_asi_status").val("1");
        }
    });

    $('#txt_correo').keyup(function() {
        $('#txt_usuario').val($(this).val());
    });

});

function showContent(){

    var fecha_inicio = new Date(document.getElementById("txt_doc_from").value); 
    console.log(fecha_inicio);
    var fecha_fin = new Date(document.getElementById("txt_doc_to").value);
      
    if (fecha_fin > fecha_inicio)
        {
            // calculo de días
            var timeDiff = Math.abs((fecha_fin.getTime() - fecha_inicio.getTime())/1000);
            var diffDays = Math.ceil(timeDiff / (60 * 60 * 24));
            // calculo de semanas
            var diffsemana = Math.ceil(timeDiff / (60 * 60 * 24 * 7));
            // calculo de años
            var numberOfYears =fecha_fin.getFullYear() - fecha_inicio.getFullYear();
            fecha_fin.setFullYear(fecha_inicio.getFullYear);
            if (fecha_inicio < fecha_fin){
                --numberOfYears;
            }
            // calculo de meses
            var numberOfMonths = Math.ceil(timeDiff / (60 * 60 * 24 * 7 * 4));
            var meses = numberOfMonths-1;
            // presentar los dias, semanas, meses y años 
            $("#txt_conteo").text(diffDays + ' Días, '+diffsemana+' Semanas, '+meses+' Mes, '+numberOfYears+' Años.');        
    }
    else if (fecha_fin != null && fecha_fin <= fecha_inicio) {
        alert("La fecha final de la promoción debe ser mayor a la fecha inicial");
        document.getElementById("txt_conteo").value = 0;
    }

    element = document.getElementById("contenidopla");
    check2  = document.getElementById("txt_doc_to");
    if (check2.click) {
        element.style.display  = 'block';
    }
    else {
        element.style.display  = 'none';
        
    }
}

function registerForm(){
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/investigacion/registroproyecto/new";
    // variables 

    arrParams.proy_id = $('#cmb_tipoproy option:selected').val();
    arrParams.linv_id = $('#cmb_linea option:selected').val();
    arrParams.mpro_id = $('#cmb_mpro option:selected').val();
    arrParams.rpro_titulo = $('#txt_titulo').val();
    arrParams.rpro_resumen = $('#txt_resumen').val();

    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        setTimeout(function() {
            location.reload();
            //parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/index";
        }, 2000);

    }, true);
}


function register_integrantes(){
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/investigacion/registroproyecto/new";
    // variables 

    arrParams.proy_id = $('#cmb_tipoproy option:selected').val();
    arrParams.linv_id = $('#cmb_linea option:selected').val();
    arrParams.mpro_id = $('#cmb_mpro option:selected').val();
    arrParams.rpro_titulo = $('#txt_titulo').val();
    arrParams.rpro_resumen = $('#txt_resumen').val();

    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        setTimeout(function() {
            location.reload();
            //parent.window.location.href = $('#txth_base').val() + "/academico/matriculacion/index";
        }, 2000);

    }, true);
}


function searchModules(idbox, idgrid) {
    var arrParams = new Object();
    arrParams.PBgetFilter = true;
    arrParams.search = $("#" + idbox).val();
    $("#" + idgrid).PbGridView("applyFilterData", arrParams);
}

function edit() {
    var link = $('#txth_base').val() + "/academico/profesor/edit" + "?id=" + $("#frm_per_id").val();
    window.location = link;
}

function update() {
    var link = $('#txth_base').val() + "/academico/profesor/update";
    var arrParams = new Object();
    arrParams.per_id = $("#frm_per_id").val();
    arrParams.pri_nombre = $('#txt_primer_nombre').val();
    arrParams.seg_nombre = $('#txt_segundo_nombre').val();
    arrParams.pri_apellido = $('#txt_primer_apellido').val();
    arrParams.seg_apellido = $('#txt_segundo_apellido').val();
    arrParams.cedula = $('#txt_cedula').val();
    arrParams.ruc = $('#txt_ruc').val();
    arrParams.pasaporte = $('#txt_pasaporte').val();
    arrParams.correo = $('#txt_correo').val();

    arrParams.pai_id = $('#cmb_pais').val();
    arrParams.pro_id = $('#cmb_provincia').val();
    arrParams.can_id = $('#cmb_canton').val();
    arrParams.sector = $('#txt_sector').val();
    arrParams.calle_pri = $('#txt_calle_pri').val();
    arrParams.calle_sec = $('#txt_calle_sec').val();
    arrParams.numeracion = $('#txt_numeracion').val();
    arrParams.referencia = $('#txt_referencia').val();
    arrParams.nacionalidad = $('#txt_nacionalidad').val();
    arrParams.celular = $('#txt_cel').val();
    arrParams.phone = $('#txt_phone').val();
    arrParams.dedicacion = $('#cmb_dedicacion').val();
    arrParams.pro_num_contrato = $('#txt_contrato').val();
    arrParams.fecha_nacimiento = $('#txt_fecha_nacimiento').val();

    arrParams.usuario = $('#txt_usuario').val();
    arrParams.clave = $('#frm_clave').val();
    arrParams.gru_id = $('#cmb_grupo').val();
    arrParams.rol_id = $('#cmb_rol').val();
    arrParams.emp_id = $('#cmb_empresa').val();
    arrParams.foto = $('#txth_doc_foto').val() + ".jpg";

    /** Session Storages **/
    arrParams.grid_instruccion_list = (JSON.parse(sessionStorage.grid_instruccion_list)).data;
    arrParams.grid_docencia_list = (JSON.parse(sessionStorage.grid_docencia_list)).data;
    arrParams.grid_experiencia_list = (JSON.parse(sessionStorage.grid_experiencia_list)).data;
    arrParams.grid_idioma_list = (JSON.parse(sessionStorage.grid_idioma_list)).data;
    arrParams.grid_investigacion_list = (JSON.parse(sessionStorage.grid_investigacion_list)).data;
    arrParams.grid_evento_list = (JSON.parse(sessionStorage.grid_evento_list)).data;
    arrParams.grid_conferencia_list = (JSON.parse(sessionStorage.grid_conferencia_list)).data;
    arrParams.grid_publicacion_list = (JSON.parse(sessionStorage.grid_publicacion_list)).data;
    arrParams.grid_coordinacion_list = (JSON.parse(sessionStorage.grid_coordinacion_list)).data;
    arrParams.grid_evaluacion_list = (JSON.parse(sessionStorage.grid_evaluacion_list)).data;
    arrParams.grid_referencia_list = (JSON.parse(sessionStorage.grid_referencia_list)).data;


    if (!validateForm()) {
        //console.log(arrParams);
        requestHttpAjax(link, arrParams, function(response) {
            var btnacciones = new Array();
            var accion1 = new Object();
            accion1.id = "btnid1";
            accion1.class = "clclass";
            accion1.value = "Aceptar";
            accion1.callback = "backtoList";
            btnacciones[0] = accion1;
            response.message.acciones = btnacciones;
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function test() {
    console.log("foto: " + $('#txth_doc_foto').val());
    console.log("extfoto: " + $('#txth_doc_cv').val());
      console.log("cmb_dedicacion: " + $('#cmb_dedicacion').val());
}
function save() {
    var link = $('#txth_base').val() + "/academico/profesor/save";
    var arrParams = new Object();
    arrParams.pri_nombre = $('#txt_primer_nombre').val();
    arrParams.seg_nombre = $('#txt_segundo_nombre').val();
    arrParams.pri_apellido = $('#txt_primer_apellido').val();
    arrParams.seg_apellido = $('#txt_segundo_apellido').val();
    arrParams.cedula = $('#txt_cedula').val();
    arrParams.ruc = $('#txt_ruc').val();
    arrParams.pasaporte = $('#txt_pasaporte').val();
    arrParams.correo = $('#txt_correo').val();

    arrParams.pai_id = $('#cmb_pais').val();
    arrParams.pro_id = $('#cmb_provincia').val();
    arrParams.can_id = $('#cmb_canton').val();
    arrParams.sector = $('#txt_sector').val();
    arrParams.calle_pri = $('#txt_calle_pri').val();
    arrParams.calle_sec = $('#txt_calle_sec').val();
    arrParams.numeracion = $('#txt_numeracion').val();
    arrParams.referencia = $('#txt_referencia').val();
    arrParams.nacionalidad = $('#txt_nacionalidad').val();
    arrParams.celular = $('#txt_cel').val();
    arrParams.phone = $('#txt_phone').val();
    arrParams.pro_num_contrato = $('#txt_contrato').val();
    arrParams.dedicacion = $('#cmb_dedicacion').val();
    arrParams.fecha_nacimiento = $('#txt_fecha_nacimiento').val();
   console.log("dedicacion: " + arrParams.dedicacion);
    arrParams.usuario = $('#txt_usuario').val();
    arrParams.clave = $('#frm_clave').val();
    arrParams.gru_id = $('#cmb_grupo').val();
    arrParams.rol_id = $('#cmb_rol').val();
    arrParams.emp_id = $('#cmb_empresa').val();
    //arrParams.foto = $('#txth_doc_foto').val() + ".jpg";
    arrParams.foto = $('#txth_doc_foto').val();
    arrParams.extfoto = $('#txth_doc_cv').val() ;
    console.log("foto: " + arrParams.foto);
    console.log("extfoto: " + $('#txth_doc_cv').val());
    /** Session Storages **/
    arrParams.grid_instruccion_list = (JSON.parse(sessionStorage.grid_instruccion_list)).data;
    arrParams.grid_docencia_list = (JSON.parse(sessionStorage.grid_docencia_list)).data;
    arrParams.grid_experiencia_list = (JSON.parse(sessionStorage.grid_experiencia_list)).data;
    arrParams.grid_idioma_list = (JSON.parse(sessionStorage.grid_idioma_list)).data;
    arrParams.grid_investigacion_list = (JSON.parse(sessionStorage.grid_investigacion_list)).data;
    arrParams.grid_evento_list = (JSON.parse(sessionStorage.grid_evento_list)).data;
    arrParams.grid_conferencia_list = (JSON.parse(sessionStorage.grid_conferencia_list)).data;
    arrParams.grid_publicacion_list = (JSON.parse(sessionStorage.grid_publicacion_list)).data;
    arrParams.grid_coordinacion_list = (JSON.parse(sessionStorage.grid_coordinacion_list)).data;
    arrParams.grid_evaluacion_list = (JSON.parse(sessionStorage.grid_evaluacion_list)).data;
    arrParams.grid_referencia_list = (JSON.parse(sessionStorage.grid_referencia_list)).data;

    if (!validateForm()) {
        //console.log(arrParams);
        requestHttpAjax(link, arrParams, function(response) {
            console.log(response.message);
            showAlert(response.status, response.label, response.message);
        }, true);
    }
}

function deleteItem(per_id) {
    var link = $('#txth_base').val() + "/academico/profesor/delete";
    var arrParams = new Object();
    arrParams.per_id = per_id;    
    requestHttpAjax(link, arrParams, function(response) {
        if (response.status == "OK") {
            var arrParams2 = new Object();
            arrParams2.PBgetFilter = true;
            arrParams2.search = $("#boxgrid").val();
            $("#grid_profesor_list").PbGridView("applyFilterData", arrParams2);
            //window.location = window.location.href;
        }
        setTimeout(function() {
            showAlert(response.status, response.label, response.message);
        }, 1000);
    }, true);
}

function fillDataAlert() {
    var type = "alert";
    var label = "error";
    var status = "NO_OK";
    var messagew = {};
    messagew = {
        "wtmessage": "Llene todos los campos obligatorios",//objLang.Must_be_Fill_all_information_in_fields_with_label___,
        "title": objLang.Error,
        "acciones": [{
            "id": "btnalert",
            "class": "btn-primary clclass praclose",
            "value": objLang.Accept
        }],
    };
    showResponse(type, status, label, messagew);
}

/**  INSTRUCCION  **/
function addInstruccion() {
    var inst_level_id = $("#cmb_instr_level").val();
    var inst_level_name = $("#cmb_instr_level :selected").text();
    var institucion = $("#txt_institucion").val();
    var career = $("#txt_career").val();
    var degree = $("#txt_degree").val();
    var senescyt = $("#txt_senescyt").val();

    if (institucion == "" || career == "" || degree == "" || senescyt == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = inst_level_id;
    tb_item[2] = institucion;
    tb_item[3] = career;
    tb_item[4] = degree;
    tb_item[5] = senescyt;
    tb_item[6] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = inst_level_name;
    tb_item2[2] = institucion;
    tb_item2[3] = career;
    tb_item2[4] = degree;
    tb_item2[5] = senescyt;
    tb_item2[6] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemInstitucion(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_instruccion_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemInstitucion(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_instruccion_list = JSON.stringify(arrData);
    addItemGridContent("grid_instruccion_list");

    $("#cmb_instr_level").val('');
    $("#txt_institucion").val('');
    $("#txt_career").val('');
    $("#txt_degree").val('');
    $("#txt_senescyt").val('');
}

function removeItemInstitucion(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_instruccion_list", indice);
    removeItemsBase(indice,1);
}

/**  EXPERIENCIA DOCENTE  **/
function addDocencia() {
    var inst_id = $("#cmb_doc_institucion").val();
    var inst_name = $("#cmb_doc_institucion :selected").text();
    var from = $("#txt_doc_from").val();
    var to = $("#txt_doc_to").val();
    var denominacion = $("#txt_denominacion").val();
    var materias = $("#txt_subjects").val();

    if (from == "" || denominacion == "" || materias == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = inst_id;
    tb_item[2] = from;
    tb_item[3] = to;
    tb_item[4] = denominacion;
    tb_item[5] = materias;
    tb_item[6] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = inst_name;
    tb_item2[2] = from;
    tb_item2[3] = to;
    tb_item2[4] = denominacion;
    tb_item2[5] = materias;
    tb_item2[6] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemDocencia(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_docencia_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemDocencia(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_docencia_list = JSON.stringify(arrData);
    addItemGridContent("grid_docencia_list");

    $("#cmb_doc_institucion").val('');
    $("#txt_doc_from").val('');
    $("#txt_doc_to").val('');
    $("#txt_denominacion").val('');
    $("#txt_subjects").val('');
}

function removeItemDocencia(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_docencia_list", indice);
    removeItemsBase(indice,2);
}

/** EXPERIENCIA PROFESIONAL **/
function addExperiencia() {
    var company = $("#txt_pro_empresa").val();
    var from = $("#txt_pro_from").val();
    var to = $("#txt_pro_to").val();
    var denominacion = $("#txt_pro_denominacion").val();
    var funciones = $("#txt_pro_funciones").val();

    if (from == "" || denominacion == "" || funciones == "" || company == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = company;
    tb_item[2] = from;
    tb_item[3] = to;
    tb_item[4] = denominacion;
    tb_item[5] = funciones;
    tb_item[6] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = company;
    tb_item2[2] = from;
    tb_item2[3] = to;
    tb_item2[4] = denominacion;
    tb_item2[5] = funciones;
    tb_item2[6] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemExperiencia(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_experiencia_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemExperiencia(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_experiencia_list = JSON.stringify(arrData);
    addItemGridContent("grid_experiencia_list");

    $("#txt_pro_empresa").val('');
    $("#txt_pro_from").val('');
    $("#txt_pro_to").val('');
    $("#txt_pro_denominacion").val('');
    $("#txt_pro_funciones").val('');
}

function removeItemExperiencia(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_experiencia_list", indice);
    removeItemsBase(indice,3);
}

/** IDIOMAS **/
function addIdioma() {
    var idio_id = $("#cmb_idiomas").val();
    var idio_name = $("#cmb_idiomas :selected").text();
    var escrito = $("#cmb_nivel_escrito :selected").text();
    var oral = $("#cmb_nivel_oral :selected").text();
    var certificado = $("#cmb_certificado :selected").text();
    var institucion = $("#txt_idio_institucion").val(); 

    if (escrito == "" || oral == "" || certificado == "" || institucion == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = idio_id;
    tb_item[2] = escrito;
    tb_item[3] = oral;
    tb_item[4] = certificado;
    tb_item[5] = institucion;
    tb_item[6] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = idio_name;
    tb_item2[2] = escrito;
    tb_item2[3] = oral;
    tb_item2[4] = certificado;
    tb_item2[5] = institucion;
    tb_item2[6] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemIdioma(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_idioma_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemIdioma(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_idioma_list = JSON.stringify(arrData);
    addItemGridContent("grid_idioma_list");

    $("#cmb_idiomas").val('');
    $("#cmb_nivel_escrito").val('');
    $("#cmb_nivel_oral").val('');
    $("#cmb_certificado").val('');
    $("#txt_idio_institucion").val('');
}

function removeItemIdioma(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_idioma_list", indice);
    removeItemsBase(indice,4);
}

/** INVESTIGACION **/
function addInvestigacion() {
    var denominancion = $("#txt_re_denominacion").val();
    var ambito = $("#txt_re_ambit").val();
    var responsabilidad = $("#txt_re_respon").val();
    var entidad = $("#txt_re_reali").val();
    var anio = $("#txt_re_year").val();
    var duracion = $("#txt_re_duration").val();

    if (denominancion == "" || ambito == "" || responsabilidad == "" || entidad == "" || anio == "" || duracion == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = denominancion;
    tb_item[2] = ambito;
    tb_item[3] = responsabilidad;
    tb_item[4] = entidad;
    tb_item[5] = anio;
    tb_item[6] = duracion;
    tb_item[7] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = denominancion;
    tb_item2[2] = ambito;
    tb_item2[3] = responsabilidad;
    tb_item2[4] = entidad;
    tb_item2[5] = anio;
    tb_item2[6] = duracion;
    tb_item2[7] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemInvestigacion(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_investigacion_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemInvestigacion(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_investigacion_list = JSON.stringify(arrData);
    addItemGridContent("grid_investigacion_list");

    $("#txt_re_denominacion").val('');
    $("#txt_re_ambit").val('');
    $("#txt_re_respon").val('');
    $("#txt_re_reali").val('');
    $("#txt_re_year").val('');
    $("#txt_re_duration").val('');
}

function removeItemInvestigacion(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_investigacion_list", indice);
    removeItemsBase(indice,5);
}

/** EVENTO **/
function addEvento() {
    var tipo_id = $("#cmb_cap_tipo").val();
    var tipo_name = $("#cmb_cap_tipo :selected").text();
    var nombre = $("#txt_cap_nombre").val();
    var instiucion = $("#txt_cap_institucion").val();
    var anio = $("#txt_cap_anio").val();
    var duracion = $("#txt_cap_duration").val();

    if (nombre == "" || instiucion == "" || anio == "" || duracion == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = nombre;
    tb_item[2] = instiucion;
    tb_item[3] = anio;
    tb_item[4] = tipo_id;
    tb_item[5] = duracion;
    tb_item[6] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = nombre;
    tb_item2[2] = instiucion;
    tb_item2[3] = anio;
    tb_item2[4] = tipo_name;
    tb_item2[5] = duracion;
    tb_item2[6] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemEvento(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_evento_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemEvento(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_evento_list = JSON.stringify(arrData);
    addItemGridContent("grid_evento_list");

    $("#cmb_cap_tipo").val('');
    $("#txt_cap_nombre").val('');
    $("#txt_cap_institucion").val('');
    $("#txt_cap_anio").val('');
    $("#txt_cap_duration").val('');
}

function removeItemEvento(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_evento_list", indice);
    removeItemsBase(indice,6);
}

/** CONFERENCIA **/
function addConferencia() {
    var evento = $("#txt_con_evento").val();
    var instiucion = $("#txt_con_insti").val();
    var anio = $("#txt_con_year").val();
    var ponencia = $("#txt_con_ponencia").val();

    if (evento == "" || instiucion == "" || anio == "" || ponencia == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = evento;
    tb_item[2] = instiucion;
    tb_item[3] = anio;
    tb_item[4] = ponencia;
    tb_item[5] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = evento;
    tb_item2[2] = instiucion;
    tb_item2[3] = anio;
    tb_item2[4] = ponencia;
    tb_item2[5] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemConferencia(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_conferencia_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemConferencia(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_conferencia_list = JSON.stringify(arrData);
    addItemGridContent("grid_conferencia_list");

    $("#txt_con_evento").val('');
    $("#txt_con_insti").val('');
    $("#txt_con_year").val('');
    $("#txt_con_ponencia").val('');
}

function removeItemConferencia(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_conferencia_list", indice);
    removeItemsBase(indice,7);
}

/** PUBLICACION **/
function addPublicacion() {
    var produccion = $("#cmb_tipo_produccion").val();
    var des_produccion = $("#cmb_tipo_produccion :selected").text();    
    var titulo = $("#txt_pub_titulo").val();
    var editorial = $("#txt_pub_editorial").val();
    var isbn = $("#txt_pub_isbn").val();
    var autoria = $("#txt_pub_autoria").val();

    if (produccion == "" || titulo == "" || editorial == "" || isbn == "" || autoria == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = produccion;
    tb_item[2] = titulo;
    tb_item[3] = editorial;
    tb_item[4] = isbn;
    tb_item[5] = autoria;
    tb_item[6] = "N";  // para identificar que es registro nuevo
    tb_item2[0] = 0;
    tb_item2[1] = des_produccion;
    tb_item2[2] = titulo;
    tb_item2[3] = editorial;
    tb_item2[4] = isbn;
    tb_item2[5] = autoria;
    tb_item2[6] = "N"; // para identificar que es registro nuevo
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemPublicacion(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_publicacion_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemPublicacion(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_publicacion_list = JSON.stringify(arrData);
    addItemGridContent("grid_publicacion_list");

    $("#cmb_tipo_produccion").val('');
    $("#txt_pub_titulo").val('');
    $("#txt_pub_editorial").val('');
    $("#txt_pub_isbn").val('');
    $("#txt_pub_autoria").val('');
}

function removeItemPublicacion(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_publicacion_list", indice);
    removeItemsBase(indice,8);
}

/** COORDINACION **/
function addCoordinacion() {
    var inst_id = $("#cmb_cor_institucion").val();
    var inst_name = $("#cmb_cor_institucion :selected").text();
    var alumno = $("#txt_cor_alumno").val();
    var programa = $("#txt_cor_programa").val();
    var academico = $("#txt_cor_academico").val();
    var anio = $("#txt_cor_anio").val();

    if (alumno == "" || programa == "" || academico == "" || anio == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = alumno;
    tb_item[2] = programa;
    tb_item[3] = academico;
    tb_item[4] = inst_id;
    tb_item[5] = anio;
    tb_item[6] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = alumno;
    tb_item2[2] = programa;
    tb_item2[3] = academico;
    tb_item2[4] = inst_name;
    tb_item2[5] = anio;
    tb_item2[6] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemCoordinacion(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_coordinacion_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemCoordinacion(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_coordinacion_list = JSON.stringify(arrData);
    addItemGridContent("grid_coordinacion_list");

    $("#txt_cor_alumno").val('');
    $("#txt_cor_programa").val('');
    $("#txt_cor_academico").val('');
    $("#txt_cor_anio").val('');
}

function removeItemCoordinacion(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_coordinacion_list", indice);
    removeItemsBase(indice,9);
}

/** EVALUACION **/
function addEvaluacion() {
    var periodo = $("#txt_eva_periodo").val();
    var institucion = $("#txt_eva_institucion").val();
    var evaluacion = $("#txt_eva_evaluacion").val();

    if (periodo == "" || institucion == "" || evaluacion == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = periodo;
    tb_item[2] = institucion;
    tb_item[3] = evaluacion;
    tb_item[4] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = periodo;
    tb_item2[2] = institucion;
    tb_item2[3] = evaluacion;
    tb_item2[4] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemEvaluacion(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_evaluacion_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemEvaluacion(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_evaluacion_list = JSON.stringify(arrData);
    addItemGridContent("grid_evaluacion_list");
    $("#txt_eva_periodo").val('');
    $("#txt_eva_institucion").val('');
    $("#txt_eva_evaluacion").val('');
}

function removeItemEvaluacion(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_evaluacion_list", indice);
    removeItemsBase(indice,10);
}

/** REFERENCIA **/
function addReferencia() {
    var nombre = $("#txt_ref_nombre").val();
    var cargo = $("#txt_ref_cargo").val();
    var company = $("#txt_ref_company").val();
    var numero = $("#txt_ref_numero").val();

    if (nombre == "" || cargo == "" || company == "" || numero == "") {
        fillDataAlert();
        return;
    }

    var tb_item = new Array();
    var tb_item2 = new Array();
    var tb_acc = new Array();
    tb_item[0] = 0;
    tb_item[1] = nombre;
    tb_item[2] = cargo;
    tb_item[3] = company;
    tb_item[4] = numero;
    tb_item[5] = "N";
    tb_item2[0] = 0;
    tb_item2[1] = nombre;
    tb_item2[2] = cargo;
    tb_item2[3] = company;
    tb_item2[4] = numero;
    tb_item2[5] = "N";
    //tb_acc[0] = {id: "borr", href: "", onclick:"", title: "Ver", class: "", tipo_accion: "view"};
    tb_acc[0] = { id: "deleteN", href: "", onclick: "javascript:removeItemReferencia(this)", title: objLang.Delete, class: "", tipo_accion: "delete" };
    var arrData = JSON.parse(sessionStorage.grid_referencia_list);

    if (arrData.data) {
        var item = arrData.data;
        tb_item[0] = item.length;
        item.push(tb_item);
        arrData.data = item;
    } else {
        var item = new Array();
        tb_item[0] = 0;
        item[0] = tb_item;
        arrData.data = item;
    }
    if (arrData.label) {
        var item2 = arrData.label;
        tb_item2[0] = item2.length;
        item2.push(tb_item2);
        arrData.label = item2;
    } else {
        var item2 = new Array();
        tb_item2[0] = 0;
        item2[0] = tb_item2;
        arrData.label = item2;
    }
    if (arrData.btnactions) {
        var item3 = arrData.btnactions;
        tb_acc[0].onclik = "javascript:removeItemReferencia(this)";
        item3[item3.length] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    } else {
        var item3 = new Array();
        item3[0] = tb_acc;
        arrData.btnactions = item3;
        // colocar codigo aqui para agregar acciones
    }
    sessionStorage.grid_referencia_list = JSON.stringify(arrData);
    addItemGridContent("grid_referencia_list");

    $("#txt_ref_nombre").val('');
    $("#txt_ref_cargo").val('');
    $("#txt_ref_company").val('');
    $("#txt_ref_numero").val('');
}

function removeItemReferencia(ref) {
    var indice = $(ref).parent().parent().attr("data-key");
    removeItemGridContent("grid_referencia_list", indice);
    removeItemsBase(indice,11);
}

function downloadPdf(ref) {
    var href = $(ref).attr('data-href');
    document.location.href = href;
}

function backtoList() {
    var link = $('#txth_base').val() + "/academico/profesor/index";
    window.location = link;
}

function viewPdf(ref) {
    var href = $(ref).attr('data-href');
    var status = "OK";
    var label = objLang.Success;
    var message = new Object();
    message.title = "Ver Documento";
    message.wtmessage = "<embed src='" + href + "' width='570' height='375' type='application/pdf'>";
    message.acciones = null;
    message.closeaction = null;
    showAlert(status, label, message);
}

function removeItemsBase(Id, tabla){
    var link = $('#txth_base').val() + "/academico/profesor/eliminaritems";
    var arrParams = new Object();
    arrParams.codigo_id = Id;    
    arrParams.tabla_id = tabla;    
    if (!validateForm()) {
        //console.log(arrParams);
        requestHttpAjax(link, arrParams, function(response) {
            console.log(response.message);
            showAlert(response.status, response.label, response.message);
        }, true);
    }
    
}