<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
use app\models\Modulo;
use app\models\ObjetoModulo;

?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_rol" class="col-sm-3 control-label"><?= Yii::t("rol", "Name of Role") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_rol" value="<?= $model->rol_nombre ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("rol", "Name of Role")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_rol_desc" class="col-sm-3 control-label"><?= Yii::t("rol", "Role Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_rol_desc" value="<?= $model->rol_descripcion ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("rol", "Role Description") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_rol_status" class="col-sm-3 control-label"><?= Yii::t("rol", "Status Role") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_rol_status" value="<?= $model->rol_estado ?>" data-type="number" placeholder="<?= Yii::t("rol", "Status Role") ?>">
                <span id="spanRolStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconRolStatuss" class="<?= ($model->rol_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_rol_id" value="<?= $model->rol_id ?>">