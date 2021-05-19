<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\CFileInputAjax;
use app\modules\admision\Module as admision;

$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 450px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
          </div>
          </div>
          </div>';

session_start();
$_SESSION['persona_solicita'] = base64_encode($per_id);

if (base64_decode($_GET['uaca']) == 2) {
    $docpos = 'block';
} else {
    $docpos = 'none';
}
?>
<?= Html::hiddenInput('txth_idp', base64_encode($datos["per_id"]), ['id' => 'txth_idp']); ?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h3><span id="lbl_solicitud"><?= Yii::t("formulario", "Upload documents") ?></span></h3>
    </div>

    <!--<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="txt_solicitud" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label"><?= admision::t("Solicitudes", "Request #") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control" value="<?= $datos["num_solicitud"] ?>" id="txt_solicitud" disabled="true">
            </div>
        </div>
    </div>-->

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="txt_nombres_completos" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label"><?= Yii::t("formulario", "Complete Names") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control" value="<?= $datos["per_pri_apellido"] ." " . $datos["per_pri_nombre"] ?>" id="txt_nombres_completos" disabled="true">
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h4><span id="lbl_Personeria"><?= Yii::t("formulario", "Attach document") ?></span></h4>    
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_titulo cinteres">
        <div class="form-group">
            <label for="txth_doc_certune" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= admision::t("Solicitudes", "UNE Letter") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::hiddenInput('txth_doc_certune', '', ['id' => 'txth_doc_certune']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_certune',
                    'name' => 'txt_doc_certune',
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
                        'uploadUrl' => Url::to(['/academico/admitidos/saveotrosdocumentos']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_certune"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_certune').val($('#txt_doc_certune').val());
        $('#txt_doc_certune').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_certune').val('');
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
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">       
        <div class="form-group">
            <label for="txt_observa" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Observation") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                
                <textarea  class="form-control keyupmce" id="txt_observa" rows="3"></textarea>                  
            </div>
        </div>      
    </div>    
</form>
