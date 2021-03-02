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
            <input type="text" class="form-control PBvalidation" id="frm_asi" value="<?= $model->asi_nombre ?>" data-type="all" placeholder="<?= academico::t("asignatura", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_asi_desc" class="col-sm-3 control-label"><?= academico::t("asignatura", "Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_asi_desc" value="<?= $model->asi_descripcion ?>" data-type="all" placeholder="<?= academico::t("asignatura", "Description")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_scon" class="col-sm-3 control-label"><?= academico::t("asignatura", "Subarea of knowledge") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_scon", $model->scon_id, $arr_scon, ["class" => "form-control", "id" => "cmb_scon"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_acon" class="col-sm-3 control-label"><?= academico::t("asignatura", "Knowledge area") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_acon", $scon_id, $arr_acon, ["class" => "form-control", "id" => "cmb_acon"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_asi_status" class="col-sm-3 control-label"><?= academico::t("asignatura", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_asi_status"  value="<?= $model->asi_estado ?>" data-type="number" placeholder="<?= academico::t("asignatura", "Status") ?>">
                <span id="spanAsiStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAsiStatus" class="<?= ($model->asi_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_asi_id" value="<?= $model->asi_id ?>">