<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_asi" class="col-sm-3 control-label"><?= academico::t("asignatura", "Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_asi" value="" data-type="all" placeholder="<?= academico::t("asignatura", "Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_asi_desc" class="col-sm-3 control-label"><?= academico::t("asignatura", "Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_asi_desc" value="" data-type="all" placeholder="<?= academico::t("asignatura", "Description")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_unidad" class="col-sm-3 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_unidad", "", $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_scon" class="col-sm-3 control-label"><?= academico::t("asignatura", "Subarea of knowledge") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_scon", "", $arr_scon, ["class" => "form-control", "id" => "cmb_scon"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_acon" class="col-sm-3 control-label"><?= academico::t("asignatura", "Knowledge area") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_acon", "", $arr_acon, ["class" => "form-control", "id" => "cmb_acon"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_asi_status" class="col-sm-3 control-label"><?= academico::t("asignatura", "Status") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_asi_status" value="0" data-type="number" placeholder="<?= academico::t("asignatura", "Status") ?>">
                <span id="spanAsiStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAsiStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>
</form>