<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
academico::registerTranslations();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="txt_unidad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="<?php echo $arr_cabecera[0]["unidad"] ?>" id="txt_unidad" disabled = "true" placeholder="<?= academico::t("Academico", "Academic unit") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="txt_modalidad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Modality") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <?php for ($a = 0; $a < count($arr_cabecera); $a++) { ?>
                        <input type="text" class="form-control" value="<?php echo $arr_cabecera[$a]["modalidad"] ?>" id="txt_modalidad" disabled = "true" placeholder="<?= academico::t("Academico", "Modality") ?>">
                    <?php } ?>                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="txt_carrera_programa" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Career/Program") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="<?php echo $arr_cabecera[0]["carrera_programa"] ?>" id="txt_carrera" disabled = "true" placeholder="<?= academico::t("Academico", "Career/Program") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="txt_malla" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Academic Mesh") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="<?php echo $arr_cabecera[0]["malla"] ?>" id="txt_carrera" disabled = "true" placeholder="<?= academico::t("Academico", "Academic Mesh") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">        
            <div class="form-group">
                <label for="txt_buscarData" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                    <input type="text" class="form-control" value="" id="txt_buscarDataDetmalla" placeholder="<?= academico::t("asignatura", "Search by subject name") ?>">
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarDataDetmalla" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div>

