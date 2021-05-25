<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
use app\models\Modulo;
use app\models\ObjetoModulo;

?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_accion" class="col-sm-3 control-label"><?= Yii::t("accion", "Action Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_accion" value="<?= $model->acc_nombre ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("accion", "Action Name")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_acc_desc" class="col-sm-3 control-label"><?= Yii::t("accion", "Action Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_acc_desc" value="<?= $model->acc_descripcion ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("accion", "Action Description") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_acc_type" class="col-sm-3 control-label"><?= Yii::t("accion", "Type of Action") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_acc_type" value="<?= $model->acc_tipo ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("accion", "Type of Action") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_acc_url" class="col-sm-3 control-label"><?= Yii::t("accion", "Link to Action") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_acc_url" value="<?= $model->acc_url_accion ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("accion", "Link to Action") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_acc_image" class="col-sm-3 control-label"><?= Yii::t("accion", "Action Image") ?></label>
        <div class="col-sm-9">
            <div class="input-group">
                <input type="text" class="form-control PBvalidation" id="frm_acc_image" value="<?= $model->acc_dir_imagen ?>" data_alias="<?= $model->acc_dir_imagen ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("accion", "Action Image") ?>">
                <span class="input-group-addon"><i id="iconMod" class="<?= $model->acc_dir_imagen ?>"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_acc_lang" class="col-sm-3 control-label"><?= Yii::t("modulo", "Language File") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_acc_lang" value="<?= $model->acc_lang_file ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("modulo", "Language File") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_acc_status" class="col-sm-3 control-label"><?= Yii::t("accion", "Status Action") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_omod_status" value="<?= $model->acc_estado ?>" data-type="number" placeholder="<?= Yii::t("accion", "Status Action") ?>">
                <span id="spanModStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconModStatuss" class="<?= ($model->acc_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_acc_id" value="<?= $model->acc_id ?>">