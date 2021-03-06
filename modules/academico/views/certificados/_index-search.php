<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as Especies;

admision::registerTranslations();
Especies::registerTranslations();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_buscarDataCertificado" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarDataCertificado" placeholder="<?= Yii::t("formulario", "Search by Names") ." o ". Yii::t("formulario", "Identification document")?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_inicio" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Start date") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_ini',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_ini", "placeholder" => Yii::t("formulario", "Start date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
            <label for="lbl_fin" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "End date") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_fin',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_fin", "placeholder" => Yii::t("formulario", "End date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">   
        <div class="form-group">
            <label for="lbl_unidad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_unidad"><?= Especies::t("Especies", "Academic unit") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">                                      
                <?= Html::dropDownList("cmb_unidad_cer", 0, array_merge([Yii::t("formulario", "All")], $arr_unidad), ["class" => "form-control", "id" => "cmb_unidad_cer"]) ?>                                       
            </div>

            <label for="lbl_modalidad_cer" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Especies::t("Academico", "Modality") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">                                
                <?= Html::dropDownList("cmb_modalidad_cer", 0, array_merge([Yii::t("formulario", "All")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad_cer"]) ?>
            </div>            
        </div>
    </div>    
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">   
        <div class="form-group">
            <label for="lbl_estadocertificado" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_tramite"><?= especies::t("Especies", "Certified Status") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">   
                <?=
                Html::dropDownList(
                        "cmb_estadocertificado_cer", -1, $arrEstados, ["class" => "form-control", "id" => "cmb_estadocertificado_cer"]
                )
                ?></div> 
        </div>
    </div>    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarCertificado" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div></br>

