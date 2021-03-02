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
        <label for="frm_provincia" class="col-sm-3 control-label"><?= Yii::t("provincia", "Name of Provincia") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_provincia" value="<?= $model->pro_nombre ?>" data-type="alfa" placeholder="<?= Yii::t("provincia", "Name of Canton")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_provincia_desc" class="col-sm-3 control-label"><?= Yii::t("provincia", "Provincia Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_provincia_desc" value="<?= $model->pro_descripcion ?>" data-type="alfa" placeholder="<?= Yii::t("provincia", "Country Provincia") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_pais" class="col-sm-3 control-label"><?= Yii::t("pais", "Contry") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_pais", $model->pai_id, $arr_pais, ["class" => "form-control", "id" => "cmb_pais"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_provincia_capital" class="col-sm-3 control-label"><?= Yii::t("provincia", "Name of Capital") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_provincia_capital" value="<?= $model->pro_capital ?>" data-type="alfa" placeholder="<?= Yii::t("provincia", "Name of Capital")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_provincia_status" class="col-sm-3 control-label"><?= Yii::t("provincia", "Status Provincia") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_provincia_status" value="<?= $model->pro_estado ?>" data-type="number" placeholder="<?= Yii::t("provincia", "Status Provincia") ?>">
                <span id="spanProvinciaStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconProvinciaStatus" class="<?= ($model->pro_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>    
</form>
<input type="hidden" id="frm_provincia_id" value="<?= $model->pro_id ?>">