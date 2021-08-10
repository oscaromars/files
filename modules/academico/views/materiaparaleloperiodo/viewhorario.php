<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;

academico::registerTranslations();
admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', $mpp_id, ['id' => 'txth_ids']); ?>

<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-10 col-sm-10 col-xs-10 col-lg-10">
        <div class="form-group">
            <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Cambiar horarios") ?></span></h3>
        </div>
    </div>
    <!-- <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_solicitud" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_solicitud"><?= Yii::t("solicitud_ins", "Application number") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <span id="lbl_solicitud"><?= $sins_id ?></span>
            </div>
        </div>
    </div>-->
    <!-- <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_valor" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="lbl_valor"><?= Yii::t("formulario", "Value") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <?= "$ " . $valor ?>
                <br>
            </div>
        </div>
    </div>-->
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-md-15 ">
                <label for="txt_horarios" class="col-sm-4 control-label"><?= academico::t("Academico", "Schedule") ?></label>
                    <!-- <= Html::dropDownList("cmb_observacion", $observacion, $cmb_observacion, ["class" => "form-control PBvalidation", "id" => "cmb_observacion", "disabled" => "disabled"]) ?> -->
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