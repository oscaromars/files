<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\CFileInputAjax;
use yii\helpers\Url;
$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 450px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
          </div>
          </div>
          </div>';
?>
<form class="form-horizontal">  
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h4><span id="lbl_Personeria"><?= Yii::t("formulario", "Attach document") ?></span></h4>    
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="convenio" style="display: none">
        <div class="form-group">
            <label for="cmb_convenio_empresa" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label keyupmce"><?= Yii::t("formulario", "Empresa Convenio") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <?= Html::dropDownList("cmb_convenio_empresa", 0, $arr_convenio_empresa, ["class" => "form-control", "id" => "cmb_convenio_empresa"]) ?>
            </div>
        </div>
    </div>     
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_dni cinteres">
        <div class="form-group">
            <label for="txth_doc_dni" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Identification document") ?></label>
            <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                <?= Html::hiddenInput('txth_doc_dni', '', ['id' => 'txth_doc_dni']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_dni',
                    'name' => 'txt_doc_dni',
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
                        'uploadUrl' => Url::to(['/inscripcioneducacioncontinua/saveinscripciontemp']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_dni", "inscripcion_id": $("#txth_twin_id").val()};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_dni').val($('#txt_doc_dni').val());
        $('#txt_doc_dni').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_dni').val('');
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

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divFormulario" style="display: none">
        <div class="form-group">
            <label for="txth_doc_formulario" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Formulario Inscripción") ?></label>
            <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                <?= Html::hiddenInput('txth_doc_formulario', '', ['id' => 'txth_doc_formulario']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_formulario',
                    'name' => 'txt_doc_formulario',
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
                        'uploadUrl' => Url::to(['/inscripcioneducacioncontinua/saveinscripciontemp']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_formulario", "inscripcion_id": $("#txth_twin_id").val()};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_formulario').val($('#txt_doc_formulario').val());
        $('#txt_doc_formulario').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_formulario').val('');
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">                        
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align: right;">
                <input type="checkbox" id="chk_mensaje1" data-type="alfa" data-keydown="true" placeholder="" >                   
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">                     
                <label for="chk_mensaje1" class="col-lg-9 col-md-9 col-sm-9 col-xs-9"><?= Yii::t("formulario", "Expreso que la información declarada y documentos cargados son válidos y legales.") ?> </label>
            </div>

        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">                        
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align: right;">
                <input type="checkbox" id="chk_mensaje2" data-type="alfa" data-keydown="true" placeholder="" >   
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">                      
                <label for="chk_mensaje2" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?= Yii::t("formulario", "Acepto y me comprometo a respetar y cumplir lo estipulado en los reglamentos internos de la universidad con respecto a la admisión y procesos estudiantiles.") ?> </label>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">         
        </br>
        </br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="col-md-2">
            <a id="paso2back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
        <div class="col-md-2">
            <a id="sendInformacionAspirante2" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
        </div>
    </div>        
</form>