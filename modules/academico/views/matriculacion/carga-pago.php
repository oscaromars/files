<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\components\CFileInputAjax;
use app\modules\Academico\Module as Academico;
use app\modules\financiero\Module as Pagos;
use app\modules\admision\Module as crm;
use app\assets\StripeAsset;
StripeAsset::register($this);

Academico::registerTranslations();

?>
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
<form class="form-horizontal">
    <h3><?= Academico::t("matriculacion", "Upload payment") ?></h3><br>
    <!-- Left column -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            <label for="txt_periodo_academico" class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label"><?= Academico::t("matriculacion", "Period") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" id="txt_periodo_academico" value="<?= $data_planificacion_pago['pla_periodo_academico'] ?>" data-type="all" disabled placeholder="">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_modalidad" class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label"><?= Academico::t("matriculacion", "Modality") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" id="txt_modalidad" value="<?= $data_planificacion_pago['mod_nombre'] ?>" data-type="all" disabled placeholder="">
            </div>
        </div>
        <div class="form-group">
            <label for="txt_valor" class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label"><?= Academico::t("matriculacion", "Valor") ?></label>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <input type="text" class="form-control PBvalidation" id="txt_valor" value="<?= $data_planificacion_pago['valor'] ?>" data-type="all" disabled placeholder="">
            </div>
        </div>
        <div class="form-group pago_documento">
            <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label" for="txt_referencia" ><?= Pagos::t("Pagos", "Reference") ?><span class="text-danger"> * </span></label>
            <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <input type="text" class="form-control keyupmce" value="" id="txt_referencia" data-type="number" placeholder="<?= Pagos::t("Pagos", "Reference") ?>">
            </div>
        </div>
        <div class="form-group pago_documento">
            <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label" for="cmb_banco"><?= crm::t("crm", "Institución Bancaria") ?><span class="text-danger"> * </span></label>
            <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_banco", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_bancos, ["class" => "form-control PBvalidation", "id" => "cmb_banco"]) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label keyupmce" for="txt_observa" ><?= Yii::t("formulario", "Observation") ?></label>
            <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <textarea  class="form-control keyupmce" id="txt_observa" rows="3"></textarea>                        
            </div>
        </div>   
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label" for="cmb_formapago"><?= crm::t("crm", "Forma de Pago") ?><span class="text-danger"> * </span></label>
            <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <?= Html::dropDownList("cmb_formapago", 0, ['0' => Yii::t('formulario', 'Select')] + $arr_forma_pago, ["class" => "form-control PBvalidation", "id" => "cmb_formapago"]) ?>
            </div>
        </div>
        <div class="form-group pago_documento" style="display:none;">
            <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label" for="txt_fechapago" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Pagos::t("Pagos", "Fecha Pago") ?><span class="text-danger"> * </span></label>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" data-tip="Fecha en la que se realizó la transacción">
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
                ?>
            </div>
        </div>
        <div class="form-group pago_documento">
            <label class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label  keyupmce" for="txt_pago_documento" id="txth_doc_titulo" name="txth_doc_pago"><?= Yii::t("formulario", "Attach document") ?><span class="text-danger"> * </span></label>
            <div   class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <?= Html::hiddenInput('txth_per', @Yii::$app->session->get("PB_perid"), ['id' => 'txth_per']); ?>
                <?= Html::hiddenInput('txth_pago_documento', '', ['id' => 'txth_pago_documento']); ?>
                <?= Html::hiddenInput('txth_pago_documento2', '', ['id' => 'txth_pago_documento2']); ?>
                <?php
                    echo CFileInputAjax::widget([
                        'id'            => 'txt_pago_documento',
                        'name'          => 'txt_pago_documento',
                        'pluginLoading' => false,
                        'showMessage'   => false,
                        'pluginOptions' => [
                            'showPreview' => false,
                            'showCaption' => true,
                            'showRemove'  => true,
                            'showUpload'  => false,
                            'showCancel'  => false,
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon'  => '<i class="fa fa-folder-open"></i> ',
                            'browseLabel' => "Subir Archivo",
                            'uploadUrl'  => Url::to(['matriculacion/registropago']),
                            //'maxFileSize' => Yii::$app->params["MaxFileSize"],
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                                return {"upload_file": true, "name_file": "pagoMatricula-' . $per_id . '-' . time() . '"};
                            }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
                                $('#txth_pago_documento2').val('pagoMatricula-" . $per_id . '-' . time() . "');
                                $('#txth_pago_documento').val($('#txt_pago_documento').val());
                                $('#txt_pago_documento').fileinput('upload');
                            }",
                            "fileuploaderror" => "function (event, data, msg) {
                                $(this).parent().parent().children().first().addClass('hide');
                                $('#txth_pago_documento').val('');        
                            }",
                            "filebatchuploadcomplete" => "function (event, files, extra) { 
                                $(this).parent().parent().children().first().addClass('hide');
                            }",
                            "filebatchuploadsuccess" => "function (event, data, previewId, index) {
                                var form = data.form, files = data.files, extra = data.extra,
                                response = data.response, reader = data.reader;
                                $(this).parent().parent().children().first().addClass('hide');
                                var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];       
                            }",
                            "fileuploaded" => "function (event, data, previewId, index) {
                                $(this).parent().parent().children().first().addClass('hide');
                                var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];                           
                            }",
                        ],//'pluginEvents'
                    ]); 
                ?>
            </div>  
        </div>
        <div class="form-group pago_documento">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
            </div>
        </div>
        <div class="form-group pago_documento">
            <div class="form-check">
                <label class = "col-xs-10 col-sm-10 col-md-10 col-lg-10 control-label" for="txt_nombres_fac" id="lbl_nombre1" style="text-align: left"><?= Yii::t("formulario", "Acepta Condiciones Y Términos. <br> Expreso que la información declarada y el documento de pago es válido y legal") ?><span class="text-danger">*</span></label>  
                <input class = "col-xs-2 col-sm-2 col-md-2 col-lg-2 form-check-input checkAcepta" type="checkbox" value="1" id="checkAcepta">
            </div>
        </div> 
        <div class="form-group" id="pago_stripe">
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
</form>
<input type="hidden" id="frm_pla_id" value="<?= $pla_id ?>">
<input type="hidden" id="frm_per_id" value="<?= $per_id ?>">
<input type="hidden" id="frm_pes_id" value="<?= $data_planificacion_pago['pes_id'] ?>">
<br />