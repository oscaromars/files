<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\components\CFileInputAjax;
use app\modules\academico\Module as academico;
academico::registerTranslations();
use app\modules\admision\Module as admision;

admision::registerTranslations();

$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 500px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg </div>
          </div>
          </div>
          </div>';
?>
<!-- <div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Schedule 1") ?></span><br/>    
</div>-->
<div class="col-md-12">    
    <br/>    
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
            <label for="txt_unidad" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= academico::t("Academico", "Academic unit") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
                <?= Html::dropDownList("cmb_unidad", 1, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad", "disabled" => "true"]) ?>
            </div>
        </div>  
        <div class="form-group">
            <label for="txt_periodo" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= academico::t("Academico", "Lecturing Period") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
                <?= Html::dropDownList("cmb_periodo", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
            </div>
        </div>
        <div class="form-group">
        <label for="txt_descripcion" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Description") ?></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">                
                <textarea  class="form-control keyupmce" id="txt_descripcion" rows="3"></textarea>                  
            </div>
        </div>
        <div class="form-group">
            <label for="txth_doc_adj_cronograma" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-5 col-md-5 col-xs-5 col-lg-5">
                <?= Html::hiddenInput('txt_doc_cronograma', '', ['id' => 'txth_doc_cronograma']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_cronograma',
                    'name' => 'txt_doc_cronograma',
                    'disabled' => $habilita,
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
                        'uploadUrl' => Url::to(['cronograma/cargarcronograma']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        var name_cronograma= $("#txth_doc_cronograma").val();
            return {"upload_file": true, "name_file": name_cronograma};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {                        
                        function d2(n) {
                        if(n<9) return '0'+n;
                        return n;
                        }
                        today = new Date();
                        var name_cronograma = 'cronograma_' + $('#cmb_unidad').val() + '-' + $('#cmb_periodo').val();
                        $('#txth_doc_cronograma').val(name_cronograma);    
        
        $('#txt_doc_cronograma').fileinput('upload');
        var fileSent = $('#txt_doc_cronograma').val();
        var ext = fileSent.split('.');
        $('#txth_doc_cronograma').val(name_cronograma + '.' + ext[ext.length - 1]);
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_cronograma').val('');
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
        <!--<div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8"></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_cargarCronograma" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Send") ?></a>
            </div>        
        </div>-->
    </div>
</form>

