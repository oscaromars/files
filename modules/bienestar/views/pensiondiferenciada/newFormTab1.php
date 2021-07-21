<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;

use app\modules\bienestar\Module as Bienestar;

Bienestar::registerTranslations();

?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_apellido" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Last Name") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_apellido" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Last Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_nombre" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "First Name") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_nombre" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "First Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_lugar_nac" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Place of Birth") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_lugar_nac" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Place of Birth") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="fecha_nacimiento" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "Date of Birth") ?><span class="text-danger">*</span> </label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <?=
                DatePicker::widget([
                    'name' => 'fecha_inicial',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "fecha_nacimiento", "placeholder" => Yii::t("formulario", "Date of Birth")],
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
            <label for="num_edad" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Age") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="number" min=0 max=100 class="form-control PBvalidation" id="num_edad" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Age") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cedula" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "C.I.") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_cedula" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "C.I.") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_direccion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Home Address") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_direccion" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Home Address") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="tlf_dom" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Home Phone") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="tlf_dom" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Home Phone") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="tlf_cel" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "CellPhone") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="tlf_cel" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "CellPhone") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_correo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Email") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_correo" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Email") ?>">
            </div>
        </div>
    </div>
</form>