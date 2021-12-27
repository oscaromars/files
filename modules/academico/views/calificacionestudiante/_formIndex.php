<?php

use yii\helpers\Html;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
            <br>
                <label for="cmb_unidad_bus" class="col-lg-4 col-md-4 col-sm-3 col-xs-3 control-label"><?=Yii::t("formulario", "Academic unit")?></label>
                <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                        <?=Html::dropDownList("cmb_unidad_bus", 0, $arr_ninteres, ["class" => "form-control", "id" => "cmb_unidad_bus"])?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
            <br>
                <label for="cmb_modalidad_bus" class="col-lg-4 col-md-4 col-sm-3 col-xs-3 control-label"><?=Yii::t("formulario", "Modality")?></label>
                <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                        <?=Html::dropDownList("cmb_modalidad_bus", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad_bus"])?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="cmb_carrera_bus" class="col-lg-4 col-md-4 col-sm-3 col-xs-3 control-label"><?=Yii::t("academico", "Program")?></label>
                <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                        <?=Html::dropDownList("cmb_carrera_bus", 0, $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera_bus"])?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="cmb_periodo" class="col-lg-4 col-md-4 col-sm-3 col-xs-3 control-label"><?=Yii::t("formulario", "Period")?></label>
                <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                    <?=Html::dropDownList("cmb_periodo", 0, $arr_periodoActual, ["class" => "form-control", "id" => "cmb_periodo"])?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8"></div>
            <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">
                <a id="btn_buscarDataestClfcns" href="javascript:" class="btn btn-primary btn-block"> <?=Yii::t("formulario", "Search")?></a>
            </div>
        </div>
    </div>

</div>

