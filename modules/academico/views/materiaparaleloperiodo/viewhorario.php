<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
// print_r($paraleloperiodo);
academico::registerTranslations();
admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', $paraleloperiodo[0]['mpp_id'], ['id' => 'txth_ids']); ?>

<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-10 col-sm-10 col-xs-10 col-lg-10">
        <div class="form-group">
            <h3><span id="lbl_horarios"><?= financiero::t("Pagos", "Cambiar horarios") ?></span></h3>
        </div>
    </div>
   <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_asignatura" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_asignatura"><?= academico::t("Academico", "Subject") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <span id="lbl_asignatura"><?= $paraleloperiodo[0]['asi_nombre'] ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_modalidad" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="lbl_modalidad"><?= academico::t("Academico", "Modality") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <?= $paraleloperiodo[0]['mod_descripcion'] ?>
                <br>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_paralelo" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="lbl_paralelo"><?= academico::t("Academico", "Parallel") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <?= $paraleloperiodo[0]['mpp_num_paralelo'] ?>
                <br>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-md-15 ">
                <label for="txt_horarios" class="col-sm-4 control-label"><?= academico::t("Academico", "Schedule") ?></label>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4 ">
                 <?= Html::dropDownList("cmb_horarios", $paraleloperiodo[0]['daho_id'], $horarios, ["class" => "form-control PBvalidation", "id" => "cmb_horarios"]) ?>
                 </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">
              <a id="btn_enviar"  class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Update") ?></a>
            </div>
            <div class="col-sm-4">
            </div>
        </div>
    </div>
</form>