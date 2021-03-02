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
            <input type="text" class="form-control PBvalidation" id="frm_umed" value="" data-type="all" placeholder="<?= financiero::t("unidad_medida", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_umed_cod" class="col-sm-3 control-label"><?= financiero::t("unidad_medida", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="2" class="form-control PBvalidation" id="frm_umed_cod" value="" data-type="number" placeholder="<?= financiero::t("unidad_medida", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_umed_med" class="col-sm-3 control-label"><?= financiero::t("unidad_medida", "Measure") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="2" class="form-control PBvalidation" id="frm_umed_med" value="" data-type="text" placeholder="<?= financiero::t("unidad_medida", "Measure") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_umed_fecha" class="col-sm-3 control-label"><?= financiero::t("unidad_medida", "Date") ?></label>
        <div class="col-sm-9">
            <?=
                DateTimePicker::widget([
                    'id' => 'frm_umed_fecha',
                    'name' => 'frm_umed_fecha',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'value' => '',
                    'options' => ["class" => "form-control PBvalidation","data-type" => "fecha", "placeholder" => financiero::t("unidad_medida", "Date"),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd hh:ii',
                    ]
                ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_umed_status" class="col-sm-3 control-label"><?= financiero::t("unidad_medida", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_umed_status" value="0" data-type="number" placeholder="<?= financiero::t("unidad_medida", "Status") ?>">
                <span id="spanUmedStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconUmedStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>
</form>