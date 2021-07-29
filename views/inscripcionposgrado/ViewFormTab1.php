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

?>

<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_cedula" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Cédula") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_cedula" value="<?= $persona_model->per_cedula ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("inscripcionposgrado", "Cédula")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_pasaporte" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Pasaporte") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_pasaporte" value="<?= $persona_model->per_pasaporte ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("inscripcionposgrado", "Pasaporte")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_pri_nombre" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Primer Nombre") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_pri_nombre" value="<?= $persona_model->per_pri_nombre ?>" data-type="all" disabled="disabled" placeholder="<?= Yii::t("inscripcionposgrado", "Primer Nombre")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_seg_nombre" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Segundo Nombre") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_seg_nombre" value="<?= $persona_model->per_seg_nombre ?>" data-type="alfa" disabled="disabled" placeholder="<?=Yii::t("inscripcionposgrado", "Segundo Nombre")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_pri_apellido" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Primer Apellido") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_pri_apellido" value="<?= $persona_model->per_pri_apellido ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("inscripcionposgrado", "Primer Apellido")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_seg_apellido" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Segundo Apellido") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_seg_apellido" value="<?= $persona_model->per_seg_apellido ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("inscripcionposgrado", "Segundo Apellido")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_ciudad" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Ciudad de Nacimiento") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_ciudad", $persona_model->can_id_nacimiento, $arr_can, ["class" => "form-control", "id" => "cmb_ciudad" , "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_fecha_nacimiento" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Fecha de Nacimiento") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_fecha_nacimiento" value="<?= $persona_model->per_fecha_nacimiento ?>" disabled="disabled" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Fecha de Nacimiento")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_nacionalidad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("perfil", "Nacionalidad") ?></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" maxlength="50" class="form-control PBvalidation" id="frm_nacionalidad" value="<?= $persona_model->per_nacionalidad ?>" disabled="disabled" data-required="false" data-type="all" placeholder="<?= Yii::t("perfil", "Nacionalidad") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_estadocivil" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Estado Civil") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_estadocivil", $persona_model->eciv_id, $arr_estado_civil, ["class" => "form-control", "id" => "cmb_estadocivil" , "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_pais" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Pais") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_pais", $persona_model->pai_id_domicilio, $arr_pais, ["class" => "form-control", "id" => "cmb_pais", "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_provincia" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Provincia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_provincia", $persona_model->pro_id_domicilio, $arr_pro, ["class" => "form-control", "id" => "cmb_provincia" , "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_canton" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Cantón") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_canton", $persona_model->can_id_domicilio, $arr_can, ["class" => "form-control", "id" => "cmb_canton" , "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_domicilio_ref" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("formulario", "Dirección del domicilio") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_domicilio_ref" value="<?= $persona_model->per_domicilio_ref ?>" data-type="all" disabled="disabled" placeholder="<?= Yii::t("inscripcionposgrado", "Detallar la dirección de su domicilio")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_cel" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Celular") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_cel" value="<?= $persona_model->per_celular ?>" data-required="false" disabled="disabled" data-type="number"  placeholder="<?= Yii::t("perfil", "Celular")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_phone" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Télefono") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_phone" value="<?= $persona_model->per_domicilio_telefono ?>" disabled="disabled" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Télefono")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="frm_per_correo" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Correo") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_correo" value="<?= $persona_model->per_correo ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("inscripcionposgrado", "Correo")  ?>">
            </div>
        </div> 
    </div>
</form>