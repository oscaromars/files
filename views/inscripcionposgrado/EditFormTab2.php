<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_pais" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Pais") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_pais", $persona_model->pai_id_domicilio, $arr_pais, ["class" => "form-control", "id" => "cmb_pais"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_provincia" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Provincia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_provincia", $persona_model->pro_id_domicilio, $arr_pro, ["class" => "form-control", "id" => "cmb_provincia"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_canton" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Cantón") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_canton", $persona_model->can_id_domicilio, $arr_can, ["class" => "form-control", "id" => "cmb_canton"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_per_domicilio_csec" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Parroquia") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_per_domicilio_csec" value="<?= $persona_model->per_domicilio_csec ?>" data-type="all"  placeholder="<?= Yii::t("inscripcionposgrado", "Parroquia")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_per_domicilio_ref" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Dirección del domicilio") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_per_domicilio_ref" value="<?= $persona_model->per_domicilio_ref ?>" data-type="all"  placeholder="<?= Yii::t("inscripcionposgrado", "Detallar la dirección de su domicilio")  ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cel" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Celular") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_cel" value="<?= $persona_model->per_celular ?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Celular") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_phone" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("perfil", "Télefono") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_phone" value="<?= $persona_model->per_domicilio_telefono ?>" data-required="false" data-type="number"  placeholder="<?= Yii::t("perfil", "Télefono") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_correo" class="col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Correo") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="txt_correo" value="<?= $email ?>" data-type="email" placeholder="<?= Yii::t("inscripcionposgrado", "Correo") ?>">
            </div>
        </div> 
    </div>
</form>