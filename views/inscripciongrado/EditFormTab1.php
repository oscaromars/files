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
            <label for="txt_cedula" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Identification Card") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_cedula" value="<?= $persona_model->per_cedula ?>" data-type=""  placeholder="<?= Academico::t("profesor", "Identification Card") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_pasaporte" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Passport") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_pasaporte" value="<?= $persona_model->per_pasaporte ?>" data-required="false" data-type="alfanumerico"  placeholder="<?= Academico::t("profesor", "Passport") ?>">
            </div>
        </div>
    </div>
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
            <label for="cmb_ciudad" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Ciudad de Nacimiento") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_ciudad", $persona_model->can_id_nacimiento, $arr_can, ["class" => "form-control", "id" => "cmb_ciudad"]) ?>
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
            <label for="txt_nacionalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("perfil", "Nationality") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="50" class="form-control PBvalidation" id="txt_nacionalidad" value="<?= $persona_model->per_nacionalidad ?>"  data-required="false" data-type="all" placeholder="<?= Yii::t("perfil", "Nationality") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_estadocivil" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Estado Civil") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_estadocivil", $persona_model->eciv_id, $arr_estado_civil, ["class" => "form-control", "id" => "cmb_estadocivil"]) ?>
            </div>
        </div>
    </div>
                  
</form>
