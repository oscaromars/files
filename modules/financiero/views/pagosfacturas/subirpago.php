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

use app\assets\DatatableAsset;
DatatableAsset::register($this);

use app\assets\StripeAsset;
StripeAsset::register($this);
//print_r($model);
Especies::registerTranslations();
Pagos::registerTranslations();
crm::registerTranslations();
academico::registerTranslations();


?>

<?= Html::hiddenInput('txth_idest', $arr_persona['est_id'], ['id' => 'txth_idest']); ?>
<?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>
<?= Html::hiddenInput('txth_per_ids', $_GET["per_ids"], ['id' => 'txth_per_ids']); ?>
<style type="text/css">
    [data-tip] {
        position:relative;
    }
    [data-tip]:before {
        content:'';
        /* hides the tooltip when not hovered */
        display:none;
        content:'';
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #1a1a1a;
        position:absolute;
        top:-3px;
        left:35px;
        z-index:8;
        font-size:0;
        line-height:0;
        width:0;
        height:0;
        -ms-transform: rotate(180deg); /* IE 9 */
        transform: rotate(180deg);
    }
    [data-tip]:after {
        display:none;
        content:attr(data-tip);
        position:absolute;
        top:-21px;
        left:20px;
        padding:5px 8px;
        background:#1a1a1a;
        color:#fff;
        z-index:9;
        font-size: 1em;
        height:18px;
        line-height:8px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        white-space:nowrap;
        word-wrap:normal;
    }
    [data-tip]:hover:before,
    [data-tip]:hover:after {
        display:block;
    }
