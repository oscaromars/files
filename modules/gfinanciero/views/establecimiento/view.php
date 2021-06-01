<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();
?>

<form class="form-horizontal">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_codigo" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("establecimiento", "Code") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->COD_PTO ?>" id="frm_codigo" data-type="all" disabled="disabled" placeholder="<?= financiero::t("establecimiento", "Code") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_fecha" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("establecimiento", "Date") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?=
                    DatePicker::widget([
                        'name' => 'frm_fecha',
                        'value' => date(Yii::$app->params["dateByDefault"], strtotime($model->FEC_PTO)),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fecha", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => financiero::t("establecimiento", "Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?> 
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_nombre" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("establecimiento", "Name") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->NOM_PTO ?>" id="frm_nombre" data-type="all" disabled="disabled" placeholder="<?= financiero::t("establecimiento", "Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_correoct" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("establecimiento", "Mail") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->CORRE_CT ?>" id="frm_correoct" data-type="mail" disabled="disabled" placeholder="<?= financiero::t("establecimiento", "Mail") ?>"> 
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cmb_pais" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("localidad", "Country") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">                    
                    <?= Html::dropDownList("cmb_pais", $pais_def, $arr_pais, ["class" => "form-control", "id" => "cmb_pais", "disabled" => "disabled",]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_provincia" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("localidad", "State") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_provincia", $provincia_def, $arr_provincia, ["class" => "form-control", "id" => "cmb_provincia", "disabled" => "disabled",]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_canton" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("localidad", "City") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_ciudad", $ciudad_def, $arr_ciudad, ["class" => "form-control", "id" => "cmb_ciudad", "disabled" => "disabled",]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_direccion" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("establecimiento", "Address") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->DIR_PTO ?>" id="frm_direccion" data-type="all" disabled="disabled" placeholder="<?= financiero::t("establecimiento", "Address") ?>"> 
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_telefono1" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("establecimiento", "Telephone 1") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->TEL_N01 ?>" id="frm_telefono1" data-type="all" disabled="disabled" placeholder="<?= financiero::t("establecimiento", "Telephone 1") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_telefono2" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("establecimiento", "Telephone 2") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->TEL_N02 ?>" id="frm_telefono2" data-type="all" disabled="disabled" placeholder="<?= financiero::t("establecimiento", "Telephone 2") ?>"> 
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_telefonofax" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("establecimiento", "Telephone Fax") ?> </label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->NUM_FAX ?>" id="frm_telefonofax" data-type="all" disabled="disabled" placeholder="<?= financiero::t("establecimiento", "Telephone Fax") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            </div>
        </div>
    </div> 
</form>

<input type="hidden" id="frm_id" value="<?= $model->COD_PTO ?>">