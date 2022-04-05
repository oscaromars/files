var stripe;
var cardElement;
$(document).ready(function() {
    $('#atach_docum_pago').css('display', 'none');
    //$('#btn_buscar').click(function() {//04 marzo 2022
    $('#btn_buscar_index').click(function() {//04 marzo 2022
        searchModules();
    });
    $('#btn_buscarLis').click(function() {
        searchModulesList();
    });
    $('#btn_modificarcargacartera').click(function() {
        var terminos = ($('#cmb_req').is(':checked')) ? 1 : 0;
        //alert(terminos);
        if(terminos != 0){
                console.log('Estado duplicado: '+$('#isDuplicate').val());
                if($('#cmb_fpago').val() == 1){
                    if(!validateForm()){
                        //iniciarEnvioSiga();
                     guardarCargarCartera();
                        //enviarPdf();
                        //iniciarEnvioSiga();
                    }
                }else{
                    //iniciarEnvioSiga();
                   guardarCargarCartera();
                }    
                
        }else{
            showAlert('NO_OK', 'error', { "wtmessage": "Se deben aceptar los Términos y Condiciones", "title": 'Información' });
        }
    });
    $('#cmb_tpago').change(function() {
        document.getElementById("ex1").hidden = false;
        if ($(this).val() == 1) {
            $('.nocredit').hide();
            $('.nocredit2').show();
            $('#frm_valor').val($('#frm_valor').val());
        } else if ($(this).val() == 2) {
            $('.nocredit').hide();
            $('.nocredit2').show();
            $('#frm_valor').val($('#frm_valor').val());
        } else {
            $('.nocredit').show();
            $('#frm_valor').val($('#frm_valor').val());
        }
        if ($(this).val() == 3) {
            $('.nocredit2').hide();
            document.getElementById("paylink2").hidden = false;
        }
    });
    $('#cmb_fpago').change(function() {
        //alert($('#cmb_fpago').val());
        if($('#cmb_fpago').val() == 1){
            $('txt_dpre_ssn_id_fact').addClass("PBvalidation");
            $('txt_nombres_fac').addClass("PBvalidation");
            $('txt_apellidos_fac').addClass("PBvalidation");
            $('txt_dir_fac').addClass("PBvalidation");
            $('txt_tel_fac').addClass("PBvalidation");
            $('txt_correo_fac').addClass("PBvalidation");
            $('#paylink2').show();
            $('#atach_docum_pago').css('display', 'none');
        }else{
        //if($(this).val() == 3){
            $('txt_dpre_ssn_id_fact').removeClass("PBvalidation");
            $('txt_nombres_fac').removeClass("PBvalidation");
            $('txt_apellidos_fac').removeClass("PBvalidation");
            $('txt_dir_fac').removeClass("PBvalidation");
            $('txt_tel_fac').removeClass("PBvalidation");
            $('txt_correo_fac').removeClass("PBvalidation");
            $('#paylink2').hide();
            $('#atach_docum_pago').css('display', 'block');
        }/*
        if($(this).val() == 5){
            $('txt_dpre_ssn_id_fact').removeClass("PBvalidation");
            $('txt_nombres_fac').removeClass("PBvalidation");
            $('txt_apellidos_fac').removeClass("PBvalidation");
            $('txt_dir_fac').removeClass("PBvalidation");
            $('txt_tel_fac').removeClass("PBvalidation");
            $('txt_correo_fac').removeClass("PBvalidation");
            $('#paylink2').hide();
            $('#atach_docum_pago').css('display', 'block');
        }*/
        
    });
   /* $('#cmb_fpago').change(function() {
        if ($(this).val() == 6 || $(this).val() == 1) {
             $('#txt_correo_fac').addClass("PBvalidation");
             $('#txt_dir_fac').addClass("PBvalidation");
             $('#txt_apellidos_fac').addClass("PBvalidation");
             $('#txt_nombres_fac').addClass("PBvalidation");
             $('#txt_dpre_ssn_id_fact').addClass("PBvalidation");
             $('#paylink2').show();
             $('#atach_docum_pago').css('display', 'none');
        } else {
            $('#txt_dpre_ssn_id_fact').removeClass("PBvalidation");
            $('#txt_correo_fac').removeClass("PBvalidation");
            $('#txt_dir_fac').removeClass("PBvalidation");
            $('#txt_apellidos_fac').removeClass("PBvalidation");
            $('#txt_nombres_fac').removeClass("PBvalidation");
            $('#paylink2').hide();
            $('#atach_docum_pago').css('display', 'block');
        }
    });*/
    $('#cmb_tpago').change(function() {
        let value = $('#cmb_cuota').val();
        let total = ($('#frm_valor').val()).replace(/,/g, '');//($('#frm_costo_item').val()).replace(/,/g, '');
        let cuota = currencyFormat(parseFloat(total / value));
        if (value == 0) $('#frm_cuota').val(currencyFormat(parseFloat(total)));
        else $('#frm_cuota').val(cuota);
        generarDataTable(value);
    });
    $('#cmb_cuota').change(function() {
        let value = $('#cmb_cuota').val();
        let total = ($('#frm_valor').val()).replace(/,/g, '');//($('#frm_costo_item').val()).replace(/,/g, '');
        let cuota = currencyFormat(parseFloat(total / value));
        if (value == 0) $('#frm_cuota').val(currencyFormat(parseFloat(total)));
        else $('#frm_cuota').val(cuota);
        generarDataTable(value);
    });
    $('#btn_calculoCuotas').click(function() {
        let value = $('#cmb_cuota').val();
        let div = $('#cmb_cuota').val();
        let total = ($('#frm_valor').val()).replace(/,/g, '');//($('#frm_costo_item').val()).replace(/,/g, '');
        let cuota = currencyFormat(parseFloat(total / div));
        if (div == 0) $('#frm_cuota').val(currencyFormat(parseFloat(total)));
        else $('#frm_cuota').val(cuota);
        generarDataTable(div);
    });
    $('#btnUpdategrid').click(function() {
        let value = ($('#frm_cuota').val()).replace(/,/g, '');
        let total = ($('#frm_valor').val()).replace(/,/g, '');
        let numCuota = $('#cmb_cuota').val();
        let cuota = (total / numCuota);
        $('#frm_cuota').val(currencyFormat(parseFloat(value)));
        if (parseFloat(value) < parseFloat(cuota)) {
            var msg = objLang.The_value_of_the_first_payment_must_be_greater_than_or_equal_to + ' $' + currencyFormat(parseFloat(cuota));
            shortModal(msg, objLang.Error, "error");
            return;
        }
        if (parseFloat(value) > parseFloat(total)) {
            var msg = objLang.The_value_of_the_first_payment_must_be_less_than_or_equal_to + ' $' + currencyFormat(parseFloat(total));
            shortModal(msg, objLang.Error, "error");
            return;
        }
        generarDataTable(numCuota, value);
    });
    sessionStorage.setItem('grid_direct_credit', '');


        /***********************************************/
    /* Filtro para busqueda en listado solicitudes */
    /***********************************************/
    $('#cmb_mod').change(function () {
        var link = $('#txth_base').val() + "/academico/registro/index";
        /*document.getElementById("cmb_carrerabus").options.item(0).selected = 'selected';*/
        var arrParams = new Object();
        arrParams.nint_id = $(this).val();
        arrParams.getperiodo = true;
        requestHttpAjax(link, arrParams, function (response) {
            if (response.status == "OK") {
                data = response.message;
                setComboDataselect(data.periodo, "cmb_per_acad", "Todos");
                /*var arrParams = new Object();
                if (data.modalidad.length > 0) {
                    arrParams.unidada = $('#cmb_unidadbus').val();
                    arrParams.moda_id = data.modalidad[0].id;
                    arrParams.getcarrera = true;
                    requestHttpAjax(link, arrParams, function (response) {
                        if (response.status == "OK") {
                            data = response.message;
                            setComboDataselect(data.carrera, "cmb_carrerabus", "Todos");
                        }
                    }, true);
                }*/
            }
        }, true);
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

function generateFee(){
    let value = $(this).val();
    let total = ($('#frm_valor').val()).replace(/,/g, '');//($('#frm_costo_item').val()).replace(/,/g, '');
    let cuota = currencyFormat(parseFloat(total / value));
    if (value == 0) $('#frm_cuota').val(currencyFormat(parseFloat(total)));
    else $('#frm_cuota').val(cuota);
    generarDataTable(value);
}

function searchModules() {
    var arrParams2 = new Object();
    arrParams2.PBgetFilter = true;
    //arrParams2.search = $("#txt_buscarData").val();
    arrParams2.mod_id = $("#cmb_mod").val();
    arrParams2.periodo = ($("#cmb_per_acad option:selected").val());
    //arrParams2.estado = $("#cmb_status").val();
    //alert(arrParams2.periodo+'-'+arrParams2.estado);
    var mod_id = $("#cmb_mod option:selected").val();
    var periodo = ($("#cmb_per_acad option:selected").val());
    var estudiante = $("#txt_buscarData").val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    //$("#grid_registropay_list").PbGridView("applyFilterData", arrParams2);
    if (!$(".blockUI").length) {
        showLoadingPopup();
        $("#grid_registropay_list").PbGridView("applyFilterData", {'modalidad': mod_id,'f_ini': f_ini, 'f_fin': f_fin, 'periodo': periodo,'estudiante': estudiante});
        setTimeout(hideLoadingPopup, 2000);
    }
}

function searchModulesList() {
    var arrParams2 = new Object();
    arrParams2.PBgetFilter = true;
    arrParams2.search = $("#txt_buscarData").val();
    arrParams2.periodo = ($("#cmb_per_acad").val() == 0) ? "" : ($("#cmb_per_acad option:selected").val());
    arrParams2.mod_id = $("#cmb_mod").val();
    arrParams2.estado = $("#cmb_status").val();
    $("#grid_cancel_list").PbGridView("applyFilterData", arrParams2);
}

function save() {
    //guardarCargarCartera();
    //Se debe de enviar el pago  y retornar el mensaje de exito o error
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/registro/save/" + $('#txt_id_code').val();//$('#frm_id').val();//
    var total = ($('#frm_costo_item').val()).replace(/,/g, '');//($('#frm_costo_item').val()).replace(/,/g, '');
    var numCuota = $('#cmb_cuota').val();
    var cuota = (total / numCuota);
    arrParams.archivo = $('#txth_pago_doc2').val() + "." + $('#txth_pago_doc').val().split('.').pop();
    arrParams.tpago = $('#cmb_tpago').val();
    arrParams.fpago = $('#cmb_fpago').val();
    arrParams.total = $('#frm_valor').val();
    arrParams.interes = $('#frm_int_ced').val();
    arrParams.financiamiento = $('#frm_finan').val();
    arrParams.numcuotas = $('#cmb_cuota').val();
    arrParams.cuotaini = $('#frm_cuota').val();
    arrParams.termino = ($('#cmb_req').is(':checked')) ? 1 : 0;
    arrParams.rama_id = $('#frm_rama_id').val();
    arrParams.per_id = $('#txt_per_id').val();

    /*
    *DATOS PARA CUOTAS
    */
    arrParams.rama = $('#txt_rama').val();
    /*arrParams.tpago = $('#cmb_tpago').val();
    arrParams.total = $('#frm_valor').val();
    arrParams.interes = $('#frm_int_ced').val();
    arrParams.financiamiento = $('#frm_finan').val();
    arrParams.numcuotas = $('#cmb_cuota').val();
    arrParams.rama_id = $('#frm_rama_id').val();
    arrParams.per_id = $('#txt_per_id').val();*/
    /**/

    if(arrParams.termino == 0){
        var mensaje = {wtmessage: '', title: "Se deben aceptar los terminos y condiciones para continuar"};
        //alert('Se deben aceptar terminos y condiciones');
        showAlert("NO_OK", "error", mensaje);
        return;
    }
    /**
    * Datos de factura por  pago boton en linea
    */
   //alert(arrParams.archivo+'-'+arrParams.tpago+'-'+arrParams.fpago+'-'+arrParams.total+'-'+arrParams.interes+'-'+arrParams.financiamiento+'-'+arrParams.numcuotas+'-'+
   //arrParams.cuotaini+'-'+arrParams.termino+'-'+arrParams.rama_id+'-'+arrParams.per_id);
    if (arrParams.fpago== 6 || arrParams.fpago== 1){
        arrParams.factssnid = $('#txt_dpre_ssn_id_fact').val();
        arrParams.factnombre = $('#txt_nombres_fac').val();
        arrParams.factapellido = $('#txt_apellidos_fac').val();
        arrParams.factcorreo = $('#txt_correo_fac').val();
        arrParams.factdirecc = $('#txt_dir_fac').val();
        arrParams.facttelef = $('#txt_tel_fac').val();

    }

    if (sessionStorage.getItem('grid_direct_credit') != '') {
        arrParams.valcuotas = JSON.parse(sessionStorage.grid_direct_credit);
    } else {
        arrParams.valcuotas = new Array();
    }

    if (arrParams.tpago == 3 && sessionStorage.getItem('grid_direct_credit') == '' && arrParams.numcuotas == 0) {
        var msg = objLang.Please_select_a_number_of_installments_;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if (arrParams.termino == false) {
        var msg = objLang.To_Continue_you_must_accept_terms_and_conditions;
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrParams.fpago == 4  || arrParams.fpago == 5 ) {
        if($('#txth_pago_doc2').val() == ""  ||  $('#txth_pago_doc').val() == "" ){
            var msg = objLang.Please_attach_a_file_in_format_png_or_jpeg;
            shortModal(msg, objLang.Error, "error");
            return;
        }
    }
    /*
    if (arrParams.tpago == 3 && (parseFloat((arrParams.cuotaini).replace(/,/g, '')) < parseFloat(cuota))) {
        var msg = objLang.The_value_of_the_first_payment_must_be_greater_than_or_equal_to + ' $' + currencyFormat(parseFloat(cuota));
        shortModal(msg, objLang.Error, "error");
        return;
    }
    if (arrParams.tpago == 3 && (parseFloat((arrParams.cuotaini).replace(/,/g, '')) > parseFloat(total))) {
        var msg = objLang.The_value_of_the_first_payment_must_be_less_than_or_equal_to + ' $' + currencyFormat(parseFloat(total));
        shortModal(msg, objLang.Error, "error");
        return;
    }*/
    if (!validateForm()) {
        if (arrParams.fpago== 6 ||arrParams.fpago== 1){
            //Utiliza pago stripe
            try{
                stripe.createToken(cardElement).then(function(result) {
                    //Si error  en transaccion
                    if (result.error) {
                        //$("#payBtn").prop("disabled",false);     
                        // Inform the user if there was an error
                        //$('#paymentResponse').html('<p>'+event.error.message+'</p>');
                    } else {
                                    // Send the token to your server
                                   // stripeTokenHandler(result.token);
                                    token = result.token.id;
                                    var hiddenInput = document.createElement('input');
                                    hiddenInput.setAttribute('type', 'hidden');
                                    hiddenInput.setAttribute('name', 'stripeToken');
                                    //hiddenInput.setAttribute('value', token.id);
                                    hiddenInput.setAttribute('value', result.token.id);
                                    var link1 = $('#txth_base').val() + "/academico/registro/stripecheckout2";


                                   
                                    //data = new FormData();
                                    //data.append( 'stripeToken', result.token.id);
                                    //data.append( 'email'      , $('#txt_correo_fac').val() );
                                    //data.append( 'name'       , $('#txt_nombres_fac').val()+ " " + $('#txt_apellidos_fac').val());
                                    //data.append( 'valFrtsPay',  arrParams.cuotaini);

                                     //datos del stripe
                                     arrParams.stripeToken = result.token.id;
                                     arrParams.email = $('#txt_correo_fac').val();
                                     arrParams.name = $('#txt_nombres_fac').val()+ " " + $('#txt_apellidos_fac').val();
                                     arrParams.valFrtsPay = $('#frm_cuota').val();
                                     requestHttpAjax(link, arrParams, function(response) {
                                     showAlert(response.status, response.label, response.message);
                                        if (response.status == "OK") {
                                            setTimeout(function() {
                                               window.location.href = $('#txth_base').val() + "/academico/matriculacion/fundacion";
					window.location.href = $('#txth_base').val() + "/academico/registro/index?per_id=" + $('#frm_per_id').val();
                                            }, 3000);
                                        }
                                     }, true); 


                                    
                         $.ajax({
                            data: data,
                            type       : "POST",
                            dataType   : "json",
                            cache      : false,
                            contentType: false,
                            processData: false,
                            async: false,
                            url: link1,
                        }).then(function( data ) {
                            //alert(data.id)
                            console.log(data);
                             if (data =="Your Payment has been Successful!") {
                                    //showAlert("OK", 'Exito pago', data);
                                    // se debe de continuar con el registro delago en las tablas 
                                    requestHttpAjax(link, arrParams, function(response) {
                                    showAlert(response.status, response.label, response.message);
                                        if (response.status == "OK") {
                                            setTimeout(function() {
                                                window.location.href = $('#txth_base').val() + "/academico/registro/index";
                                            }, 3000);
                                        }
                                    }, true); 
                                 //save();
                             } else{
                                var mensaje = {wtmessage: data, title: "Información"};
                                showAlert("NO_OK", "error", mensaje);
                             }
                            





                            $("#seccion_pago_online").html('<i class="fas fa-check-circle" style="color: #a31b5c;"> SU PAGO FUE INGRESADO CORRECTAMENTE</i>');
                            //$("#payBtn").hide();
                            //llamar a la funcion de save de pagos.
                            //sendInscripcionSubirPago3();
                            //$.LoadingOverlay("hide"); 
                            var mensaje = {wtmessage: data, title: "SU PAGO FUE INGRESADO CORRECTAMENTE"};
                            showAlert("OK", "success", mensaje);                
                        });  

                        }// Fin if (result.error) {


                });
            }catch(err){
                $('#paymentResponse').html('<p>'+err+'</p>');
               //$("#payBtn").prop("disabled",false);     
                console.log("error: "+err)
            }

        }else{



                requestHttpAjax(link, arrParams, function(response) {
                showAlert(response.status, response.label, response.message);
                    if (response.status == "OK") {
                        setTimeout(function() {
			   // window.location.href = $('#txth_base').val() + "/academico/matriculacion/index";
			   //window.location.href = $('#txth_base').val() + "/academico/matriculacion/fundacion";
			 // window.location.href = $('#txth_base').val() + "/academico/registro/index?per_id=" + $('#frm_per_id').val();
			 //   window.location.href = $('#txth_base').val() + "/academico/registro/index" + $('#frm_per_id').val();
                           // window.location.href = $('#txth_base').val() + "/academico/registro/index";
                        }, 3000);
                    }
                }, true); 
        }

        //create token stripe
       // createToken();
    }


}

function update() {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/registro/update/" + $('#frm_id').val();
    arrParams.archivo = $('#txth_pago_doc2').val() + "." + $('#txth_pago_doc').val().split('.').pop();
    arrParams.rama_id = $('#frm_rama_id').val();
    arrParams.rpm_id = $('#frm_rpmid').val();
    if (arrParams.termino == false) {
        var msg = objLang.To_Continue_you_must_accept_terms_and_conditions;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if ($('#txth_pago_doc2').val() == "") {
        var msg = objLang.Please_attach_a_file_in_format_png_or_jpeg;
        shortModal(msg, objLang.Error, "error");
        return;
    }

    if (!validateForm()) {
        requestHttpAjax(link, arrParams, function(response) {
            showAlert(response.status, response.label, response.message);
            if (response.status == "OK") {
                setTimeout(function() {
                    window.location.href = $('#txth_base').val() + "/academico/registro/index";
                }, 3000);
            }
        }, true);
    }
}

function currencyFormat(num) {
    return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function generarDataTable(cuotas, primerPago) {
    primerPago = primerPago || null;
    let total = ($('#frm_valor').val()).replace(/,/g, '');//($('#frm_costo_item').val()).replace(/,/g, '');
    let cuota = (total / cuotas);
    let labelPay = $('#lbl_payment').val();
    let percentaje = (100 / cuotas).toFixed(2);
    loadSessionCampos('grid_direct_credit', '', '', '');
    let arrData = JSON.parse(sessionStorage.grid_direct_credit);
    let sumPer = 0.00;
    let sumTot = 0.00;
    let perPriC = 0;
    let perCuot = 0;
    let diffCuota = 0;
    let per_one = 0;
    if (primerPago !== null && cuotas > 1) {
        perPriC = ((primerPago * 100) / total).toFixed(2);
        diffCuota = (total - primerPago);
        perCuot = ((diffCuota * 100) / total).toFixed(2);
        percentaje = (perCuot / (cuotas - 1)).toFixed(2);
        diffCuota = diffCuota / (cuotas - 1);
        cuota = (diffCuota).toFixed(2);
    }

    for (let i = 1; i <= cuotas; i++) {
        sumPer += (primerPago !== null && cuotas > 1 && i == 1) ? parseFloat(perPriC) : parseFloat(percentaje);
        sumTot += (primerPago !== null && cuotas > 1 && i == 1) ? parseFloat(primerPago) : parseFloat(cuota);
        if (i == cuotas && sumPer != 100) {
            percentaje = ((100 - sumPer) + parseFloat(percentaje)).toFixed(2);
        }
        if(i==cuotas){
            per_one = percentaje;
        }else{
            per_total = percentaje;
        }
    }
   // alert(per_one + '-'+ per_total);
    //alert((total*per_one).toFixed(2));
    //alert((total*per_total).toFixed(2));

    for (let i = 1; i <= cuotas; i++) {
        /*sumPer += (primerPago !== null && cuotas > 1 && i == 1) ? parseFloat(perPriC) : parseFloat(percentaje);
        sumTot += (primerPago !== null && cuotas > 1 && i == 1) ? parseFloat(primerPago) : parseFloat(cuota);
        if (i == cuotas && sumPer != 100) {
            percentaje = ((100 - sumPer) + parseFloat(percentaje)).toFixed(2);
        }
        if (i == cuotas && sumTot != total) {
            cuota = ((total - sumTot) + (parseFloat(cuota))).toFixed(2);
        }*/
        primerCuota = ((total*(100/percentaje))/100).toFixed(1);
        cuotageneral = (total*cuotas/100).toFixed(2);
        porc = (i == 1) ? (per_one) : (percentaje);
        monto = (i == 1) ? (total*(per_one/100)).toFixed(2):(total*(per_total/100)).toFixed(2);
        var tb_item = new Array();
        var tb_item2 = new Array();
        tb_item[0] = 0;
        tb_item[1] = labelPay + i;
        tb_item[2] = (i == 1) ? (per_one + '%') : (per_total + '%');
        tb_item[3] = '$ '+monto;//( i == 1) ? primerCuota: cuotageneral;//('$' + currencyFormat(parseFloat(primerPago))) : ('$' + currencyFormat(parseFloat(cuota)));
        tb_item[4] = $('#vencimiento_' + i).val();
        tb_item[5] = "PENDIENTE";//(i == 1) ? "TO CHECK" : "PENDING";
        tb_item2[0] = 0;
        tb_item2[1] = labelPay + i;
        tb_item2[2] = (i == 1) ? (per_one + '%') : (per_total + '%');
        tb_item2[3] = '$ '+monto;//( i == 1) ? primerCuota: cuotageneral;//(primerPago !== null && cuotas > 1 && i == 1) ? ('$' + currencyFormat(parseFloat(primerPago))) : ('$' + currencyFormat(parseFloat(cuota)));
        tb_item2[4] = $('#vencimiento_' + i).val();
        tb_item2[5] = "PENDIENTE";//(i == 1) ? "TO CHECK" : "PENDING";
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
    }
    console.log(arrData);
    sessionStorage.grid_direct_credit = JSON.stringify(arrData);
    addItemGridContent("grid_direct_credit");
}

function downloadEnrollment(id, rpm_id) {
    window.location.href = $('#txth_base').val() + "/academico/registro/downloadenroll?id=" + id + "&rpm_id=" + rpm_id;
}

function aprobarCancelacion(id) {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/registro/aprobarcancel";
    arrParams.id = id;

    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
           setTimeout(function() {
                window.location.href = $('#txth_base').val() + "/academico/registro/index";
            }, 3000); 
	  
	  //searchModulesList();
        }
    }, true);
}

function confirmarDevolucion(id) {
    var arrParams = new Object();
    var link = $('#txth_base').val() + "/academico/registro/confirmrefund";
    arrParams.id = id;

    requestHttpAjax(link, arrParams, function(response) {
        showAlert(response.status, response.label, response.message);
        if (response.status == "OK") {
            searchModulesList();
        }
    }, true);
}

 /************************************************************/
    /***** INICIO STRIPE ****************************************/
    /************************************************************/
    // Create an instance of the Stripe object
    // Set your publishable API key
    
    //CLAVE PRODUCCION
    //var stripe = Stripe('pk_live_51HrVkKC4VyMkdPFRjqnwytVZZb552sp7TNEmQanSA78wA1awVHIDp94YcNKfa66Qxs6z2E73UGJwUjWN2pcy9nWl008QHsVt3Q');
    //CLAVE DESARROLLO
    //var stripe = Stripe('pk_test_51HrVkKC4VyMkdPFRZ5aImiv4UNRIm1N7qh2VWG5YMcXJMufmwqvCVYAKSZVxvsjpP6PbjW4sSrc8OKrgfNsrmswt00OezUqkuN');
    // Create an instance of elements
    
    $('#payBtn').on('click', function (e) {

        var arrParamsbp = new Object();
        //var link = $('#txth_base').val() + "/academico/registro/save/" + $('#frm_id').val();
        var total = ($('#frm_costo_item').val()).replace(/,/g, '');
        var numCuota = $('#cmb_cuota').val();
        var cuota = (total / numCuota);
        arrParamsbp.archivo = $('#txth_pago_doc2').val() + "." + $('#txth_pago_doc').val().split('.').pop();
        arrParamsbp.tpago = $('#cmb_tpago').val();
        arrParamsbp.fpago = $('#cmb_fpago').val();
        arrParamsbp.total = $('#frm_valor').val();
        arrParamsbp.interes = $('#frm_int_ced').val();
        arrParamsbp.financiamiento = $('#frm_finan').val();
        arrParamsbp.numcuotas = $('#cmb_cuota').val();
        arrParamsbp.cuotaini = $('#frm_cuota').val();
        arrParamsbp.termino = ($('#cmb_req').is(':checked')) ? 1 : 0;
        arrParamsbp.rama_id = $('#frm_rama_id').val();
        if (sessionStorage.getItem('grid_direct_credit') != '') {
            arrParamsbp.valcuotas = JSON.parse(sessionStorage.grid_direct_credit);
        } else {
            arrParamsbp.valcuotas = new Array();
        }

        if (arrParamsbp.tpago == 3 && sessionStorage.getItem('grid_direct_credit') == '' && arrParamsbp.numcuotas == 0) {
            var msg = objLang.Please_select_a_number_of_installments_;
            shortModal(msg, objLang.Error, "error");
            return;
        }

        if (arrParamsbp.termino == false) {
            var msg = objLang.To_Continue_you_must_accept_terms_and_conditions;
            shortModal(msg, objLang.Error, "error");
            return;
        }

        if ($('#txth_pago_doc2').val() == ""  &&  (arrParamsbp.fpago == 1  || arrParamsbp.fpago == 2 )) {
            var msg = objLang.Please_attach_a_file_in_format_png_or_jpeg;
            shortModal(msg, objLang.Error, "error");
            return;
        }
/*
        if (arrParamsbp.tpago == 3 && (parseFloat((arrParamsbp.cuotaini).replace(/,/g, '')) < parseFloat(cuota))) {
            var msg = objLang.The_value_of_the_first_payment_must_be_greater_than_or_equal_to + ' $' + currencyFormat(parseFloat(cuota));
            shortModal(msg, objLang.Error, "error");
            return;
        }
        if (arrParamsbp.tpago == 3 && (parseFloat((arrParamsbp.cuotaini).replace(/,/g, '')) > parseFloat(total))) {
            var msg = objLang.The_value_of_the_first_payment_must_be_less_than_or_equal_to + ' $' + currencyFormat(parseFloat(total));
            shortModal(msg, objLang.Error, "error");
            return;
        }*/
        if (!validateForm()) {
            $("#payBtn").prop("disabled",true);        
            /*
            $.LoadingOverlay("show",{
                color  : "#a41b5e"
            });
            */
            console.log("DIO CLICK EN EL BOTON");
            createToken();
        }



       
    });
    
    // Create single-use token to charge the user
    function createToken() {
        try{
            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    $("#payBtn").prop("disabled",false);     
                    // Inform the user if there was an error
                    $('#paymentResponse').html('<p>'+event.error.message+'</p>');
                } else {
                    // Send the token to your server
                    stripeTokenHandler(result.token);
                }
            });
        }catch(err){
            $('#paymentResponse').html('<p>'+err+'</p>');
            $("#payBtn").prop("disabled",false);     
            console.log("error: "+err)
        }
    }//function createToken

    // Callback to handle the response from stripe
    function stripeTokenHandler(token) {
        console.log(token);
        // Insert the token ID into the form so it gets submitted to the server
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        
        var link = $('#txth_base').val() + "/academico/registro/stripecheckout2";

        data = new FormData();
        data.append( 'stripeToken', token.id );
        data.append( 'email'      , $('#txt_correo_fac').val() );
        data.append( 'name'       , $('#txt_nombres_fac').val()+ " " + $('#txt_apellidos_fac').val()  );

        $.ajax({
            data: data,
            type       : "POST",
            dataType   : "json",
            cache      : false,
            contentType: false,
            processData: false,
            async: false,
            url: link,
        }).then(function( data ) {
            //alert(data.id)
            //console.log(data);
            
            $("#seccion_pago_online").html('<i class="fas fa-check-circle" style="color: #a31b5c;"> SU PAGO FUE INGRESADO CORRECTAMENTE</i>');
            //$("#payBtn").hide();

            //llamar a la funcion de save de pagos.
            
            //sendInscripcionSubirPago3();
            //$.LoadingOverlay("hide");
            
        });
    }//function stripeTokenHandler
    
    //$.LoadingOverlay("hide");
    /************************************************************/
    /***** FIN STRIPE *******************************************/
    /************************************************************/

function guardarCargarCartera(){
    enviarDatosFacturacion();
    //iniciarEnvioSiga(); //04 marzo 2022, se deshabilita webservices Siga.
    var link = $('#txth_base').val() + "/academico/registro/modificarcargacartera";
    showLoadingPopup();
    var arrParams       = new Object();
    arrParams.rama      = $('#txt_rama').val();
    arrParams.tpago     = $('#cmb_tpago').val();
    arrParams.total     = $('#frm_valor').val();
    arrParams.interes   = $('#frm_int_ced').val();
    arrParams.financiamiento = $('#frm_finan').val();
    arrParams.numcuotas = $('#cmb_cuota').val();
    arrParams.ron_id    = $('#txt_ron_id').val();
    arrParams.per_id    = $('#txt_per_id').val();
    arrParams.pla_id    = $('#txt_pla_id').val();
    arrParams.bloque    = $('#txt_bloque').val();
    arrParams.saca_id   = $('#txt_saca_id').val();
    arrParams.cmb_fpago = $("#cmb_fpago").val();
    arrParams.documento = $('#txth_doc_pago').val();
    //------Datos de Facturacion----------------
        arrParams.factssnid = $('#txt_dpre_ssn_id_fact').val();
        arrParams.factnombre = $('#txt_nombres_fac').val();
        arrParams.factapellido = $('#txt_apellidos_fac').val();
        arrParams.factcorreo = $('#txt_correo_fac').val();
        arrParams.factdirecc = $('#txt_dir_fac').val();
        arrParams.facttelef = $('#txt_tel_fac').val();
    //------------------------------------------

    var terminos = ($('#cmb_req').is(':checked')) ? 1 : 0;
    // $per_id,  $forma_pago,$in, $numcuotas,$valor_cuota, $total, $usu_id);
    //alert(arrParams.pla_id + '-' + arrParams.per_id);
    //redirect = $('#txth_base').val() + "/academico/registro/new/"+arrParams.per_id+'?rama_id='+arrParams.rama_id ;
    //redirect = $('#txth_base').val() + "/academico/registro/index";
    //alert(arrParams.tpago+'-'+arrParams.total+'-'+arrParams.interes +'-'+arrParams.financiamiento+'-'+arrParams.numcuotas+'-'+arrParams.rama_id+'-'+arrParams.per_id +'-'+ $redirect);
    try{
        if(arrParams.tpago == 2 && arrParams.cmb_fpago == 1){
        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                console.log(result);

                var mensaje = {wtmessage: '<p>'+result.error.message+'</p>', title: "Error"};
                showAlert("NO_OK", "error", mensaje);
                return false;
            } else {
                arrParams.token = result.token.id;
                console.log(arrParams.token);

                if(arrParams.numcuotas != 0 || terminos==0){
                    //if(terminos != 0){
                        //if(!validateForm()){
                            //try{
                    setTimeout(function() {
                        requestHttpAjax(link, arrParams, function(response) {
                            var message = response.message;
                            if (response.status == "OK") {
                                setTimeout(function() {
                                    //windows.location.href = $('#txth_base').val() + "/academico/registro/index";
                                    hideLoadingPopup();
                                    parent.window.location.href = $('#txth_base').val() + "/academico/registro/index";  
                                    }, 4000);
                            } else {
                                //showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
                            }

                        }, true);

                     }, 2000);
                            /*}catch(err){
                                alert( "wtmessage <p>+"+$err+"</p>");    
                                console.log("error: "+err)
                            }*/
                        //}    
                    //}
                } else 
                    showAlert('NO_OK', 'error', { "wtmessage": "Se debe escoger el numero de cuotas.", "title": 'Información' });
    
                //alert(arrParams.token);
            }//else
        });//stripe
        }else{
            setTimeout(function() {
                requestHttpAjax(link, arrParams, function(response) {
                    var message = response.message;
                    if (response.status == "OK") {
                        setTimeout(function() {
                            if($('#cmb_tpago').val()==3){
                                enviarPdf();
                            }else{
                                showAlert(response.status, response.type, { "wtmessage": 'Su información se registro con éxito.', "title": response.label });
                                setTimeout(function() {
                                //windows.location.href = $('#txth_base').val() + "/academico/registro/index";
                                hideLoadingPopup();
                                parent.window.location.href = $('#txth_base').val() + "/academico/registro/index";  
                                }, 4000);
                            }
                        }, 3000);
                    }
                }, true);
             }, 2000);
        }
    }catch(err){
        var mensaje = {wtmessage: err+" ///catch", title: "Error"};
        showAlert("NO_OK", "error1", mensaje);
        return false;
    }
    
}//function guardarCargarCartera

