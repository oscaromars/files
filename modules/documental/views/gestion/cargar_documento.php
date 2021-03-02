<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\modules\documental\Module as documental;

documental::registerTranslations();

$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12">
          <div style = "width: 500px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe set tipo xlsx o xls.</div>
          </div>
          </div>
          </div>';
?>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h3><span id="lbl_titulo"><?= documental::t("documento", "Load document") ?></span></h3>
    </div>    
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="form-group">
            <label for="txth_doc_adj_documento" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
                <?= Html::hiddenInput('txth_doc_adj_documento', '', ['id' => 'txth_doc_adj_documento']); ?>
                <?= Html::hiddenInput('txth_doc_adj_documento2', '', ['id' => 'txth_doc_adj_documento2']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_adj_documento',
                    'name' => 'txt_doc_adj_documento',
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
                        'uploadUrl' => Url::to(['gestion/upload']),
                        // 'maxFileSize' => Yii::$app->params["MaxFileSize"],
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "op_documento-' . @Yii::$app->session->get("PB_iduser") . '-' . time() . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_adj_documento2').val('op_documento-" . @Yii::$app->session->get("PB_iduser") . '-' . time() . "');
        $('#txth_doc_adj_documento').val($('#txt_doc_adj_documento').val());
        $('#txt_doc_adj_documento').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_adj_documento').val('');        
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
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
            <div class="col-sm-10 col-md-10 col-xs-8 col-lg-10"></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_cargarDocumento" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Send") ?></a>
            </div>        
        </div>
    </div>
</form>

