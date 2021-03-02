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
            <input type="text" class="form-control PBvalidation" id="frm_div" value="<?= $model->div_nombre ?>" data-type="all" disabled="disabled" placeholder="<?= financiero::t("divisa", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_div_cod" class="col-sm-3 control-label"><?= financiero::t("divisa", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="2" class="form-control PBvalidation" id="frm_div_cod" value="<?= $model->div_cod ?>" data-type="number" disabled="disabled" placeholder="<?= financiero::t("divisa", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_div_cot" class="col-sm-3 control-label"><?= financiero::t("divisa", "Price") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_div_cot" value="<?= $model->div_cotizacion ?>" data-type="dinero" disabled="disabled" placeholder="<?= financiero::t("divisa", "Price") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="dtp_div_fecha" class="col-sm-3 control-label"><?= financiero::t("tipoarticulo", "Date") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="dtp_div_fecha" value="<?= $model->div_fecha ?>" data-type="all" disabled="disabled" placeholder="<?= financiero::t("tipoarticulo", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_div_status" class="col-sm-3 control-label"><?= financiero::t("divisa", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_div_status" value="<?= $model->div_estado ?>" data-type="number" placeholder="<?= financiero::t("divisa", "Status") ?>">
                <span id="spanDivStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconDivStatuss" class="<?= ($model->div_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_div_id" value="<?= $model->div_id ?>">