<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\investigacion\Module as investigacion;

investigacion::registerTranslations();
?>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_planear"><?= investigacion::t("registroproyecto", "Register of Project") ?></span></h3>
    </div><br><br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4><span id="lbl_planear"><?= investigacion::t("registroproyecto", "Information - Project Director") ?></span></h4>
    </div><br><br>
    <form class="form-horizontal" enctype="multipart/form-data" >
    <!-- Informacion del Director de Proyecto -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nombre" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Name") ?><span class="text-danger">*</span></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" id="txt_nombre" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Name") ?>">
                        </div>
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_apellido" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Surname") ?><span class="text-danger">*</span></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" id="txt_apellido" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Surname") ?>">
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_cedula" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Identification Card") ?><span class="text-danger">*</span></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" id="txt_cedula" data-type="cedula"  placeholder="<?= investigacion::t("registroproyecto", "Identification Card") ?>">
                        </div>
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_nacionalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Nationality") ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" maxlength="50" class="form-control PBvalidation" id="txt_nacionalidad" data-required="false" data-type="all" placeholder="<?= investigacion::t("registroproyecto", "Nationality") ?>">
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_correo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Mail") ?><span class="text-danger">*</span></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" id="txt_correo" data-type="email" placeholder="<?= investigacion::t("registroproyecto", "Mail") ?>">
                        </div>
                    </div>
                </div> 
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                    <div class="form-group">
                        <label for="txt_cell" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "CellPhone") ?></label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" class="form-control PBvalidation" id="txt_cell" data-type="email" placeholder="<?= investigacion::t("registroproyecto", "CellPhone") ?>">
                        </div>
                    </div>
                </div> 
            </div>
    <br><br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4><span id="lbl_planear"><?= investigacion::t("registroproyecto", "Information - Director of Research") ?></span></h4>
    </div><br><br>
    <!-- Informacion de la Entidad Educativa -->
    
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_entidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Educational Entity") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation " id="txt_entidad" data-type="all"  placeholder="<?= investigacion::t("registroproyecto", "Educational Entity") ?>">
                </div>
            </div>
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="txt_departamento" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Execution area") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation " id="txt_departamento" data-type="all"  placeholder="<?= investigacion::t("registroproyecto", "Execution area") ?>">
                </div>
            </div>
        </div> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_manager" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Area Manager") ?><span class="text-danger">*</span></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" id="txt_manager" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Area Manager") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_correo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Mail") ?><span class="text-danger">*</span></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" id="txt_correo" data-type="email" placeholder="<?= investigacion::t("registroproyecto", "Mail") ?>">
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_manager" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Area Manager") ?><span class="text-danger">*</span></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" id="txt_manager" data-type="alfa" placeholder="<?= investigacion::t("registroproyecto", "Area Manager") ?>">
                    </div>
                </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cell" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "CellPhone") ?></label>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <input type="text" class="form-control PBvalidation" id="txt_cell" data-type="email" placeholder="<?= investigacion::t("registroproyecto", "CellPhone") ?>">
                    </div>
                </div>
            </div> 
        </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_ruc" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Ruc") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="15" class="form-control PBvalidation" id="txt_ruc" data-required="false" data-type="number" placeholder="<?= investigacion::t("registroproyecto", "Ruc") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_pasaporte" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Passport") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="50" class="form-control PBvalidation" id="txt_pasaporte" data-required="false" data-type="alfanumerico" placeholder="<?= investigacion::t("registroproyecto", "Passport") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_nacionalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("perfil", "Nationality") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="50" class="form-control PBvalidation" id="txt_nacionalidad" data-required="false" data-type="all" placeholder="<?= Yii::t("perfil", "Nationality") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_correo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= investigacion::t("registroproyecto", "Mail") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_correo" data-type="email" placeholder="<?= investigacion::t("profesor", "Mail") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cel" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("perfil", "CellPhone") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="15" class="form-control PBvalidation" id="txt_cel" data-required="false" data-type="number" placeholder="<?= Yii::t("perfil", "CellPhone") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_phone" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("perfil", "Phone") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="15" class="form-control PBvalidation" id="txt_phone" data-required="false" data-type="number" placeholder="<?= Yii::t("perfil", "Phone") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_contrato" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("registroproyecto", "# Contrato") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="15" class="form-control PBvalidation" id="txt_contrato" data-required="false" data-type="number" placeholder="<?= Yii::t("registroproyecto", "# Contrato") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_dedicacion" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= investigacion::t("registroproyecto", "Dedicación") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_dedicacion", 0, $model_dedicacion, ["class" => "form-control", "id" => "cmb_dedicacion" ]) ?>
            </div>
        </div>
    </div>
        
       
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_fecha_nacimiento" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("perfil", "Birth Date") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_nacimiento',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_nacimiento", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Birth Date yyyy-mm-dd")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>  
            </div>
        </div>
    </div>
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">           
            <label for="txth_doc_foto" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "Photo") ?> </label>                    
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
               
                <?= Html::hiddenInput('txth_doc_cv', $typeFile, ['id' => 'txth_doc_cv']); ?>
                <?= Html::hiddenInput('txth_doc_foto', $per_foto, ['id' => 'txth_doc_foto']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_cv',
                    'name' => 'txt_doc_cv',
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
                        'browseLabel' => "Subir Foto",
                        'uploadUrl' => Url::to(['/academico/profesor/new']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize2m"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                            return {"upload_file": true, "name_file": "foto-' . @Yii::$app->session->get("PB_iduser") . '-' . time() . '"};
                        }',
                    ],
                    'options' => ['accept' => 'image/*'],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
                            $('#txth_doc_foto').val('foto-" . @Yii::$app->session->get("PB_iduser") . '-' . time() . "');

                            $('#txth_doc_cv').val($('#txt_doc_cv').val());
                            $('#txt_doc_cv').fileinput('upload');
                        }",
                        "fileuploaderror" => "function (event, data, msg) {
                            $(this).parent().parent().children().first().addClass('hide');
                            $('#txth_doc_cv').val('');  
                            $('#txt_doc_cv').val('');  
                            var mensaje = {wtmessage: msg, title: 'Información'};
                                 showAlert('NO_OK', 'error', mensaje);      
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
</form>