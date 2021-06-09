<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\CFileInputAjax;
use app\modules\academico\Module as academico;

academico::registerTranslations();

$style = "style='display: none;'";
if($value_credit == 3){
    $style = "";
}


?>

<form class="form-horizontal" enctype="multipart/form-data">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span><?= academico::t("registro", "Student Information") ?></span></h3>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="frm_name" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("matriculacion", "Student") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="<?= $estudiante ?>" id="frm_name" data-type="all" disabled='disabled' placeholder="<?= academico::t("matriculacion", "Student") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="frm_carrera" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("Academico", 'Career/Program') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="<?= $carrera ?>" id="frm_carrera" data-type="all" disabled='disabled' placeholder="<?= academico::t("Academico", "Career/Program") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="frm_periodo" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("Academico", 'Period') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control PBvalidation" value="<?= $periodo ?>" id="frm_periodo" data-type="all" disabled='disabled' placeholder="<?= academico::t("Academico", "Period") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span><?= academico::t("registro", "Payment Information") ?></span></h3>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_tpago" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("registro", 'Credit') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_tpago", $value_credit, $arr_credito, ["class" => "form-control", "id" => "cmb_tpago", "disabled" => "disabled"]) ?>  
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_fpago" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("registro", 'Payment Method') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_fpago", $value_payment, $arr_forma_pago, ["class" => "form-control", "id" => "cmb_fpago", "disabled" => "disabled"]) ?>  
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txth_pago_doc" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= Yii::t("registro", "Attach Voucher") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::hiddenInput('txth_pago_doc', '', ['id' => 'txth_pago_doc']); ?>
                    <?= Html::hiddenInput('txth_pago_doc2', '', ['id' => 'txth_pago_doc2']); ?>
                    <?php
                    echo CFileInputAjax::widget([
                        'id' => 'txt_pago_doc',
                        'name' => 'txt_pago_doc',
                        'pluginLoading' => false,
                        'showMessage' => false,
                        'pluginOptions' => [
                            'showPreview' => false,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showCancel' => false,
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                            'browseLabel' => "Upload File",
                            'allowedFileExtensions' => ['png','jpeg', 'jpg'],
                            'uploadUrl' => Url::to(["registro/update/$id"]),
                            // 'maxFileSize' => Yii::$app->params["MaxFileSize"],
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                                return {"upload_file": true, "name_file": "payment-' . @Yii::$app->session->get("PB_perid") . '-' .  "$id-" . date('YmdHis') . '"};
                            }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
                                $('#txth_pago_doc2').val('payment-" . @Yii::$app->session->get("PB_perid") . '-' .  "$id-" . date('YmdHis') . "');
                                $('#txth_pago_doc').val($('#txt_pago_doc').val());
                                $('#txt_pago_doc').fileinput('upload');
                            }",
                            "fileuploaderror" => "function (event, data, msg) {
                                $(this).parent().parent().children().first().addClass('hide');
                                $('#txth_pago_doc').val('');        
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
                        ],
                    ]);
                    ?>
                </div>   
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="frm_valor" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("registro", 'Total Payment') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control PBvalidation" value="<?= $value_total ?>" id="frm_valor" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "Total Payment") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row nocredit" <?= $style ?>>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="frm_int_ced" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("registro", 'Interest on Direct Credit') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control PBvalidation" value="<?= $value_interes ?>" id="frm_int_ced" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "Interest on Direct Credit") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="frm_finan" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("registro", 'Financing Cost') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">   
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control PBvalidation" value="<?= $value_financiamiento ?>" id="frm_finan" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "Financing Cost") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row nocredit" <?= $style ?>>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_cuota" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("registro", 'Payment Installments') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_cuota", $value_cuotas, $cuotas, ["class" => "form-control", "id" => "cmb_cuota", "disabled" => "disabled",]) ?>  
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="frm_cuota" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("registro", 'Initial Payment Installment') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control PBvalidation" value="<?= $value_pago_inicial ?>" id="frm_cuota" disabled='disabled' data-type="all" placeholder="<?= academico::t("registro", "Total Payment") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nocredit" <?= $style ?>>
        <h3><span><?= academico::t("registro", "Direct Credit") ?></span></h3>
    </div>
    <div class="row nocredit" <?= $style ?>>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $this->render('new-grid', ['dataGrid' => $dataGrid]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_req" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("registro", 'Terms and Conditions') ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <div class="checkbox">
                        <label><input type="checkbox" id='cmb_req' > <?= academico::t('registro', 'I accept the terms and conditions of the university regarding payments for subject registration.')?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_id" value="<?= $model->ron_id ?>" />
<input type="hidden" id="frm_rama_id" value="<?= $rama_id ?>" />
<input type="hidden" id="frm_rpmid" value="<?= $rpm_id ?>" />
<input type="hidden" id="frm_costo_item" value="<?= $costoItem ?>" />
<input type="hidden" id="frm_credito_item" value="<?= $creditoItem ?>" />
<input type="hidden" id="frm_costo_cred" value="<?= $costoCredito ?>" />
<input type="hidden" id="frm_creditos_carr" value="<?= $creditosCarrera ?>" />
<input type="hidden" id="frm_costo_carr" value="<?= $costCarrera ?>" />
<input type="hidden" id="frm_ini_cuota" value="<?= $costoItem ?>" />
<input type="hidden" id="frm_num_cuota" value="1" />
<input type="hidden" id="lbl_payment" value="Payment #" />
