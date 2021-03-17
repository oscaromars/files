<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module as admision;
use app\modules\financiero\Module as financiero;

admision::registerTranslations();
financiero::registerTranslations();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_buscarDatacartera" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-xs-8 col-md-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarDatacartera" placeholder="<?= Yii::t("formulario", "Search by Names") . ' o ' . Yii::t("formulario", "DNI 1")?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_iniciofact" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("Pagos", "Date Bill") . ' ' . Yii::t("formulario", "Start")?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_inifact',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_inifact", "placeholder" => financiero::t("Pagos", "Date Bill") . ' ' . Yii::t("formulario", "Start")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
            <label for="lbl_finfact" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("Pagos", "Date Bill") . ' ' . Yii::t("formulario", "End") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_finfact',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_finfact", "placeholder" => financiero::t("Pagos", "Date Bill") . ' ' . Yii::t("formulario", "End")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>            
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_iniciofact" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("Pagos", "Expiration date") . ' ' . Yii::t("formulario", "Start")?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_inifact',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_inifact", "placeholder" => financiero::t("Pagos", "Expiration date") . ' ' . Yii::t("formulario", "Start")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
            <label for="lbl_finfactve" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= financiero::t("Pagos", "Expiration date") . ' ' . Yii::t("formulario", "End") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_finfactve',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_finfactve", "placeholder" => financiero::t("Pagos", "Expiration date") . ' ' . Yii::t("formulario", "End")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>            
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">       
            <div class="form-group">
                <label for="cmb_estadocartera" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Status") ?>  </label>
                <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                    <?= Html::dropDownList("cmb_estadocartera", 0, $arrEstados, ["class" => "form-control", "id" => "cmb_estadocartera"]) ?>
                </div>
            </div>     
    </div>
</div>

