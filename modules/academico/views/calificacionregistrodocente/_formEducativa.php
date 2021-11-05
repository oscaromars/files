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
            <label for="cmb_periodo_all" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">  
                    <?= Html::dropDownList("cmb_periodo_all",1,$arr_periodos, ["class" => "form-control", "id" => "cmb_periodo_all"]) ?>              
            </div>
              <label for="cmb_unidad_all" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_unidad_all", 1, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad_all", "disabled" => "True"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group"> 
            <label for="cmb_modalidad_all" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Modality") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_modalidad_all", 1,$arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad_all"]) ?>
            </div>
           
        </div>
    </div>
   
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarEducativa" href="javascript:" class="btn btn-primary btn-block"> <?= academico::t("Academico", "Search") ?></a>
        </div>
          <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
          <br>
        </div>
         <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
      
    </div>


</div>