<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\components\CFileInputAjax;
$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 450px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg, png o pdf.</div>
          </div>
          </div>
          </div>';
?>
<!-- <p>Cédula Obtenida: <input type="text" name="cedula" id="txt_cedula2" disabled></p>-->
<?= Html::hiddenInput('txth_personaid', '', ['id' => 'txth_personaid']); ?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_documentos"><?= Yii::t("formulario", "Documentación") ?></span></h3>
    </div><br><br></br>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <?php echo $leyenda; ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="convenio" style="display: none">
        <div class="form-group">
            <label for="cmb_convenio_empresa" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Empresa Convenio") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <?= Html::dropDownList("cmb_convenio_empresa", 0, $arr_convenio_empresa, ["class" => "form-control", "id" => "cmb_convenio_empresa"]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_titulo cinteres">
        <div class="form-group">
            <label for="txth_doc_titulo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label keyupmce"><?= Yii::t("formulario", "Title/Degree Certificate") ?></label>
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
                        'uploadUrl' => Url::to(['/inscripciongrado/guardarinscripciongrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                         var personaid = $("#txth_personaid").val();
                         return {"upload_file": true, "name_file": "doc_titulo_per_" + personaid};
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
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_dni cinteres">
        <div class="form-group">
            <label for="txth_doc_dni" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Identification document") ?></label>
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
                        'uploadUrl' => Url::to(['/inscripciongrado/guardarinscripciongrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        var personaid = $("#txth_personaid").val();
                        return {"upload_file": true, "name_file": "doc_dni_per_" + personaid};
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

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divCertvota" style="display: block">
        <div class="form-group">
            <label for="txth_doc_certvota" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Voting Certificate") ?></label>
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
                        'uploadUrl' => Url::to(['/inscripciongrado/guardarinscripciongrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        var personaid = $("#txth_personaid").val();
                        return {"upload_file": true, "name_file": "doc_certvota_" + personaid};
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
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_foto cinteres">
        <div class="form-group">
            <label for="txth_doc_foto" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Foto") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
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
                        'uploadUrl' => Url::to(['/inscripciongrado/guardarinscripciongrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        var personaid = $("#txth_personaid").val();
                        return {"upload_file": true, "name_file": "doc_foto_" + personaid};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_foto').val($('#txt_doc_foto').val());
        $('#txt_doc_foto').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_adj_disi').val('');
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
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_comprobantepago">
        <div class="form-group">
            <label for="txth_doc_comprobantepago" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Comprobante de depósito o transferencia de pago de matrícula") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?= Html::hiddenInput('txth_doc_comprobantepago', '', ['id' => 'txth_doc_comprobantepago']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_comprobantepago',
                    'name' => 'txt_doc_comprobantepago',
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
                        'uploadUrl' => Url::to(['/inscripciongrado/guardarinscripciongrado']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                        var personaid = $("#txth_personaid").val();
                        return {"upload_file": true, "name_file": "doc_comprobantepago_" + personaid};
        }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
        $('#txth_doc_comprobantepago').val($('#txt_doc_comprobantepago').val());
        $('#txt_doc_comprobantepago').fileinput('upload');
    }",
                        "fileuploaderror" => "function (event, data, msg) {
        $(this).parent().parent().children().first().addClass('hide');
        $('#txth_doc_comprobantepago').val('');
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
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align: right;">
                <input type="checkbox" id="chk_mensaje1" data-type="alfa" data-keydown="true" placeholder="" >
            </div>
            <label for="chk_mensaje1" class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><?= Yii::t("formulario", "Expreso que la información declarada y documentos cargados son válidos y legales.") ?> </label>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align: right;">
                <input type="checkbox" id="chk_mensaje2" data-type="alfa" data-keydown="true" placeholder="" >
            </div>
            <label for="chk_mensaje2" class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><?= Yii::t("formulario", "Acepto y me comprometo a respetar y cumplir lo estipulado en los reglamentos internos de la universidad con respecto a la admisión y procesos estudiantiles.") ?> </label>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        </br>
        </br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_homologacion" class="col-sm-5 control-label"><?= Yii::t("bienestar", "Homologación") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <label>
                    <input type="radio" name="signup-hom" id="signup-hom" value="1" checked> Si<br>
                </label>
                <label>
                    <input type="radio" name="signup-hom_no" id="signup-hom_no" value="2" > No<br>
                </label>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="homologacion" style="display: block;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Documentos adicionales por homologación") ?></span></h3>
            </div><br><br></br>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  doc_record">
                <div class="form-group">
                    <label for="txth_doc_record" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Récord Académico") ?></label>
                    <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                        <?= Html::hiddenInput('txth_doc_record', '', ['id' => 'txth_doc_record']); ?>
                        <?php
                        echo CFileInputAjax::widget([
                            'id' => 'txt_doc_record',
                            'name' => 'txt_doc_record',
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
                                'uploadUrl' => Url::to(['/inscripciongrado/guardarinscripciongrado']),
                                'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                                'uploadExtraData' => 'javascript:function (previewId,index) {
                                var personaid = $("#txth_personaid").val();
                                return {"upload_file": true, "name_file": "doc_record_" + personaid};
                }',
                            ],
                            'pluginEvents' => [
                                "filebatchselected" => "function (event) {
                $('#txth_doc_record').val($('#txt_doc_record').val());
                $('#txt_doc_record').fileinput('upload');
            }",
                                "fileuploaderror" => "function (event, data, msg) {
                $(this).parent().parent().children().first().addClass('hide');
                $('#txth_doc_record').val('');
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 doc_nosancion">
                <div class="form-group">
                    <label for="txth_doc_nosancion" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Certificado de no haber sido sancionado (firma y sello original)") ?></label>
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
                                'uploadUrl' => Url::to(['/inscripciongrado/guardarinscripciongrado']),
                                'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                                'uploadExtraData' => 'javascript:function (previewId,index) {
                                var personaid = $("#txth_personaid").val();
                                return {"upload_file": true, "name_file": "doc_certificado_" + personaid};
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
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 doc_syllabus">
                <div class="form-group">
                    <label for="txth_doc_syllabus" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Syllabus de materias aprobadas (Firma y sellos originales)") ?></label>
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
                                'uploadUrl' => Url::to(['/inscripciongrado/guardarinscripciongrado']),
                                'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                                'uploadExtraData' => 'javascript:function (previewId,index) {
                                var personaid = $("#txth_personaid").val();
                                return {"upload_file": true, "name_file": "doc_syllabus_" + personaid};
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
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 doc_especievalorada">
                <div class="form-group">
                    <label for="txth_doc_especievalorada" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label keyupmce"><?= Yii::t("formulario", "Especie valorada por homologación") ?></label>
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
                                'uploadUrl' => Url::to(['/inscripciongrado/guardarinscripciongrado']),
                                'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                                'uploadExtraData' => 'javascript:function (previewId,index) {
                                var personaid = $("#txth_personaid").val();
                                return {"upload_file": true, "name_file": "doc_especievalorada_" + personaid};
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
            </div>
        </div><br><br></br>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-2">
            <a id="paso2back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
        <div class="col-md-2">
            <a id="btn_save_1" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?> <span class="glyphicon glyphicon-floppy-disk"></span></a>
        </div>
    </div>
</form>
