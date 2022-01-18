<?php

use yii\helpers\Html;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as aspirante;

aspirante::registerTranslations();

$tipodoc='';
if (!empty($personalData['per_cedula'])) {
    $tipodoc = "Cédula";
    $docdni = $personalData['per_cedula'];
} else {
    if (!empty($personalData['per_pasaporte'])) {
        $tipodoc = "Pasaporte";
        $docdni = $personalData['per_pasaporte'];
    } else {
        $tipodoc = "Cédula";
        $docdni = $personalData['per_cedula'];
    }
}
?>
<?= Html::hiddenInput('txth_per_id', base64_encode($personalData['per_id']), ['id' => 'txth_per_id']); ?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h3><span id="lbl_Personeria"><?= admision::t("Solicitudes", "Applications per enrollee") ?></span></h3>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <h4><span id="lbl_general"><?= admision::t("Solicitudes", "General Information Registered") ?></span></h4>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombre1" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?>:</label>
                <span for="txt_nombre1" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label" id="lbl_nombre1"><?= $personalData['per_pri_nombre'] . " " . $personalData['per_seg_nombre'] ?> </span>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_apellido1" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?>: </label>
                <span for="txt_apellido1" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label" id="lbl_apellido1"><?= $personalData['per_pri_apellido'] . " " . $personalData['per_seg_apellido'] ?> </span>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_nombre1" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre1"><?= $tipodoc ?>:</label>
                <span for="txt_nombre1" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label" id="lbl_nombre1"><?= $personalData['per_cedula'] ?> </span>
            </div>
        </div>
    </div>

</div>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <?=
    $this->render('_listarSolxinteresadoGrid', [
        'model' => $model,
        'url' => $url,
        ]);
    ?>
</div>


