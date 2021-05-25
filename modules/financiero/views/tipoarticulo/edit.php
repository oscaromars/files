<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
use app\modules\financiero\Module as financiero;
financiero::registerTranslations();
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_tart" class="col-sm-3 control-label"><?= financiero::t("tipoarticulo", "Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_tart" value="<?= $model->tart_nombre ?>" data-type="all" placeholder="<?= financiero::t("tipoarticulo", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_tart_cod" class="col-sm-3 control-label"><?= financiero::t("tipoarticulo", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_tart_cod" value="<?= $model->tart_cod ?>" data-type="number" placeholder="<?= financiero::t("tipoarticulo", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="dtp_tart_fecha" class="col-sm-3 control-label"><?= financiero::t("tipoarticulo", "Date") ?></label>
        <div class="col-sm-9">
            <?=
            DateTimePicker::widget([
                'id' => 'dtp_tart_fecha',
                'name' => 'dtp_tart_fecha',
                'type' => DateTimePicker::TYPE_INPUT,
                'value' => $model->tart_fecha,
                'options' => ["class" => "form-control PBvalidation","data-type" => "fecha", "placeholder" => financiero::t("tipoarticulo", "Date"),],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd hh:ii',
                ]
            ]);            
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_tart_status" class="col-sm-3 control-label"><?= financiero::t("tipoarticulo", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_tart_status" value="<?= $model->tart_estado ?>" data-type="number" placeholder="<?= financiero::t("tipoarticulo", "Status") ?>">
                <span id="spanTartStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconTartStatus" class="<?= ($model->tart_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_tart_id" value="<?= $model->tart_id ?>">