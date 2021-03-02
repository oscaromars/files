<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\academico\models\SubareaConocimiento;
use app\modules\academico\models\AreaConocimiento;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_asi" class="col-sm-3 control-label"><?= academico::t("asignatura", "Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_asi" value="<?= $model->asi_nombre ?>" data-type="all" disabled="disabled" placeholder="<?= academico::t("asignatura", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_asi_desc" class="col-sm-3 control-label"><?= academico::t("asignatura", "Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_asi_desc" value="<?= $model->asi_descripcion ?>" data-type="all" disabled="disabled" placeholder="<?= academico::t("asignatura", "Description")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_scon" class="col-sm-3 control-label"><?=  academico::t("asignatura", "Subarea of knowledge") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_asi_scon" value="<?= SubareaConocimiento::findOne($model->scon_id)->scon_nombre ?>" data-type="alfa" disabled="disabled" placeholder="<?=  academico::t("asignatura", "Subarea of knowledge") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_acon" class="col-sm-3 control-label"><?=  academico::t("asignatura", "Knowledge area") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_asi_acon" value="<?= $area_conocimiento_model[0]['acon_nombre'] ?>" data-type="alfa" disabled="disabled" placeholder="<?=  academico::t("asignatura", "Knowledge area") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_asi_status" class="col-sm-3 control-label"><?= academico::t("asignatura", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_asi_status"  value="<?= $model->asi_estado ?>" data-type="number" placeholder="<?= academico::t("asignatura", "Status") ?>">
                <span id="spanAsiStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAsiStatuss" class="<?= ($model->asi_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_asi_id" value="<?= $model->asi_id ?>">