<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
use app\modules\financiero\Module as financiero;
financiero::registerTranslations();
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_mart" class="col-sm-3 control-label"><?= financiero::t("marca_articulo", "Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_mart" value="" data-type="all" placeholder="<?= financiero::t("marca_articulo", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mart_cod" class="col-sm-3 control-label"><?= financiero::t("marca_articulo", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="2" class="form-control PBvalidation" id="frm_mart_cod" value="" data-type="number" placeholder="<?= financiero::t("marca_articulo", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="dtp_mart_fecha" class="col-sm-3 control-label"><?= financiero::t("marca_articulo", "Date") ?></label>
        <div class="col-sm-9">
            <?=
                DateTimePicker::widget([
                    'id' => 'dtp_mart_fecha',
                    'name' => 'dtp_mart_fecha',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'value' => '',
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
        <label for="frm_mart_status" class="col-sm-3 control-label"><?= financiero::t("marca_articulo", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_mart_status" value="0" data-type="number" placeholder="<?= financiero::t("marca_articulo", "Status") ?>">
                <span id="spanmartStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconmartStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>
</form>