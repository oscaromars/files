<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
use app\modules\financiero\Module as financiero;
financiero::registerTranslations();
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_umed" class="col-sm-3 control-label"><?= financiero::t("unidad_medida", "Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_umed" value="<?= $model->umed_nombre ?>" data-type="text" placeholder="<?= financiero::t("unidad_medida", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_umed_cod" class="col-sm-3 control-label"><?= financiero::t("unidad_medida", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="2" class="form-control PBvalidation" id="frm_umed_cod" value="<?= $model->umed_cod ?>" data-type="number" placeholder="<?= financiero::t("unidad_medida", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_umed_med" class="col-sm-3 control-label"><?= financiero::t("unidad_medida", "Medida") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_umed_med" value="<?= $model->umed_medida ?>" data-type="all" placeholder="<?= financiero::t("unidad_medida", "Medida")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_umed_fecha" class="col-sm-3 control-label"><?= financiero::t("marca_articulo", "Date") ?></label>
        <div class="col-sm-9">
            <?=
                DateTimePicker::widget([
                    'id' => 'frm_umed_fecha',
                    'name' => 'frm_umed_fecha',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'value' => $model->umed_fecha,
                    'options' => ["class" => "form-control PBvalidation","data-type" => "fecha", "placeholder" => financiero::t("marca_articulo", "Date"),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd hh:ii',
                    ]
                ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_med_status" class="col-sm-3 control-label"><?= financiero::t("unidad_medida", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_umed_status" value="<?= $model->umed_estado ?>" data-type="number" placeholder="<?= financiero::t("unidad_medida", "Status") ?>">
                <span id="spanUmedStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconUmedStatus" class="<?= ($model->umed_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_umed_id" value="<?= $model->umed_id ?>">