</style>
<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-row">
            <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Student Data") ?></span></h4>
        </div>
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="txt_nombres" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_nombre'] . " " . $arr_persona['per_seg_nombre'] . " " . $arr_persona['per_pri_apellido'] . " " . $arr_persona['per_seg_apellido'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
            </div>
        </div>
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="txt_cedula" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" id="lbl_nombre1"><?= Pagos::t("Pagos", "Cell") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_cedula'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-row">
            <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Academic Data") ?></span></h4>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            <label for="cmb_ninteres" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Especies::t("Academico", "Academic unit") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_ninteres", $arr_persona['uaca_id'], array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_ninteres", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="divModalidad">
        <div class="form-group">
            <label for="cmb_modalidad" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Especies::t("Academico", "Modality") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_modalidad", $arr_persona['mod_id'], array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="cmb_carrera" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Especies::t("Academico", "Career/Program") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_carrera", $arr_persona['eaca_id'], $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-row">
            <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Payment Data") ?></span></h4>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" for="txt_valor"> <?= Pagos::t("Pagos", "Value") ?><span class="text-danger"> * </span></label>
                <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_valor" data-type="dinero" placeholder="<?= Pagos::t("Pagos", "Value") ?>">
                    <input type="hidden" value="0" id="txt_valor_respaldo"/>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pago_documento">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" for="txt_referencia" ><?= Pagos::t("Pagos", "Reference") ?><span class="text-danger"> * </span></label>
                <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="number" class="form-control keyupmce" value="" id="txt_referencia" data-type="number" placeholder="<?= Pagos::t("Pagos", "Reference") ?>">
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pago_documento">
            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" for="cmb_banco"><?= crm::t("crm", "Institucion Bancaria") ?><span class="text-danger"> * </span></label>
                <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?= Html::dropDownList("cmb_banco", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_bancos, ["class" => "form-control PBvalidation", "id" => "cmb_banco"]) ?>
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

            <div class="form-group">
                <label class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label" for="txt_fechapago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Pagos::t("Pagos", "Payment Date") ?><span class="text-danger"> * </span></label>
                <div   class="col-xs-12 col-sm-12 col-md-7 col-lg-7" data-tip="Fecha en la que se realizó la transacción">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fechapago',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fechapago", "placeholder" => Pagos::t("Pagos", "Fecha en la que se realizó la transacción")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?></div>
            </div>
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
            <div class="form-row">
                    <div class="alert alert-info" style="margin-bottom: 1em;"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
                </div>
            <div class="form-row">
                <label class = "col-xs-10 col-sm-10 col-md-10 col-lg-10 control-label " for="txt_nombres_fac" id="lbl_nombre1" style="text-align: left"><?= Yii::t("formulario", "Acepta Condiciones Y Terminos. <br> Acepto que los documentos no han sido alterados o manipulados") ?><span class="text-danger">*</span></label>
                <input class = "col-xs-2 col-sm-2 col-md-2 col-lg-2 form-check-input checkAcepta" type="checkbox" value="1" id="checkAcepta">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " id="pago_stripe">
            <!------------------------------------------------------->
            <!----- INI PAGO STRIPE --------------------------------->
            <!------------------------------------------------------->
            <style type="text/css">
                #pago_stripe{
                    display:none;
                    background-color: lightblue;
                    border-radius:6px;
                    border: 1px gray solid;
                    padding: 0 0 0 10px;
                }

                #card-element {
                  border-radius: 4px 4px 0 0 ;
                  padding: 12px;
                  border: 1px solid rgba(50, 50, 93, 0.1);
                  background: ghostwhite;
                  margin-top: 10px;
                  margin-bottom: 10px;
                }

                #card-element  input {
                  border-radius: 6px;
                  margin-bottom: 6px;
                  padding: 8px;
                  border: 1px solid rgba(50, 50, 93, 0.1);
                  height: 44px;
                  font-size: 16px;
                  width: 100%;
                  background: white;
                  font-family: -apple-system, BlinkMacSystemFont, sans-serif;
                  font-size: 16px;
                }
            </style>


            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style=" display: flex; justify-content: center;">
                <img src="https://www.uteg.edu.ec/wp-content/themes/UTEG4/images/055693c79f5990e523846b9f43c6779d_logouteg.png" alt="MBTU" style="border-radius:4px;height:40px;margin-top: 10px;margin-bottom: 10px;">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                <div id="card-element"><!--Stripe.js injects the Card Element--></div>
            </div>
            <!------------------------------------------------------->
            <!----- FIN PAGO STRIPE --------------------------------->
            <!------------------------------------------------------->
        </div>
    </div>
    <!--div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="div_detalle"></div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            &nbsp;&nbsp;
        </div>
    </div-->

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-row">
            <h4><span id="lbl_general"><?= Pagos::t("Pagos", "Pending Invoices Data") ?></span></h4>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <p class="text-danger"> <?= Pagos::t("Pagos", "Select the amounts to pay") ?> </p>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <?=
        PbGridView::widget([
            'id' => 'TbgPagopendiente',
            'dataProvider' => $model,
            'columns' => [
                [
                    'attribute' => 'Cuota_pendiente',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Pending Fee"),
                    'value' => 'cuota',
                ],
                [
                    'attribute' => 'Factura',
                    'contentOptions' => ['style' => 'text-align: center;'],
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
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Date Bill"),
                    'value' => 'F_SUS_D',
                ],
                /*[
                    'attribute' => 'Saldo',
                    'header' => Pagos::t("Pagos", "Balance"),
                    'value' => 'SALDO',
                ],*/

                [
                    'attribute' => 'valor_cuota',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Quota value"),
                    'value' => 'ccar_valor_cuota',
                ],
                [
                    'attribute' => 'vencimiento',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Expiration date"),
                    'value' => 'F_VEN_D',
                ],
                [
                    'attribute' => 'Abono',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'header' => Pagos::t("Pagos", "Abono"),
                    'value' => 'abono',
                ],
                [
                    'attribute' => 'Saldo',
                    'contentOptions' => ['style' => 'text-align: center;'],
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
                    'header' => '<i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>',//Academico::t("matriculacion", "Select"),
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'headerOptions' => ['width' => '60'],
                    'template' => '{select}',
                    'buttons' => [
                        'select' => function ($url, $model) {
                            if($model['saldo'] > 0){
                                return Html::checkbox("", false, ["value" => $model['NUM_NOF'].';'.$model['NUM_DOC'].';'.$model['total_deuda']]);
                             }else return " ";
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

<script type="text/javascript">
    $(document).ready(function() {

        $('#TbgPagopendiente > table').DataTable({
            "dom": 't',
            responsive: true,
            columnDefs: [
                { targets: 0, responsivePriority: 1},
                { targets: 3, responsivePriority: 2},
                { targets: 7, responsivePriority: 3},
            ],
        });
    });
</script>

<style type="text/css">
    .barexportp{
        display: none;
    }

    #TbgPagopendiente > .summary{
        display: none;
    }

    .sorting{
        text-align: center;
    }
</style>
