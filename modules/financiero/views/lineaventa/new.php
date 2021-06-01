<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
use app\modules\financiero\Module as financiero;
financiero::registerTranslations();
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_lven" class="col-sm-3 control-label"><?= financiero::t("linea_venta", "Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_lven" value="" data-type="all" placeholder="<?= financiero::t("linea_venta", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_lven_cod" class="col-sm-3 control-label"><?= financiero::t("linea_venta", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="2" class="form-control PBvalidation" id="frm_lven_cod" value="" data-type="number" placeholder="<?= financiero::t("linea_venta", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="dtp_lven_fecha" class="col-sm-3 control-label"><?= financiero::t("linea_venta", "Date") ?></label>
        <div class="col-sm-9">
            <?=
                DateTimePicker::widget([
                    'id' => 'dtp_lven_fecha',
                    'name' => 'dtp_lven_fecha',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'value' => '',
                    'options' => ["class" => "form-control PBvalidation","data-type" => "fecha", "placeholder" => financiero::t("linea_venta", "Date"),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd hh:ii',
                    ]
                ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_lven_status" class="col-sm-3 control-label"><?= financiero::t("linea_venta", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_lven_status" value="0" data-type="number" placeholder="<?= financiero::t("linea_venta", "Status") ?>">
                <span id="spanlvenStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconlvenStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>
</form>