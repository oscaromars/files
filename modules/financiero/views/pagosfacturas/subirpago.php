<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use kartik\date\DatePicker;
use app\modules\academico\Module as Especies;
use app\modules\financiero\Module as Pagos;
use app\modules\admision\Module as crm;
use app\modules\academico\Module as academico;

//print_r($model);
Especies::registerTranslations();
Pagos::registerTranslations();
crm::registerTranslations();
academico::registerTranslations();

$this->registerJsFile("https://js.stripe.com/v3/",['depends' => [\yii\web\YiiAsset::className()]]);


$this->registerJs("
    $(document).ready(function () {
    /************************************************************/
    /***** INICIO STRIPE ****************************************/
    /************************************************************/
        // Create an instance of the Stripe object
        // Set your publishable API key
        
        //CLAVE PRODUCCION
        //var stripe = Stripe('pk_live_51HrVkKC4VyMkdPFRjqnwytVZZb552sp7TNEmQanSA78wA1awVHIDp94YcNKfa66Qxs6z2E73UGJwUjWN2pcy9nWl008QHsVt3Q');
        //CLAVE DESARROLLO    
        stripe = Stripe('pk_test_51HrVkKC4VyMkdPFRZ5aImiv4UNRIm1N7qh2VWG5YMcXJMufmwqvCVYAKSZVxvsjpP6PbjW4sSrc8OKrgfNsrmswt00OezUqkuN');
        
        // Create an instance of elements
        var elements = stripe.elements();

        var style = {
            base: {
                //fontWeight: 400,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                fontSize: '12px',
                lineHeight: '1.4',
                color: '#555',
                backgroundColor: '#fff',
                '::placeholder': {
                    color: '#888',
                },
            },
            invalid: {
                color: '#eb1c26',
            }
        };

        cardElement = elements.create('cardNumber', {
            style: style
        });
        cardElement.mount('#card_number');

        var exp = elements.create('cardExpiry', {
            'style': style
        });
        exp.mount('#card_expiry');

        var cvc = elements.create('cardCvc', {
            'style': style
        });
        
        cvc.mount('#card_cvc');

        cardElement.addEventListener('change', function(event) {
            if (event.error) {
                //resultContainer.innerHTML = '<p>'+event.error.message+'</p>';
                $('#paymentResponse').html('<p>'+event.error.message+'</p>');
            } else {
                //resultContainer.innerHTML = '';
                $('#paymentResponse').html('');
            }
        });
    /************************************************************/
    /***** FIN STRIPE *******************************************/
    /************************************************************/
    })
    "
);

?>

