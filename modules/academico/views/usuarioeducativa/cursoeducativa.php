<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\modules\academico\Module as academico;

academico::registerTranslations();

$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
          <div class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 15 KB máximo y tipo xlsx o xls </div>
          </div>
          </div>
          </div>';
?>
<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Upload course") ?></span><br/>    
</div>
<div class="col-md-12">    
    <br/>    
</div>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div>    
    <div class='row'>
        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <div class="form-group">
        <label for="lbl_plantilla" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label"><?= academico::t("planificacion", "Template"); ?></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
            <?= Html::a(academico::t("matriculacion", "Download"), Url::to(['downloadplantillacurso', 'filename' => 'Plantilla_cargarcursoeducativa.xlsx']));   ?>
            </div>                       
        </div> 
        </div> 
            <div class="form-group">
                <label for="cmb_per_aca" class="col-sm-2 control-label"><?= academico::t("matriculacion", 'Academic Period') ?><span class="text-danger"> *</span></label>
                <div class="col-sm-5">
                    <?= Html::dropDownList("cmb_per_aca", 0, $arr_periodoAcademico, ["class" => "form-control", "id" => "cmb_per_aca"]) ?>
                </div>
            </div>
            
            <div class="form-group">
            <label for="txth_doc_adj_educativacu" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
                <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
                    <?= Html::hiddenInput('txth_doc_adj_educativacu', '', ['id' => 'txth_doc_adj_educativacu']); ?>
                    <?= Html::hiddenInput('txth_doc_adj_educativacu2', '', ['id' => 'txth_doc_adj_educativacu2']); ?>
                    <?php
                    echo CFileInputAjax::widget([
                        'id' => 'txt_doc_adj_educativacu',
                        'name' => 'txt_doc_adj_educativacu',
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
                            'uploadUrl' => Url::to(['usuarioeducativa/upload']),
                            'maxFileSize' => Yii::$app->params["MaxFileSize"],
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                return {"upload_file": true, "name_file": "op_educativacurso-' . @Yii::$app->session->get("PB_iduser") . '-' . time() . '"};
            }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
            $('#txth_doc_adj_educativacu2').val('op_educativacurso-" . @Yii::$app->session->get("PB_iduser") . '-' . time() . "');
            $('#txth_doc_adj_educativacu').val($('#txt_doc_adj_educativacu').val());
            $('#txt_doc_adj_educativacu').fileinput('upload');
        }",
                            "fileuploaderror" => "function (event, data, msg) {
            $(this).parent().parent().children().first().addClass('hide');
            $('#txth_doc_adj_educativacu').val('');        
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
                    ]);//style="display: none;"
                    ?>
                </div>     
            </div>  
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-2">
                    <a id="btn_guardarcurso" href="javascript:" class="btn btn-primary btn-block"><?= Yii::t("formulario", "Save") ?> </a>
                </div>                
            </div>  
        </div>    
    </div>               
</form>

