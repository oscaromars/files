<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\financiero\Module as financiero;
financiero::registerTranslations();
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_tart" class="col-sm-3 control-label"><?= financiero::t("tipoarticulo", "Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_tart" value="<?= $model->tart_nombre ?>" data-type="all" disabled="disabled" placeholder="<?= financiero::t("tipoarticulo", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_tart_cod" class="col-sm-3 control-label"><?= financiero::t("tipoarticulo", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_tart_cod" value="<?= $model->tart_cod ?>" data-type="number" disabled="disabled" placeholder="<?= financiero::t("tipoarticulo", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="dtp_tart_fecha" class="col-sm-3 control-label"><?= financiero::t("tipoarticulo", "Date") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="dtp_tart_fecha" value="<?= $model->tart_fecha ?>" data-type="all" disabled="disabled" placeholder="<?= financiero::t("tipoarticulo", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_tart_status" class="col-sm-3 control-label"><?= financiero::t("tipoarticulo", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_tart_status" value="<?= $model->tart_estado ?>" data-type="number" placeholder="<?= financiero::t("tipoarticulo", "Status") ?>">
                <span id="spanTartStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconTartStatuss" class="<?= ($model->tart_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_tart_id" value="<?= $model->tart_id ?>">