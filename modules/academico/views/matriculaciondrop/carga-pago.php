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

Academico::registerTranslations();

$tipodoc = "CED";
?>

<form class="form-horizontal">
    <!-- Personal data -->
    <div>
        <h3><?= Academico::t("matriculacion", "Upload payment") ?></h3>
        <br></br>
        <div class="row">
            <!-- Left column -->
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label for="txt_periodo_academico" class="col-sm-3 control-label"><?= Academico::t("matriculacion", "Period") ?></label>
                    <div class="col-sm-9 ">
                        <input type="text" class="form-control PBvalidation" id="txt_periodo_academico" value="<?= $data_planificacion_pago['pla_periodo_academico'] ?>" data-type="all" disabled placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="txt_modalidad" class="col-sm-3 control-label"><?= Academico::t("matriculacion", "Modality") ?></label>
                    <div class="col-sm-9 ">
                        <input type="text" class="form-control PBvalidation" id="txt_modalidad" value="<?= $data_planificacion_pago['mod_nombre'] ?>" data-type="all" disabled placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="txth_pago_documento" class="col-sm-3 control-label keyupmce"><?= Yii::t("formulario", "Payment file") ?></label>
                    <div class="col-sm-9">
                        <?= Html::hiddenInput('txth_pago_documento', '', ['id' => 'txth_pago_documento']); ?>
                        <?= Html::hiddenInput('txth_pago_documento2', '', ['id' => 'txth_pago_documento2']); ?>
                        <?php
                        echo CFileInputAjax::widget([
                            'id' => 'txt_pago_documento',
                            'name' => 'txt_pago_documento',
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
                                'browseLabel' => "Subir Archivo",
                                'uploadUrl' => Url::to(['matriculacion/registropago']),
                                // 'maxFileSize' => Yii::$app->params["MaxFileSize"],
                                'uploadExtraData' => 'javascript:function (previewId,index) {
                                return {"upload_file": true, "name_file": "pago-' . @Yii::$app->session->get("PB_perid") . '-' . time() . '"};
                            }',
                            ],
                            'pluginEvents' => [
                                "filebatchselected" => "function (event) {
                                $('#txth_pago_documento2').val('pago-" . @Yii::$app->session->get("PB_perid") . '-' . time() . "');
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
                            ],
                        ]); //style="display: none;"
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_pla_id" value="<?= $pla_id ?>">
<input type="hidden" id="frm_pes_id" value="<?= $data_planificacion_pago['pes_id'] ?>">
<br />
