<?php

use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\academico\Module as academico;
use app\modules\admision\Module;
?> 
<div class="row">
   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
             <label for="lbl_modalidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidades", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidades"]) ?>
            </div> 
            <label for="lbl_periodo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodo", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
            </div>                  
        </div>        
    </div>        
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_buscarPlanestudiante" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div></br>
