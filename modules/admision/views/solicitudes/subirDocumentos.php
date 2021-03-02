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
<?= Html::hiddenInput('txth_idp', base64_encode($per_id), ['id' => 'txth_idp']); ?>
<?= Html::hiddenInput('txth_ids', base64_encode($sins_id), ['id' => 'txth_ids']); ?>
<?= Html::hiddenInput('txth_extranjero', base64_encode($txth_extranjero), ['id' => 'txth_extranjero']); ?>
<?= Html::hiddenInput('txth_int_id', base64_encode($int_id), ['id' => 'txth_int_id']); ?>
<?= Html::hiddenInput('txth_beca', base64_encode($beca), ['id' => 'txth_beca']); ?>
<?= Html::hiddenInput('txth_opcion', base64_decode($_GET['opcion']), ['id' => 'txth_opcion']); ?>
<?= Html::hiddenInput('txth_uaca', base64_decode($_GET['uaca']), ['id' => 'txth_uaca']); ?>
<?= Html::hiddenInput('txth_cemp', $cemp_id, ['id' => 'txth_cemp']); ?>

<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h3><span id="lbl_solicitud"><?= Yii::t("formulario", "Upload documents") ?></span></h3>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="txt_solicitud" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label"><?= admision::t("Solicitudes", "Request #") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control" value="<?= $num_solicitud ?>" id="txt_solicitud" disabled="true">
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="txt_nombres_completos" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label"><?= Yii::t("formulario", "Complete Names") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control" value="<?= $cliente ?>" id="txt_nombres_completos" disabled="true">
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
            <label for="txth_doc_titulo" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= admision::t("Solicitudes", "Title") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
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
                        'uploadUrl' => Url::to(['/admision/solicitudes/savedocumentos']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_titulo"};
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
            <label for="txth_doc_dni" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Identification document") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
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
                        'uploadUrl' => Url::to(['/admision/solicitudes/savedocumentos']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_dni"};
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
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_certvota cinteres" <?= ($txth_extranjero == "0" )  ? 'style="display:none;"' : "" ?> >
        <div class="form-group">
            <label for="txth_doc_certvota" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= admision::t("Solicitudes", "Voting Certificate") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
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
      'uploadUrl' => Url::to(['/admision/solicitudes/savedocumentos']),
      'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
      'uploadExtraData' => 'javascript:function (previewId,index) {
      return {"upload_file": true, "name_file": "doc_certvota"};
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
            <label for="txth_doc_foto" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Foto") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
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
                        'uploadUrl' => Url::to(['/admision/solicitudes/savedocumentos']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
            return {"upload_file": true, "name_file": "doc_foto"};
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
    <div  id="divCertificado" style="display: <?php echo $docpos; ?>">   
        <!-- <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_certificado cinteres">
            <div class="form-group">
                <label for="txth_doc_certificado" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><? Yii::t("formulario", "Materials Certificate") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <? Html::hiddenInput('txth_doc_certificado', '', ['id' => 'txth_doc_certificado']); ?>
        <?php
        /* echo CFileInputAjax::widget([
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
          'uploadUrl' => Url::to(['/admision/solicitudes/savedocumentos']),
          'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
          'uploadExtraData' => 'javascript:function (previewId,index) {
          return {"upload_file": true, "name_file": "doc_certificado", "inscripcion_id": $("#txth_twin_id").val()};
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
          ]); */
        ?>
                </div>
            </div>
        </div>-->

        <?php //Aqui voy a colocar la informacion de de la hoja de vida  ?>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_hoja_vida">
            <div class="form-group">
                <label for="txth_doc_hojavida" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Curriculum") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
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
                            'uploadUrl' => Url::to(['/admision/solicitudes/savedocumentos']),
                            'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                                return {"upload_file": true, "name_file": "doc_hojavida", "inscripcion_id": $("#txth_twin_id").val()};
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
        </div>        
        <?php //Fin de la hoja de vida  ?>                
    </div>
    <?php if ($cemp_id == 1) { ?>        
        <?php //Aqui voy a colocar la informacion de la carta de convenio  ?>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 doc_carta_convenio">
            <div class="form-group">
                <label for="txth_carta_convenio" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Agreement Letter") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::hiddenInput('txth_carta_convenio', '', ['id' => 'txth_carta_convenio']); ?>
                    <?php
                    echo CFileInputAjax::widget([
                        'id' => 'txt_carta_convenio',
                        'name' => 'txt_carta_convenio',
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
                            'uploadUrl' => Url::to(['/admision/solicitudes/savedocumentos']),
                            'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                            return {"upload_file": true, "name_file": "doc_carta_convenio"};
                        }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
                            $('#txth_carta_convenio').val($('#txt_carta_convenio').val());
                            $('#txt_carta_convenio').fileinput('upload');
                        }",
                            "fileuploaderror" => "function (event, data, msg) {
                            $(this).parent().parent().children().first().addClass('hide');
                            $('#txth_carta_convenio').val('');
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
    <?php } ?>
    <?php // fin de la carta de convenio ?>        
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">       
        <div class="form-group">
            <label for="txt_observa" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= Yii::t("formulario", "Observation") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                
                <textarea  class="form-control keyupmce" id="txt_observa" rows="3"><?= $datos['observa'] ?></textarea>                  
            </div>
        </div>      
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">        
        <div class="form-group">
            <label for="txt_declararbeca" class="col-sm-5 control-label"><?= admision::t("Solicitudes", "Apply Cala Foundation scholarship") ?></label>
            <div class="col-sm-7">  
                <?php if (base64_decode($beca) == 1) { ?>
                    <label><input type="radio" name="opt_declara_si"  id="opt_declara_si" value="1" checked disabled="true"><b>Si</b></label>
                    <label><input type="radio" name="opt_declara_no"  id="opt_declara_no" value="2" disabled="true"><b>No</b></label>                                              
                <?php } else { ?>
                    <label><input type="radio" name="opt_declara_si"  id="opt_declara_si" value="1" disabled="true"><b>Si</b></label>
                    <label><input type="radio" name="opt_declara_no"  id="opt_declara_no" value="2" checked disabled="true"><b>No</b></label>                                              
                <?php } ?>
            </div>            
        </div>        
    </div> 

    <?php if (base64_decode($beca) == 1) { ?>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divDeclarabeca">
            <div class="form-group">
                <label for="txth_doc_beca" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label keyupmce"><?= admision::t("Solicitudes", "Scholarship document") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::hiddenInput('txth_doc_beca', '', ['id' => 'txth_doc_beca']); ?>
                    <?php
                    echo CFileInputAjax::widget([
                        'id' => 'txt_doc_beca',
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
                            'uploadUrl' => Url::to(['/admision/solicitudes/savedocumentos']),
                            'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                            'uploadExtraData' => 'javascript:function (previewId,index) {
                return {"upload_file": true, "name_file": "doc_beca"};
            }',
                        ],
                        'pluginEvents' => [
                            "filebatchselected" => "function (event) {
            $('#txth_doc_beca').val($('#txt_doc_beca').val());
            $('#txt_doc_beca').fileinput('upload');
        }",
                            "fileuploaderror" => "function (event, data, msg) {
            $(this).parent().parent().children().first().addClass('hide');
            $('#txth_doc_beca').val('');
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
    <?php } ?>    
</form>
