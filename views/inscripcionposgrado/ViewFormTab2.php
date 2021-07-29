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
            <label for="txt_titulo_3erNivel" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Título de Tercer Nivel") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_titulo_3erNivel" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Título de Tercer Nivel") ?>">
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
            <label for="frm_per_domicilio_csec" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Parroquia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation" id="frm_per_domicilio_csec" value="<?= $persona_model->per_domicilio_csec ?>" data-type="all" disabled="disabled" placeholder="<?= Yii::t("inscripcionposgrado", "Parroquia")  ?>">
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