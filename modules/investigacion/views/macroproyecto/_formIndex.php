<?php

use yii\web\JsExpression;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use app\modules\investigacion\Module as investigacion;
investigacion::registerTranslations();


/* @var $this yii\web\View */
/* @var $model app\modules\investigacion\models\LineaInvestigacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    
    
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_linea" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= investigacion::t("lineainvestigacion", "Line of research") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <?= Html::dropDownList("cmb_linea", 0,  $arr_linv , ["class" => "form-control", "id" => "cmb_linea"]) ?>
            </div>
               
        </div>
    </div>
    
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarDataLinea" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div>
