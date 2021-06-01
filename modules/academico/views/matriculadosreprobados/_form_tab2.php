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
<?= Html::hiddenInput('txth_twer_id', 0, ['id' => 'txth_twer_id']); ?>
<form class="form-horizontal">  
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h4><span id="lbl_Personeria"><?= Yii::t("formulario", "Attach document") ?></span></h4>    
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_titulo cinteres">
        <div class="form-group">
            <label for="txth_doc_titulo" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Title") ?></label>
            <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                <?= Html::hiddenInput('txth_doc_titulo', '', ['id' => 'txth_doc_titulo']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_titulo',
                    'name' => 'txt_doc_titulo',
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
                        'uploadUrl' => Url::to(['/academico/matriculadosreprobados/savereprobadostemp']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                            return {"upload_file": true, "name_file": "doc_titulo", "matr_repro_id": $("#txth_twer_id").val()};
                        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
                            $('#txth_doc_titulo').val($('#txt_doc_titulo').val());
                            $('#txt_doc_titulo').fileinput('upload');
                    }",
                        "fileuploaderror" => "function (event, data, msg) {
                            $(this).parent().parent().children().first().addClass('hide');
                            $('#txth_doc_titulo').val('');
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
                        'uploadUrl' => Url::to(['/academico/matriculadosreprobados/savereprobadostemp']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                            return {"upload_file": true, "name_file": "doc_dni", "matr_repro_id": $("#txth_twer_id").val()};
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

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divCertvota" style="display: block">
        <div class="form-group">
            <label for="txth_doc_certvota" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Voting Certificate") ?></label>
            <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                <?= Html::hiddenInput('txth_doc_certvota', '', ['id' => 'txth_doc_certvota']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_certvota',
                    'name' => 'txt_doc_certvota',
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
                        'uploadUrl' => Url::to(['/academico/matriculadosreprobados/savereprobadostemp']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                    return {"upload_file": true, "name_file": "doc_certvota", "matr_repro_id": $("#txth_twer_id").val()};                            
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_certvota').val($('#txt_doc_certvota').val());
        $('#txt_doc_certvota').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_certvota').val('');
        
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
    <div  id="divCertificado" style="display: none">   
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_certificado cinteres">
            <div class="form-group">
                <label for="txth_doc_certificado" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Materials Certificate") ?></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                    <?= Html::hiddenInput('txth_doc_certificado', '', ['id' => 'txth_doc_certificado']); ?>
                    <?php
                    echo CFileInputAjax::widget([
                        'id' => 'txt_doc_certificado',
                        'name' => 'txt_doc_certificado',
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
                            'uploadUrl' => Url::to(['/academico/matriculadosreprobados/savereprobadostemp']),
                            'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_certificado", "matr_repro_id": $("#txth_twer_id").val()};                            
            }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
            $('#txth_doc_certificado').val($('#txt_doc_certificado').val());
            $('#txt_doc_certificado').fileinput('upload');
        }",
                            "fileuploaderror" => "function (event, data, msg) {
            $(this).parent().parent().children().first().addClass('hide');
            $('#txth_doc_certificado').val('');
            
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
        <?php //Fin de la hoja de vida  ?>
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
            <a id="sendInformacionAdmitidoPendDos" href="javascript:" class="btn btn-primary btn-block"> <?php echo "Siguiente"; ?> <span class="glyphicon glyphicon-menu-right"></span></a>
        </div>
    </div>        
</form>