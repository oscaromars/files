<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
use app\models\Modulo;
use app\models\ObjetoModulo;
use app\models\Provincia;


?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_canton" class="col-sm-3 control-label"><?= Yii::t("canton", "Name of Canton") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_canton" value="<?= $model->can_nombre ?>" data-type="alfa" placeholder="<?= Yii::t("canton", "Name of Canton")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_canton_desc" class="col-sm-3 control-label"><?= Yii::t("canton", "Canton Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_canton_desc" value="<?= $model->can_descripcion ?>" data-type="alfa" placeholder="<?= Yii::t("canton", "Country Canton") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_provincia" class="col-sm-3 control-label"><?= Yii::t("canton", "Province") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_provincia", $model->pro_id, $arr_provinces, ["class" => "form-control", "id" => "cmb_provincia"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_canton_status" class="col-sm-3 control-label"><?= Yii::t("canton", "Status Canton") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_canton_status" value="<?= $model->can_estado ?>" data-type="number" placeholder="<?= Yii::t("canton", "Status Canton") ?>">
                <span id="spanCantonesStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconCantonStatus" class="<?= ($model->can_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>    
</form>
<input type="hidden" id="frm_canton_id" value="<?= $model->can_id ?>">