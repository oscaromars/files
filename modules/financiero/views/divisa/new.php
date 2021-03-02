<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
use app\modules\financiero\Module as financiero;
financiero::registerTranslations();
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_div" class="col-sm-3 control-label"><?= financiero::t("divisa", "Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_div" value="" data-type="all" placeholder="<?= financiero::t("divisa", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_div_cod" class="col-sm-3 control-label"><?= financiero::t("divisa", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="2" class="form-control PBvalidation" id="frm_div_cod" value="" data-type="number" placeholder="<?= financiero::t("divisa", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_div_cot" class="col-sm-3 control-label"><?= financiero::t("divisa", "Price") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_div_cot" value="" data-type="dinero" placeholder="<?= financiero::t("divisa", "Price") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="dtp_div_fecha" class="col-sm-3 control-label"><?= financiero::t("divisa", "Date") ?></label>
        <div class="col-sm-9">
            <?=
                DateTimePicker::widget([
                    'id' => 'dtp_div_fecha',
                    'name' => 'dtp_div_fecha',
                    'type' => DateTimePicker::TYPE_INPUT,
                    'value' => '',
                    'options' => ["class" => "form-control PBvalidation","data-type" => "fecha", "placeholder" => financiero::t("divisa", "Date"),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd hh:ii',
                    ]
                ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_div_status" class="col-sm-3 control-label"><?= financiero::t("divisa", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_div_status" value="0" data-type="number" placeholder="<?= financiero::t("divisa", "Status") ?>">
                <span id="spanDivStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconDivStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>
</form>