<?= Html::hiddenInput('txth_idest', $arr_persona['est_id'], ['id' => 'txth_idest']); ?>
<?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Student Data") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_nombre'] . " " . $arr_persona['per_seg_nombre'] . " " . $arr_persona['per_pri_apellido'] . " " . $arr_persona['per_seg_apellido'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Pagos::t("Pagos", "Cell") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_cedula'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Academic Data") ?></span></h4> 
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_ninteres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Academic unit") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_ninteres", $arr_persona['uaca_id'], array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_ninteres", "disabled" => "true"]) ?>
                    </div>
                </div>  
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
                <div class="form-group">
                    <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Modality") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_modalidad", $arr_persona['mod_id'], array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
                    </div>
                </div>
            </div>                             
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="cmb_carrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Especies::t("Academico", "Career/Program") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <?= Html::dropDownList("cmb_carrera", $arr_persona['eaca_id'], $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera", "disabled" => "true"]) ?>
                    </div>
                </div>                                        
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-group">
            <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Payment Data") ?></span></h4> 
        </div>
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" for="txt_referencia" ><?= Pagos::t("Pagos", "Reference") ?></label>
                <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value="" id="txt_referencia" data-type="number" placeholder="<?= Pagos::t("Pagos", "Reference") ?>">
                </div>
            </div>  
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" for="txt_valor"> <?= Pagos::t("Pagos", "Value") ?><span class="text-danger"> * </span></label>
                <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_valor" data-type="dinero" placeholder="<?= Pagos::t("Pagos", "Value") ?>">
                </div>
            </div>                                        
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label keyupmce" for="txt_observa" ><?= Yii::t("formulario", "Observation") ?></label>
                <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <textarea  class="form-control keyupmce" id="txt_observa" rows="3"></textarea>                        
                </div>
            </div>   
        </div>    
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" for="cmb_formapago"><?= crm::t("crm", "Payment Method") ?><span class="text-danger"> * </span></label>
                <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_formapago", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_forma_pago, ["class" => "form-control PBvalidation", "id" => "cmb_formapago"]) ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="pago_documento" style="display:none;">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" for="txt_fechapago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Pagos::t("Pagos", "Payment Date") ?><span class="text-danger"> * </span></label>
                    <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <?=
                        DatePicker::widget([
                            'name' => 'txt_fechapago',
                            'value' => '',
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fechapago", "placeholder" => Pagos::t("Pagos", "Payment Date")],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => Yii::$app->params["dateByDatePicker"],
                            ]]
                        );
                        ?></div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label  keyupmce" for="txth_doc_pago" id="txth_doc_titulo" name="txth_doc_pago"><?= Yii::t("formulario", "Attach document") ?><span class="text-danger"> * </span></label>
                    <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>
                        <?= Html::hiddenInput('txth_doc_pago', '', ['id' => 'txth_doc_pago']); ?>
                        <?php
                            echo CFileInputAjax::widget([
                                'id' => 'txt_doc_pago',
                                'name' => 'txth_doc_pago',
                                'pluginLoading' => false,
                                'showMessage' => false,
                                //'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_doc_pago", "placeholder" => Pagos::t("Pagos", "Payment Date")],
                                'pluginOptions' => [
                                    'showPreview' => false,
                                    'showCaption' => true,
                                    'showRemove' => true,
                                    'showUpload' => false,
                                    'showCancel' => false,
                                    'browseClass' => 'btn btn-primary btn-block',
                                    'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                                    'browseLabel' => "Subir Archivo",
                                    'uploadUrl' => Url::to(['pagosfacturas/cargarpago']),
                                    'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                                    'uploadExtraData' => 'javascript:function (previewId,index) {
                                        var name_pago= $("#txth_doc_pago").val();
                                        return {"upload_file": true, "name_file": name_pago};
                                    }', 
                                ],
                                'pluginEvents' => [
                                    "filebatchselected" => "function (event) {                        
                                        function d2(n) {
                                            if(n<9) return '0'+n;
                                            return n;
                                        }
                                        today = new Date();
                                        var name_pago = 'pagoest_' + $('#txth_per').val() + '-' + today.getFullYear() + '-' + d2(parseInt(today.getMonth()+1)) + '-' + d2(today.getDate()) + ' ' + d2(today.getHours()) + ':' + d2(today.getMinutes()) + ':' + d2(today.getSeconds());
                                        $('#txth_doc_pago').val(name_pago);                        
                                        $('#txt_doc_pago').fileinput('upload');
                                        var fileSent = $('#txt_doc_pago').val();
                                        var ext = fileSent.split('.');
                                        $('#txth_doc_pago').val(name_pago + '.' + ext[ext.length - 1]);
                                    }",
                                    "fileuploaderror" => "function (event, data, msg) {
                                        $(this).parent().parent().children().first().addClass('hide');
                                        $('#txth_doc_pago').val('');
                                        //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
                                    }",
                                    "filebatchuploadcomplete" => "function (event, files, extra) { 
                                        $(this).parent().parent().children().first().addClass('hide');
                                    }",
                                    "filebatchuploadsuccess" => "function (event, data, previewId, index) {
                                        var form = data.form, files = data.files, extra = data.extra,
                                        response = data.response, reader = data.reader;
                                        $(this).parent().parent().children().first().addClass('hide');
                                        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
                                        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
                                    }",
                                    "fileuploaded" => "function (event, data, previewId, index) {
                                        $(this).parent().parent().children().first().addClass('hide');        
                                        var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
                                        //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
                                    }",
                                ],
                            ]);
                        ?>
                    </div>  
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                <div class="form-group">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="pago_stripe"  style="display:none; justify-content: center;background-color: antiquewhite;flex-direction:column;border-radius:6px;justify-content:space-between">
            <!------------------------------------------------------->
            <!----- INI PAGO STRIPE --------------------------------->                
            <!------------------------------------------------------->
            <style type="text/css">
                .checkout-button{height:36px;background:#556cd6;border-radius:0 0 4px 4px;color:white;border:0;font-weight:600;cursor:pointer;transition:all 0.2s ease;box-shadow:0px 4px 5.5px 0px rgba(0,0,0,0.07)}
                .checkout-button:hover{opacity:0.8}
            </style>

            <div id="paymentResponse"></div>
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style=" display: flex; justify-content: center;">
                <img src="https://www.mbtu.us/wp-content/uploads/2021/01/MBTU-Logo-Flat.png" alt="MBTU" style="border-radius:4px;margin:10px;height:56px">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                <!--div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 6px;" id="seccion_pago_online"></div--->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label">CARD NUMBER</label>
                        <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8 form-control" id="card_number" class="field"></div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-6 col-lg-6 control-label">EXPIRY DATE</label>
                        <div   class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-control" id="card_expiry" class="field"></div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-6 col-lg-6 control-label">CVC CODE</label>
                        <div   class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-control" id="card_cvc" class="field"></div>
                    </div>
                </div>
            </div>
            <!--div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="display: grid;">
                <button type="button" class="checkout-button" id="payBtn">Realizar Pago</button>
            </div-->

            <div id="form_temp" style="display:none"></div>
            <!------------------------------------------------------->
            <!----- FIN PAGO STRIPE --------------------------------->                
            <!------------------------------------------------------->      
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="div_detalle"></div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            &nbsp;&nbsp;
        </div>
    </div>  
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Pending Invoices Data") ?></span></h4> 
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
        <?=
        PbGridView::widget([
            'id' => 'TbgPagopendiente',
            'dataProvider' => $model,
            'columns' => [
                [
                    'attribute' => 'Factura',
                    'header' => Pagos::t("Pagos", "Bill"),
                    'value' => 'NUM_NOF',
                ],
                /*[
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t("formulario", "Subject"),
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (strlen($model['MOTIVO']) > 30) {
                                $texto = '...';
                            }
                            return Html::a('<span>' . substr($model['MOTIVO'], 0, 20) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['MOTIVO']]);
                        },
                    ],
                ],*/
                [
                    'attribute' => 'Fecha_factura',
                    'header' => Pagos::t("Pagos", "Date Bill"),
                    'value' => 'F_SUS_D',
                ],
                /*[
                    'attribute' => 'Saldo',
                    'header' => Pagos::t("Pagos", "Balance"),
                    'value' => 'SALDO',
                ],*/
                [
                    'attribute' => 'Cuota_pendiente',
                    'header' => Pagos::t("Pagos", "Pending Fee"),
                    'value' => 'cuota',
                ],
                [
                    'attribute' => 'valor_cuota',
                    'header' => Pagos::t("Pagos", "Quota value"),
                    'value' => 'ccar_valor_cuota',
                ],
                [
                    'attribute' => 'vencimiento',
                    'header' => Pagos::t("Pagos", "Expiration date"),
                    'value' => 'F_VEN_D',
                ],
                [
                    'attribute' => 'Abono',
                    'header' => Pagos::t("Pagos", "Abono"),
                    'value' => 'abono',
                ],
                [
                    'attribute' => 'Saldo',
                    'header' => Pagos::t("Pagos", "Saldo"),
                    'value' => 'saldo',
                ],
                /*
                [
                    'attribute' => 'cantidad',
                    'header' => Pagos::t("Pagos", "Amount Fees"),
                    'value' => 'cantidad',
                ],
                */
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Academico::t("matriculacion", "Select"),
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'headerOptions' => ['width' => '60'],
                    'template' => '{select}',
                    'buttons' => [
                        'select' => function ($url, $model) {
                            return Html::checkbox("", false, ["value" => $model['NUM_NOF'].';'.$model['NUM_DOC'].';'.$model['total_deuda']]);
                        },
                    ],
                ],
            ],
        ])
        ?>
    </div>   
    <!-- <div class="row"> 
        <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11"></div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">&nbsp;&nbsp;  
            <a id="btn_guardarpago" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?> </a>
        </div>
    </div>-->
</form>
