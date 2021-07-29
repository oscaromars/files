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

$birthDate = $persona['per_fecha_nacimiento'];
$birthDate = explode("-", $birthDate);
$edad = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md")
? ((date("Y") - $birthDate[0]) - 1)
: (date("Y") - $birthDate[0]));

// \app\models\Utilities::putMessageLogFile($persona);

?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_apellido" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Last Name") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_apellido" value="<?= $persona['per_pri_apellido'] . " " . $persona['per_seg_apellido'] ?>" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Last Name") ?>" disabled>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_nombre" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "First Name") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_nombre" value="<?= $persona['per_pri_nombre'] . " " . $persona['per_seg_nombre'] ?>" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "First Name") ?>" disabled>
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
            <label for="fecha_nacimiento" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Date of Birth") ?><span class="text-danger">*</span> </label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <?=
                DatePicker::widget([
                    'name' => 'fecha_inicial',
                    'value' => $persona['per_fecha_nacimiento'],
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "fecha_nacimiento", "placeholder" => Bienestar::t("pensiondiferenciada", "Date of Birth"), 'disabled' => true],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"]
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
                <input type="number" min=0 max=100 class="form-control PBvalidation" id="num_edad" value="<?= $edad ?>" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Age") ?>" disabled>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cedula" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "C.I.") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_cedula" value="<?= $persona['per_cedula'] ?>" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "C.I.") ?>" disabled>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_direccion" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Home Address") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_direccion" value="<?= $persona['per_domicilio_sector'] ?>" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Home Address") ?>" disabled>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="tlf_dom" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Home Phone") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="tlf_dom" value="<?= $persona['per_domicilio_telefono'] ?>" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Home Phone") ?>" disabled>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="tlf_cel" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "CellPhone") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="tlf_cel" value="<?= $persona['per_celular'] ?>" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "CellPhone") ?>" disabled>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_correo" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Email") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="txt_correo" value="<?= $persona['per_correo'] ?>" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Email") ?>" disabled>
            </div>
        </div>
    </div>
</form>