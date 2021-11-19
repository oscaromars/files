<?php
/* @var $this yii\web\View */

use app\components\CFileInputAjax;
use app\modules\Academico\Module as Academico;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;

Academico::registerTranslations();
?>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_primer_nombre" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "First Name")?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_primer_nombre" data-type="alfa" placeholder="<?=Academico::t("profesor", "First Name")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_segundo_nombre" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "Second Name")?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations " id="txt_segundo_nombre" data-type="alfa" placeholder="<?=Academico::t("profesor", "Second Name")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_primer_apellido" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "First Surname")?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_primer_apellido" data-type="alfa" placeholder="<?=Academico::t("profesor", "First Surname")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_segundo_apellido" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "Second Surname")?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidations " id="txt_segundo_apellido" data-type="alfa" placeholder="<?=Academico::t("profesor", "Second Surname")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_caracteristica" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "Nacionalidad")?></label>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <input type="checkbox" id="chk_nacionalidad1" value="Ecuatoriano">
                <label id="chk_nac" class="control-label" name="<?php echo $tip_nacionalidad['ECU'] ?>">  <?php echo $tip_nacionalidad['ECU'] ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <input type="checkbox" id="chk_nacionalidad2" value="Extranjero" >
                <label id="chk_nac" class="control-label" name="<?php echo $tip_nacionalidad['EXT'] ?>">  <?php echo $tip_nacionalidad['EXT'] ?></label>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="nac_extr" style="display:none">
        <div class="form-group">
            <label for="txt_nacionalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Yii::t("perfil", "Nacionalidad Extranjera")?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="50" class="form-control PBvalidation" id="txt_nacionalidad" data-required="false" data-type="all" placeholder="<?=Yii::t("perfil", "Nationality")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">

            <label for="txt_cedula" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "Identificación")?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation " id="txt_cedula" data-type="Identificación"  placeholder="<?=Academico::t("profesor", "Identification Card")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ruc" style="display:none">
        <div class="form-group">
            <label for="txt_ruc" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "Ruc")?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="15" class="form-control PBvalidation" id="txt_ruc" data-required="false" data-type="number" placeholder="<?=Academico::t("profesor", "Ruc")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="passport" style="display:none">
        <div class="form-group">
            <label for="txt_pasaporte" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "Passport")?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="50" class="form-control PBvalidation" id="txt_pasaporte" data-required="false" data-type="alfanumerico" placeholder="<?=Academico::t("profesor", "Passport")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_correo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "Mail")?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_correo" data-type="email" placeholder="<?=Academico::t("profesor", "Mail")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cel" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Yii::t("perfil", "CellPhone")?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="15" class="form-control PBvalidation" id="txt_cel" data-required="false" data-type="number" placeholder="<?=Yii::t("perfil", "CellPhone")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_phone" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Yii::t("perfil", "Phone")?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="15" class="form-control PBvalidation" id="txt_phone" data-required="false" data-type="number" placeholder="<?=Yii::t("perfil", "Phone")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_contrato" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Yii::t("profesor", "# Contrato")?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="15" class="form-control PBvalidation" id="txt_contrato" data-required="false" data-type="number" placeholder="<?=Yii::t("profesor", "# Contrato")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_dedicacion" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?=Academico::t("perfil", "Dedicación")?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?=Html::dropDownList("cmb_dedicacion", 0, $model_dedicacion, ["class" => "form-control", "id" => "cmb_dedicacion"])?>
            </div>
        </div>
    </div>


    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_fecha_nacimiento" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Yii::t("perfil", "Birth Date")?> <span class="text-danger">*</span></label>
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
            <label for="txth_doc_foto" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Yii::t("formulario", "Photo")?> </label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">

                <?=Html::hiddenInput('txth_doc_cv', $typeFile, ['id' => 'txth_doc_cv']);?>
                <?=Html::hiddenInput('txth_doc_foto', $per_foto, ['id' => 'txth_doc_foto']);?>
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