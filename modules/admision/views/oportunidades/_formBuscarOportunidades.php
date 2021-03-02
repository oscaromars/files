<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\admision\Module;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <!-- <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-12">
           <div class="form-group">
                <label for="txt_buscarDataAgente" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Executive") ?></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                    <input type="text" class="form-control" value="" id="txt_buscarDataAgente" placeholder="<?= Yii::t("formulario", "Search by Agent Names") ?>">
                </div>
            </div>
        </div> 
    </div> --> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-12">
            <div class="form-group">
                <label for="txt_buscarDataPersona" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Module::t("crm", "Contact") ?></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                    <input type="text" class="form-control" value="" id="txt_buscarDataPersona" placeholder="<?= Yii::t("formulario", "Search by Contact Names") ?>">
                </div>
            </div>
        </div> 
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_inicio" class="col-sm-4 control-label"><?= Yii::t("formulario", "Registration Date Start")?></label>
                <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_registro_ini',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_registro_ini", "placeholder" => Yii::t("formulario", "Registration Date Start")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
            <label for="lbl_fin" class="col-sm-4 control-label"><?= Yii::t("formulario", "Registration Date End") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_registro_fin',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_registro_fin", "placeholder" => Yii::t("formulario", "Registration Date End")],
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
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_inicio" class="col-sm-4 control-label"><?= Yii::t("formulario", "Date Next attention Start")?></label>
                <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_proxima_ini',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_proxima_ini", "placeholder" => Yii::t("formulario", "Date Next attention Start")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
            <label for="lbl_fin" class="col-sm-4 control-label"><?= Yii::t("formulario", "Date Next attention End") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_proxima_fin',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_proxima_fin", "placeholder" => Yii::t("formulario", "Date Next attention Start")],
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
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>          
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_estadop" class="col-sm-4 control-label"><?= Yii::t("formulario", "Status") ?>  </label>
                <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                    <?= Html::dropDownList("cmb_estadop", 0, $arr_estgestion, ["class" => "form-control", "id" => "cmb_estadop"]) ?>                     
                </div>
            </div>
        </div>  
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_empresa" class="col-sm-4 control-label"><?= Yii::t("formulario", "Company") ?>  </label>
                <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                    <?= Html::dropDownList("cmb_empresa", 0, $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa"])  ?>                     
                </div>
            </div>
        </div> 
    </div>     
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8"></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
                <a id="btn_buscarGestion" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
            </div>
        </div>
    </div></div></br>