function enviarPdf(){
    var link = $('#txth_base').val() + "/academico/registro/sendpdf";
    var arrParams = new Object();
    arrParams.per_id = $('#txt_per_id').val();
    arrParams.cuotas = $('#txt_cuotas').val();
    arrParams.rama = $('#txt_rama').val();
    arrParams.modalidad = $('#txt_mod_nombre').val();
    //alert(arrParams.rama);
    //iniciarEnvioSiga();
    try{
        requestHttpAjax(link, arrParams, function(response) {
        var message = response.message;
        if (response.status == "OK") {
            showAlert(response.status, response.type, { "wtmessage": 'Su información se registro con éxito.', "title": response.label });
            setTimeout(function() {
            //windows.location.href = $('#txth_base').val() + "/academico/registro/index";
            hideLoadingPopup();
            parent.window.location.href = $('#txth_base').val() + "/academico/registro/index";
            }, 4000);
        } 
        }, true);
    }catch(err){
        //alert( "wtmessage <p>+"+$err+"</p>");    
        console.log("error: "+err)
    }
}

/*function iniciarEnvioSiga(){
    var link = $('#txth_base').val() + "/academico/registro/enviosigamatricula";
    var arrParams       = new Object();
    arrParams.rama      = $('#txt_rama').val();
    arrParams.tpago     = $('#cmb_tpago').val();

    var cedula  = $("#txt_cedula").val();
    var ron_id  = $("#txt_ron_id").val();
    var cuota  = $("#cmb_cuota").val();
    var arr_materias = $('#data_siga').val();
    var num_reg = $('#num_reg').val();
    var mod = $('#txt_mod_nombre').val();
    var flujo = $('#txt_flujo').val();
    var macs_id = $('#txt_macs').val();
    //alert("per_id: "+per_id+" - ron_id: "+ron_id);
    var data;
    var varios = 0;
    if( mod == 1){if(cuota>3){valor = 50;}else{valor=40}}
    else{if(cuota>3){valor = 60;varios=240;}else{valor=30;varios=120;}}
    var virtuales = valor;

    var arrParams       = new Object();
    arrParams.accion        = "registro";
    arrParams.cedula        = mod;
    arrParams.online        = cedula;
    arrParams.ron_id        = ron_id;
    arrParams.num_reg       = num_reg;
    arrParams.arr_materias  = arr_materias;
    arrParams.flujo         = flujo;
    arrParams.macs_id       = macs_id;
    arrParams.virtuales     = virtuales;
    arrParams.varios        = varios;
    arrParams.msg           = '';

    //setTimeout(function() {
      //  requestHttpAjax(link, arrParams, function(response) {
        //    var message = response.message;
          //  if (response.status == "OK") {
            //    setTimeout(function() {
              //      showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
                //    }, 1000);
            //} else {
                //showAlert(response.status, response.type, { "wtmessage": message.info, "title": response.label });
            //}

        //}, true);

     //}, 1000);
   
    data = new FormData();
    data.append( 'accion' , "registro" );
    data.append( 'online' , mod);
    data.append( 'cedula' , cedula );
    data.append( 'ron_id', ron_id);
    data.append( 'num_reg', num_reg);
    data.append( 'arr_materias', arr_materias);
    data.append( 'flujo', flujo);
    data.append( 'macs_id', macs_id);
    data.append( 'virtuales', virtuales);
    data.append( 'varios', varios);
    data.append( 'msg' ,'');
    //alert('Si');
    $.ajax({
        data: data,
        type: "POST",
        //dataType: "json",
        cache      : false,
        contentType: false,
        processData: false,
        async: false,
        url: "https://acade.uteg.edu.ec/registro_matriculacion_desa/rest.php",
        success: function (data) {
            //alert("Envío de registros exitoso");
        },
    });
    alert('Envío exitoso');
}*/
//function iniciarEnvioSiga+

