<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', $opag_id, ['id' => 'txth_ids']); ?>
<?= Html::hiddenInput('txth_idd', $idd, ['id' => 'txth_idd']); ?>
<?= Html::hiddenInput('txth_int', $int_id, ['id' => 'txth_int']); ?>
<?= Html::hiddenInput('txth_sins', $sins_id, ['id' => 'txth_sins']); ?>
<?= Html::hiddenInput('txth_perid', $per_id, ['id' => 'txth_perid']); ?>
<?= Html::hiddenInput('txth_val', $val, ['id' => 'txth_val']); ?>
<?= Html::hiddenInput('txth_valt', $valtotal, ['id' => 'txth_valt']); ?>
<?= Html::hiddenInput('txth_valp', $valpagado, ['id' => 'txth_valp']); ?>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-10 col-sm-10 col-xs-10 col-lg-10">
        <div class="form-group">
            <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Approval Payment") ?></span></h3>
        </div>
    </div>
    <?php if (base64_decode($_GET['fpag']) == 'Transferencia' || base64_decode($_GET['fpag']) == 'Depósito') { ?>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="txt_solicitud" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_solicitud"><?= admision::t("Solicitudes", "Document") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                    <?php
                    echo "<a href='" . Url::to(['/site/getimage', 'route' => "/uploads/documento/$per_id/$imagen"]) . "' download='" . $imagen . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_solicitud" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_solicitud"><?= Yii::t("solicitud_ins", "Application number") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <span id="lbl_solicitud"><?= $sins_id ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_valor" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label" id="lbl_valor"><?= Yii::t("formulario", "Value") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <?= "$ " . $valor ?>
                <br>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_revision" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label keyupmce"><?= Yii::t("formulario", "Result") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <?php if ($estado == 'PE') { ?>
                    <?= Html::dropDownList("cmb_revision", 0, $revision, ["class" => "form-control PBvalidation", "id" => "cmb_revision"]) ?>
                <?php } ?>
                <?php if ($estado == 'AP') { ?>
                    <input type="text" value= "Aprobado" readonly="readonly" class="form-control" id="txt_observacion" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Observations") ?>">
                <?php } ?>
                <?php if ($estado == 'RE') { ?>
                    <input type="text" value= "Rechazado" readonly="readonly" class="form-control" id="txt_observacion" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Observations") ?>">
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <?php
            if ($estado == 'PE') {
                $estilo = "display: none;";
            }
            ?>
            <div class="col-md-15 " id="Divobservalbl" style="<?php echo $estilo ?>" >
                <label for="txt_observacion" class="col-sm-4 control-label"><?= Yii::t("formulario", "Observations") ?></label>
            </div>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <?php if ($estado == 'PE') { ?>
                    <div class="col-md-15" id="Divobservacmb" style="display: none;" >
                        <?= Html::dropDownList("cmb_observacion", $observacion, $cmb_observacion, ["class" => "form-control PBvalidation", "id" => "cmb_observacion"]) ?>
                    </div>
                <?php } else { ?>
                    <?= Html::dropDownList("cmb_observacion", $observacion, $cmb_observacion, ["class" => "form-control PBvalidation", "id" => "cmb_observacion", "disabled" => "disabled"]) ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">
                <?php if ($estado == 'PE') { ?>
                    <a id="btn_enviar"  class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Send") ?></a>
                <?php } ?>
            </div>
            <div class="col-sm-4">
            </div>
        </div>
    </div>
</form>