<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\components\CFileInputAjax;
use app\models\Persona;
use app\modules\Academico\models\Profesor;
use app\modules\Academico\Module as Academico;

Academico::registerTranslations();

$leyenda = '<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 400px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Al subir archivo debe ser 800 KB máximo y tipo jpg.</div>
          </div>
          </div>
          </div>';
?>

<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_primer_nombre" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "First Name") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_primer_nombre" value="<?= $persona_model->per_pri_nombre ?>" data-type="alfa"  placeholder="<?= Academico::t("profesor", "First Name") ?>">
            </div>
        </div>  
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_segundo_nombre" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Second Name") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidations " id="txt_segundo_nombre" value="<?= $persona_model->per_seg_nombre ?>" data-type="alfa"  placeholder="<?= Academico::t("profesor", "Second Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_primer_apellido" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "First Surname") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_primer_apellido" value="<?= $persona_model->per_pri_apellido ?>" data-type="alfa"  placeholder="<?= Academico::t("profesor", "First Surname") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_segundo_apellido" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Second Surname") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidations " id="txt_segundo_apellido" value="<?= $persona_model->per_seg_apellido ?>" data-type="alfa"  placeholder="<?= Academico::t("profesor", "Second Surname") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_caracteristica" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?=Academico::t("profesor", "Nacionalidad")?></label>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <?php if ($persona_model->per_nac_ecuatoriano == 1) { ?>
                    <input type="checkbox" id="chk_nacionalidad1" value="Ecuatoriano"  checked>
                    <label id="chk_nac" class="control-label" name="<?php echo $tip_nacionalidad['ECU'] ?>">  <?php echo $tip_nacionalidad['ECU'] ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="chk_nacionalidad2" value="Extranjero" >
                    <label id="chk_nac" class="control-label" name="<?php echo $tip_nacionalidad['EXT'] ?>">  <?php echo $tip_nacionalidad['EXT'] ?></label>
                <?php } else { ?>
                    <input type="checkbox" id="chk_nacionalidad1" value="Ecuatoriano" >
                    <label id="chk_nac" class="control-label" name="<?php echo $tip_nacionalidad['ECU'] ?>">  <?php echo $tip_nacionalidad['ECU'] ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="chk_nacionalidad2" value="Extranjero" checked >
                    <label id="chk_nac" class="control-label" name="<?php echo $tip_nacionalidad['EXT'] ?>">  <?php echo $tip_nacionalidad['EXT'] ?></label>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cedula" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Identification Card") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_cedula" value="<?= $persona_model->per_cedula ?>" data-type="cedula"  placeholder="<?= Academico::t("profesor", "Identification Card") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ruc">
        <div class="form-group">
            <label for="txt_ruc" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Ruc") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_ruc" value="<?= $persona_model->per_ruc ?>" data-required="false" data-type="number"  placeholder="<?= Academico::t("profesor", "Ruc") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="passport">
        <div class="form-group">
            <label for="txt_pasaporte" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Passport") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_pasaporte" value="<?= $persona_model->per_pasaporte ?>" data-required="false" data-type="alfanumerico"  placeholder="<?= Academico::t("profesor", "Passport") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="nac_extr">
        <div class="form-group">
            <label for="txt_nacionalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("perfil", "Nationality") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="50" class="form-control PBvalidation" id="txt_nacionalidad" value="<?= $persona_model->per_nacionalidad ?>"  data-required="false" data-type="all" placeholder="<?= Yii::t("perfil", "Nationality") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_correo" class="col-sm-3 control-label"><?= Academico::t("profesor", "Mail") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_correo" value="<?= $email ?>" data-type="email"  disabled="true" placeholder="<?= Academico::t("profesor", "Mail") ?>">
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cel" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "CellPhone") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_cel" value="<?= $persona_model->per_celular ?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "CellPhone") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_phone" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Phone") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_phone" value="<?= $persona_model->per_domicilio_telefono ?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Phone") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_contrato" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("profesor", "# Contrato") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_contrato" value="<?= $persona_model->profesor[0]->pro_num_contrato?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("profesor", "# Contrato") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_dedicacion" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("perfil", "Dedicación") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_dedicacion", $persona_model->profesor[0]->ddoc_id, $arr_dedic, ["class" => "form-control", "id" => "cmb_dedicacion" ]) ?>
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
                    'value' => $persona_model->per_fecha_nacimiento,
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
            <label for="txth_doc_cv" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "Photo") ?> <span class="text-danger">*</span></label>                    
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <?= Html::hiddenInput('txth_doc_cv', $per_foto, ['id' => 'txth_doc_cv']); ?>
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
                        'uploadUrl' => Url::to(['/academico/profesor/edit']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                                return {"upload_file": true, "name_file": "foto-' . @Yii::$app->session->get("PB_iduser") . '-' . time() . '"};
                            }',
                    ],
                    'options' => ['accept' => 'application/jpg'],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
                                $('#txth_doc_foto').val('foto-" . @Yii::$app->session->get("PB_iduser") . '-' . time() . "');
                                $('#txth_doc_cv').val($('#txth_doc_cv').val());
                                $('#txt_doc_cv').fileinput('upload');
                            }",
                        "fileuploaderror" => "function (event, data, msg) {
                                $(this).parent().parent().children().first().addClass('hide');
                                $('#txth_doc_cv').val('');        
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
            <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
                <div class="form-group">                    
                    <?php
                    if (!empty($persona_model->per_foto)) {
                        echo "<a href='" . Url::to(['/site/getimage', 'route' => "/uploads/" . $persona_model->per_foto]) . "' download='" . $persona_model->per_foto . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Foto</a>";
                    }
                    ?>                
                </div>
            </div><br><br><br>
            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
                <?php echo $leyenda; ?>
            </div>
        </div>
    </div>          
</form>
