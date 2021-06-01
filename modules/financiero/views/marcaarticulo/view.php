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
            <input type="text" class="form-control PBvalidation" id="frm_mart" value="<?= $model->mart_nombre ?>" data-type="all" disabled="disabled" placeholder="<?= financiero::t("marca_articulo", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mart_cod" class="col-sm-3 control-label"><?= financiero::t("marca_articulo", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="2" class="form-control PBvalidation" id="frm_mart_cod" value="<?= $model->mart_cod ?>" data-type="number" disabled="disabled" placeholder="<?= financiero::t("marca_articulo", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="dtp_mart_fecha" class="col-sm-3 control-label"><?= financiero::t("marca_articulo", "Date") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="dtp_mart_fecha" value="<?= $model->mart_fecha ?>" data-type="all" disabled="disabled" placeholder="<?= financiero::t("marca_articulo", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mart_status" class="col-sm-3 control-label"><?= financiero::t("marca_articulo", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_mart_status" value="<?= $model->mart_estado ?>" data-type="number" placeholder="<?= financiero::t("marca_articulo", "Status") ?>">
                <span id="spanmartStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconmartStatuss" class="<?= ($model->mart_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_mart_id" value="<?= $model->mart_id ?>">