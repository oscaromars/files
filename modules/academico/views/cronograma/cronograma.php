<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
academico::registerTranslations();
use app\modules\admision\Module as admision;

admision::registerTranslations();

//print_r($arr_horario);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>   
<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center">
    <?php if(!empty($arr_cronograma["cro_archivo"])) {?>
       <img src="<?= Url::base(). '/site/getimage/?route=/uploads/cronogramas/'.$arr_cronograma["cro_archivo"] ?>" width="<?= $widthImg ?>px" height="<?= $heightImg ?>px" id='img_destino' <?php //class="img-circle" ?> alt="Malla Carrera" />
       <?php } else {?>
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 600px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Si no se visualiza la malla, es porque no existe imagen o la imagen no está en formato jpg.</div>
          </div>
          </div>
          </div>
       <?php } ?> 
    </div> 
</div>
