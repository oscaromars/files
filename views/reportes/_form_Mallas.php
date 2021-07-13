<?php

use yii\helpers\Html;


?>

<form class="form-horizontal">   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_unidad" class="col-sm-4 col-lg-4 col-md-4 col-xs-4 control-label"><?= Yii::t("formulario", "Unidad Académica:") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidad", 0, $arr_unidad, ["class" => "form-control pro_combo", "id" => "cmb_unidad"]) ?>
            </div>
        </div>
    </div>
    
   

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_modalidad" class="col-sm-4 col-lg-4 col-md-4 col-xs-4 control-label"><?= Yii::t("formulario", "Modalidad:") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control pro_combo", "id" => "cmb_modalidad"]) ?>
            </div>
        </div>
    </div>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_carrera" class="col-sm-4 col-lg-4 col-md-4 col-xs-4 control-label"><?= Yii::t("formulario", "Carrera:") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_carrera", 0, $arr_carrera, ["class" => "form-control pro_combo", "id" => "cmb_carrera"]) ?>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_malla" class="col-sm-4 col-lg-4 col-md-4 col-xs-4 control-label"><?= Yii::t("formulario", "Malla Académica:") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_malla", 0, $arr_malla, ["class" => "form-control pro_combo", "id" => "cmb_malla"]) ?>
            </div>
        </div>
    </div>


    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">  
            <a id="btn_buscarMallas" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>   
    </div>


</form>
</div>