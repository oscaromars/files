<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
use app\models\Modulo;
use app\models\ObjetoModulo;
use app\models\Continente;


?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_pais" class="col-sm-3 control-label"><?= Yii::t("pais", "Name of Country") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_pais" value="" data-type="alfa" placeholder="<?= Yii::t("pais", "Name of Country")  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_pais_desc" class="col-sm-3 control-label"><?= Yii::t("pais", "Country Description") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_pais_desc" value="" data-type="alfa" placeholder="<?= Yii::t("pais", "Country Description") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_pais" class="col-sm-3 control-label"><?= Yii::t("pais", "Cont") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_pais", "", $arr_continents, ["class" => "form-control", "id" => "cmb_pais"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_pais_cap" class="col-sm-3 control-label"><?= Yii::t("pais", "Capital") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_pais_cap" value="" data-type="alfa" placeholder="<?= Yii::t("pais", "Capital") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_pais_nac" class="col-sm-3 control-label"><?= Yii::t("pais", "Nationality") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_pais_nac" value="" data-type="all" placeholder="<?= Yii::t("pais", "Nationality") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_pais_iso2" class="col-sm-3 control-label"><?= Yii::t("pais", "Iso 2") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="2" class="form-control PBvalidation" id="frm_pais_iso2" value="" data-type="alfa" placeholder="<?= Yii::t("pais", "Iso 2") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_pais_iso3" class="col-sm-3 control-label"><?= Yii::t("pais", "Iso 3") ?></label>
        <div class="col-sm-9">
            <input type="text" maxlength="3" class="form-control PBvalidation" id="frm_pais_iso3" value="" data-type="alfa" placeholder="<?= Yii::t("pais", "Iso 3") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_pais_cod" class="col-sm-3 control-label"><?= Yii::t("pais", "Code") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_pais_cod" value="" data-type="number" placeholder="<?= Yii::t("pais", "Code") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_pais_status" class="col-sm-3 control-label"><?= Yii::t("pais", "Status Country") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_pais_status" value="0" data-type="number" placeholder="<?= Yii::t("pais", "Status Country") ?>">
                <span id="spanPaisStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconPaisStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>    
</form>