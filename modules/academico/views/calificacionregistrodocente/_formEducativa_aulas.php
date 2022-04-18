<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
Academico::registerTranslations();
?>
 <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">

<div class="row">
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12"><br><br>
        <div class="form-group">                 
            <label for="cmb_periodo_aul" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">  
                    <?= Html::dropDownList("cmb_periodo_aul",$paca,$arr_periodos, ["class" => "form-control", "id" => "cmb_periodo_aul"]) ?>              
            </div>
              <label for="cmb_unidad_aul" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_unidad_aul", $unidad, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad_aul"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group"> 
            <label for="cmb_modalidad_aul" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Modality") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_modalidad_aul",$modalidad,$arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad_aul"]) ?>
            </div>
             <label for="cmb_aulas_aul" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Course") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_aulas_aul", $aula,$arr_aula, ["class" => "form-control", "id" => "cmb_aulas_aul"]) ?>
            </div>
           
        </div>
    </div>

 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group"> 
            <label for="cmb_parcial_aul" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Partial") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_parcial_aul", $parcial,$arr_parcial, ["class" => "form-control", "id" => "cmb_parcial_aul"]) ?>
            </div>
            <!-- <label for="cmb_aulas_aul" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Course") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_aulas_aul", 0,$arr_aula, ["class" => "form-control", "id" => "cmb_aulas_aul"]) ?>
            </div>-->
           
        </div>
    </div>
    
   
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarEducativaulas" href="javascript:" class="btn btn-primary btn-block"> <?= academico::t("Academico", "Search") ?></a>
        </div>
          <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
          <br>
        </div>
         <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
      
    </div>


</div>