<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;


?>
<form class="form-horizontal">   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_estudiante" class="col-sm-4 col-lg-4 col-md-4 col-xs-4 control-label"><?= Yii::t("formulario", "Estudiante:") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_estudiante", 0, $arr_estudiante, ["class" => "form-control pro_combo", "id" => "cmb_estudiante"]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">  
            <a id="btn_buscarDatapromedios" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>   
    </div>

</div>
