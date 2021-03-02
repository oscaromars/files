<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
use app\models\Modulo;
use app\models\ObjetoModulo;
use app\models\Pais;

?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_provincia" class="col-sm-3 control-label"><?= Yii::t("provincia", "Nombre de Provincia") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_provincia" value="<?= $model->pro_nombre ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("provincia", "Name de Provincia")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_provincia_desc" class="col-sm-3 control-label"><?= Yii::t("provincia", "Descripcion de Provincia") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_provincia_desc" value="<?= $model->pro_descripcion ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("provincia", "Country Canton") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_prov_pai" class="col-sm-3 control-label"><?= Yii::t("provincia", "Pais") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_prov_pai" value="<?= Pais::findOne($model->pai_id)->pai_nombre ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("provincia", "Pais") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_cap_pro" class="col-sm-3 control-label"><?= Yii::t("provincia", "Capital") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_cap_pro" value="<?= $model->pro_capital ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("provincia", "Capital") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_provincia_status" class="col-sm-3 control-label"><?= Yii::t("provincia", "Povincia Estado") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_provincia_status" value="<?= $model->pro_estado ?>" data-type="number" placeholder="<?= Yii::t("provincia", "Estado Provincia") ?>">
                <span id="spanProvinciasStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconProvinciaStatuss" class="<?= ($model->pro_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>    
</form>
<input type="hidden" id="frm_provincia_id" value="<?= $model->pro_id ?>">