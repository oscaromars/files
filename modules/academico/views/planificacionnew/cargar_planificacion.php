<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
/* use kartik\datetime\DatePicker; */
use app\modules\academico\Module as academico;

academico::registerTranslations();

$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 520px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Si solo necesita crear el período de planificación no cargue ningún archivo.</div>
          </div>
          </div>
          </div>';
?>
<form class="form-horizontal" enctype="multipart/form-data" >
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Planning Load") ?></span></h3>
    <br></br>    
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
     <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div> 
    <div class='row'>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
        <label for="lbl_plantilla" class="col-sm-3 col-lg-3 col-md-3 col-xs-3 control-label"><?= academico::t("planificacion", "Template"); ?></label>
            <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
            <?= Html::a(academico::t("matriculacion", "Download"), Url::to(['planificacion/downloadplantilla', 'filename' => 'plantilla_carga_planificacionestudiante.xlsx']));   ?>
            </div>                       
        </div> 
            <div class="form-group">
                <label for="frm_per_aca" class="col-sm-3 control-label"><?= academico::t("Academico", "Lecturing Period") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control PBvalidation" id="frm_per_aca" value="" data-type="all" placeholder="<?= academico::t("planificacion", "Periodo Academico") ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="dtp_pla_fecha_ini" class="col-sm-3 control-label"><?= yii::t("formulario", "Start") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-9">
                    <?=
                    DatePicker::widget([
                        'id' => 'dtp_pla_fecha_ini',
                        'name' => 'dtp_pla_fecha_ini',
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => '',
                        'options' => ["class" => "form-control PBvalidation", "data-type" => "fecha", "placeholder" => yii::t("formulario", "Start"),],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="dtp_pla_fecha_fin" class="col-sm-3 control-label"><?= yii::t("formulario", "End") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-9">
                    <?=
                    DatePicker::widget([
                        'id' => 'dtp_pla_fecha_fin',
                        'name' => 'dtp_pla_fecha_fin',
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => '',
                        'options' => ["class" => "form-control PBvalidation", "data-type" => "fecha", "placeholder" => yii::t("formulario", "End"),],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="cmb_moda" class="col-sm-3 control-label"><?= yii::t("formulario", "Mode") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-9">
                    <?= Html::dropDownList("cmb_moda", "", $arr_modalidad, ["class" => "form-control", "id" => "cmb_moda"]) ?>
                </div>
            </div>    
            <div class="form-group">
                <label for="txth_pla_adj_documento" class="col-sm-3 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
                <div class="col-sm-9">
                    <?= Html::hiddenInput('txth_pla_adj_documento', '', ['id' => 'txth_pla_adj_documento']); ?>
                    <?= Html::hiddenInput('txth_pla_adj_documento2', '', ['id' => 'txth_pla_adj_documento2']); ?>
                    <?php
                    echo CFileInputAjax::widget([
                        'id' => 'txt_pla_adj_documento',
                        'name' => 'txt_pla_adj_documento',
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
                            'uploadUrl' => Url::to(['planificacion/upload']),
                            // 'maxFileSize' => Yii::$app->params["MaxFileSize"],
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                                return {"upload_file": true, "name_file": "planificacion--' . @Yii::$app->session->get("PB_username") . '-' . time() . '", "mod_id": $("#cmb_moda").val()};
                            }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
                                $('#txth_pla_adj_documento2').val('planificacion--" . @Yii::$app->session->get("PB_username") . '-' . time() . "');
                                $('#txth_pla_adj_documento').val($('#txt_pla_adj_documento').val());
                                $('#txt_pla_adj_documento').fileinput('upload');
                            }",
                            "fileuploaderror" => "function (event, data, msg) {
                                $(this).parent().parent().children().first().addClass('hide');
                                $('#txth_pla_adj_documento').val('');        
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
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
        <div class="col-sm-10 col-md-10 col-xs-8 col-lg-10"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_cargarDocumento" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Send") ?></a>
        </div>        
    </div>      
</form>

