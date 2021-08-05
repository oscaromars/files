<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\models\Persona;
use app\modules\Academico\models\Profesor;
use app\modules\Academico\Module as Academico;
Academico::registerTranslations();
?>

<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_cedula" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Identification Card") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_cedula" value="<?= $persona_model->per_cedula ?>" data-type="alfa" disabled="disabled" placeholder="<?= Academico::t("profesor", "Identification Card")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_pasaporte" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Passport") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_pasaporte" value="<?= $persona_model->per_pasaporte ?>" data-type="alfa" disabled="disabled" placeholder="<?= Academico::t("profesor", "Passport")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_pri_nombre" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "First Name") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_pri_nombre" value="<?= $persona_model->per_pri_nombre ?>" data-type="all" disabled="disabled" placeholder="<?= Academico::t("profesor", "First Name")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_seg_nombre" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Second Name") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_seg_nombre" value="<?= $persona_model->per_seg_nombre ?>" data-type="alfa" disabled="disabled" placeholder="<?= Academico::t("profesor", "Second Name")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_pri_apellido" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "First Surname") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_pri_apellido" value="<?= $persona_model->per_pri_apellido ?>" data-type="alfa" disabled="disabled" placeholder="<?= Academico::t("profesor", "First Surname")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_seg_apellido" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Second Surname") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_seg_apellido" value="<?= $persona_model->per_seg_apellido ?>" data-type="alfa" disabled="disabled" placeholder="<?= Academico::t("profesor", "Second Surname")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_ciudad" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Ciudad de Nacimiento") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_ciudad", $persona_model->can_id_nacimiento, $arr_can, ["class" => "form-control", "id" => "cmb_ciudad" , "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_fecha_nacimiento" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Birth Date") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_fecha_nacimiento" value="<?= $persona_model->per_fecha_nacimiento ?>" disabled="disabled" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Birth Date")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_nacionalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("perfil", "Nationality") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="50" class="form-control PBvalidation" id="frm_nacionalidad" value="<?= $persona_model->per_nacionalidad ?>" disabled="disabled" data-required="false" data-type="all" placeholder="<?= Yii::t("perfil", "Nationality") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_estadocivil" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Estado Civil") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_estadocivil", $persona_model->eciv_id, $arr_estado_civil, ["class" => "form-control", "id" => "cmb_estadocivil" , "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>    
</form>