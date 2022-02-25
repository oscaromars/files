<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as aspirante;

academico::registerTranslations();
financiero::registerTranslations();
aspirante::registerTranslations();
$tipodoc = '';
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
<?= Html::hiddenInput('txth_pids', base64_decode($_GET['per_id']), ['id' => 'txth_pids']); ?>
<?= Html::hiddenInput('txth_eids', base64_decode($_GET['est_id']), ['id' => 'txth_eids']); ?>
<?= Html::hiddenInput('txth_perids', $_GET['per_id'], ['id' => 'txth_perids']); ?>
<?= Html::hiddenInput('txth_estids', $_GET['est_id'], ['id' => 'txth_estids']); ?>
<form class="form-horizontal" enctype="multipart/form-data" id="formsolicitud">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <h3><span id="lbl_solicitud"><?= academico::t("estudiantes", "View Students") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= academico::t("estudiantes", "Data General Students") ?></span></h4>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_nombres" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= Yii::t("formulario", "Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_nombre'] . " " . $arr_persona['per_seg_nombre'] ?>" id="txt_nombres" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_apellidos" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_apellido1"><?= Yii::t("formulario", "Last Names") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_pri_apellido'] . " " . $arr_persona['per_seg_apellido'] ?>" id="txt_apellidos" disabled data-type="alfa" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="form-group">
                    <label for="txt_cedula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= $tipodoc ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                        <input type="text" class="form-control keyupmce" value="<?php echo $arr_persona['per_cedula'] ?>" id="txt_cedula" data-type="alfa" disabled placeholder="<?= Yii::t("formulario", "DNI Document") ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
            <div class="form-group">
                <h4><span id="lbl_general"><?= academico::t("estudiantes", "Data Career/Program") ?></span></h4>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_unidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Academic unit") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_unidad", $arr_estudiante['unidad'], $arr_ninteres, ["class" => "form-control", "disabled"=> "true", "id" => "cmb_unidad"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="divModalidad">
            <div class="form-group">
                <label for="cmb_modalidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= academico::t("Academico", "Modality") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_modalidad", $arr_estudiante['modalidad'], $arr_modalidad, ["class" => "form-control", "disabled"=> "true", "id" => "cmb_modalidad"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_carrera" id="lbl_carrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= academico::t("Academico", "Career/Program") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_carrera", $arr_estudiante['carrera'], $arr_carrera, ["class" => "form-control", "disabled"=> "true", "id" => "cmb_carrera"]) ?>
                </div>
            </div>

        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_categoria" id="lbl_carrera" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label keyupmce"><?= Yii::t("formulario", "Category") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                   <?= Html::dropDownList("cmb_categoria", $arr_estudiante['categoria'], $arr_categorias, ["class" => "form-control", "disabled"=> "true", "id" => "cmb_categoria"]) ?>
                </div>
            </div>

        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_matricula" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_nombre1"><?= academico::t("Academico", 'Enrollment Number') ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control keyupmce" value= "<?php echo $arr_estudiante['matricula'] ?>" id="txt_matricula" disabled data-type="number" data-keydown="true" placeholder="<?= academico::t("Academico", 'Enrollment Number') ?>">
                </div>
            </div>
        </div>
    </div>
</form>