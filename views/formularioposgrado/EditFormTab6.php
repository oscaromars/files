<?php

use yii\helpers\Html;
use app\components\CFileInputAjax;
use yii\helpers\Url;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;

academico::registerTranslations();
financiero::registerTranslations();
//print_r($arr_condcurriculum);
$per_id = $persona_model->per_id;
$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 540px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo, en formato pdf, excepto foto que es jpg.</div>
          </div>
          </div>
          </div>';
?>
<?= Html::hiddenInput('txth_igra_id', base64_encode($igra_id), ['id' => 'txth_igra_id']); ?>
<?= Html::hiddenInput('txth_per_id', base64_encode($per_id), ['id' => 'txth_per_id']); ?>
<?= Html::hiddenInput('txth_emp_id', base64_encode($emp_id), ['id' => 'txth_emp_id']); ?>
<?= Html::hiddenInput('txth_cemp_id', $personaData["cemp_id"], ['id' => 'txth_cemp_id']); ?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
<?php echo $leyenda; ?>
</div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_documento">
        <div class="form-group">
            <label for="txth_doc_documento" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "Documento") ?></label>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <?= Html::hiddenInput('txth_doc_documento', '', ['id' => 'txth_doc_documento']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_documento',
                    'name' => 'txt_doc_documento',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_documento' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_documento').val($('#txt_doc_documento').val());
        $('#txt_doc_documento').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_documento').val('');
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
            <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
                <div class="form-group">
                    <?php
                        if (!empty($arch16)) {
                            echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch16"]) . "' download='" . $arch16 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                        }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch16 . "' >Documento no Cargado</a>";
                }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 doc_foto">
        <div class="form-group">
            <label for="txth_doc_foto" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "Foto") ?></label>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <?= Html::hiddenInput('txth_doc_foto', '', ['id' => 'txth_doc_foto']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_foto',
                    'name' => 'txt_doc_foto',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_foto": true, "name_file": "doc_foto' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_foto').val($('#txt_doc_foto').val());
        $('#txt_doc_foto').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_foto').val('');
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
            <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
                <div class="form-group">
                    <?php
                        if (!empty($arch1)) {
                            echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch1"]) . "' download='" . $arch1 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                        }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch1 . "' >Documento no Cargado</a>";
                }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_dni">
        <div class="form-group">
            <label for="txth_doc_dni" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Cédula o Pasaporte") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_dni' . "_per_" . $per_id . '"};
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

        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch2)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch2"]) . "' download='" . $arch2 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch2 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>-->
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="txth_doc_certvota" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Voting Certificate") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_certvota' . "_per_" . $per_id . '"};
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch3)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch3"]) . "' download='" . $arch3 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch3 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_titulo">
        <div class="form-group">
            <label for="txth_doc_titulo" class="col-sm-3 col-lg-3 col-md-3 col-xs-3 control-label keyupmce"><?= Yii::t("formulario", "Título Tercer Nivel o Acta de Grado notarizada") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_titulo' . "_per_" . $per_id . '"};
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch4)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch4"]) . "' download='" . $arch4 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch4 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div> -->
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_comprobante">
        <div class="form-group">
            <label for="txth_doc_comprobante" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Comprobante de depósito o transferencia de pago de Matrícula") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_comprobante', '', ['id' => 'txth_doc_comprobante']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_comprobante',
                    'name' => 'txt_doc_comprobante',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_comprobante' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_comprobante').val($('#txt_doc_comprobante').val());
        $('#txt_doc_comprobante').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_comprobante').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch5)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch5"]) . "' download='" . $arch5 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch5 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_record1">
        <div class="form-group">
            <label for="txth_doc_record1" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Récord Académico Actualizado") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_record1', '', ['id' => 'txth_doc_record1']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_record1',
                    'name' => 'txt_doc_record1',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_record' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_record1').val($('#txt_doc_record1').val());
        $('#txt_doc_record1').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_record1').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch6)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch6"]) . "' download='" . $arch6 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch6 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_senescyt">
        <div class="form-group">
            <label for="txth_doc_senecyt" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Registro de Senescyt") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_senecyt', '', ['id' => 'txth_doc_senecyt']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_senecyt',
                    'name' => 'txt_doc_senecyt',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_senescyt' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_senecyt').val($('#txt_doc_senecyt').val());
        $('#txt_doc_senecyt').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_senecyt').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch7)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch7"]) . "' download='" . $arch7 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch7 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_hoja_vida">
        <div class="form-group">
            <label for="txth_doc_hojavida" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Hoja de Vida") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_hojavida', '', ['id' => 'txth_doc_hojavida']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_hoja_vida',
                    'name' => 'txt_doc_hoja_vida',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_hojavida' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_hojavida').val($('#txt_doc_hoja_vida').val());
        $('#txt_doc_hoja_vida').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_hojavida').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch8)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch8"]) . "' download='" . $arch8 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch8 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_cartarecomendacion cinteres">
        <div class="form-group">
            <label for="txth_doc_cartarecomendacion" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Carta de Recomendación") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_cartarecomendacion', '', ['id' => 'txth_doc_cartarecomendacion']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_cartarecomendacion',
                    'name' => 'txt_doc_cartarecomendacion',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_cartarecomendacion' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_cartarecomendacion').val($('#txt_doc_cartarecomendacion').val());
        $('#txt_doc_cartarecomendacion').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_cartarecomendacion').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch9)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch9"]) . "' download='" . $arch9 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch9 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_certificadolaboral">
        <div class="form-group">
            <label for="txth_doc_certificadolaboral" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Certificado Laboral") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_certificadolaboral', '', ['id' => 'txth_doc_certificadolaboral']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_certificadolaboral',
                    'name' => 'txt_doc_certificadolaboral',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_certlaboral' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_certificadolaboral').val($('#txt_doc_certificadolaboral').val());
        $('#txt_doc_certificadolaboral').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_certificadolaboral').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch10)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch10"]) . "' download='" . $arch10 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch10 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_certificadoingles cinteres">
        <div class="form-group">
            <label for="txth_doc_certificadoingles" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Certificado de Suficiencia en Inglés Nivel A2") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_certificadoingles', '', ['id' => 'txth_doc_certificadoingles']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_certificadoingles',
                    'name' => 'txt_doc_certificadoingles',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_certingles' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_certificadoingles').val($('#txt_doc_certificadoingles').val());
        $('#txt_doc_certificadoingles').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_certificadoingles').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch11)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch11"]) . "' download='" . $arch11 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch11 . "' >Documento no Cargado</a>";
                }
                ?>
            </div><br><br></br>
        </div>
    </div> -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Documentos adicionales por homologación") ?></span></h3><br><br></br>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txth_doc_recordacad" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Récord Académico") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_recordacad', '', ['id' => 'txth_doc_recordacad']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_recordacad',
                    'name' => 'txt_doc_recordacad',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_record' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_recordacad').val($('#txt_doc_recordacad').val());
        $('#txt_doc_recordacad').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_recordacad').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch12)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch12"]) . "' download='" . $arch12 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch12 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 doc_nosancion">
        <div class="form-group">
            <label for="txth_doc_nosancion" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Certificado de no haber sido sancionado (firma y sello original)") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_nosancion', '', ['id' => 'txth_doc_nosancion']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_nosancion',
                    'name' => 'txt_doc_nosancion',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_certificado' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_nosancion').val($('#txt_doc_nosancion').val());
        $('#txt_doc_nosancion').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_nosancion').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch13)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch13"]) . "' download='" . $arch13 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch13 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 doc_syllabus">
        <div class="form-group">
            <label for="txth_doc_syllabus" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Syllabus de materias aprobadas (Firma y sellos originales)") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_syllabus', '', ['id' => 'txth_doc_syllabus']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_syllabus',
                    'name' => 'txt_doc_syllabus',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_syllabus' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_syllabus').val($('#txt_doc_syllabus').val());
        $('#txt_doc_syllabus').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_syllabus').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch14)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch14"]) . "' download='" . $arch14 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch14 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 doc_especievalorada">
        <div class="form-group">
            <label for="txth_doc_especievalorada" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Especie valorada por homologación") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_especievalorada', '', ['id' => 'txth_doc_especievalorada']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_especievalorada',
                    'name' => 'txt_doc_especievalorada',
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
                        'uploadUrl' => Url::to(['/formularioposgrado/guardarinscripcionposgrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        return {"upload_file": true, "name_file": "doc_especievalorada' . "_per_" . $per_id . '"};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_especievalorada').val($('#txt_doc_especievalorada').val());
        $('#txt_doc_especievalorada').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_especievalorada').val('');
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
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <div class="form-group">
                <?php
                if (!empty($arch15)) {
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "$arch15"]) . "' download='" . $arch15 . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Pdf</a>";
                }else {
                      echo "<a style= 'color:#b08500;'  download='" . $arch15 . "' >Documento no Cargado</a>";
                }
                ?>
            </div>
        </div>
    </div>

</form>
