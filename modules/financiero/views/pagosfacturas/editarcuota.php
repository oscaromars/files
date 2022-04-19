<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;

?>

<?= Html::hiddenInput('txth_per_ids', base64_encode($perids), ['id' => 'txth_per_ids']); ?>
<?= Html::hiddenInput('txth_ccar_id', $cuota['ccar_id'], ['id' => 'txth_ccar_id']); ?>
<?= Html::hiddenInput('txth_est_id', $cuota['est_id'], ['id' => 'txth_est_id']); ?>
<?= Html::hiddenInput('txth_num_doc', $cuota['ccar_numero_documento'], ['id' => 'txth_num_doc']); ?>
<?= Html::hiddenInput('txth_estado', $cuota['ccar_estado_cancela'], ['id' => 'txth_estado']); ?>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-md-10 col-sm-10 col-xs-10 col-lg-10">
        <div class="form-group">
            <h3><span id="lbl_Personeria"><?= financiero::t("Pagos", "Modificar Cuota") ?></span></h3>
        </div>
    </div>
    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_solicitud" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_solicitud"><?= Yii::t("solicitud_ins", "Valor Cuota") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="<?php echo $cuota['ccar_valor_cuota'] ?>" id="txt_valorCuota" placeholder="<?= Yii::t("formulario", "Valor Cuota") ?>">
                <!--<span id="lbl_solicitud"><?= $cuota['ccar_valor_cuota'] ?></span>-->
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_solicitud" class="col-sm-4 col-md-4 col-xs-4 col-lg-4 control-label" id="lbl_solicitud"><?= Yii::t("solicitud_ins", "Fecha de vencimiento de pago") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fechaVencimiento',
                    'value' => $cuota['ccar_fecha_vencepago'],
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fechaVencimiento", "placeholder" => Yii::t("formulario", "Fecha de vencimiento")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
                <!--<input type="text" class="form-control" value="<?php echo $cuota['ccar_fecha_vencepago'] ?>" id="txt_fechaVencimiento" placeholder="<?= Yii::t("formulario", "Fecha de vencimiento de pago") ?>">-->
                <!--<span id="lbl_solicitud"><?= $cuota['ccar_fecha_vencepago'] ?></span>-->
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">
                <a id="btn_actualizar"  class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Actualizar") ?></a>
            </div>
            <div class="col-sm-4">
            </div>
        </div>
    </div>
</form>