function enviarDatosFacturacion(){
    var link = $('#txth_base').val() + "/academico/registro/datosfacturacion";
    var arrParams = new Object();

    arrParams.ron_id = $('#txt_ron_id').val();
    arrParams.rama = $('#txt_rama').val();
    arrParams.per_id = $('#txt_id_code').val();
    
    arrParams.cedula = $('#txt_dpre_ssn_id_fact').val();
    arrParams.nombre = $('#txt_nombres_fac').val();
    arrParams.apellidos = $('#txt_apellidos_fac').val();
    arrParams.direccion = $('#txt_dir_fac').val();
    arrParams.telefono = $('#txt_tel_fac').val();
    arrParams.correo = $('#txt_correo_fac').val();
    
    //alert(arrParams.rama);
    //iniciarEnvioSiga();
    try{
        requestHttpAjax(link, arrParams, function(response) {
        var message = response.message;
        if (response.status == "OK") {
            showAlert(response.status, response.type, { "wtmessage": 'Datos de Facturacion: Su información se registro con éxito.', "title": response.label });
            setTimeout(function() {
            //windows.location.href = $('#txth_base').val() + "/academico/registro/index";
            }, 1000);
        } 
        }, true);
    }catch(err){
        //alert( "wtmessage <p>+"+$err+"</p>");    
        console.log("error: "+err)
    }
}

function exportExcel() {
    var estudiante = $('#txt_buscarData').val();
    var modalidad = $('#cmb_mod option:selected').val();
    var periodo = $('#cmb_per_acad option:selected').val();
    var f_ini = $('#txt_fecha_ini').val();
    var f_fin = $('#txt_fecha_fin').val();
    window.location.href = $('#txth_base').val() + "/academico/registro/exportexcel?estudiante=" + estudiante +  '&modalidad=' + modalidad + "&periodo=" + periodo + '&f_fin=' + f_fin + '&f_ini=' + f_ini;
 }