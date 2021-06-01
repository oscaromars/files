<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
use app\models\Modulo;
use app\models\ObjetoModulo;
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_omodulo" class="col-sm-3 control-label"><?= Yii::t("modulo", "SubModule Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_omodulo" value="" data-type="all" placeholder="<?= Yii::t("modulo", "SubModule Name") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_modulo" class="col-sm-3 control-label"><?= Yii::t("modulo", "Module Name") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_modulo", "", $arr_modulos, ["class" => "form-control", "id" => "cmb_modulo"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_omod_type" class="col-sm-3 control-label"><?= Yii::t("modulo", "Type of SubModule") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_omod_type", "", $arr_typeObjMod, ["class" => "form-control", "id" => "cmb_omod_type"]) ?>
        </div>
    </div>
    <div class="form-group hideSubModulo">
        <label for="cmb_omod_padre" class="col-sm-3 control-label"><?= Yii::t("modulo", "SubModule Main") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_omod_padre", "", $arr_omodulos, ["class" => "form-control", "id" => "cmb_omod_padre"]) ?>
        </div>
    </div>
    <div class="form-group" style="display: none;">
        <label for="cmb_omod_type_btn" class="col-sm-3 control-label"><?= Yii::t("modulo", "Type of Button") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_omod_type_btn", "", $arr_typeBtnObjMod, ["class" => "form-control", "id" => "cmb_omod_type_btn"]) ?>
        </div>
    </div>
    <div class="form-group" style="display: none;">
        <label for="frm_omod_acc" class="col-sm-3 control-label"><?= Yii::t("modulo", "Type of Action") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidations" id="frm_omod_acc" value="" data-type="all" placeholder="<?= Yii::t("modulo", "Type of Action") ?>">
        </div>
    </div>
    <div class="form-group" style="display: none;">
        <label for="frm_omod_fn" class="col-sm-3 control-label"><?= Yii::t("modulo", "Function JS to Execute") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidations" id="frm_omod_fn" value="" data-type="all" placeholder="<?= Yii::t("modulo", "Function JS to Execute") ?>">
        </div>
    </div>
    <div class="form-group" style="display: none;">
        <label for="frm_omod_image" class="col-sm-3 control-label"><?= Yii::t("modulo", "Image") ?></label>
        <div class="col-sm-9">
            <div class="input-group">
                <input type="text" class="form-control PBvalidations" id="frm_omod_image" value="" data_alias="" data-type="all" placeholder="<?= Yii::t("modulo", "Image") ?>">
                <span class="input-group-addon"><i id="iconOMod" class=""></i></span>
            </div>
        </div>
    </div>
    <div class="form-group hideActions">
        <label for="cmb_acc_type" class="col-sm-3 control-label"><?= Yii::t("accion", "Type of Action") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_acc_type", "", $arr_acciones, ["class" => "form-control", "id" => "cmb_acc_type"]) ?>
        </div>
    </div>
    <div class="form-group hideActions">
        <label for="cmb_acc_type_btn" class="col-sm-3 control-label"><?= Yii::t("modulo", "Type of Button") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_acc_type_btn", "", $arr_typeBtnObjMod, ["class" => "form-control", "id" => "cmb_acc_type_btn"]) ?>
        </div>
    </div>
    <div class="form-group hideJsFn">
        <label for="frm_acc_fn" class="col-sm-3 control-label"><?= Yii::t("modulo", "Function JS to Execute") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidations" id="frm_acc_fn" value="" data-type="all" placeholder="<?= Yii::t("modulo", "Function JS to Execute") . " ex: save" ?>">
        </div>
    </div>
    <div class="form-group hideLk">
        <label for="frm_acc_lk" class="col-sm-3 control-label"><?= Yii::t("accion", "Link to Action") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidations" id="frm_acc_lk" value="" data-type="all" placeholder="<?= Yii::t("accion", "Link to Action") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_omod_url" class="col-sm-3 control-label"><?= Yii::t("modulo", "SubModule Link Entity") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="frm_omod_url" value="" data-type="all" placeholder="<?= Yii::t("modulo", "SubModule Link Entity") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_omod_orden" class="col-sm-3 control-label"><?= Yii::t("modulo", "Position SubModule") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_omod_orden" value="" data-type="number" placeholder="<?= Yii::t("modulo", "Position SubModule") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_omod_visibility" class="col-sm-3 control-label"><?= Yii::t("modulo", "Visibility SubModule") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_omod_visibility" value="0" data-type="number" placeholder="<?= Yii::t("modulo", "Visibility SubModule") ?>">
                <span id="spanOModVisib" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconOModVisib" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_omod_lang" class="col-sm-3 control-label"><?= Yii::t("modulo", "Language File") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_omod_lang" value="" data-type="all" placeholder="<?= Yii::t("modulo", "Language File") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_omod_status" class="col-sm-3 control-label"><?= Yii::t("modulo", "Status SubModule") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_omod_status" value="0" data-type="number" placeholder="<?= Yii::t("modulo", "Status SubModule") ?>">
                <span id="spanOModStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconOModStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>
</form>
