<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
use app\models\Modulo;
use app\models\ObjetoModulo;

?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_grupo" class="col-sm-3 control-label"><?= Yii::t("grupo", "Name of Group") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_grupo" value="" data-type="all" placeholder="<?= Yii::t("grupo", "Name of Group")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_grupo_desc" class="col-sm-3 control-label"><?= Yii::t("grupo", "Group Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_grupo_desc" value="" data-type="all" placeholder="<?= Yii::t("grupo", "Group Description") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_grupo_seg" class="col-sm-3 control-label"><?= Yii::t("grupo", "Security Configuration") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_grupo_seg", "", $arr_seguridad, ["class" => "form-control", "id" => "cmb_grupo_seg"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_grupo_status" class="col-sm-3 control-label"><?= Yii::t("grupo", "Status Group") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_grupo_status" value="0" data-type="number" placeholder="<?= Yii::t("grupo", "Status Group") ?>">
                <span id="spanGrupStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconGrupStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_roles" class="col-sm-3 control-label"><?= Yii::t("rol", "Add Role to Group") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_roles", "", $arr_roles, ["class" => "form-control", "id" => "cmb_roles", "multiple" => ""]) ?>
        </div>
    </div>
</form>