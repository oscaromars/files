<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
use app\models\Modulo;
use app\models\ObjetoModulo;


?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_provincia" class="col-sm-3 control-label"><?= Yii::t("provincia", "Name of Provincia") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_provincia" value="" data-type="alfa" placeholder="<?= Yii::t("provincia", "Name of Provincia")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_provincia_desc" class="col-sm-3 control-label"><?= Yii::t("provincia", "Provincia Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_provincia_desc" value="" data-type="alfa" placeholder="<?= Yii::t("Provincia", "Country Provincia") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_pais" class="col-sm-3 control-label"><?= Yii::t("provincia", "Paises") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_pais", "", $arr_pais, ["class" => "form-control", "id" => "cmb_pais"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_provincia_capital" class="col-sm-3 control-label"><?= Yii::t("provincia", "Capital") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_provincia_capital" value="" data-type="alfa" placeholder="<?= Yii::t("provincia", "Capital") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_provincia_status" class="col-sm-3 control-label"><?= Yii::t("provincia", "Status Provincia") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_provincia_status" value="0" data-type="number" placeholder="<?= Yii::t("provincia", "Status Provincia") ?>">
                <span id="spanProvinciaStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconProvinciaStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>    
</form>